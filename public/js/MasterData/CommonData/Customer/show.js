const formData = document.getElementById("formData");

const dataForm = {
  token: document.getElementById("data_token"),
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
  update: document.getElementById("btnUpdate"),
  edit: document.getElementById("btnEdit"),
  cancel: document.getElementById("btnCancel"),
  prev: document.getElementById("btnPrev"),
  next: document.getElementById("btnNext"),
  add: document.getElementById("btnAdd"),
};

buttons.back.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/customer");
});

function enableForm() {
  dataForm.name.removeAttribute("readonly");
  dataForm.alamat.removeAttribute("readonly");
  dataForm.email.removeAttribute("readonly");
  dataForm.phone.removeAttribute("readonly");
  dataForm.contact_person.removeAttribute("readonly");
  dataForm.cp_email.removeAttribute("readonly");
  dataForm.cp_phone.removeAttribute("readonly");
  dataForm.remark.removeAttribute("readonly");

  dataForm.name.classList.remove("bg-secondary-subtle");
  dataForm.alamat.classList.remove("bg-secondary-subtle");
  dataForm.email.classList.remove("bg-secondary-subtle");
  dataForm.phone.classList.remove("bg-secondary-subtle");
  dataForm.contact_person.classList.remove("bg-secondary-subtle");
  dataForm.cp_email.classList.remove("bg-secondary-subtle");
  dataForm.cp_phone.classList.remove("bg-secondary-subtle");
  dataForm.remark.classList.remove("bg-secondary-subtle");

  buttons.update.removeAttribute("hidden");
  buttons.cancel.removeAttribute("hidden");
  buttons.back.setAttribute("hidden", true);
  buttons.edit.setAttribute("hidden", true);
  buttons.add.setAttribute("hidden", true);
  buttons.prev.setAttribute("hidden", true);
  buttons.next.setAttribute("hidden", true);

  dataForm.name.focus();
}

buttons.edit.addEventListener("click", () => {
  enableForm();
});

buttons.cancel.addEventListener("click", () => {
  loading();
  window.location.reload();
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
      }
    });
  }
  return isValid;
}

buttons.update.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/customer/update", "POST", new FormData(formData))
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
      hideLoading();
    }
  }
});

buttons.prev.addEventListener("click", () => {
  try {
    loading();
    fetchData(
      baseurl + "/customer/prev",
      "POST",
      JSON.stringify({ code: dataForm.code.value })
    )
      .then((result) => {
        window.location.replace(
          baseurl + "/customer/show/" + result.data.token
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

buttons.next.addEventListener("click", () => {
  try {
    loading();
    fetchData(
      baseurl + "/customer/next",
      "POST",
      JSON.stringify({ code: dataForm.code.value })
    )
      .then((result) => {
        window.location.replace(
          baseurl + "/customer/show/" + result.data.token
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

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/customer/add");
});
