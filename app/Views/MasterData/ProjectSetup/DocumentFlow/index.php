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
                        <li class="breadcrumb-item">Document Flow</li>
                        <li class="breadcrumb-item active">Setup Document FLow</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header rounded-0">
                            <h3 class="card-title"><i class="bi bi-bezier2"></i>&ensp;Flow Setup</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-black" title="Add Flow" data-bs-toggle="modal" data-bs-target="#modalFlow"><i class="bi bi-plus-circle"></i>&ensp;Add Flow</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <ul class="list-group list-group-flush" id="listLevel">
                                    <?php foreach ($flow_level as $row): ?>
                                        <li class="list-group-item"><?= $row->name ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-3 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header rounded-0">
                            <h3 class="card-title">Document List</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-black" data-lte-toggle="card-collapse" title="Add Document">
                                    <i class="bi bi-plus-circle"></i>&ensp;Add Document
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->include('MasterData/ProjectSetup/DocumentFlow/modal'); ?>

<?= $this->endSection(); ?>