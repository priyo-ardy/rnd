<?= $this->extend('Template/layout') ?>

<?= $this->section('content') ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= $title; ?></h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard'  ?>">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item">Common Data</li>
                        <li class="breadcrumb-item active">Customer Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2 mb-3">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 clearfix">
                    <form id="formData">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header">
                                <h5 class="card-title"><i class="bi bi-pencil-square"></i>&ensp;Form Data</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3" style="display: none;">
                                    <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label" for="data_name">Category Name</label>
                                    <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required maxlength="150" autofocus autocomplete="off" placeholder="Category Name">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-group" for="data_remark">Remark</label>
                                    <textarea name="data_remark" id="data_remark" class="form-control rounded-0" rows="3" placeholder="Remark"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <button type="button" id="btnCancel" class="btn btn-secondary rounded-0">
                                        <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                                    </button>
                                    <button hidden type="button" id="btnUpdate" class="btn btn-primary rounded-0">
                                        <i class="bi bi-floppy"></i>&ensp;Update
                                    </button>
                                    <button type="button" id="btnSave" class="btn btn-primary rounded-0">
                                        <i class="bi bi-floppy"></i>&ensp;Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-3 clearfix">
                    <div class="card rounded-0 card-primary card-outline">
                        <div class="card-header rounded-0">
                            <h5 class="card-title"><i class="bi bi-list-ul"></i>&ensp;List of Customer Category</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool fw-bold text-dark" title="Export Data" id="btnExport"><i class="bi bi-download"></i></button>
                                <button type="button" class="btn btn-tool fw-bold text-dark" title="Refresh" id="btnRefresh"><i class="bi bi-arrow-repeat"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered table-primary" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle bg-secondary-subtle col-2">Code</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-3">Name</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-6">Remark</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-1">#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>