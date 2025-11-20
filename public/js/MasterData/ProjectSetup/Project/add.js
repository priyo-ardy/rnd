const buttons = {
  back: document.getElementById("btnBack"),
  save: document.getElementById("btnSave"),
  cancel: document.getElementById("btnCancel"),
  mass_date: document.getElementById("btnDate"),
  set_date: document.getElementById("btnSetDate"),
};

const formData = document.getElementById("formData");

const inputForm = {
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  type: document.getElementById("data_type"),
  customer: document.getElementById("data_customer"),
  remark: document.getElementById("data_remark"),
};

function addRow() {
  const tableBody = document.getElementById("partList");

  const row = `
    <tr>
        <td class="col-4 align-middle">
            <select name="part_no[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose --</option>
                ${document.getElementById("listMaterial").innerHTML}
            </select>
            <div class="invalid-feedback"></div>
        </td>
        <td class="col-2 align-middle">
            <input type="date" name="due_date[]" class="form-control rounded-0" required>
            <div class="invalid-feedback"></div>
        </td>
        <td class="col-5 align-middle">
            <input type="text" name="remark[]" class="form-control rounded-0" placeholder="Remark">
        </td>
        <td class="col-1 align-middle text-center">
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()" title="Click to add row"><i class="bi bi-plus-circle align-middle"></i></button>
            <button type="button" class="btn btn-sm btn-danger rounded-0" onclick="removeRow(this)" title="Click to remove current row"><i class="bi bi-dash-circle align-middle"></i></button>
        </td>
    </tr>
  `;

  tableBody.insertAdjacentHTML("beforeend", row);

  $(".select2bs5").select2({
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

function removeRow(btn) {
  const row = btn.closest("tr");
  if (row) {
    const tbody = document.getElementById("listApprover");
    row.remove();
  }
}

function checkDuplikat() {
  const partList = document.querySelectorAll('select[name="part_no[]"]');

  let valuesMap = {};
  let hasDuplicates = false;
  let isValid = true;

  partList.forEach((select) => {
    $(select).removeClass("is-invalid");
    $(select)
      .next(".select2-container")
      .find(".select2-selection")
      .removeClass("border border-danger");
    const feedback = select.parentElement.querySelector(".invalid-feedback");
    if (feedback) feedback.style.display = "none";
  });

  partList.forEach((select, index) => {
    const value = select.value;

    if (value !== "") {
      if (valuesMap[value]) {
        valuesMap[value].push(select);
        hasDuplicates = true;
      } else {
        valuesMap[value] = [select];
      }
    } else {
      if (select.hasAttribute("required")) {
        isValid = false;
        markError(select, "This field is required");
      }
    }
  });

  if (hasDuplicates) {
    for (const [val, element] of Object.entries(valuesMap)) {
      if (element.length > 1) {
        element.forEach((select) => {
          markError(select, "Duplicate data");
        });
      }
    }
    return false;
  }

  if (!isValid) return false;

  return true;
}

function markError(element, message) {
  $(element).addClass("is-invalid");

  $(element)
    .next(".select2-container")
    .find(".select2-selection")
    .addClass("border border-danger");

  const feedback = element.parentElement.querySelector(".invalid-feedback");
  if (feedback) {
    feedback.textContent = message;
    feedback.style.display = "block";
  }
}

function validasi() {
  let isValid = true;

  const dueDate = document.querySelectorAll('input[name="due_date[]"]');

  if (inputForm.code.value === "") {
    inputForm.code.classList.add("is-invalid");
    inputForm.code.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
    isValid = false;
  } else {
    inputForm.code.classList.remove("is-invalid");
    inputForm.code.parentNode.querySelector(".invalid-feedback").textContent =
      "";
  }

  if (inputForm.name.value === "") {
    inputForm.name.classList.add("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
    isValid = false;
  } else {
    inputForm.name.classList.remove("is-invalid");
    inputForm.name.parentNode.querySelector(".invalid-feedback").textContent =
      "";
  }

  if (inputForm.type.value === "") {
    inputForm.type.classList.add("is-invalid");
    inputForm.type.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
    isValid = false;
  } else {
    inputForm.type.classList.remove("is-invalid");
    inputForm.type.parentNode.querySelector(".invalid-feedback").textContent =
      "";
  }

  if (inputForm.customer.value === "") {
    inputForm.customer.classList.add("is-invalid");
    inputForm.customer.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "This field is required";
    isValid = false;
  } else {
    inputForm.customer.classList.remove("is-invalid");
    inputForm.customer.parentNode.querySelector(
      ".invalid-feedback"
    ).textContent = "";
  }

  if (!checkDuplikat()) isValid = false;

  dueDate.forEach((element) => {
    if (element.value === "") {
      isValid = false;
      element.classList.add("is-invalid");
      element.parentNode.querySelector(".invalid-feedback").textContent =
        "This field is required";
    } else {
      element.classList.remove("is-invalid");
      element.parentNode.querySelector(".invalid-feedback").textContent = "";
    }
  });

  return isValid;
}

buttons.mass_date.addEventListener("click", () => {
  $("#modalSetDate").modal("show");
});

buttons.set_date.addEventListener("click", () => {
  const mass_date = document.getElementById("mass_due_date");
  const dueDate = document.querySelectorAll('input[name="due_date[]"]');

  if (mass_date.value == "") {
    mass_date.classList.add("is-invalid");
    mass_date.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
  } else {
    mass_date.classList.remove("is-invalid");
    mass_date.parentNode.querySelector(".invalid-feedback").textContent = "";

    dueDate.forEach((element) => {
      element.value = mass_date.value;
    });

    mass_date.value = "";
    $("#modalSetDate").modal("hide");
  }
});

buttons.save.addEventListener("click", () => {
  if (validasi()) {
    try {
      loading();
      fetchData(baseurl + "/project/save", "POST", new FormData(formData))
        .then((result) => {
          pesanSukses(result.message);
          setTimeout(() => {
            window.location.replace(
              baseurl + "/project/show/" + result.data.token
            );
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
