// window.onload = () => {};

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
        if (result.data !== null) {
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
        <td></td>
        <td class="text-center align-middle></td>
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
            </select>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-primary rounded-0" onclick="saveRow(this)"><i class="bi bi-floppy"></i></button>
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()"><i class="bi bi-plus-circle"></i></button>
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

function addRow() {
  const tableBody = document.getElementById("listApprover");
  const row = `
    <tr>
        <td>
            <select name="approver[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose --</option>
            </select>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-primary rounded-0" onclick="saveRow(this)"><i class="bi bi-floppy align-middle"></i></button>
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
}

function removeRow(btn) {
  const row = btn.closest("tr");

  if (row) {
    const tbody = document.getElementById("listApprover");
    row.remove();
  }
}

function saveRow(btn) {
  const row = btn.closest("tr");
  const approver = row.querySelector('select[name="approver"]').value;
  fetchData(baseurl + "/apqp_approver/save_approver", {
    method: "POST",
    body: JSON.stringify({ approver: approver, token: inputModal.token.value }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const select = row.querySelector('select[name="approver"]');
      select.value = data.approver_id;
      select.disabled = true;
      select.innerHTML = `<option value="${data.approver_id}">${data.approver_name}</option>`;
    })
    .catch((err) => {
      console.error(err);
    });
}
