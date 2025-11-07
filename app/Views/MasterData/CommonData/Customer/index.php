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
                        <li class="breadcrumb-item active">List of Customers</li>
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
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="New">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;Add
                        </button>
                        <button type="button" id="btnFilter" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                            <i class="bi bi-funnel"></i>&ensp;Filter
                        </button>
                        <button type="button" id="btnRefresh" class="btn shadow-none rounded-0 btn-light-order-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh">
                            <i class="bi bi-arrow-repeat"></i>&ensp;Refresh
                        </button>
                        <button type="button" id="btnExport" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Export">
                            <i class="bi bi-download"></i>&ensp;Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12 clearfix">
                    <div class="card rounded-0 card-primary card-outline">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-primary" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <th class="align-middle text-center bg-secondary-subtle">Code</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Name</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Address</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Email</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Phone</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Contact Person</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Contact Person Phone</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Contact Person Email</th>
                                        <th class="align-middle text-center bg-secondary-subtle">Remark</th>
                                        <th class="align-middle text-center bg-secondary-subtle">#</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>