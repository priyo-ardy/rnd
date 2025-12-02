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
                        <li class="breadcrumb-item active"><a href="<?= base_url() . 'dashboard' ?>" onclick="loading()">Dashboard</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2 mb-3">
                <?php foreach ($project_detail as $item): ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0">
                                <h5 class="card-title fw-bolder">
                                    <?= $item->material_code ?> - <?= $item->material_name ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="row mb-2">
                                        <div class="col-4">Mass Production Date</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-7 fw-bold"><?= $item->due_date ?></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-4">Project Status</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-7 fw-bold"><?= $item->status ?></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-4">Project Status</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-7 fw-bold"><?= $item->status ?></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-4">On Going Task</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-7 fw-bold"></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="" type="button" class="btn btn-primary rounded-0 w-100 d-block" title="Show Document Details"><i class="bi bi-box-arrow-up-right"></i>&ensp;Show Document</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>