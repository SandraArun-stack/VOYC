<div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="messageBox" class="alert alert-success" style="display: none;"></div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="heading-content-my-order">
                    <h4>My Wallet</h4>
                    <small class="text-muted">Manage your account details</small>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col d-flex justify-content-between align-items-center">
                    <!-- Left: Token Card -->
                    <div class="card shadow-sm card-token">
                        <div class="card-body text-center py-2 px-3">
                            <h6 class="mb-1">Tokens 🪙</h6>
                            <h6 class="text-primary" id="userTokens"></h6>
                        </div>
                    </div>

                    <!-- Right: Topup Button -->
                    <button class="btn black text-white" id="topupBtn">
                        <i class="fa fa-plus"></i> Topup
                    </button>
                </div>
            </div>


            <div>
                <!-- <div class="card-header bg-dark text-white">
                    <strong>Details</strong>
                </div> -->

                <div>

                    <div>
                        <table id="tokenTable" class="table table-bordered DataTableWebsite">
                            <thead class="off-white">
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Validity</th>
                                    <th>Token</th>
                                    <th>Purchased Token</th>
                                    <th>Bonus Token</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>