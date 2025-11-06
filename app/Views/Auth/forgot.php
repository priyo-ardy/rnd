<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.:: Forgot Password ::.</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('img/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= base_url('img/favicon.ico') ?>" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="<?= base_url('image/favicon.ico') ?>" sizes="180x180">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Ubuntu Font -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">
    <!-- Auth CSS -->
    <link href="<?= base_url() . 'css/auth/auth.css' ?>" rel="stylesheet">
</head>

<body>
    <div class="login-container floating">
        <div class="login-header">
            <img src="<?= base_url() . 'img/panjang.png' ?>" alt="Schlemmer Logo" class="company-logo">
            <div class="logo-line"></div>
        </div>

        <form id="formAuth">
            <div class="mb-3">
                <input type="hidden" readonly class="form-control" name="data_token" id="data_token" placeholder="User name" autofocus autocomplete="off">
                <div class="invalid-feedback">Username is required</div>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email_address" id="email_address" placeholder="Email Address" autocomplete="off">
                <div class="invalid-feedback">Password is required</div>
            </div>
            <div class="mb-3 text-danger text-center col-12" id="error_message"></div>
            <div class="mb-3 text-success text-center col-12" id="success_message"></div>

            <div class="forgot-password">
                <a href="<?= base_url() ?>"><i class="bi bi-arrow-left"></i>&ensp;Back to login</a>
            </div>

            <button type="button" id="btnAuth" class="btn btn-login"><i class="bi bi-key"></i>&ensp;Reset Password</button>
        </form>

        <div class="login-footer">
            <div>© 2024 <?= $app_ver ?></div>
            <div><?= $app_name ?></div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sweetalert2 plugins -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Authjavascript -->
    <script src="<?= base_url() . 'js/Auth/forgot.js' ?>"></script>
    <script src="<?= base_url() . 'js/App/fetching.js' ?>"></script>
</body>

</html>