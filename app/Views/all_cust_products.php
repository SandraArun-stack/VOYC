

<!-- Shop Section Begin -->
<section class="shop spad" id="allcustomisible_Product_view">
    <div class="container">
        <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h2 class="shop-heading">
                Explore Our Customizable Collection
            </h2>
            <p class="shop-subheading">
                Choose your style and make it truly yours
            </p>
            <hr class="shop-divider">
        </div>
    </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="row product-list">
                    <?= session('eligible_for_free_tee'); ?>
                     <?= session('free_tee_coupon'); ?>
                      <?= session('free_tee_lb_id'); ?>

                    <?php if (!empty($customizable_products)): ?>
                        <?php foreach ($customizable_products as $item): ?>
                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-4 product__card">
                                <div class="product__item"
                                    data-url="<?= base_url('productdetails/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg"
                                            data-setbg="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'])) ?>">

                                            <!-- <ul class="product__hover">
                                                <li>
                                                    <a href="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'])) ?>"
                                                        class="image-popup">
                                                        <span class="arrow_expand"></span>
                                                    </a>
                                                </li>
                                                <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                                                <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                                            </ul> -->
                                        </div>
                                        <div class="product__item__text">
                                            <h6><a href="#"><?= esc($item['pr_Name']) ?></a></h6>
                                            <div class="rating">
                                                <?php
                                                $avg = (float) $item['average_rating'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= floor($avg)) {
                                                        echo '<i class="fa fa-star text-warning"></i>';
                                                    } elseif ($i == ceil($avg) && $avg - floor($avg) >= 0.5) {
                                                        echo '<i class="fa fa-star-half-o text-warning"></i>';
                                                    } else {
                                                        echo '<i class="fa fa-star-o text-muted"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="product__price">
                                                ₹ <?= esc($item['selected_price']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-lg-12 text-center">
                            <p> No Products found</p>
                        </div>
                    <?php endif; ?>

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