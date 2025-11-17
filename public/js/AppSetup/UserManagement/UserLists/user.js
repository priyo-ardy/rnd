const buttons = {
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  refreshTable: document.getElementById("btnRefresh"),
  export: document.getElementById("btnExport"),
  cancel: document.getElementById("btnCancel"),
  save: document.getElementById("btnSave"),
  update: document.getElementById("btnUpdate"),
  cancel_password: document.getElementById("cancelPassword"),
  save_password: document.getElementById("changePassword"),
};

const formUser = document.getElementById("formUser");
const formPassword = document.getElementById("formPassword");

const modalInput = {
  title: document.getElementById("modal-title"),
  token: document.getElementById("data_token"),
  username: document.getElementById("data_username"),
  fullname: document.getElementById("data_fullname"),
  email: document.getElementById("data_email"),
  phone: document.getElementById("data_phone"),
  level: document.getElementById("data_level"),
  password: document.getElementById("data_password"),
};

const modalPassword = {
  user_token: document.getElementById("user_token"),
  new_password: document.getElementById("new_password"),
};

window.onload = () => {
  loadTable();

  $(".select2bs5").select2({
    dropdownParent: $("#modalUser"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
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
      url: baseurl + "/users/table",
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

buttons.refreshTable.addEventListener("click", () => {
  refreshTable();
});

buttons.add.addEventListener("click", () => {
  modalInput.title.innerHTML = "Add User";
  $("#modalUser").modal("show");
});

function resetForm() {
  formUser.reset();
  modalInput.level.value = "";
  $(modalInput.level).trigger("change");
  buttons.update.setAttribute("hidden", true);
  buttons.save.removeAttribute("hidden");
  modalInput.password.setAttribute("required", true);
  modalInput.password.removeAttribute("disabled");
  modalInput.token.removeAttribute("required");
}

function validasiSave() {
  let isValid = true;

  const requiredElement = formUser.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value == "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");
        if (element.parentNode.querySelector(".invalid-feedback")) {
          element.parentNode.querySelector(".invalid-feedback").textContent =
            "";
        }
      }
    });
  }
  return isValid;
}

buttons.save.addEventListener("click", () => {
  if (validasiSave()) {
    try {
      loading();
      fetchData(baseurl + "/users/save", "POST", new FormData(formUser))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
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

function editUser(token) {
  modalInput.token.value = token;
  try {
    loading();
    fetchData(baseurl + "/users/get", "POST", JSON.stringify({ token: token }))
      .then((result) => {
        modalInput.title.innerHTML = "Edit User";
        modalInput.username.value = result.data.user_name;
        modalInput.fullname.value = result.data.full_name;
        modalInput.email.value = result.data.user_email;
        modalInput.phone.value = result.data.user_phone;
        modalInput.level.value = result.data.user_level;
        $(modalInput.level).trigger("change");
        modalInput.password.removeAttribute("required");
        modalInput.password.setAttribute("disabled", true);
        buttons.save.setAttribute("hidden", true);
        buttons.update.removeAttribute("hidden");
        modalInput.token.setAttribute("required", true);
        $("#modalUser").modal("show");
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

buttons.update.addEventListener("click", (e) => {
  if (validasiSave()) {
    try {
      loading();
      fetchData(baseurl + "/users/update", "POST", new FormData(formUser))
        .then((result) => {
          pesanSukses(result.message);
          resetForm();
          refreshTable();
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

function deleteData(token) {
  hapusData("/users/delete", token);
}

buttons.export.addEventListener("click", async () => {
  try {
    loading();

    // Buat AbortController untuk timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    // Lakukan fetch dengan streaming

    const response = await fetch(baseurl + "/users/export", {
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
    a.download = "users_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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

function changePassword(token) {
  modalPassword.user_token.value = token;
  $("#modalPassword").modal("show");
}

function clearModal() {
  formPassword.reset();
}

function validasiUpdate() {
  let isValid = true;

  if (modalPassword.user_token.value == "") {
    isValid = false;
  }

  if (modalPassword.new_password.value == "") {
    isValid = false;
    modalPassword.new_password.classList.add("is-invalid");
    modalPassword.new_password.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "This field is required";
  } else {
    modalPassword.new_password.classList.remove("is-invalid");
    modalPassword.new_password.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "";
  }

  return isValid;
}

buttons.save_password.addEventListener("click", (e) => {
  if (validasiUpdate()) {
    try {
      loading();
      fetchData(
        baseurl + "/users/update_password",
        "POST",
        new FormData(formPassword)
      )
        .then((result) => {
          pesanSukses(result.message);
          clearModal();
          $("#modalPassword").modal("hide");
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
