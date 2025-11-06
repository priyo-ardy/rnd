const protocol = window.location.protocol;
const hostname = window.location.hostname;
const port = window.location.port;
const baseurl = `${protocol}//${hostname}${port ? ":" + port : ""}`;

const formAuth = document.getElementById("formAuth");
const userEmail = document.getElementById("email_address");
const btnAuth = document.getElementById("btnAuth");
const error_message = document.getElementById("error_message");
const success_message = document.getElementById("success_message");

userEmail.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (userEmail.value === "") {
      userEmail.classList.add("is-invalid");
      userEmail.parentNode.querySelector(".invalid-feedback").textContent =
        "Email address is required";
      userEmail.focus();
    } else {
      userEmail.classList.remove("is-invalid");
      disableForm();
      prosesForgot();
    }
  }
});

function enableForm() {
  userEmail.removeAttribute("readonly");
  userEmail.focus();
  btnAuth.innerHTML = `<i class="bi bi-key"></i>&ensp;Reset Password`;
}

function disableForm() {
  userEmail.setAttribute("readonly", true);
  btnAuth.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
}

function validasi() {
  let isValid = true;
  if (userEmail.value == "") {
    isValid = false;
    userEmail.classList.add("is-invalid");
    userEmail.parentNode.querySelector(".invalid-feedback").textContent =
      "Email address is required";
  } else {
    userEmail.classList.remove("is-invalid");
    userEmail.parentNode.querySelector(".invalid-feedback").textContent = "";
  }

  return isValid;
}

btnAuth.addEventListener("click", (e) => {
  prosesForgot();
});

function prosesForgot() {
  if (validasi()) {
  }
}
