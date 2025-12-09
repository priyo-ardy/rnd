const formData = document.getElementById("formData");
const inputForm = {
  token: document.getElementById("data_token"),
  name: document.getElementById("data_name"),
  remark: document.getElementById("data_remark"),
};
const buttons = {
  cancel: document.getElementById("btnCancel"),
  update: document.getElementById("btnUpdate"),
  save: document.getElementById("btnSave"),
  export: document.getElementById("btnExport"),
  refresh: document.getElementById("btnRefresh"),
};

window.onload = () => {
  loadTable();
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
      url: baseurl + "/customer_category/table",
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
  $("#dataTable").DataTable().ajax.reload();
}

buttons.cancel.addEventListener("click", () => {
  formData.reset();
  inputForm.name.classList.remove("is-invalid");
  inputForm.name.parentNode.querySelector(".invalid-feedback").textContent = "";
  buttons.update.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
});

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

buttons.save.addEventListener("click", (e) => {
  if (inputForm.name.value === "") {
    inputForm.name.classList.add("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
  } else {
    inputForm.name.classList.remove("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "";

    try {
      loading();
      fetchData(
        baseurl + "/customer_category/save",
        "POST",
        new FormData(formData)
      )
        .then((result) => {
          pesanSukses(result.message);
          refreshTable();
          formData.reset();
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
});

function editData(token) {
  try {
    loading();
    fetchData(
      baseurl + "/customer_category/edit",
      "POST",
      JSON.stringify({ token: token })
    )
      .then((result) => {
        inputForm.token.value = result.data.token;
        inputForm.name.value = result.data.name;
        inputForm.remark.value = result.data.remark;
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

  if (inputForm.token.value === "") {
    isValid = false;
  }

  if (inputForm.name.value === "") {
    isValid = false;
    inputForm.name.classList.add("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
  } else {
    inputForm.name.classList.remove("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "";
  }

  return isValid;
}

buttons.update.addEventListener("click", (e) => {
  if (validasiUpdate()) {
    try {
      loading();
      fetchData(
        baseurl + "/customer_category/update",
        "POST",
        new FormData(formData)
      )
        .then((result) => {
          pesanSukses(result.message);
          refreshTable();
          formData.reset();
          buttons.update.setAttribute("hidden", true);
          buttons.save.removeAttribute("hidden");
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
  } else {
    pesanWarning("Please complete the form before submit");
  }
});

function deleteData(token) {
  try {
    hapusData("/customer_category/delete", token);
  } catch (e) {
    pesanError(e.message);
  }
}

buttons.export.addEventListener("click", async (e) => {
  try {
    loading();
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    const response = await fetch(baseurl + "/customer_category/export", {
      method: "GET",
      signal: controller.signal,
    });

    if (!response.ok) {
      const errorData = await response.json().catch(() => null);
      throw new Error(
        errorData?.error || `HTTP error! status: ${response.status}`
      );
    }

    const blob = await response.blob();

    if (blob.size === 0) {
      throw new Error("Failed to creating exported file");
    }

    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.style.display = "none";
    a.href = url;
    a.download =
      "customer_category_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
    document.body.appendChild(a);
    a.click();

    window.URL.revokeObjectURL(url);
    a.remove();
    hideLoading();
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});
