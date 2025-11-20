<?= $this->extend('Template/layout'); ?>

<?= $this->section('content'); ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class='bi bi-bezier2'></i>&ensp;<?= $title; ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item">Project Setup</li>
                        <li class="breadcrumb-item active">Project List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <form id="formData">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="toolbar">
                            <button type="button" id="btnBack" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="New">
                                <i class="bi bi-arrow-left"></i>&ensp;Back
                            </button>
                            <button type="button" id="btnSave" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                                <i class="bi bi-floppy"></i>&ensp;Save
                            </button>
                            <button type="button" id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">
                                <i class="bi bi-arrow-clockwise"></i>&ensp;Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header rounded-0">
                            <h5 class="card-title">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 g-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                    <label class="form-label" for="data_code">Project Code <strong class="text-danger fw-bolder">*</strong></label>
                                    <input type="text" name="data_code" id="data_code" class="form-control rounded-0" maxlength="50" required placeholder="Project Code" autofocus autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                    <label class="form-label" for="data_name">Project Name <strong class="text-danger fw-bolder">*</strong></label>
                                    <input type="text" name="data_name" id="data_name" class="form-control rounded-0" maxlength="150" required placeholder="Project Name">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group  col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                    <label class="form-label" for="data_type">Project Type <strong class="text-danger fw-bolder">*</strong></label>
                                    <select name="data_type" id="data_type" class="form-control select2 select2bs5" required>
                                        <option value="">-- Choose --</option>
                                        <option value="1">New Project</option>
                                        <option value="2">Transfer Project</option>
                                        <option value="3">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group  col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                    <label class="form-label" for="data_customer">Customer <strong class="text-danger fw-bolder">*</strong></label>
                                    <select name="data_customer" id="data_customer" class="form-control select2 select2bs5" required>
                                        <option value="">-- Choose --</option>
                                        <?php foreach ($customer_list as $customer): ?>
                                            <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group col-12 clearfix">
                                    <label class="form-label" for="data_remark">Remark</label>
                                    <textarea name="data_remark" id="data_remark" class="form-control rounded-0 summernote" placeholder="Additional Information"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="card rounded-0 card-warning card-outline">
                        <div class="card-header rounded-0">
                            <h5 class="card-title"><i class="bi bi-list-ul"></i>&ensp;Project Part List</h5>
                            <div class="card-tools">
                                <button type="button" id="btnDate" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add">
                                    <i class="bi bi-calendar-date"></i>&ensp;Set Mass Due Date
                                </button>
                                <button type="button" id="btnAddPart" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add">
                                    <i class="bi bi-plus-circle"></i>&ensp;Add New Part No
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-primary" id="tblPartList">
                                    <thead>
                                        <tr>
                                            <th class="bg-secondary-subtle text-center align-middle">Part No</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Part No</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Remark</th>
                                            <th class="bg-secondary-subtle text-center align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="partList">
                                        <tr>
                                            <td class="col-4 align-middle">
                                                <select name="part_no[]" class="form-control select2 select2bs5" required>
                                                    <option value="">-- Choose --</option>
                                                    <?php foreach ($material_list as $material) : ?>
                                                        <option value="<?= $material->id ?>"><?= "$material->code - $material->name" ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </td>
                                            <td class="col-2 align-middle">
                                                <input type="date" name="due_date[]" class="form-control rounded-0" required>
                                                <div class="invalid-feedback"></div>
                                            </td>
                                            <td class="col-5 align-middle">
                                                <input type="text" name="remark[]" class="form-control rounded-0" placeholder="Remark">
                                            </td>
                                            <td class="col-1 align-middle text-center">
                                                <button type="button" class="btn btn-sm btn-success rounded-0" onclick="addRow()"><i class="bi bi-plus-circle align-middle"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?= $this->include('MasterData/ProjectSetup/Project/modal')  ?>

<div id="listMaterial" style="display: none;">
    <?php foreach ($material_list as $material) : ?>
        <option value="<?= $material->id ?>"><?= "$material->code - $material->name" ?></option>
    <?php endforeach; ?>
</div>
<?= $this->endSection(); ?>