const protocol = window.location.protocol;
const hostname = window.location.hostname;
const port = window.location.port;
const baseurl = `${protocol}//${hostname}${port ? ":" + port : ""}`;

const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
const Default = {
  scrollbarTheme: "os-theme-dark",
  scrollbarAutoHide: "leave",
  scrollbarClickScroll: true,
};

document.addEventListener("DOMContentLoaded", function () {
  const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
  if (
    sidebarWrapper &&
    typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
  ) {
    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
      scrollbars: {
        theme: Default.scrollbarTheme,
        autoHide: Default.scrollbarAutoHide,
        clickScroll: Default.scrollbarClickScroll,
      },
    });
  }

  hideLoading();

  $(".select2bs5").select2({
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
});

function pesanSukses(message) {
  Swal.fire({
    icon: "success",
    title: "Success !",
    html: message,
    showConfirmButton: false,
    timer: 1500,
  });
}

function pesanError(message) {
  Swal.fire({
    icon: "error",
    title: "Error !",
    html: message,
    showConfirmButton: true,
  });
}

function pesanWarning(message) {
  Swal.fire({
    icon: "warning",
    title: "Warning !",
    html: message,
    showConfirmButton: true,
  });
}

// begin::Bootstrap Tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll("[title]"));
tooltipTriggerList.forEach(function (tooltipTriggerEl) {
  new bootstrap.Tooltip(tooltipTriggerEl);
});
// end::Bootstrap Tooltips

// begin::Bootstrap Toast
const toastTriggerList = document.querySelectorAll('[data-bs-toggle="toast"]');
toastTriggerList.forEach((btn) => {
  btn.addEventListener("click", (event) => {
    event.preventDefault();
    const toastEle = document.getElementById(
      btn.getAttribute("data-bs-target")
    );
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastEle);
    toastBootstrap.show();
  });
});
// end::Bootstrap Tooltip

function loading() {
  const loadingOverlay = document.getElementById("loading-overlay");
  loadingOverlay.classList.add("active");
}

function hideLoading() {
  const loadingOverlay = document.getElementById("loading-overlay");
  loadingOverlay.classList.remove("active");
}

function removeValidation() {
  const invalidInput = document.querySelectorAll(".is-invalid");
  const validInput = document.querySelectorAll(".is-valid");
  const invalidFeedback = document.querySelectorAll(".invalid-feedback");
  const validFeedback = document.querySelectorAll(".valid-feedback");

  invalidInput.forEach((input) => {
    input.classList.remove("is-invalid");
  });

  validInput.forEach((input) => {
    input.classList.remove("is-valid");
  });

  invalidFeedback.forEach((feedback) => {
    feedback.classList.remove("invalid-feedback");
    feedback.textContent = "";
  });

  validFeedback.forEach((feedback) => {
    feedback.classList.remove("valid-feedback");
    feedback.textContent = "";
  });
}

function hapusData(url, token) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-primary rounded-0",
      cancelButton: "btn btn-secondary rounded-0",
    },
  });

  swalWithBootstrapButtons
    .fire({
      title: "Warning !",
      text: "Deleted data cannot be recovered",
      icon: "warning",
      showCancelButton: true,
      cancelButtonColor: "#d33",
      confirmButtonText: '<i class="bi bi-check"></i>&ensp;Yes',
      cancelButtonText: '<i class="bi bi-x"></i>&ensp;Cancel',
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "Please wait...",
          timerProgressBar: true,
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            swal.showLoading();
          },
        }).then(
          fetchData(baseurl + url, "POST", JSON.stringify({ token: token }))
            .then((result) => {
              pesanSukses(result.message);
              if (typeof refreshTable === "function") {
                // Jika fungsi ada, panggil
                refreshTable();
              } else {
                // Jika tidak ada, panggil alternatif
                window.location.reload();
              }
            })
            .catch((err) => {
              pesanError(err.message);
            })
        );
      }
    });
}

function formatPhone(phone) {
  const regex = /^[0-9]+$/;
  return regex.test(phone);
}

function validasiEmail(email) {
  const regex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  return regex.test(email);
}

function validasiPassword(password) {
  let isValid = true;
  const minLength = 8;
  const hasUpperCase = /[A-Z]/;
  const hasNumber = /[0-9]/;
  const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

  const errors = [];

  if (password.length < minLength) {
    dataForm.password.classList.add("is-invalid");
    errors.push(`The minimum password length is ${minLength} characters`);
    isValid = false;
  } else {
    dataForm.password.classList.remove("is-invalid");
    feedback.fpassword.textContent = "";
  }

  if (!hasNumber.test(password)) {
    dataForm.password.classList.add("is-invalid");
    errors.push("Password must have at least 1 uppercase character");
    isValid = false;
  } else {
    dataForm.password.classList.remove("is-invalid");
    feedback.fpassword.textContent = "";
  }

  if (!hasUpperCase.test(password)) {
    dataForm.password.classList.add("is-invalid");
    errors.push("Password must have at least 1 numeric character");
    isValid = false;
  } else {
    dataForm.password.classList.remove("is-invalid");
    feedback.fpassword.textContent = "";
  }

  if (!hasSpecialChar.test(password)) {
    dataForm.password.classList.add("is-invalid");
    errors.push("Passwords must have at least 1 special character");
    isValid = false;
  } else {
    dataForm.password.classList.remove("is-invalid");
    feedback.fpassword.textContent = "";
  }

  if (errors.length > 0) {
    return {
      isValid: false,
      errors: errors,
    };
  }

  return {
    isValid: true,
    errors: [],
  };
}
