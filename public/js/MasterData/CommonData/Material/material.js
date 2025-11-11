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
  $("#dataTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    bDestroy: true,
    search: {
      return: true,
    },
    order: [],
    ajax: {
      url: baseurl + "/material/table",
      type: "POST",
      data: "raw",
      action: "calls",
    },
    deferRender: true,
    columnDefs: [
      {
        targets: 0,
        orderable: false,
      },
    ],
  });
}

function refreshTable() {
  $("#dataTable").DataTable().ajax.reload(null, false);
}

function deleteData(token) {
  try {
    hapusData("/material/delete", token);
  } catch (e) {
    pesanError(e.message);
  }
}

buttons.refresh.addEventListener("click", () => {
  refreshTable();
});

buttons.export.addEventListener("click", async () => {
  try {
    loading();

    // Buat AbortController untuk timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 300000);

    // Lakukan fetch dengan streaming

    const response = await fetch(baseurl + "/material/export", {
      method: "GET",
      signal: controller.signal,
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      const errorData = await response.json().catch(() => null);
      throw new Error(
        errorData?.error || `HTTP error! status: ${response.status}`
      );
    }

    // Dapatkan Blob
    const blob = await response.blob();

    if (blob.size === 0) {
      throw new Error("Failed to creating exported file");
    }

    // Buat link downlaod
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.style.display = "none";
    a.href = url;
    a.download =
      "material_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
    document.body.appendChild(a);
    a.click();

    // Bersihkan
    window.URL.revokeObjectURL(url);
    a.remove();
    hideLoading();
  } catch (e) {
    if (e.name === "AbortError") {
      pesanError(
        "Proses ekspor terlalu lama. Silakan coba lagi atau ekspor data lebih kecil."
      );
    } else {
      pesanError(e.message || "Gagal mengekspor data");
    }

    hideLoading();
  }
});
