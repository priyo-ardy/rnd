window.onload = () => {
  $(".summernote").summernote("disable");
};

const buttons = {
  back: document.getElementById("btnBack"),
  add: document.getElementById("btnAdd"),
  edit: document.getElementById("btnEdit"),
  update: document.getElementById("btnUpdate"),
  cancel: document.getElementById("btnCancel"),
  prev: document.getElementById("btnPrev"),
  next: document.getElementById("btnNext"),
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

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/material");
});

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/material/add");
});

buttons.cancel.addEventListener("click", () => {
  loading();
  window.location.reload();
});

function bukaForm() {
  const inputElement = document.querySelectorAll("input");
  const selectElement = document.querySelectorAll("select");
  const textElement = document.querySelectorAll("textarea");

  if (inputElement.length > 0) {
    inputElement.forEach((element) => {
      element.removeAttribute("readonly");
      element.classList.remove("bg-secondary-subtle");
    });
  }

  if (selectElement.length > 0) {
    selectElement.forEach((element) => {
      element.removeAttribute("disabled");
    });
  }

  if (textElement.length > 0) {
    textElement.forEach((element) => {
      element.removeAttribute("readonly");
      element.classList.remove("bg-secondary-subtle");
    });
  }

  $(".summernote").summernote("enable");
  document.getElementById("data_token").setAttribute("readonly", true);
  document.getElementById("data_token").classList.add("bg-secondary-subtle");

  buttons.back.setAttribute("hidden", true);
  buttons.add.setAttribute("hidden", true);
  buttons.edit.setAttribute("hidden", true);
  buttons.update.removeAttribute("hidden");
  buttons.cancel.removeAttribute("hidden");
  buttons.prev.setAttribute("hidden", true);
  buttons.next.setAttribute("hidden", true);
}

buttons.edit.addEventListener("click", () => {
  bukaForm();
});

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
        baseurl + "/material/update_code",
        "POST",
        JSON.stringify({
          token: document.getElementById("data_token").value,
          code: inputForm.code.value,
        })
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

buttons.update.addEventListener("click", (e) => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/material/update", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        })
        .catch((err) => {
          pesanError(err.message);
          hideLoading();
        });
    } catch (e) {
      pesanError(e.message);
    }
  }
});

buttons.prev.addEventListener("click", () => {
  try {
    loading();
    fetchData(
      baseurl + "/material/prev",
      "POST",
      JSON.stringify({ code: inputForm.code.value })
    )
      .then((result) => {
        window.location.replace(
          baseurl + "/material/show/" + result.data.token
        );
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});

buttons.next.addEventListener("click", (e) => {
  try {
    loading();
    fetchData(
      baseurl + "/material/next",
      "POST",
      JSON.stringify({ code: inputForm.code.value })
    )
      .then((result) => {
        window.location.replace(
          baseurl + "/material/show/" + result.data.token
        );
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
});
