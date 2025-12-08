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
                        <li class="breadcrumb-item"><?= $header->category_name ?></li>
                        <li class="breadcrumb-item"><?= $header->code ?></li>
                        <li class="breadcrumb-item active">Project Part List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <?php foreach ($part_list as $item): ?>
                    <?php
                    $overdue = '';

                    if ($item->overdue > 0 && $item->overdue <= 3) {
                        $overdue = 'warning';
                    } else if ($item->overdue > 3) {
                        $overdue = 'danger text-white';
                    } else {
                        $overdue = 'primary text-white';
                    }
                    ?>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 clearfix">
                        <div class="card rounded-0">
                            <div class="card-header rounded-0 bg-<?= $overdue ?>">
                                <h5 class="card-title fw-bolder">
                                    <?= $item->material_code ?> - <?= $item->material_name ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="row mb-2">
                                        <div class="col-3">Due Date</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= date("D, d F Y") ?> </div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">Status</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->status_name ?></div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">Task Open</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->open_status ?> task</div>
                                    </li>
                                    <li class="row mb-2">
                                        <div class="col-3">Task Close</div>
                                        <div class="col-1 text-center">:</div>
                                        <div class="col-8 fw-bold"><?= $item->close_status ?> task</div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="<?= base_url() . 'document/upload/' . enkripsi($item->id_material) ?>" class="btn btn-<?= $overdue ?> rounded-0 w-100 d-block"><i class="bi bi-box-arrow-up-right"></i>&ensp;Upload Document</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>