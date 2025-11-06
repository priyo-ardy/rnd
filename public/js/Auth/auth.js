const protocol = window.location.protocol;
const hostname = window.location.hostname;
const port = window.location.port;
const baseurl = `${protocol}//${hostname}${port ? ":" + port : ""}`;

const formAuth = document.getElementById("formAuth");
const user_name = document.getElementById("username");
const user_password = document.getElementById("password");
const btnAuth = document.getElementById("btnAuth");
const pesanLogin = document.getElementById("pesanLogin");

user_name.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (user_name.value === "") {
      user_name.classList.add("is-invalid");
      user_name.parentNode.querySelector(".invalid-feedback").textContent =
        "Username is required";
    } else {
      user_name.classList.remove("is-invalid");
      user_password.focus();
    }
  }
});

user_password.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    if (user_name.value === "") {
      user_name.classList.add("is-invalid");
      user_name.parentNode.querySelector(".invalid-feedback").textContent =
        "Username is required";
      user_name.focus();
    } else if (user_password.value === "") {
      user_password.classList.add("is-invalid");
      user_password.parentNode.querySelector(".invalid-feedback").textContent =
        "Password is required";
    } else {
      processLogin();
    }
  }
});

function validasi() {
  let isValid = true;

  if (user_name.value === "") {
    user_name.classList.add("is-invalid");
    user_name.parentNode.querySelector(".invalid-feedback").textContent =
      "Username is required";
    isValid = false;
  } else {
    user_name.classList.remove("is-invalid");
    user_name.parentNode.querySelector(".invalid-feedback").textContent = "";
  }

  if (user_password.value === "") {
    user_password.classList.add("is-invalid");
    user_password.parentNode.querySelector(".invalid-feedback").textContent =
      "Password is required";
    isValid = false;
  } else {
    user_password.classList.remove("is-invalid");
    user_password.parentNode.querySelector(".invalid-feedback").textContent =
      "";
  }

  return isValid;
}

function disabledForm() {
  user_name.setAttribute("readonly", true);
  user_password.setAttribute("readonly", true);
  btnAuth.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
}

function enabledForm() {
  user_name.removeAttribute("readonly");
  user_password.removeAttribute("readonly");
  btnAuth.innerHTML = `<i class="bi bi-box-arrow-in-right"></i>&ensp;Log in`;
  user_name.focus();
}
function processLogin() {
  if (validasi()) {
    try {
      disabledForm();
      fetchData(baseurl + "/login", "POST", new FormData(formAuth))
        .then((result) => {
          window.location.replace(baseurl + "/dashboard");
        })
        .catch((err) => {
          pesanLogin.textContent = err.message;
          enabledForm();
        });
    } catch (e) {
      enabledForm();
      pesanLogin.textContent = e.message;
    }
  }
}
