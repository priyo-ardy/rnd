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
                        <li class="breadcrumb-item active">Add</li>
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
                        <button type="button" id="btnSave" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
                            <i class="bi bi-floppy"></i>&ensp;Save
                        </button>
                        <button type="button" id="btnCancel" class="btn shadow-none rounded-0 btn-light-order-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
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
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Code <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Customer Code">
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="data_name" id="data_name" class="form-control rounded-0" required maxlength="150" autofocus autocomplete="off" placeholder="Customer Name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Address</label>
                                        <input type="text" name="data_alamat" id="data_alamat" class="form-control rounded-0" autocomplete="off" placeholder="Customer Address">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Email Address</label>
                                        <input type="email" class="form-control rounded-0" name="data_email" id="data_email" autocomplete="off" placeholder="Customer Email Address" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Customer Phone Number</label>
                                        <input type="number" class="form-control rounded-0" name="data_phone" id="data_phone" autocomplete="off" placeholder="Customer Phone Number" maxlength="20">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" class="form-control rounded-0" name="data_cp" id="data_cp" autocomplete="off" placeholder="Contact Person" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Contact Person Email Address</label>
                                        <input type="email" class="form-control rounded-0" name="data_cp_email" id="data_cp_email" autocomplete="off" placeholder="Contact Person Email Address" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Contact Person Phone Number</label>
                                        <input type="number" class="form-control rounded-0" name="data_cp_phone" id="data_cp_phone" autocomplete="off" placeholder="Contact Person Phone Number" maxlength="150">
                                    </div>
                                    <div class="form-group col-xl-9 col-lg-9 col-md-12 col-sm-12 mb-3 clearfix">
                                        <label class="form-label">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control rounded-0" placeholder="Additional Information"></textarea>
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