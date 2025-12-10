<div class="col-lg-9 col-md-9">
    <div class="container mt-4">
        <div class="row justify-content-center">

            <?php if (!empty($plans)): ?>
                <?php foreach ($plans as $plan): ?>

                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="sub-plan-box" style="width: 90%;">
                            <h4 class="text-white"><?= esc($plan['sp_plan_name']) ?></h4>

                            <div class="sub-amount">
                                ₹ <?= number_format($plan['sp_amount']) ?>
                            </div>

                            <div class="token-sec mt-3">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/coin/coins.png">
                                <span><?= esc($plan['sp_token']) ?> Tokens</span>
                            </div>

                            <div class="sub-validity">
                                Validity: <?= esc($plan['sp_validity']) ?>
                            </div>

                            <button class="btn btn-light mt-3 razorpay-btn"
                                    data-plan-id="<?= $plan['sp_Id'] ?>"
                                    data-plan-name="<?= esc($plan['sp_plan_name']) ?>"
                                    data-plan-amount="<?= $plan['sp_amount'] ?>"
                                    data-plan-token="<?= $plan['sp_token'] ?>">
                                Subscribe Now
                            </button>

                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-white text-center">No subscription plans found.</p>
            <?php endif; ?>

        </div>
    </div>

</div>
</div>
</div>
</section>

<!-- Change Password Modal -->