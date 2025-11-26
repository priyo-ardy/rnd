<?= $this->extend('Template/layout'); ?>

<?= $this->section('content'); ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class='bi bi-bezier2'></i>&ensp;<?= "$title | $data_header->code" ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item">Project Setup</li>
                        <li class="breadcrumb-item">Project Details</li>
                        <li class="breadcrumb-item active"><?= $data_header->code  ?></li>
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
                            <button type="button" id="btnGenerate" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate APQP">
                                <i class="bi bi-gear"></i>&ensp;Generate APQP
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 mb-3 clearfix">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header rounded-0">
                                <h5 class="card-title"><i class="bi bi-pencil-square"></i>&ensp;Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3 g-2">
                                    <div class="form-group col-12">
                                        <input type="hidden" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= enkripsi($data_header->id) ?>">
                                    </div>
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_code">Project Code <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" disabled name="data_code" id="data_code" class="form-control rounded-0" maxlength="50" required placeholder="Project Code" autofocus autocomplete="off" value="<?= $data_header->code ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_name">Project Name <strong class="text-danger fw-bolder">*</strong></label>
                                        <input type="text" disabled name="data_name" id="data_name" class="form-control rounded-0" maxlength="150" required placeholder="Project Name" value="<?= $data_header->name ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group  col-xl-12 col-lg-12 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_type">Project Type <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_type" disabled id="data_type" class="form-control select2 select2bs5" required>
                                            <option <?= ($data_header->project_type == '') ? 'selected' : '' ?> value="">-- Choose --</option>
                                            <option <?= ($data_header->project_type == '1') ? 'selected' : '' ?> value="1">New Project</option>
                                            <option <?= ($data_header->project_type == '2') ? 'selected' : '' ?> value="2">Transfer Project</option>
                                            <option <?= ($data_header->project_type == '3') ? 'selected' : '' ?> value="3">Other</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group  col-xl-12 col-lg-12 col-md-12 col-sm-12 clearfix">
                                        <label class="form-label" for="data_customer">Customer <strong class="text-danger fw-bolder">*</strong></label>
                                        <select name="data_customer" disabled id="data_customer" class="form-control select2 select2bs5" required>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($customer_list as $customer): ?>
                                                <option <?= ($data_header->customer == $customer->id) ? 'selected' : '' ?> value="<?= $customer->id ?>"><?= $customer->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-12 clearfix">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control rounded-0 summernote" placeholder="Additional Information"><?= $data_header->remark ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 mb-3 clearfix">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header rounded-0">
                                <h5 class="card-title"><i class="bi bi-list-ul"></i>&ensp;Part List</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-primary" id="tblPartList">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle bg-secondary-subtle col-3">Part No</th>
                                                <th class="text-center align-middle bg-secondary-subtle col-2">Part Name</th>
                                                <th class="text-center align-middle bg-secondary-subtle col-5">Specification</th>
                                                <th class="text-center align-middle bg-secondary-subtle col-2">Due Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data_details as $row) : ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <a href="#listApqp" class="text-primary fw-bolder text-decoration-none" title="Click to show APQP details" onclick="showApqpData('<?= $data_header->id ?>', '<?= $row->id_material ?>')">
                                                            <?= $row->material_code ?>
                                                        </a>
                                                    </td>
                                                    <td class=" align-middle"><?= $row->material_name ?></td>
                                                    <td class="align-middle"><?= $row->material_spesifikasi  ?></td>
                                                    <td class="align-middle"><?= date("d-M-Y", strtotime($row->due_date)) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" row g-2 mb-3">
                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 mb-3 clearfix">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header rounded-0">
                                <h5 class="card-title">APQP Level</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-primary" id="tblApqp">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle bg-secondary-subtle">APQP Stage</th>
                                                <th class="text-center align-middle bg-secondary-subtle">APQP Stage Name</th>
                                                <th class="text-center align-middle bg-secondary-subtle">Approver</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listApqp"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 mb-3 clearfix">
                        <div class="card card-primary card-outline rounded-0">
                            <form id="formDocument">
                                <div class="card-header rounded-0">
                                    <h5 class="card-title">APQP Document</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-primary" id="tblApqpDocument">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">Document Name</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">Uploader</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">Due Date</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="listApqpDocument"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <input type="text" class="form-control rounded-0" name="id_project" readonly id="id_project" value="<?= $data_header->id ?>">
                                    <input type="text" class="form-control rounded-0" name="id_material" readonly id="id_material" value="">
                                    <input type="text" class="form-control rounded-0" name="id_apqp" readonly id="id_material" value="">
                                    <button type="button" id="btnSaveDocument" class="btn btn-primary rounded-0"><i class="bi bi-floppy"></i>&ensp;Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <div class="card rounded-0">
                            <div class="card-body">
                                <button class="btn btn-success rounded-0 w-100 d-block">Complete & Start Project</button>
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

<div id="listUsers" style="display: none;">
    <option value="">-- Choose --</option>
    <?php foreach ($user_list as $user): ?>
        <option value="<?= $user->user_id ?>"><?= "$user->user_name - $user->full_name"  ?></option>
    <?php endforeach; ?>
</div>
<?= $this->endSection(); ?>