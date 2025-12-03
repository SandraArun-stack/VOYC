<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?= isset($subscription) ? 'Update Subscription' : 'Add Subscription'; ?></h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard') ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#"><?= isset($subscription) ? 'Update Subscription' : 'Add Subscription'; ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row mb-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button class="btn btn-secondary" onclick="window.location.href='<?= base_url('admin/subscription'); ?>'"> Back to Subscription List
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-2">

                                        </div>
                                        <div class="col-md-7">

                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex justify-content-end p-2">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div id="messageBox" class="alert alert-success" style="display: none;"></div>
                                    <form name="createsubscription" id="createsubscription" method="post">
                                        <!-- Plan Name -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Plan Name <span style="color: red;">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="plan_name" id="plan_name" class="form-control" maxlength="50"
                                                       value="<?= isset($subscription) ? esc($subscription['sp_plan_name']) : '' ?>"
                                                       placeholder="Enter subscription plan name" required>
                                                <span class="text-danger error-msg" id="error-plan_name"></span>
                                            </div>
                                        </div>

                                        <!-- Amount -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Amount <span style="color: red;">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="number" name="amount" id="amount" class="form-control"
                                                       value="<?= isset($subscription) ? (int)$subscription['sp_amount'] : '' ?>"
                                                       placeholder="Enter plan amount" required>
                                                <span class="text-danger error-msg" id="error-amount"></span>
                                            </div>
                                        </div>

                                        <!-- Validity -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Validity (Days) <span style="color: red;">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="number" name="validity" id="validity" class="form-control"
                                                       value="<?= isset($subscription) ? (int)$subscription['sp_validity'] : '' ?>"
                                                       placeholder="Enter validity in days" required>
                                                <span class="text-danger error-msg" id="error-validity"></span>
                                            </div>
                                        </div>

                                        <!-- Discount -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Discount (%)</label>
                                            <div class="col-sm-6">
                                                <input type="number" name="discount" id="discount" class="form-control"
                                                       value="<?= isset($subscription) ? (float)$subscription['sp_discount'] : '0' ?>"
                                                       placeholder="Enter discount percentage">
                                                <span class="text-danger error-msg" id="error-discount"></span>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <!-- <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-6">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1" <?= isset($subscription) && $subscription['sp_status'] == 1 ? 'selected' : '' ?>>Active</option>
                                                    <option value="0" <?= isset($subscription) && $subscription['sp_status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <!-- Token -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Token</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="token" id="token" class="form-control"
                                                    value="<?= isset($subscription) ? esc($subscription['sp_token']) : '' ?>"
                                                    placeholder="Enter token value">
                                                <span class="text-danger error-msg" id="error-token"></span>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <input type="hidden" name="subscription_id" value="<?= isset($subscription['sp_Id']) ? esc($subscription['sp_Id']) : '' ?>">
                                            <div class="button-group">
                                                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= base_url('admin/subscription'); ?>'">
                                                    <i class="bi bi-x-circle"></i> Discard
                                                </button>
                                                <button type="button" class="btn btn-primary" id="subscriptionSubmit" name="subscriptionSubmit">
                                                    <i class="bi bi-check-circle"></i>
                                                    <?= isset($subscription['sp_Id']) && !empty($subscription['sp_Id']) ? 'Update' : 'Save'; ?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>
</div>
