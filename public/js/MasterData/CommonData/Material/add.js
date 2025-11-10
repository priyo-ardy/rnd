const formData = document.getElementById("formData");

const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
};

const inputForm = {
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  spesifikasi: document.getElementById("data_spesifikasi"),
  satuan: document.getElementById("data_satuan"),
  workshop: document.getElementById("data_workshop"),
};

inputForm.code.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (inputForm.code.value.trim() === "") {
      inputForm.code.classList.add("is-invalid");
      inputForm.code.parentNode.querySelector(".invalid-feedback").textContent =
        "This field is required";
    } else {
      cekMaterialCode();
    }
  }
});

function validasi() {
  let isValid = true;

  const requiredElement = document.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value.trim() === "") {
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
        isValid = false;
      } else {
        element.classList.remove("is-invalid");
      }
    });
  }

  if (!cekMaterialCode()) {
    isValid = false;
  }

  return isValid;
}

function cekMaterialCode() {
  let isValid = true;
  if (inputForm.code.value !== "") {
    try {
      fetchData(
        baseurl + "/material/check_code",
        "POST",
        JSON.stringify({ code: inputForm.code.value })
      )
        .then((result) => {
          inputForm.code.classList.remove("is-invalid");
          inputForm.code.classList.add("is-valid");
          inputForm.name.focus();
          if (inputForm.code.parentNode.querySelector(".invalid-feedback")) {
            inputForm.code.parentNode.querySelector(
              ".invalid-feedback"
            ).textContent = "";
          }
        })
        .catch((err) => {
          isValid = false;
          inputForm.code.classList.add("is-invalid");
          inputForm.code.parentNode.querySelector(
            ".invalid-feedback"
          ).textContent = err.message;
          inputForm.code.focus();
        });
    } catch (e) {
      isValid = false;
      pesanError(e.message);
      inputForm.code.focus();
    }
  }

  return isValid;
}

function clearForm() {
  formData.reset();

  const selectElement = document.querySelectorAll("select2");
  if (selectElement.length > 0) {
    selectElement.forEach((element) => {
      element.value = "";
      $(element).trigger("change");
    });
  }

  const validElement = document.querySelectorAll(".is-valid");
  if (validElement.length > 0) {
    validElement.forEach((element) => {
      element.classList.remove("is-valid");
    });
  }

  const invalidElement = document.querySelectorAll(".is-invalid");
  if (invalidElement.length > 0) {
    invalidElement.forEach((element) => {
      element.classList.remove("is-invalid");
      element.parentNode.querySelector(".invalid-feedback").textContent = "";
    });
  }

  $(".summernote").summernote("code", "");
  inputForm.code.focus();
}

buttons.save.addEventListener("click", (e) => {
  if (validasi()) {
  }
});

buttons.cancel.addEventListener("click", (e) => {
  clearForm();
});
