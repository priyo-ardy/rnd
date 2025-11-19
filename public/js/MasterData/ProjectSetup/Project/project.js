const buttons = {
  add: document.getElementById("btnAdd"),
  filter: document.getElementById("btnFilter"),
  search: document.getElementById("btnSearch"),
  refresh: document.getElementById("btnRefresh"),
  sort: document.getElementById("btnSort"),
};

buttons.add.addEventListener("click", () => {
  loading();
  window.location.replace(baseurl + "/project/add");
});
