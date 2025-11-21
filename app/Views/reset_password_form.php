<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" href="<?= base_url() . ASSET_PATH; ?>assets/img/favicon.ico" type="image/x-icon">
    <style>
        .password-container {
            position: relative;
        }

        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #555;
        }

        .logo-container img {
            width: 160px;
        }
    </style>
</head>

<body style="background:#f8f8f8; padding-top:25px;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <!-- Logo -->
                <div class="text-center mb-4 logo-container">
                    <a href="<?= base_url('/') ?>">
                        <img src="<?= base_url(ASSET_PATH . 'assets/img/logo-black.jpg') ?>" alt="Team VOYC">
                    </a>
                </div>

                <h3 class="mb-3 text-center">Reset Your Password</h3>
                <p class=" text-muted mb-1">
                    Please create a strong password. Make sure both passwords match.
                </p>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('updatePassword') ?>" method="post">
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <!-- New Password -->
                    <label>New Password</label>
                    <div class="password-container mb-3">
                        <input type="password" name="new_password" id="new_pass" class="form-control" required>
                        <i class="bi bi-eye-slash toggle-eye" onclick="togglePassword('new_pass', this)"></i>
                    </div>

                    <!-- Confirm Password -->
                    <label>Confirm Password</label>
                    <div class="password-container mb-4">
                        <input type="password" name="confirm_password" id="confirm_pass" class="form-control" required>
                        <i class="bi bi-eye-slash toggle-eye" onclick="togglePassword('confirm_pass', this)"></i>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Reset Password</button>
                </form>
                <div class="text-end mt-3">
                    <a href="#" onclick="showLoginPopup()" class="text-decoration-none fw-bold">
                        Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Eye toggle script -->
    <script>
        function togglePassword(id, icon) {
            const input = document.getElementById(id);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }
        function showLoginPopup() {

            // Open the modal
            const authModal = new bootstrap.Modal(document.getElementById('authModal'));
            authModal.show();

            // Show login view only
            $('#loginView').show();
            $('#registerView').hide();
            $('#forgotPassView').hide();
        }

    </script>

</body>

</html>