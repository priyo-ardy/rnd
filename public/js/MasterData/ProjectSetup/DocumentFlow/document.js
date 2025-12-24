const listLevel = document.getElementById("listLevel");

const inputFlow = {
  name: document.getElementById("flow_name"),
};

const buttonsFlow = {
  closeFlow: document.getElementById("btnCloseFlow"),
  saveFlow: document.getElementById("btnSaveFlow"),
};

buttonsFlow.closeFlow.addEventListener("click", () => {
  inputFlow.name.value = "";
  $("#modalFlow").modal("hide");
});

function getFlowLevel() {
  listLevel.innerHTML = "";
  fetchData(baseurl + "/document-flow/get-flow-level", "GET")
    .then((result) => {
      if (result.data.length > 0) {
        result.data.forEach((item) => {
          const list = `
            <li class="list-group-item">
              <a href="" onclick="getDocumentStage('${item.id}')" class="text-primary text-decoration-none link-underline-opacity-100-hover">
                ${item.name}
              </a>
            </li>
          `;

          listLevel.insertAdjacentHTML("beforeend", list);
        });
      }
    })
    .catch((err) => {
      pesanError(err.message);
    });
}

buttonsFlow.saveFlow.addEventListener("click", () => {
  if (inputFlow.name.value == "") {
    inputFlow.name.classList.add("is-invalid");
    inputFlow.name.parentNode.querySelector(".invalid-feedback").textContent =
      "This field is required";
  } else {
    inputFlow.name.classList.remove("is-invalid");
    inputFlow.name.parentNode.querySelector(".invalid-feedback").textContent =
      "";

    try {
      loading();
      fetchData(
        baseurl + "/document-flow/save-flow",
        "POST",
        JSON.stringify({
          name: inputFlow.name.value,
        })
      )
        .then((result) => {
          pesanSukses(result.message);
          inputFlow.name.value = "";
          getFlowLevel();
          hideLoading();
        })
        .catch((err) => {
          pesanError(err.message);
          hideLoading();
        });
    } catch (e) {
      inputFlow.name.classList.add("is-invalid");
      inputFlow.name.parentNode.querySelector(".invalid-feedback").textContent =
        e.message;
      hideLoading();
    }
  }
});

function getDocumentStage(token) {
  document.getElementById("level_token").value = token;
  const tableBody = document.getElementById("listDocument");
  try {
    loading();
    fetchData(
      baseurl + "/document-flow/get-document-list",
      "POST",
      JSON.stringify({ token: token })
    )
      .then((result) => {
        hideLoading();
        console.log(result.data);
      })
      .catch((err) => {
        const row = `
          <tr>
            <td class="align-middle">
              <select name="document[]" class="form-control select2 select2bs5" required>
                <option value="">-- Choose Document --</option>
              </select>
            </td>
            <td class="align-middle">
              <input type="number" name="sequence[]" class="form-control rounded-0" placeholder="Sequence" required step="1">
            </td>
            <td class="align-middle text-center">
              <button type="button" class="btn btn-primary rounded-0 btn-sm">Add</button>
            </td>
          </tr>
        `;

        tableBody.insertAdjacentHTML("beforeend", row);

        $(".select2bs5").select2({
          theme: "bootstrap-5",
          dropdownCssClass: "rounded-0",
          selectionCssClass: "rounded-0",
        });

        hideLoading();
      });
  } catch (e) {
    pesanError(e.message);
    hideLoading();
  }
}
