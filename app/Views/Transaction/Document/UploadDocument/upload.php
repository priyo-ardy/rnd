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
                <?php foreach ($apqp as $row): ?>
                    <div class="col-12">
                        <div class="card card-primary rounded-0">
                            <div class="card-header rounded-0">
                                <h5 class="card-title">Stage <?= $row->baris  ?> - <?= $row->apqp_level_name ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($document[$row->id_apqp]) && count($document[$row->id_apqp]) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered table-primary">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">Document Name</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-3">Upload Document</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">View Document</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">Due Date</th>
                                                    <th class="text-center align-middle bg-secondary-subtle col-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($document[$row->id_apqp] as $item): ?>
                                                    <tr>
                                                        <td><?= $item->document_name ?></td>
                                                        <td>
                                                            <input type="file" name="document_[]" class="form-control form-control-sm rounded-0">
                                                        </td>
                                                        <td></td>
                                                        <td class="text-center">
                                                            <?= date("d/M/Y", strtotime($item->due_date)) ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $selisih = calculate_duration_days($item->due_date, date('Y-m-d'));
                                                            if ($item->due_date < date('Y-m-d')) {
                                                                if ($selisih > 0) {
                                                                    echo 'This document is overdue by ' . $selisih . ' days';
                                                                } else if ($selisih < 3) {
                                                                    echo 'This document will be due in ' . $selisih . ' days';
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>