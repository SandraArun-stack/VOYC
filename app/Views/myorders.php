<div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-lg-12 col-md-12">

            <div class="row my__orders__container">
                <div class="col-lg-12 col-md-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="heading-content-my-order justify-items-end">
                            <h4>All Orders</h4>
                            <small>From any time</small>
                        </div>

                        <div class="search-box position-relative">
                            <input type="text" class="form-control" id="orderSearch" placeholder="Search orders...">
                            <i class="bi bi-search search__my__order"></i>
                        </div>
                    </div>
                    <?php if (!empty($my_orders)): ?>
                        <?php foreach ($my_orders as $order): ?>

                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span class="order-status" data-status="<?= esc($order['od_Status']) ?>">
                                        <!-- <i class="bi bi-truck me-2"></i> -->
                                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/order/truck.png"
                                            class="truck-image">
                                        Delivery Status:
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i>
                                        <?= date('d M Y', strtotime($order['od_createdon'])) ?>
                                    </small>
                                </div>

                                <div class="card-block p-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php

                                            $productUrl = base_url('productdetails/' . $order['pr_Id'] . '/' . $order['pri_Id']);

                                            if ($order['design_Id'] == 0) {
                                                echo '<a href="' . $productUrl . '" class="order-image-link">
                                                        <img src="' . base_url('uploads/productmedia/' . esc($order['order_Image'])) . '" alt="Product Image">
                                                    </a>';
                                            } else {
                                                echo '<a href="' . $productUrl . '" class="order-image-link">
                                                        <img src="' . base_url('uploads/designs/' . esc($order['order_Image'])) . '" alt="Custom Design">
                                                    </a>';
                                            }
                                            ?>

                                        </div>
                                        <div class="col-md-9">
                                            <div class="row mb-2 my_order_details">
                                                <div class="col-12 mt-2">
                                                    <p class="mb-2"><b><?= esc($order['od_number']) ?></b></p>
                                                    <p class="mb-2"><?= esc($order['pr_Code']) ?></p>
                                                    <p class="mb-2"><?= esc($order['pr_Name']) ?></p>
                                                    <p class="mb-2">Size: <?= esc($order['od_Size']) ?></p>
                                                    <p class="mb-2">Quantity: <?= esc($order['od_Quantity']) ?></p>
                                                </div>
                                            </div>

                                            <div class="row mb-2 my_order_details">

                                                <div class="col-12 mt-2">
                                                    <div class="alert alert-success small p-2 ms-1 d-none"
                                                        id="review_msg_alert"></div>

                                                    <div class="row ">
                                                        <div class="col-6 review__stars">
                                                            <small class="mb-1">Rate & Shine: Your Voyc Product Awaits!</small>
                                                            <p class="mb-2">
                                                                <span class="star-rating"
                                                                    data-orderid="<?= esc($order['od_Id']) ?>"
                                                                    data-prId="<?= esc($order['pr_Id']) ?>"
                                                                    data-priId="<?= esc($order['pri_Id']) ?>">
                                                                    <?php
                                                                    $rating = !empty($order['review_rating']) ? intval($order['review_rating']) : 0;
                                                                    for ($i = 1; $i <= 5; $i++):
                                                                        $starClass = ($i <= $rating) ? 'active text-gold' : '';
                                                                        ?>
                                                                        <i class="bi bi-star-fill <?= $starClass ?>"
                                                                            data-value="<?= $i ?>"></i>
                                                                    <?php endfor; ?>

                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-6 add__feedback">
                                                            <p class="mb-0">
                                                                <?php if (empty($order['review']) || is_null($order['review'])): ?>
                                                                    <b><a href="#" class="write_feedback">Write a Review</a></b>
                                                                <?php else: ?>
                                                                    <b><a href="#" class="see_feedback text-success">See My Feedback</a></b>
                                                                <?php endif; ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3 review_adding_block d-none">
                                                        <div class="col-12">
                                                            <input type="text" class="form-control review-input"
                                                                placeholder="Write your review here...">
                                                        </div>
                                                        <div class="col-12 review-submit">
                                                            <button class="btn button btn-dark p-1"
                                                                id="review-submit-btn">Submit</button>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3 review_see_block d-none">
                                                        <div class="col-12">
                                                            <p class="mb-1"><?= esc($order['review']) ?></p>
                                                            <p class="text-muted small mb-0">Created on </p>
                                                            <p class="text-muted small mb-1">
                                                                <i class="bi bi-calendar3"></i>
                                                                <?= date('d M Y', strtotime($order['created_at'])) ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mb-2 my_order_details">
                                                <div class="col-12 mt-2">

                                                    <p class="mb-2"><i class="bi bi-geo-alt-fill location-my-orders"></i>Shipping Address</p>
                                                    <?php
                                                    $addressParts = explode(',', $order['od_Shipping_Address']);
                                                    $formattedAddress = '';

                                                    foreach (array_chunk($addressParts, 2) as $pair) {
                                                        $formattedAddress .= esc(implode(', ', array_map('trim', $pair))) . '<br>';
                                                    }
                                                    ?>

                                                    <p><?= $formattedAddress ?></p>

                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info text-center mt-3">No orders found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>



        <div class="col-lg-12 text-center">
            <div class="pagination__option">
                <!-- <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#"><i class="fa fa-angle-right"></i></a> -->
                <?= $pager->links() ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
<!-- Shop Section End -->

<!-- Instagram Begin -->
<div class="instagram">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-1.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-2.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-3.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-4.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-5.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-6.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Instagram End -->