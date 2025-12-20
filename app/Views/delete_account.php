<div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-lg-12 col-md-12">

            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <strong>Delete Account</strong>
                </div>

                <div class="card-body text-center">

                    <h5 class="mb-3">Are you sure you want to delete your account?</h5>
                    <p class="text-muted">
                        Once deleted, your account will be deactivated and cannot be accessed again.
                    </p>

                    <div id="deleteMessage" class="alert d-none"></div>

                    <button class="btn btn-secondary" onclick="window.location.href='<?= base_url('myprofile') ?>'">
                        Cancel
                    </button>

                    <button id="confirmDeleteBtn" class="btn btn-danger">
                        Yes, Delete My Account
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
