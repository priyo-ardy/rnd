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
                <?php foreach ($category_list as $item): ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 clearfix">
                        <div class="card rounded-0 card-primary">
                            <div class="card-header rounded-0"><?= $item->name ?> </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered table-primary">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle bg-secondary-subtle">Total Project</th>
                                                <th class="text-center align-middle bg-secondary-subtle">Total P/N</th>
                                                <th class="text-center align-middle bg-secondary-subtle">Total Task</th>
                                                <th class="text-center align-middle bg-secondary-subtle">Task Close</th>
                                                <th class="text-center align-middle bg-secondary-subtle">In Progress Task</th>
                                                <th class="text-center align-middle bg-secondary-subtle">Overdue Task</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
</main>
<?= $this->endSection(); ?>