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
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="toolbar">
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="New">
                            <i class="bi bi-plus-circle"></i>&ensp;New
                        </button>
                        <button type="button" id="btnFilter" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                            <i class="bi bi-funnel"></i>&ensp;Filter
                        </button>
                        <button type="button" id="btnSearch" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">
                            <i class="bi bi-search"></i>&ensp;Search
                        </button>
                        <button type="button" id="btnRefresh" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh">
                            <i class="bi bi-arrow-repeat"></i>&ensp;Refresh
                        </button>
                        <button type="button" id="btnSort" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Sort">
                            <i class="bi bi-filter"></i>&ensp;Sort
                        </button>
                    </div>
                </div>
            </div>

            <!-- <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="btn-group rounded-0" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary" for="btnradio1">Open</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio2">On Progress</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio3">Hold</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio4">Reject</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio5" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio5">Urgent</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio6" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio6">Critical</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio7" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio7">Closed</label>
                    </div>
                </div>
            </div> -->

            <div class="row mb-3 g-2">
                <?php foreach ($data as $item): ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                        <div class="card rounded-0 card-primary">
                            <div class="card-header rounded-0 fw-bolder"><?= $item->code  ?></div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">

                                    <li class="row mb-2">
                                        <div class="col-3">Project Name</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->name ?></div>
                                    </li>

                                    <li class="row mb-2">
                                        <div class="col-3">Customer</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->customer_name ?></div>
                                    </li>

                                    <li class="row mb-2">
                                        <div class="col-3">Project Type</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->type_of_project ?></div>
                                    </li>

                                    <li class="row mb-2">
                                        <div class="col-3">Project Status</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->status_name ?></div>
                                    </li>

                                    <li class="row mb-2">
                                        <div class="col-3">Total Part No</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->total_part_no  ?> Part No</div>
                                    </li>

                                    <li class="row mb-2">
                                        <div class="col-3">APQP Progress</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= ($item->progress > 0) ? ($item->progress / $item->total_document) * 100 : '0' ?> %</div>
                                    </li>

                                    <li class="row mb-2">
                                        <div class="col-3">Remark</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->remark ?></div>
                                    </li>

                                </ul>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="<?= base_url() . 'project/show/' . enkripsi($item->id)  ?> ?>" onclick="loading()" class="btn btn-primary rounded-0 w-100 d-block" title="Show Details"><i class="bi bi-box-arrow-up-right"></i>&ensp;Show Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
</main>
<?= $this->endSection(); ?>