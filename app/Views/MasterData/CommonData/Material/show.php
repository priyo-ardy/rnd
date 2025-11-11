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
                        <li class="breadcrumb-item">Material</li>
                        <li class="breadcrumb-item">List of Material</li>
                        <li class="breadcrumb-item active">Show</li>
                        <li class="breadcrumb-item active"><?= $data->code ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <form id="formData">
                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="toolbar">
                            <button type="button" id="btnBack" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Back">
                                <i class="bi bi-arrow-left"></i>&ensp;Back
                            </button>
                            <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Add">
                                <i class="bi bi-plus-circle"></i>&ensp;Add
                            </button>
                            <button type="button" id="btnEdit" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="bi bi-pencil-square"></i>&ensp;Edit
                            </button>
                            <button type="button" hidden id="btnUpdate" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Update">
                                <i class="bi bi-floppy"></i>&ensp;Update
                            </button>
                            <button type="button" hidden id="btnCancel" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                                <i class="bi bi-arrow-counterclockwise"></i>&ensp;Cancel
                            </button>
                            <button type="button" id="btnPrev" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous">
                                <i class="bi bi-chevron-double-left"></i>&ensp;Prev
                            </button>
                            <button type="button" id="btnNext" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Next">
                                Next&ensp;<i class="bi bi-chevron-double-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-body">
                                <div class="row g-2 mb-3">
                                    <div class="form-group col-12 mb-3 clearfix">
                                        <input type="text" name="data_token" id="data_token" class="form-control rounded-0 bg-secondary-subtle" readonly value="<?= enkripsi($data->id)  ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_kategori">Material Category <strong class="text-danger">*</strong></label>
                                        <select name="data_kategori" id="data_kategori" class="form-control select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($kategori as $ktg): ?>
                                                <option <?= ($data->kategori) == $ktg->id ? 'selected' : '' ?> value="<?= $ktg->id ?>"><?= $ktg->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class=" form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 clearfix mb-3">
                                        <label class="form-label" for="data_code">Material Code <strong class="text-danger">*</strong></label>
                                        <input type="text" name=" data_code" id="data_code" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Material Code" maxlength="100" autofocus autocomplete="off" required value="<?= $data->code ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix mb-3">
                                        <label class="form-label" for="data_name">Material Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name=" data_name" id="data_name" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Material Name" maxlength="150" autocomplete="off" required value="<?= $data->name ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix mb-3">
                                        <label class="form-label" for="data_spesifikasi">Material Specification <strong class="text-danger">*</strong></label>
                                        <textarea name=" data_spesifikasi" id="data_spesifikasi" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Material Specification" rows="1" autocomplete="off" required rows="1"><?= $data->spesifikasi ?></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_satuan">UoM <strong class="text-danger">*</strong></label>
                                        <select name="data_satuan" id="data_satuan" class="form-control select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($satuan as $uom): ?>
                                                <option <?= ($data->satuan) == $uom->id ? 'selected' : '' ?> value="<?= $uom->id; ?>"><?= "$uom->simbol - $uom->name" ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_workshop">Workshop <strong class="text-danger">*</strong></label>
                                        <select name="data_workshop" id="data_workshop" class="form-control select2 select2bs5" required disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($workshop as $whs): ?>
                                                <option <?= ($data->workshop) == $whs->id ? 'selected' : '' ?> value="<?= $whs->id ?>"><?= $whs->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_route">Production Routes</label>
                                        <select name="data_route" id="data_route" class="form-control select2 select2bs5" disabled>
                                            <option value="">-- Choose --</option>
                                            <?php foreach ($route as $rts): ?>
                                                <option <?= ($data->route) == $rts->id ? 'selected' : '' ?> value="<?= $rts->id ?>"><?= $rts->route ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_color">Product Color</label>
                                        <input type="text" name="data_color" id="data_color" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Product Color" maxlength="50" value="<?= $data->color ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_teori_nw">Theoritical Net Weight</label>
                                        <input type="number" name="data_teori_nw" id="data_teori_nw" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Theoritical Net Weight" min="0" step="0.0001" autocomplete="off" value="<?= $data->teori_nw ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_teori_gw">Theoritical Gross Weight</label>
                                        <input type="number" name="data_teori_gw" id="data_teori_gw" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Theoritical Gross Weight" min="0" step="0.0001" autocomplete="off" value="<?= $data->teori_gw ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_teori_shift_capacity">Theoritical Shift Capacity</label>
                                        <input type="number" name="data_teori_shift_capacity" id="data_teori_shift_capacity" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Theoritical Shift Capacity" min="0" step="1" autocomplete="off" value="<?= $data->teori_shift_capacity ?>">
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_nw">Net Weight</label>
                                        <input type="number" name="data_nw" id="data_nw" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Actual Net Weight" min="0" step="0.0001" autocomplete="off" value="<?= $data->nw ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_gw">Gross Weight</label>
                                        <input type="number" name="data_gw" id="data_gw" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Actual Gross Weight" min="0" step="0.0001" autocomplete="off" value="<?= $data->gw ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3 clearfix">
                                        <label class="form-label" for="data_shift_capacity">Shift Capacity</label>
                                        <input type="number" name="data_shift_capacity" id="data_shift_capacity" class="form-control rounded-0 bg-secondary-subtle" readonly placeholder="Theoritical Shift Capacity" min="0" step="1" autocomplete="off" value="<?= $data->shift_capacity ?>">
                                    </div>
                                    <div class="form-group col-12 mb-3 clearfix">
                                        <label class="form-label" for="data_remark">Remark</label>
                                        <textarea name="data_remark" id="data_remark" class="form-control rounded-0 summernote" placeholder="Additional Information"><?= $data->remark ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>