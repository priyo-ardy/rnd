window.onload = () => {
  loadTable();
};

const buttons = {
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  refresh: document.getElementById("btnRefresh"),
  export: document.getElementById("btnExport"),
};

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/material/add");
});

function loadTable() {
  $("#dataTable").DataTable();
}

function refreshTable() {
  $("#dataTable").DataTable().ajax.reload(null, false);
}
