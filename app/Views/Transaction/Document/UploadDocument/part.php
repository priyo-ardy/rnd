<?= $this->extend('Template/layout'); ?>

<?= $this->section('content'); ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= $title; ?></h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard';  ?>">Dashboard</a></li>
                        <li class="breadcrumb-item">List Of Project</li>
                        <li class="breadcrumb-item">YAZAKI PROJECT</li>
                        <li class="breadcrumb-item">150B</li>
                        <li class="breadcrumb-item active">Project Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <?php foreach ($part_list as $item): ?>
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
                                        <div class="col-3">Due Date</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">Status</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">Task Open</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">In Progress Task</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">Task Close</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="<?= base_url() . 'document/upload/' . enkripsi($item->id_material) ?>" class="btn btn-primary rounded-0 w-100 d-block"><i class="bi bi-box-arrow-up-right"></i>&ensp;Upload Document</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>