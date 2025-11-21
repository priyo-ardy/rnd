// window.onload = () => {};

const btns = {
  save_approver: document.getElementById("btnSaveApprover"),
};

const formApprover = document.getElementById("formApprover");

const inputModal = {
  token: document.getElementById("apqp_token"),
};

function showApprover(token) {
  try {
    loading();
    fetchData(
      baseurl + "/apqp_approver/load_approver",
      "POST",
      JSON.stringify({ apqp_level: token })
    )
      .then((result) => {
        if (result.data.length > 0) {
          approvalList(result.data);
        } else {
          firstRowApprover();
        }
        inputModal.token.value = token;
        $("#modalApprover").modal("show");
        hideLoading();
      })
      .catch((err) => {
        pesanError(err.message);
        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
  }
}

function approvalList(approver) {
  const tBody = document.getElementById("listApprover");
  tBody.innerHTML = "";
  approver.forEach((item) => {
    const row = `
      <tr>
        <td class="editable">${item.NIK} - ${item.approver_name}</td>
        <td class="text-center align-middle">
          <button type="button" class="btn btn-add btn-success rounded-0 btn-sm" onclick="addEditableRow(this)"><i class="bi bi-plus-circle"></i>&ensp;Add</button>
          <button type="button" class="btn btn-edit btn-warning rounded-0 btn-sm" onclick="editBaris(this)"><i class="bi bi-pencil-square"></i>&ensp;Edit</button>
          <button type="button" class="btn btn-delete btn-danger rounded-0 btn-sm" onclick="hapusApprover(this, '${item.id}')"><i class="bi bi-x"></i>&ensp;Delete</button>
          <button hidden type="button" class="btn btn-update btn-primary rounded-0 btn-sm" onclick="updateRow(this, '${item.id}')"><i class="bi bi-floppy"></i>&ensp;Update</button>
          <button hidden type="button" class="btn btn-cancel btn-warning rounded-0 btn-sm" onclick="cancelUpdate(this)"><i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel</button>
        </td>
      </tr>
    `;

    tBody.insertAdjacentHTML("beforeend", row);
  });
}

function firstRowApprover() {
  const tableBody = document.getElementById("listApprover");
  tableBody.innerHTML = "";
  const row = `
    <tr>
        <td>
            <select name="approver[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose --</option>
                ${document.getElementById("listUsers").innerHTML}
            </select>
            <div class="invalid-feedback"></div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRowApprover()"><i class="bi bi-plus-circle"></i></button>
        </td>
    </tr>
  `;

  tableBody.insertAdjacentHTML("beforeend", row);
  btns.save_approver.removeAttribute("hidden");

  $(".select2bs5").select2({
    dropdownParent: $("#modalApprover"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });

  console.log(row);
}

function addRowApprover() {
  const tableBody = document.getElementById("listApprover");
  const row = `
    <tr>
        <td>
            <select name="approver[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose --</option>
                ${document.getElementById("listUsers").innerHTML}
            </select>
            <div class="invalid-feedback"></div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRowApprover()"><i class="bi bi-plus-circle align-middle"></i></button>
            <button type="button" class="btn btn-sm btn-danger rounded-0" onclick="removeRow(this)"><i class="bi bi-dash-circle align-middle"></i></button>
        </td>
    </tr>
  `;

  tableBody.insertAdjacentHTML("beforeend", row);

  $(".select2bs5").select2({
    dropdownParent: $("#modalApprover"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

function clearModel() {
  const tableBody = document.getElementById("listApprover");
  tableBody.innerHTML = "";
  inputModal.token.value = "";
  btns.save_approver.setAttribute("hidden", "true");
}

function removeRow(btn) {
  const row = btn.closest("tr");

  if (row) {
    const tbody = document.getElementById("listApprover");
    row.remove();
  }
}

function validasiApprover() {
  const approverList = document.querySelectorAll("select[name='approver[]']");

  let valuesMap = {};
  let hasDuplicates = false;
  let isValid = true;

  approverList.forEach((select) => {
    $(select).removeClass("is-invalid");
    $(select)
      .next(".select2-container")
      .find(".select2-selection")
      .removeClass("border border-danger");
    const feedback = select.parentElement.querySelector(".invalid-feedback");
    if (feedback) feedback.style.display = "none";
  });

  approverList.forEach((select, index) => {
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
        markError(select, "Approver is required");
      }
    }
  });

  if (hasDuplicates) {
    for (const [val, elements] of Object.entries(valuesMap)) {
      if (elements.length > 1) {
        elements.forEach((el) => {
          markError(el, "Approver already exists");
        });
      }
    }
    return false;
  }

  if (!isValid) return false;

  return true;
}

function markError(element, message) {
  // Tambah class error ke select asli
  $(element).addClass("is-invalid");

  // Tambah border merah ke container Select2 (karena select asli disembunyikan)
  $(element)
    .next(".select2-container")
    .find(".select2-selection")
    .addClass("border border-danger");

  // Tampilkan pesan error
  const feedback = element.parentElement.querySelector(".invalid-feedback");
  if (feedback) {
    feedback.textContent = message;
    feedback.style.display = "block";
  }
}

btns.save_approver.addEventListener("click", (e) => {
  if (validasiApprover()) {
    try {
      loading();
      fetchData(
        baseurl + "/apqp_approver/save_approver",
        "POST",
        new FormData(formApprover)
      )
        .then((result) => {
          pesanSukses(result.message);
          hideLoading();
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

function editBaris(button) {
  const row = button.closest("tr");
  const editableCell = row.querySelectorAll(".editable");

  let originalValue = [];
  editableCell.forEach((cell) => {
    originalValue.push(cell.textContent);
  });

  row.setAttribute("data-original-value", originalValue.join("|||"));

  editableCell.forEach((cell, index) => {
    const currentValue = cell.textContent.trim();
    cell.innerHTML = "";
    const selectElement = document.createElement("select");
    selectElement.className = "form-control select2 select2bs5";
    selectElement.innerHTML = document.getElementById("listUsers").innerHTML;

    for (let i = 0; i < selectElement.options.length; i++) {
      if (selectElement.options[i].text.trim() === currentValue) {
        selectElement.options[i].selected = true;
        break; // Berhenti loop jika sudah ketemu (Best Practice)
      }
    }

    cell.appendChild(selectElement);
  });

  $(".select2bs5").select2({
    dropdownParent: $("#modalApprover"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });

  row.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-add").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-update").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-cancel").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
}

function cancelUpdate(btn) {
  const row = btn.closest("tr");
  const editableCells = row.querySelectorAll(".editable");
  const originalValuesString = row.getAttribute("data-original-value");

  if (!originalValuesString) return;

  const originalValues = originalValuesString.split("|||");

  editableCells.forEach((cell, index) => {
    cell.innerHTML = originalValues[index]; // Kembalikan ke teks asli
  });

  row.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-add").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-update").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-cancel").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });

  row.removeAttribute("data-original-values");
}

function updateRow(btn, token) {
  const row = btn.closest("tr");
  const editableCells = row.querySelectorAll(".editable");

  editableCells.forEach((cell) => {
    const selectElement = cell.querySelectorAll(".select2bs5");
    if (selectElement.length > 0) {
      row.removeAttribute("data-original-values");
      try {
        loading();
        fetchData(
          baseurl + "/apqp_approver/update_approver/",
          "POST",
          JSON.stringify({ token: token, approver: selectElement[0].value })
        )
          .then((result) => {
            cell.innerHTML =
              result.data.NIK + " - " + result.data.approver_name;
            row.querySelectorAll(".btn-edit").forEach((btn) => {
              btn.removeAttribute("hidden");
            });
            row.querySelectorAll(".btn-add").forEach((btn) => {
              btn.removeAttribute("hidden");
            });
            row.querySelectorAll(".btn-delete").forEach((btn) => {
              btn.removeAttribute("hidden");
            });
            row.querySelectorAll(".btn-update").forEach((btn) => {
              btn.setAttribute("hidden", true);
            });
            row.querySelectorAll(".btn-cancel").forEach((btn) => {
              btn.setAttribute("hidden", true);
            });

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
}

function addEditableRow() {
  const tbody = document.getElementById("listApprover");

  const newRow = `
    <tr>
        <td>
            <select name="approver[]" class="form-control select2 select2bs5" required>
                ${document.getElementById("listUsers").innerHTML}
            </select>
            <div class="invalid-feedback"></div>
        </td>
        <td class="col-2 align-middle text-center">
            <button type="button" class="btn btn-primary rounded-0 btn-sm" onclick="saveRow(this)" title="add"><i class="bi bi-floppy"></i>&ensp;Save</button>
            <button type="button" class="btn btn-danger rounded-0 btn-sm" onclick="removeRow(this)" title="cancel"><i class="bi bi-x-circle"></i>&ensp;Remove</button>
        </td>
    </tr>
  `;
  tbody.insertAdjacentHTML("beforeend", newRow);

  $(".select2bs5").select2({
    dropdownParent: $("#modalApprover"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

function saveRow(btn) {
  const row = btn.closest("tr");
  const token = document.getElementById("apqp_token").value;
  const approver = row.querySelector('select[name="approver[]"]').value;

  if (approver.value !== "") {
    try {
      loading();
      fetchData(
        baseurl + "/apqp_approver/add_approver",
        "POST",
        JSON.stringify({ token: token, approver: approver })
      )
        .then((result) => {
          pesanSukses();
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
}

function hapusApprover(btn, token) {
  const row = btn.closest("tr");
  try {
    hapusData("/apqp_approver/delete_approver", token);
  } catch (e) {
    pesanError(e.message);
  }
}
