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
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h5 class="card-title">YAZAKI PROJECT</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-primary">
                                    <thead>
                                        <tr>
                                            <th>Total Project</th>
                                            <th>Total Part No</th>
                                            <th>Total Task</th>
                                            <th>Total Task Close</th>
                                            <th>Total Task On Progress</th>
                                            <th>Total Overdue Task</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>4</td>
                                            <td>20</td>
                                            <td>100</td>
                                            <td>50</td>
                                            <td>50</td>
                                            <td>10</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h5 class="card-title">SUMITOMO PROJECT</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-primary">
                                    <thead>
                                        <tr>
                                            <th>Total Project</th>
                                            <th>Total Part No</th>
                                            <th>Total Task</th>
                                            <th>Total Task Close</th>
                                            <th>Total Task On Progress</th>
                                            <th>Total Overdue Task</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>4</td>
                                            <td>20</td>
                                            <td>100</td>
                                            <td>50</td>
                                            <td>50</td>
                                            <td>10</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 clearfix">
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h5 class="card-title">OTHER PROJECT</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-primary">
                                    <thead>
                                        <tr>
                                            <th>Total Project</th>
                                            <th>Total Part No</th>
                                            <th>Total Task</th>
                                            <th>Total Task Close</th>
                                            <th>Total Task On Progress</th>
                                            <th>Total Overdue Task</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>4</td>
                                            <td>20</td>
                                            <td>100</td>
                                            <td>50</td>
                                            <td>50</td>
                                            <td>10</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>