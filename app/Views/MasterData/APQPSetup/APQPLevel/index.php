<?= $this->extend('Template/layout'); ?>

<?= $this->section('content'); ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= $title; ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item">APQP Setup</li>
                        <li class="breadcrumb-item active">Setup APQP Level</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 mb-3 clearfix">
                    <form id="dataForm">
                        <div class="card rounded-0 card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="bi bi-input-cursor-text"></i>&ensp;Form Data
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3" style="display: none;">
                                    <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Level</label>
                                    <input type="number" name="data_level" id="data_level" class="form-control rounded-0" placeholder="APQP level" autofocus required min="1" max="99" step="1" autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Name <strong class="text-danger">*</strong></label>
                                    <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required maxlength="150" autocomplete="off" placeholder="APQP level name">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Remark</label>
                                    <textarea name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Additional Information"></textarea>
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

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 mb3 clearfix">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header rounded-0">
                            <h5 class="card-title"><i class="bi bi-list-ul"></i>&ensp;Material Category List</h5>
                            <div class="card-tools">
                                <button type="button" id="btnRefresh" class="btn btn-tool text-black fw-bold" title="Refresh"><i class="bi bi-arrow-repeat"></i></button>
                                <button type="button" id="btnExport" class="btn btn-tool text-black fw-bold" title="Export Data"><i class="bi bi-download"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-primary" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle bg-secondary-subtle col-1">Level</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-3">Name</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-2">Approver</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-2">Document List</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-3">Remark</th>
                                            <th class="text-center align-middle bg-secondary-subtle col-1">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>

<?= $this->include('MasterData/APQPSetup/APQPLevel/modal') ?>
<?= $this->endSection(); ?>