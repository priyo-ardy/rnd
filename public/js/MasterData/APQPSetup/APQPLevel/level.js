window.onload = () => {
  loadTable();
};

const dataForm = document.getElementById("dataForm");
const inputForm = {
  token: document.getElementById("data_token"),
  level: document.getElementById("data_level"),
  name: document.getElementById("data_name"),
  remark: document.getElementById("data_remark"),
};

const buttons = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  refresh: document.getElementById("btnRefresh"),
  export: document.getElementById("btnExport"),
};

function loadTable() {
  $("#dataTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    bDestroy: true,
    search: {
      return: true,
    },
    order: [],
    ajax: {
      url: baseurl + "/apqp_level/table",
      type: "POST",
      data: "raw",
      action: "calls",
    },
    deferRender: true,
    columnDefs: [
      {
        targets: 0,
        orderable: false,
      },
    ],
  });
}

function refreshTable() {
  $("#dataTable").DataTable().ajax.reload(null, false);
}

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

function levelCheck() {
  let isValid = true;

  fetchData(
    baseurl + "/apqp_level/level_check",
    "POST",
    JSON.stringify({ level: inputForm.level.value })
  )
    .then((result) => {
      return true;
      inputForm.level.classList.remove("is-invalid");
      inputForm.level.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "";
    })
    .catch((err) => {
      inputForm.level.classList.add("is-invalid");
      inputForm.level.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = err.message;

      isValid = false;
    });

  return isValid;
}

function validasi() {
  let isValid = true;

  const requiredElement = document.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value.trim() === "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");
      }
    });
  }

  if (!levelCheck()) {
    isValid = false;
  }

  return isValid;
}

function clearForm() {
  dataForm.reset();
  inputForm.level.removeAttribute("readonly");
  inputForm.level.classList.remove("bg-secondary-subtle");
  buttons.update.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
  inputForm.level.focus();

  const invalidFeedBack = document.querySelectorAll(".invalid-feedback");
  const invalidElement = document.querySelectorAll(".is-invalid");
  invalidFeedBack.forEach((feedback) => (feedback.textContent = ""));
  invalidElement.forEach((element) => element.classList.remove("is-invalid"));
}

buttons.cancel.addEventListener("click", () => {
  clearForm();
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      fetchData(baseurl + "/apqp_level/save", "POST", new FormData(dataForm))
        .then((result) => {
          pesanSukses(result.message);
          hideLoading();
          clearForm();
          refreshTable();
        })
        .catch((err) => {
          pesanError(err.message);
          hideLoading();
        });
    } catch (e) {
      pesanError(e.message);
      hideLoading();
    }
  } else {
    console.log(validasi());
  }
});

function editData(token) {
  try {
    loading();
    fetchData(
      baseurl + "/apqp_level/get_data",
      "POST",
      JSON.stringify({ token: token })
    )
      .then((result) => {
        inputForm.token.value = token;
        inputForm.level.value = result.data.level;
        inputForm.level.setAttribute("readonly", true);
        inputForm.level.classList.add("bg-secondary-subtle");
        inputForm.name.value = result.data.name;
        inputForm.remark.value = result.data.remark;
        inputForm.name.focus();
        buttons.update.removeAttribute("hidden");
        buttons.save.setAttribute("hidden", true);
        hideLoading();
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
}

function validasiUpdate() {
  let isValid = true;

  if (inputForm.token.value == "") {
    isValid = false;
  }

  if (inputForm.name.value == "") {
    isValid = false;
    inputForm.name.classList.add("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
  } else {
    inputForm.name.classList.remove("is-invalid");
  }

  return isValid;
}

buttons.update.addEventListener("click", () => {
  if (validasiUpdate()) {
    try {
      fetchData(baseurl + "/apqp_level/update", "POST", new FormData(dataForm))
        .then((result) => {
          pesanSukses(result.message);
          hideLoading();
          clearForm();
          refreshTable();
        })
        .catch((err) => {
          pesanError(err.message);
          hideLoading();
        });
    } catch (e) {
      pesanError(e.message);
      hideLoading();
    }
  }
});

function deleteData(token) {
  try {
    hapusData("/apqp_level/delete", token);
  } catch (e) {
    pesanError(e.message);
  }
}

buttons.export.addEventListener("click", async () => {
  try {
    loading();

    // Buat AbortController untuk timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    // Lakukan fetch dengan streaming

    const response = await fetch(baseurl + "/apqp_level/export", {
      method: "GET",
      signal: controller.signal,
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      const errorData = await response.json().catch(() => null);
      throw new Error(
        errorData?.error || `HTTP error! status: ${response.status}`
      );
    }

    // Dapatkan Blob
    const blob = await response.blob();

    if (blob.size === 0) {
      throw new Error("Failed to creating exported file");
    }

    // Buat link downlaod
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.style.display = "none";
    a.href = url;
    a.download =
      "apqp_level_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
    document.body.appendChild(a);
    a.click();

    // Bersihkan
    window.URL.revokeObjectURL(url);
    a.remove();
    hideLoading();
  } catch (e) {
    if (e.name === "AbortError") {
      pesanError(
        "Proses ekspor terlalu lama. Silakan coba lagi atau ekspor data lebih kecil."
      );
    } else {
      pesanError(e.message || "Gagal mengekspor data");
    }

    hideLoading();
  }
});
