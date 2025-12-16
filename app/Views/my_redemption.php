<style>
    .page-wrapper-my-redemptions {
        background: #f5f7fa;
        padding: 25px;
        border-radius: 5px;
    }

    .heading-content-my-order h4 {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .heading-content-my-order small {
        font-size: 14px;
        color: #7f8c8d;
    }

    #my_redemption {
        background: #ffffff;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
    }

    #my_redemption thead {
        background: #000000ff !important;
        color: #ffffff;
    }

    #my_redemption tbody tr {
        transition: 0.2s ease-in-out;
    }

    #my_redemption tbody tr:hover {
        background: #ecf0f1;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-success {
        background: #2ecc71;
        color: white;
    }

    .badge-pending {
        background: #f1c40f;
        color: white;
    }

    .badge-failed {
        background: #e74c3c;
        color: white;
    }

    .alert-custom {
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 15px;
        display: none;
    }

    .redeemFreeTeeBtn {
        padding: 6px 12px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
    }

    .viewCouponBtn {
        padding: 6px 12px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
    }
</style>
<div class="col-lg-9 col-md-9">

    <div class="page-wrapper-my-redemptions">

        <div id="messageBox" class="alert alert-success alert-custom"></div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="heading-content-my-order">
                <h4>My Redeemptions</h4>
                <small class="text-muted">Game Details & Redeemption Status</small>
            </div>
        </div>

        <table id="my_redemption" class="table table-bordered">
            <thead>
                <tr>
                    <th>SI. No.</th>
                    <th>Date</th>
                    <th>Game</th>
                    <th>Score</th>
                    <th>Position</th>
                    <th>Won</th>

                    <th>Redeem</th>
                    <th>Expiry</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($redemptions)): ?>
                    <?php $i = 1;
                    foreach ($redemptions as $row): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('d M Y', strtotime($row['lb_date'])); ?></td>
                            <td><?= esc($row['game_name']); ?></td>
                            <td><?= esc($row['lb_score']); ?></td>
                            <td><?= esc($row['lb_rank']); ?></td>

                            <!-- Won -->
                            <td>
                                <span class="badge-status badge-success">
                                    <?= ($row['lb_status'] == 1) ? 'Free 0' : (($row['lb_status'] == 2) ? 'Free 5' : 'Unknown'); ?>
                                </span>
                            </td>

                            <!-- Redeem Status -->
                            <!-- Redeem Status -->
                            <td>
                                <?php
                                $expiryDate = date('Y-m-d', strtotime($row['lb_created_at'] . ' +7 days'));
                                $remaining = (strtotime($expiryDate) - time()) / 86400;

                                $isNotExpired = $remaining > 0;
                                $isNew = $row['lb_redeemed_status'] == '1';
                                $isFree0 = $row['lb_status'] == '1'; // Free0 condition
                                $isfree5 = $row['lb_status'] == '2'; // Free5 condition
                                if ($isNew && $isNotExpired && $isFree0) {
                                    echo '<button class="btn btn-sm btn-success redeemFreeTeeBtn" 
                                            data-coupon="' . $row['lb_coupen_code'] . '" 
                                            data-id="' . $row['lb_Id'] . '">
                                            Use Now
                                        </button>';
                                } elseif ($isNew && $isNotExpired && $isfree5) {
                                    echo '<button class="btn btn-sm btn-success viewCouponBtn" 
                                            data-coupon="' . $row['lb_coupen_code'] . '" 
                                            data-id="' . $row['lb_Id'] . '">
                                            Use Now
                                        </button>';
                                } elseif ($row['lb_redeemed_status'] == '2') {
                                    echo '<span class="badge-status badge-warning">Redeemed</span>';
                                } elseif ($row['lb_redeemed_status'] == '3') {
                                    echo '<span class="badge-status badge-danger">Expired</span>';
                                } else {
                                    echo '<span class="badge-status badge-secondary">Unknown</span>';
                                }
                                ?>

                            </td>


                            <!-- Expiry -->
                            <td>
                                <?php
                                // If redeemed → show ---
                                if ($row['lb_redeemed_status'] == '2') {
                                    echo '<span class="text-muted">---</span>';
                                } else {
                                    $expiryDate = date('Y-m-d', strtotime($row['lb_date'] . ' +7 days'));
                                    $remaining = (strtotime($expiryDate) - time()) / 86400;

                                    if ($remaining > 0) {
                                        echo '<span class="text-success">' . floor($remaining) . ' Days Left</span>';
                                    } else {
                                        echo '<span class="text-danger">Expired</span>';
                                    }
                                }
                                ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No redemption data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>
<div class="modal fade" id="couponModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-white">Your Coupon</h5>

                <!-- Close button (Bootstrap 4 compatible) -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body text-center">
                <p id="couponText" class="fw-bold coupon-box"></p>

                <button class="btn btn-light btn-sm mt-2" id="copyCouponBtn">
                    <i class="fa fa-copy"></i> Copy
                </button>
            </div>

        </div>
    </div>
</div>


</div>
</div>
</section>