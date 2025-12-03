<!DOCTYPE html>
<html lang="en">

<head>
    <link preload href="<?php echo base_url() . 'img/favicon.png'; ?>" rel="icon">
    <link preload href="<?php echo base_url() . 'img/favicon.png'; ?>" rel="apple-touch-icon">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <meta name="title" content="Schlemmer Automotive Indonesia WebApp" />
    <meta name="author" content="Ardy Priyo Sudiyantoko" />


    <link
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-pro@5.2.5/400.min.css"
        rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!-- Select2 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Datatable CSS -->
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css" />
    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- <link
        rel="stylesheet"
        href="<?= base_url() . 'admin/css/adminlte.css'; ?>" /> -->
    <link
        rel="stylesheet"
        href="<?= base_url() . 'css/loading.css' ?>">
    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">

    <style>
        .form-label {
            font-weight: bold;
        }

        /* Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .swal2-container {
            z-index: 9999 !important;
            top: 0 !important;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <?= csrf_meta() . PHP_EOL ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div id="loading-overlay">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body-secondary" data-bs-theme="dark">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" title="Profile">
                            <img
                                src="<?= base_url() . 'img/' . session('user_image'); ?>"
                                class="user-image rounded-circle shadow"
                                alt="User Image" />
                            <span class="d-none d-md-inline"><?= session('full_name') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-light">
                                <img
                                    src="<?= base_url() . 'img/' . session('user_image'); ?>"
                                    class="rounded-circle shadow"
                                    alt="User Image" />
                                <p>
                                    <?= session('full_name') ?>
                                    <small>Member since Nov. 2023</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="<?= base_url() . 'Profile'; ?>" class="btn btn-light rounded-0" title="Profile">Profile</a>
                                <a href="<?= base_url() . 'logout'; ?>" onclick="loading()" class="btn btn-light rounded-0 float-end" title="Sign Out">Sign Out</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url() . 'setup'; ?>" class="nav-link" title="Setup">
                            <i class="fas fa-cogs"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <?= $this->include('Template/sidebar.php'); ?>

        <?= $this->renderSection('content') ?>

        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">
                Schlemmer Automotive Indonesia<br>
                <?= $app_name ?><br>
                App Ver <?= $app_ver ?>
            </div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.<br>
            <?= $app_name; ?>
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>
    <!-- Select2 JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <!-- Datatable -->
    <script
        src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
    <!-- Font awesome -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js"
        integrity="sha512-6BTOlkauINO65nLhXhthZMtepgJSghyimIalb+crKRPhvhmsCdnIuGcVbR5/aQY2A+260iC1OPy1oCdB6pSSwQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <!-- AdminLTE JS -->
    <!-- <script
        src="<?= base_url() . 'admin/js/adminlte.js'; ?>"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js"></script>

    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

    <!-- My App Custom JS -->
    <script src="<?= base_url() . 'js/App/app.js' ?>"></script>
    <script src="<?= base_url() . 'js/App/fetching.js' ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>

    <!-- Module JS -->
    <?php foreach ($footer as $ft): ?>
        <?= $ft . PHP_EOL; ?>
    <?php endforeach ?>
</body>

</html>