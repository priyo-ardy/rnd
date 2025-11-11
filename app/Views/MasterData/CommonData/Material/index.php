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
                        <li class="breadcrumb-item active">List of Material</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="toolbar">
                        <button type="button" id="btnAdd" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="New">
                            <i class="bi bi-file-earmark-plus"></i>&ensp;New
                        </button>
                        <button type="button" id="btnFilter" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                            <i class="bi bi-funnel"></i>&ensp;Filter
                        </button>
                        <button type="button" id="btnRefresh" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh">
                            <i class="bi bi-arrow-repeat"></i>&ensp;Refresh
                        </button>
                        <button type="button" id="btnExport" class="btn shadow-none rounded-0 btn-light border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Export">
                            <i class="bi bi-download"></i>&ensp;Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-primary" id="dataTable">
                            <thead>
                                <th class="text-center bg-secondary-subtle align-middle">Category</th>
                                <th class="text-center bg-secondary-subtle align-middle">Code</th>
                                <th class="text-center bg-secondary-subtle align-middle">Name</th>
                                <th class="text-center bg-secondary-subtle align-middle">Specification</th>
                                <th class="text-center bg-secondary-subtle align-middle">UoM</th>
                                <th class="text-center bg-secondary-subtle align-middle">Workshop</th>
                                <th class="text-center bg-secondary-subtle align-middle">Product Color</th>
                                <th class="text-center bg-secondary-subtle align-middle">Production Routes</th>
                                <th class="text-center bg-secondary-subtle align-middle">Theoritical Net Weight</th>
                                <th class="text-center bg-secondary-subtle align-middle">Theoritical Gross Weight</th>
                                <th class="text-center bg-secondary-subtle align-middle">Theoritical Shift Capacity</th>
                                <th class="text-center bg-secondary-subtle align-middle">Actual Net Weight</th>
                                <th class="text-center bg-secondary-subtle align-middle">Actual Gross Weight</th>
                                <th class="text-center bg-secondary-subtle align-middle">Shift Capacity</th>
                                <th class="text-center bg-secondary-subtle align-middle">Remark</th>
                                <th class="text-center bg-secondary-subtle align-middle">#</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
<?= $this->endSection(); ?>