const formDocument = document.getElementById("formDocument");
const listDocument = document.getElementById("listDocument");

const documentInput = {
  title: document.getElementById("document-title"),
  token: document.getElementById("apqp_document_token"),
};

const documentButton = {
  save: document.getElementById("btnSaveDocument"),
};

function showDocument(token) {
  documentInput.title.innerHTML = "Document List";
  documentInput.token.value = token;
  try {
    fetchData(
      baseurl + "/apqp_document/get_document_list",
      "POST",
      JSON.stringify({ token: token })
    )
      .then((result) => {
        if (result.data.length > 0) {
          documentList(result.data);
        } else {
          firstRow();
        }
        $("#modalDocument").modal("show");
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

function documentList(data) {
  listDocument.innerHTML = "";
  data.forEach((item) => {
    const row = `
        <tr>
            <td class="editableDocument">
                ${item.nama_dokumen}
            </td>
            <td class="editableUploader">
                ${item.NIK} - ${item.uploader_name}
            </td>
            <td>
                <button type="button" class="btn btn-add btn-success rounded-0 btn-sm" onclick="addRow(this)"><i class="bi bi-plus-circle"></i>&ensp;Add</button>
                <button type="button" class="btn btn-edit btn-warning rounded-0 btn-sm" onclick="editDocument(this)"><i class="bi bi-pencil-square"></i>&ensp;Edit</button>
                <button type="button" class="btn btn-delete btn-danger rounded-0 btn-sm" onclick="hapusDocument(this, '${item.id}')"><i class="bi bi-x"></i>&ensp;Delete</button>
                <button hidden type="button" class="btn btn-update btn-primary rounded-0 btn-sm" onclick="updateDocument(this, '${item.id}')"><i class="bi bi-floppy"></i>&ensp;Update</button>
                <button hidden type="button" class="btn btn-cancel btn-warning rounded-0 btn-sm" onclick="cancelDocument(this)"><i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel</button>
            </td>
        </tr>
    `;

    listDocument.insertAdjacentHTML("beforeend", row);
  });
}

function firstRow() {
  listDocument.innerHTML = "";
  const row = `
    <tr>
        <td>
            <input type="text" name="nama_dokumen[]" class="form-control rounded-0" placeholder="Document Name" maxlength="150" required>
            <div class="invalid-feedback"></div>
        </td>
        <td>
            <select name="uploader[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose Uploader --</option>
                ${document.getElementById("listUsers").innerHTML}
            </select>
            <div class="invalid-feedback"></div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()" title="Add new row"><i class="bi bi-plus-circle"></i></button>
        </td>
    </tr>
  `;

  listDocument.insertAdjacentHTML("beforeend", row);
  documentButton.save.removeAttribute("hidden");

  $(".select2bs5").select2({
    dropdownParent: $("#modalDocument"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

function addRow() {
  const row = `
    <tr>
        <td>
            <input type="text" name="nama_dokumen[]" class="form-control rounded-0" placeholder="Document Name" maxlength="150" required>
            <div class="invalid-feedback"></div>
        </td>
        <td>
            <select name="uploader[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose Uploader --</option>
                ${document.getElementById("listUsers").innerHTML}
            </select>
            <div class="invalid-feedback"></div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()" title="Add new row"><i class="bi bi-plus-circle"></i></button>
            <button type="button" class="btn btn-sm btn-danger rounded-0" onclick="removeRow(this)" title="Add new row"><i class="bi bi-dash-circle"></i></button>
        </td>
    </tr>
  `;

  listDocument.insertAdjacentHTML("beforeend", row);
  documentButton.save.removeAttribute("hidden");

  $(".select2bs5").select2({
    dropdownParent: $("#modalDocument"),
    theme: "bootstrap-5",
    dropdownCssClass: "rounded-0",
    selectionCssClass: "rounded-0",
  });
}

function closeModalDocument() {
  listDocument.innerHTML = "";
}

function validasiDocument() {
  let isValid = true;
  const requiredElement = formDocument.querySelectorAll("[required]");
  if (requiredElement.length > 0) {
    requiredElement.forEach((element) => {
      if (element.value == "") {
        isValid = false;
        element.classList.add("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent =
          "This field is required";
      } else {
        element.classList.remove("is-invalid");
        element.parentNode.querySelector(".invalid-feedback").textContent = "";
      }
    });
  }

  return isValid;
}

documentButton.save.addEventListener("click", (e) => {
  if (validasiDocument()) {
    try {
      loading();
      fetchData(
        baseurl + "/apqp_document/save_document_list",
        "POST",
        new FormData(formDocument)
      )
        .then((result) => {
          pesanSukses(result.message);
          hideLoading();
          closeModalDocument();
          $("#modalDocument").modal("hide");
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

function hapusDocument(button, token) {
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
          fetchData(
            baseurl + "/apqp_document/delete_document",
            "POST",
            JSON.stringify({ token: token })
          )
            .then((result) => {
              pesanSukses(result.message);
              removeRow(button);
            })
            .catch((err) => {
              pesanError(err.message);
            })
        );
      }
    });
}

function editDocument(button) {
  const row = button.closest("tr");
  const namaDocument = row.querySelectorAll(".editableDocument");
  const namaUploader = row.querySelectorAll(".editableUploader");

  let namaDokumenOriginal = [];
  let namaUploaderOriginal = [];

  namaDocument.forEach((cell) => {
    namaDokumenOriginal.push(cell.textContent.trim());
  });

  namaUploader.forEach((cell) => {
    namaUploaderOriginal.push(cell.textContent.trim());
  });

  row.setAttribute(
    "data-original-document-value",
    `${namaDokumenOriginal.join("|||")}`
  );

  row.setAttribute(
    "data-original-uploader-value",
    `${namaUploaderOriginal.join("|||")}`
  );

  namaDocument.forEach((cell) => {
    const currentValue = cell.textContent.trim();
    cell.innerHTML = "";

    cell.innerHTML = `<input type="text" name="nama_dokumen[]" class="form-control rounded-0" value="${currentValue}" placeholder="Document Name" maxlength="150" value="${cell.textContent.trim()}" required>
        <div class="invalid-feedback"></div>`;
  });

  namaUploader.forEach((cell) => {
    const currentValue = cell.textContent.trim();
    cell.innerHTML = "";

    const selectElement = document.createElement("select");
    selectElement.className = "form-control select2 select2bs5";
    selectElement.name = "uploader[]";
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
    dropdownParent: $("#modalDocument"),
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

function updateDocument(button, token) {
  const row = button.closest("tr");
  const namaDocument = row.querySelector("input[name='nama_dokumen[]']");
  const namaUploader = row.querySelector("select[name='uploader[]']");
  const editableDocument = row.querySelectorAll(".editableDocument");
  const editableUploader = row.querySelectorAll(".editableUploader");

  if (namaDocument.value == "") {
    namaDocument.classList.add("is-invalid");
  } else if (namaUploader.value == "") {
    namaUploader.classList.add("is-invalid");
  } else {
    namaDocument.classList.remove("is-invalid");
    namaUploader.classList.remove("is-invalid");

    try {
      loading();
      fetchData(
        baseurl + "/apqp_document/update_document",
        "POST",
        JSON.stringify({
          token: token,
          nama_dokumen: namaDocument.value,
          uploader: namaUploader.value,
        })
      )
        .then((result) => {
          pesanSukses(result.message);

          editableDocument.forEach((cell) => {
            cell.innerHTML = result.data.nama_dokumen;
          });

          editableUploader.forEach((cell) => {
            cell.innerHTML =
              result.data.NIK + " - " + result.data.uploader_name;
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
}

function cancelDocument(btn) {
  const row = btn.closest("tr");
  const namaDokumen = row.querySelectorAll(".editableDocument");
  const namaUploader = row.querySelectorAll(".editableUploader");
  const originalDocument = row.getAttribute("data-original-document-value");
  const originalUploader = row.getAttribute("data-original-uploader-value");

  if (!originalDocument || !originalUploader) return;

  const originalDocumentValue = originalDocument.split("|||");
  const originalUploaderValue = originalUploader.split("|||");

  namaDokumen.forEach((cell, index) => {
    cell.innerHTML = originalDocumentValue[index]; // Kembalikan ke teks asli
  });

  namaUploader.forEach((cell, index) => {
    cell.innerHTML = originalUploaderValue[index]; // Kembalikan ke teks asli
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
