window.onload = () => {
  $(".summernote").summernote("disable");

  loadProjectDetails();
};

function loadProjectDetails() {
  $("#tblPartList").DataTable({
    pageLength: "50",
  });
}
