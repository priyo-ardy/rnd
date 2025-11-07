const formData = document.getElementById("formData");

const dataForm = {
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  alamat: document.getElementById("data_alamat"),
  email: document.getElementById("data_email"),
  phone: document.getElementById("data_phone"),
  contact_person: document.getElementById("data_cp"),
  cp_email: document.getElementById("data_cp_email"),
  cp_phone: document.getElementById("data_cp_phone"),
  remark: document.getElementById("data_remark"),
};

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/customer");
});

function validasi() {
  let isValid = true;

  const requiredElement = document.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value == "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent = "";
      }
    });
  }
  return isValid;
}

buttons.cancel.addEventListener("click", () => {
  clearForm();
});

function clearForm() {
  formData.reset();
  dataForm.name.focus();
}

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/customer/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          hideLoading();
          clearForm();
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
