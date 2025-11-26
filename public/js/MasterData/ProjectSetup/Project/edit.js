const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  type: document.getElementById("data_type"),
  customer: document.getElementById("data_customer"),
  remark: document.getElementById("data_remark"),
  modal_token: document.getElementById("modal_token"),
};

const formDocument = document.getElementById("formDocument");

const buttons = {
  back: document.getElementById("btnBack"),
  generate: document.getElementById("btnGenerate"),
  modal_generate: document.getElementById("btnModalGenerate"),
  save_document: document.getElementById("btnSaveDocument"),
};

window.onload = () => {
  $(".summernote").summernote("disable");

  loadProjectDetails();
};

function loadProjectDetails() {
  $("#tblPartList").DataTable({
    pageLength: "50",
  });
}

buttons.generate.addEventListener("click", () => {
  inputForm.modal_token.value = inputForm.token.value;

  $("#modalGenerate").modal("show");
});

buttons.modal_generate.addEventListener("click", () => {
  try {
    loading();
    fetchData(
      baseurl + "/project/generate_apqp",
      "POST",
      JSON.stringify({ token: inputForm.modal_token.value })
    )
      .then((result) => {
        pesanSukses(result.message);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
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
});

function showApqpData(id_project, id_material) {
  const listApqp = document.getElementById("listApqp");
  try {
    loading();
    fetchData(
      baseurl + "/project/get_apqp",
      "POST",
      JSON.stringify({ id_project: id_project, id_material: id_material })
    )
      .then((result) => {
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            const row = `
              <tr>
                <td class="text-center">Stage - ${item.baris}</td>
                <td>
                  <a href="#" class="text-primary fw-bolder text-decoration-none" onclick="showDocument('${item.id_project}', '${item.id_material}', '${item.id_apqp}')">${item.apqp_level_name}</a>
                </td>
                <td>
                  <a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to show Approver details" onclick="showApproverData('${item.id_project}', '${item.id_material}', '${item.id_apqp}')"><i class="bi bi-info-circle"></i>
                    Show Approver
                  </a>
                </td>
              </tr>
            `;

            listApqp.insertAdjacentHTML("beforeend", row);
          });
        }
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

function showApproverData(id_project, id_material, id_apqp) {
  const tableBody = document.getElementById("listApprover");
  const modalTitle = document.getElementById("apqp_name");
  try {
    loading();
    fetchData(
      baseurl + "/project/get_approver",
      "POST",
      JSON.stringify({
        id_project: id_project,
        id_material: id_material,
        id_apqp: id_apqp,
      })
    )
      .then((result) => {
        tableBody.innerHTML = "";
        hideLoading();
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            const row = `
              <tr>
                <td class="editable">${item.NIK} - ${item.approver_name}</td>
                <td class="text-center align-middle">
                  <button type="button" class="btn-edit btn btn-info btn-sm rounded-0" title="Click to edit" onclick="editApprover(this, '${item.id}')"><i class="bi bi-pencil-square"></i>&ensp;Edit</button>
                  <button type="button" class="btn-delete btn btn-danger btn-sm rounded-0" title="Click to delete" onclick="deleteApprover(this, '${item.id}')"><i class="bi bi-x"></i>&ensp;Delete</button>
                  <button type="button" hidden class="btn-update btn btn-success btn-sm rounded-0" title="Click to edit" onclick="updateApprover(this, '${item.id}')"><i class="bi bi-floppy"></i>&ensp;Update</button>
                  <button type="button" hidden class="btn-cancel btn btn-warning btn-sm rounded-0" title="Click to delete" onclick="cancelEditApprover(this)"><i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel</button>
                </td>
              </tr>
            `;

            tableBody.insertAdjacentHTML("beforeend", row);
          });
        }
        $("#modalListApprover").modal("show");
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

function closeModalApprover() {
  const tableBody = document.getElementById("listApprover");
  tableBody.innerHTML = "";
}

function editApprover(button, id) {
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
      // console.log(selectElement.options[i].text.trim());
      if (selectElement.options[i].text.trim() === currentValue) {
        selectElement.options[i].selected = true;
        console.log(currentValue);
        break; // Berhenti loop jika sudah ketemu (Best Practice)
      }
    }

    cell.appendChild(selectElement);
  });

  $(".select2bs5").select2({
    dropdownParent: $("#modalListApprover"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });

  row.querySelectorAll(".btn-edit").forEach((btn) => {
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

  document.getElementById("btnSaveDocument").removeAttribute("hidden");
}

function cancelEditApprover(btn) {
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

function updateApprover(btn, token) {
  const row = btn.closest("tr");
  const editableCells = row.querySelectorAll(".editable");

  editableCells.forEach((cell) => {
    const selectElement = cell.querySelectorAll(".select2bs5");
    if (selectElement.length > 0) {
      row.removeAttribute("data-original-values");
      try {
        loading();
        fetchData(
          baseurl + "/project/update_approver",
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

function deleteApprover(btn, token) {
  const row = btn.closest("tr");
  try {
    hapusData("/project/delete_approver", token, row);
  } catch (e) {
    pesanError(e.message);
  }
}

function showDocument(id_project, id_material, id_apqp) {
  const tableBody = document.getElementById("listApqpDocument");
  try {
    loading();
    fetchData(
      baseurl + "/project/get_document",
      "POST",
      JSON.stringify({
        id_project: id_project,
        id_material: id_material,
        id_apqp: id_apqp,
      })
    )
      .then((result) => {
        console.log(result.data);
        tableBody.innerHTML = "";
        if (result.data.length > 0) {
          result.data.forEach((item) => {
            const row = `
              <tr>
                <td>${item.document_name}</td>
                <td class="editableUploader">${item.NIK} - ${item.uploader_name}</td>
                <td>
                  <input type="date" name="due_date[]" class="form-control rounded-0" required>
                  <input type="text" name="id_dokumen[]" value="${item.id}" class="form-control rounded-0" readonly>
                </td>
                <td class="align-middle text-center">
                  <button type="button" class="btn btn-sm btn-info rounded-0 btn-edit-document" onclick="editDocument(this)"><i class="bi bi-pencil-square"></i>&ensp;Edit</button>
                  <button type="button" class="btn btn-sm btn-danger rounded-0 btn-delete-document" onclick="deleteDocument(this, '${item.id}')"><i class="bi bi-dash-circle"></i>&ensp;Delete</button>
                  <button type="button" hidden class="btn btn-sm btn-success rounded-0 btn-update-document" onclick="updateDocument(this, '${item.id}')"><i class="bi bi-floppy"></i>&ensp;Update</button>
                  <button type="button" hidden class="btn btn-sm btn-warning rounded-0 btn-cancel-document" onclick="cancelDocument(this)"><i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel</button>
                </td>
              </tr>
            `;
            tableBody.insertAdjacentHTML("beforeend", row);
          });
        }
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

function editDocument(btn) {
  const row = btn.closest("tr");
  const editableCell = row.querySelectorAll(".editableUploader");

  let originalValue = [];
  editableCell.forEach((cell) => {
    originalValue.push(cell.textContent);
  });

  row.setAttribute("data-original-document-value", originalValue.join("|||"));

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
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });

  row.querySelectorAll(".btn-edit-document").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-delete-document").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-update-document").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-cancel-document").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
}

function cancelDocument(btn) {
  const row = btn.closest("tr");
  const editableCells = row.querySelectorAll(".editableUploader");
  const originalValuesString = row.getAttribute("data-original-document-value");

  if (!originalValuesString) return;

  const originalValues = originalValuesString.split("|||");

  editableCells.forEach((cell, index) => {
    cell.innerHTML = originalValues[index]; // Kembalikan ke teks asli
  });

  row.querySelectorAll(".btn-edit-document").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-delete-document").forEach((btn) => {
    btn.removeAttribute("hidden");
  });
  row.querySelectorAll(".btn-update-document").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });
  row.querySelectorAll(".btn-cancel-document").forEach((btn) => {
    btn.setAttribute("hidden", true);
  });

  row.removeAttribute("data-original-document-value");
}

buttons.save_document.addEventListener("click", (e) => {});
