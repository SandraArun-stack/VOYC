<div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="messageBox" class="alert alert-success" style="display: none;"></div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="heading-content-my-order">
                    <h4>Leaderboard</h4>
                    <small class="text-muted">Game Details</small>
                </div>
            </div>

            <table id="userLeaderboard" class="table table-bordered DataTableWebsite">
                <thead class="off-white">
                    <tr>
                        <th>SI. No.</th>
                        <th>Date</th>
                        <th>Player Name</th>
                        <th>Game</th>
                        <th>Score</th>
                        <th>Position</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- // modal for redeem coupon -->
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