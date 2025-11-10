const dataForm = document.getElementById('dataForm');
const table = document.getElementById('dataTable');

window.onload = () => {
    loadTable();
};

const inputForm = {
    token: document.getElementById('data_token'),
    code: document.getElementById('data_code'),
    name: document.getElementById('data_name'),
    route: document.getElementById('data_simbol'),
    remark: document.getElementById('data_remark'),
};

const buttons = {
    cancel: document.getElementById('btnCancel'),
    save: document.getElementById('btnSave'),
    update: document.getElementById('btnUpdate'),
    export: document.getElementById('btnExport'),
    refresh: document.getElementById('btnRefresh'),
};

function validasi() {
    let isValid = true;

    const requiredElement = document.querySelectorAll("[required]");
    if (requiredElement.length > 0) {
        requiredElement.forEach((element) => {
            if (element.value == "") {
                isValid = false;
                element.classList.add("is-invalid");
                element.parentNode.querySelector(".invalid-feedback").textContent = "This field is required";
            } else {
                element.classList.remove("is-invalid");
                element.parentNode.querySelector(".invalid-feedback").textContent = "";
            }
        });
    }
    return isValid;
}

function clearForm() {
    dataForm.reset();
    inputForm.name.focus();
}

buttons.save.addEventListener("click", () => {
    if (validasi()) {
        try {
            loading();
            fetchData(baseurl + '/routes/save', 'POST', new FormData(dataForm))
                .then((result) => {
                    pesanSukses(result.message);
                    hideLoading();
                    clearForm();
                    refreshTable();
                }).catch((err) => {
                    pesanError(err.message);
                    hideLoading();
                });
        } catch (e) {
            pesanError(e.message);
            hideLoading();
        }
    }
});

buttons.refresh.addEventListener("click", () => {
    refreshTable();
});

function loadTable() {
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        bDestroy: true,
        search: {
            return: true,
        },
        order: [],
        ajax: {
            url: baseurl + "/routes/table",
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
    $('#dataTable').DataTable().ajax.reload(null, false);
}

function editData(token) {
    try {
        loading();
        fetchData(baseurl + '/routes/edit', 'POST', JSON.stringify({ token: token }))
            .then(result => {
                inputForm.token.value = result.data.token;
                inputForm.code.value = result.data.code;
                inputForm.name.value = result.data.name;
                inputForm.route.value = result.data.route;
                inputForm.remark.value = result.data.remark;
                inputForm.name.focus();
                buttons.update.removeAttribute('hidden');
                buttons.save.setAttribute('hidden', true);
                hideLoading();
            })
            .catch(err => {
                pesanError(err.message);
            });
    } catch (e) {
        pesanError(e.message);
        hideLoading();
    }
}

function validasiUpdate() {
    let isValid = true;

    if (inputForm.token.value == '') {
        isValid = false;
    }

    if (inputForm.code.value == '') {
        isValid = false;
        inputForm.code.classList.add('is-invalid');
        inputForm.code.parentNode.querySelector('.invalid-feedback').textContent = 'This field is required';
    } else {
        inputForm.code.classList.remove('is-invalid');
    }

    if (!validasi()) {
        isValid = false;
    }

    return isValid;
}

buttons.update.addEventListener('click', (e) => {
    if (validasiUpdate) {
        try {
            loading();
            fetchData(baseurl + '/routes/update', 'POST', new FormData(dataForm))
                .then((result) => {
                    pesanSukses(result.message);
                    hideLoading();
                    clearForm();
                    refreshTable();
                }).catch((err) => {
                    pesanError(err.message);
                    hideLoading();
                });
        } catch (e) {
            pesanError(e.message);
            hideLoading();
        }
    }
})

function deleteData(token) {
    try {
        hapusData("/routes/delete", token);
    } catch (e) {
        pesanError(e.message);
    }
}

buttons.export.addEventListener('click', async () => {
    try {
        loading();

        // Buat AbortController untuk timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 300000);

        // Lakukan fetch dengan streaming

        const response = await fetch(baseurl + "/routes/export", {
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
            "production_routes_list_" + moment().format("YYYYMMDD_HHMMSS") + ".xlsx";
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
})