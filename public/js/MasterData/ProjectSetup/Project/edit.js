window.onload = () => {
  $(".summernote").summernote("disable");

  loadProjectDetails();
};

function loadProjectDetails() {
  $("#tblPartList").DataTable();
}
