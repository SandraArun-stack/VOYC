<?= $this->extend('common/layout') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <form action="<?= base_url('updatePassword') ?>" method="post">
            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <label>New Password</label>
            <input type="password" name="new_password" class="form-control mb-3" required>

            <button type="submit" class="btn btn-dark">Update Password</button>
        </form>
    </div>
<?= $this->endSection() ?>
