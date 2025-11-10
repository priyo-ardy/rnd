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
  teori_nw: document.getElementById("data_teori_nw"),
  teori_gw: document.getElementById("data_teori_gw"),
  nw: document.getElementById("data_nw"),
  gw: document.getElementById("data_gw"),
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

  if (inputForm.teori_nw.value !== "" || inputForm.teori_gw.value !== "") {
    if (
      parseFloat(inputForm.teori_nw.value.trim()) >
      parseFloat(inputForm.teori_gw.value.trim())
    ) {
      inputForm.teori_nw.classList.add("is-invalid");
      inputForm.teori_gw.classList.add("is-invalid");

      inputForm.teori_nw.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "Net weight cannot greather than gross weight";
      inputForm.teori_gw.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "Net weight cannot greather than gross weight";
      isValid = false;
    } else {
      inputForm.teori_nw.classList.remove("is-invalid");
      inputForm.teori_gw.classList.remove("is-invalid");
      inputForm.teori_nw.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "";
      inputForm.teori_gw.parentNode.querySelector(
        ".invalid-feedback"
      ).textContent = "";
    }
  }

  if (inputForm.nw.value.trim() !== "" || inputForm.gw.value.trim() !== "") {
    if (
      parseFloat(inputForm.nw.value.trim()) >
      parseFloat(inputForm.gw.value.trim())
    ) {
      inputForm.nw.classList.add("is-invalid");
      inputForm.gw.classList.add("is-invalid");

      inputForm.nw.parentNode.querySelector(".invalid-feedback").textContent =
        "Net weight cannot greather than gross weight";
      inputForm.gw.parentNode.querySelector(".invalid-feedback").textContent =
        "Net weight cannot greather than gross weight";
      isValid = false;

      console.log("SALAH");
    } else {
      //   console.log("BENAR");
      inputForm.nw.classList.remove("is-invalid");
      inputForm.gw.classList.remove("is-invalid");

      inputForm.nw.parentNode.querySelector(".invalid-feedback").textContent =
        "";
      inputForm.gw.parentNode.querySelector(".invalid-feedback").textContent =
        "";
    }
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
    try {
      loading();
      fetchData(baseurl + "/material/save", "POST", new FormData(formData))
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

buttons.cancel.addEventListener("click", (e) => {
  clearForm();
});
