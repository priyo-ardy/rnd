const inputForm = {
  token: document.getElementById("data_token"),
  code: document.getElementById("data_code"),
  name: document.getElementById("data_name"),
  type: document.getElementById("data_type"),
  customer: document.getElementById("data_customer"),
  remark: document.getElementById("data_remark"),
  modal_token: document.getElementById("modal_token"),
};

const buttons = {
  back: document.getElementById("btnBack"),
  generate: document.getElementById("btnGenerate"),
  modal_generate: document.getElementById("btnModalGenerate"),
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
                <td>${item.apqp_level_name}</td>
                <td>
                  <a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to show Approver details" onclick="showApproverData('${item.id_project}', '${item.id_material}')"><i class="bi bi-info-circle"></i>
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

function showApproverData(id_project, id_material) {
  try {
    loading();
    fetchData(
      baseurl + "/project/get_approver",
      "POST",
      JSON.stringify({ id_project: id_project, id_material: id_material })
    )
      .then((result) => {
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
