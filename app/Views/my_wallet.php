<div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="messageBox" class="alert alert-success" style="display: none;"></div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="heading-content-my-order">
                    <h4>My Wallet</h4>
                    <small class="text-muted">Manage Wallet Details</small>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col d-flex justify-content-end align-items-center tokens__top__up">
                    <!-- Left: Token Card -->
                    <div class="card shadow-sm card-token">
                        <div class="card-body text-center total_token">
                            <h6 class="mb-1 total_token_text">Tokens &nbsp; <img
                                    src="<?= base_url() . ASSET_PATH; ?>assets/img/coin/coins.png"
                                    style="width:30px; height:30px;"></h6>
                            <h6 class="text-primary" id="userTokens"></h6>
                        </div>
                    </div>

                    <!-- Right: Topup Button -->
                    <button class="btn black text-white" id="topupBtn">
                        <i class="fa fa-plus"></i> Topup
                    </button>
                </div>
            </div>
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
</section>