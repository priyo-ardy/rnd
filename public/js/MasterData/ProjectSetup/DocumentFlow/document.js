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
            <li class="list-group-item">${item.name}</li>
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
