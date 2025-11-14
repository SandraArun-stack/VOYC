<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- Load your existing CSS -->
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/style.css">
</head>

<body style="background:#f8f8f8; padding-top:60px;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <h3 class="mb-4 text-center">Reset Password</h3>

                <form action="<?= base_url('updatePassword') ?>" method="post">
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control mb-3" required>

                    <button type="submit" class="btn btn-dark w-100">Update Password</button>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
