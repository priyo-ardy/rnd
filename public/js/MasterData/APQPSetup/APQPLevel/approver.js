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
        console.log(result.data.length);
        if (result.data.length > 0) {
          approvalList(result.data);
        } else {
          firstRow();
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
        <td>${item.NIK} - ${item.approver_name}</td>
        <td class="text-center align-middle">
          <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()"><i class="bi bi-plus-circle"></i></button>
          <button type="button" class="btn btn-sm btn-warning rounded-0" onclick="editRow(this)"><i class="bi bi-pencil-square"></i></button>
          <button type="button" class="btn btn-sm btn-danger rounded-0" onclick="deleteRow(this)"><i class="bi bi-dash-circle"></i></button>
        </td>
      </tr>
    `;

    tBody.insertAdjacentHTML("beforeend", row);
  });
}

function firstRow() {
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
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()"><i class="bi bi-plus-circle"></i></button>
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
}

function addRow() {
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
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()"><i class="bi bi-plus-circle align-middle"></i></button>
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
