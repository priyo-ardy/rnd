// window.onload = () => {};

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
        } else {
          firstRow();
        }
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

function firstRow() {
  const tableBody = document.getElementById("listApprover");
  const row = `
    <tr>
        <td></td>
        <td>
            <select name="approver[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose --</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-primary rounded-0" onclick="saveRow(this)"><i class="bi bi-floppy"></i></button>
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
