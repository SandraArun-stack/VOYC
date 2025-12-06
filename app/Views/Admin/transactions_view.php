<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Transaction Details</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard') ?>">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#!">Transaction Details</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <!-- Back Button -->
                    <div class="row">
                        <div class="col text-end">
                            <a href="<?= base_url('admin/transactions') ?>" class="btn btn-secondary">
                                Back to list
                            </a>
                        </div>
                    </div>
                    <br />

                    <!-- Transaction Card -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <h5>Transaction Details</h5>
                                </div>

                                <div class="card-block" id="transaction-details">

                                    <!-- Row 1: Customer & Transaction ID -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Customer Name:</strong>
                                            <?= esc($transaction['cust_Name'] ?? 'N/A') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Transaction Amount:</strong>
                                            ₹ <?= esc(number_format($transaction['transaction_amount'] ?? 0, 2)) ?>
                                        </div>

                                        
                                    </div>
                                    <div class="row mb-3">
                                        

                                        <div class="col-md-6">
                                            <strong>Transaction Date:</strong>
                                            <?= !empty($transaction['initiated_at']) ? date('d/m/Y H:i:s', strtotime($transaction['initiated_at'])) : 'N/A' ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Transaction Status:</strong>
                                            <?php
                                                $statusClass = match($transaction['transaction_status'] ?? '') {
                                                    'success' => 'badge-success',
                                                    'failed'  => 'badge-danger',
                                                    'initiated' => 'badge-secondary',
                                                    'refund'  => 'badge-info',
                                                    default => 'badge-secondary',
                                                };
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= esc(ucfirst($transaction['transaction_status'] ?? 'N/A')) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Row 2: Payment Method & Transaction Amount -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Transaction ID:</strong>
                                            <?= esc($transaction['transaction_Id'] ?? 'N/A') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Payment Method:</strong>
                                            <?= esc($transaction['payment_method'] ?? 'N/A') ?>
                                        </div>
                                    </div>

                                    <!-- Row 4: Subscription Details (if any) -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Subscription Name:</strong>
                                            <?= esc($transaction['sp_plan_name'] ?? 'N/A') ?>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Subscription Amount:</strong>
                                            ₹ <?= esc(number_format($transaction['sp_amount'] ?? 0, 2)) ?>
                                        </div>
                                    </div>

                                    <!-- Row 5: Financial / Gateway Details -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Gateway Transaction ID:</strong>
                                            <?= esc($transaction['gateway_transaction_Id'] ?? 'N/A') ?>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Commission Amount:</strong>
                                            ₹<?= esc(number_format($transaction['commission_amount'] ?? 0, 2)) ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Net Credited Amount:</strong>
                                            ₹ <?= esc(number_format($transaction['net_credited_amount'] ?? 0, 2)) ?>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Transaction Completed At:</strong>
                                            <?= !empty($transaction['completed_at']) ? date('d/m/Y H:i:s', strtotime($transaction['completed_at'])) : 'N/A' ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Topup Tokens:</strong>
                                            <?= esc(number_format($transaction['tt_count'] )) ?>
                                        </div>
                                    </div>
                                </div> <!-- card-block end -->
                            </div> <!-- card end -->
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>
</div>
