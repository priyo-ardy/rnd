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
                        <li class="breadcrumb-item">Common Data</li>
                        <li class="breadcrumb-item">Customer</li>
                        <li class="breadcrumb-item">List of Customers</li>
                        <li class="breadcrumb-item active">Edit</li>
                        <li class="breadcrumb-item active"><?= $data->name ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="tooltip">
                        <button type="button" id="btnBack" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                            <i class="bi bi-arrow-left"></i>&ensp;Back
                        </button>
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add">
                            <i class="bi bi-plus-circle"></i>&ensp;Add
                        </button>
                        <button type="button" hidden id="btnUpdate" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Update">
                            <i class="bi bi-floppy"></i>&ensp;Update
                        </button>
                        <button type="button" id="btnEdit" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="bi bi-pencil-square"></i>&ensp;Edit
                        </button>
                        <button type="button" hidden id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                        </button>
                        <button type="button" id="btnPrev" class="btn shadow-none rounded-0 btn-light border-0" title="Previous">
                            <i class="bi bi-chevron-double-left"></i>&ensp;Previous
                        </button>
                        <button type="button" id="btnNext" class="btn shadow-none rounded-0 btn-light border-0" title="Previous">
                            Next&ensp;<i class="bi bi-chevron-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <form id="formData">
                    <div class="col-12 clearfix">
                        <div class="card rounded-0 card-primary card-outline">
                            <div class="card-body">
                                <div class="row mb-3 g-2">
                                    <div class="form-group col-12">
                                        <input type="hidden" name="data_token" id="data_token" required class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= enkripsi($data->id) ?>">
                                    </div>
                                </div>
                                <div class="row mb-3 g-2">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Code <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle text-primary fw-bolder" readonly placeholder="Customer Code" value="<?= $data->code ?>">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0 bg-secondary-subtle" readonly required maxlength="150" autofocus autocomplete="off" placeholder="Customer Name" value="<?= $data->name ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Address</label>
                                        <input type="text" name="data_alamat" id="data_alamat" class="form-control rounded-0 bg-secondary-subtle" readonly autocomplete="off" placeholder="Customer Address" value="<?= $data->address ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Email Address</label>
                                        <input type="email" class="form-control rounded-0 bg-secondary-subtle" readonly name="data_email" id="data_email" autocomplete="off" placeholder="Customer Email Address" maxlength="150" value="<?= ($data->email) ? dekripsi($data->email) : '' ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Phone Number</label>
                                        <input type="number" class="form-control rounded-0 bg-secondary-subtle" readonly name="data_phone" id="data_phone" autocomplete="off" placeholder="Customer Phone Number" maxlength="20" value="<?= ($data->phone) ? dekripsi($data->phone) : '' ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" class="form-control rounded-0 bg-secondary-subtle" readonly name="data_cp" id="data_cp" autocomplete="off" placeholder="Contact Person" maxlength="150" value="<?= $data->contact_person ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Contact Person Email Address</label>
                                        <input type="email" class="form-control rounded-0 bg-secondary-subtle" readonly name="data_cp_email" id="data_cp_email" autocomplete="off" placeholder="Contact Person Email Address" maxlength="150" value="<?= ($data->contact_person_email) ? dekripsi($data->contact_person_email) : '' ?>">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Contact Person Phone Number</label>
                                        <input type="number" class="form-control rounded-0 bg-secondary-subtle" readonly name="data_cp_phone" id="data_cp_phone" autocomplete="off" placeholder="Contact Person Phone Number" maxlength="150" value="<?= ($data->contact_person_phone) ? dekripsi($data->contact_person_phone) : '' ?>">
                                    </div>
                                    <div class="form-group col-xl-9 col-lg-9 col-md-12 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Additional Information"><?= $data->remark ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>