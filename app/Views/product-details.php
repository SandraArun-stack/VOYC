<?php
$userId = session()->get('user_id');
?>
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                    <a href="#">Women’s </a>
                    <span><?= esc($product['pr_Name']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__left product__thumb nice-scroll">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $i => $img): ?>
                                <a class="pt <?= $i === 0 ? 'active' : '' ?>" href="#product-<?= $i + 1 ?>">
                                    <img src="<?= base_url('uploads/productmedia/' . $img); ?>" class="product__small__img"
                                        alt="">
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a class="pt active" href="#product-1">
                                <img src="<?= base_url('uploads/productmedia/' . ($product['prd_first_image'] ?? 'default.jpg')); ?>"
                                    alt="">
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="product__details__slider__content">
                        <div class="product__details__pic__slider owl-carousel">
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $i => $img): ?>
                                    <img data-hash="product-<?= $i + 1 ?>" class="product__big__img"
                                        src="<?= base_url('uploads/productmedia/' . $img); ?>" alt="">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <img data-hash="product-1" class="product__big__img"
                                    src="<?= base_url('uploads/productmedia/' . ($product['prd_first_image'] ?? 'default.jpg')); ?>"
                                    alt="">

                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="product__details__text">
                    <h3><?= esc($product['pr_Name']) ?> <span>Brand: SKMEIMore Men Watches from SKMEI

                        </span></h3>
                    <div class="rating">
                        <?php
                        $avg = (float) $product['average_rating'];
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
                        <span>( <?= esc($product['review_count']) ?> reviews )</span>
                    </div>
                    <div class="product__details__price">₹ <?= round(esc($product['pr_Selling_Price'] ?? '0')) ?><span>₹
                            <?= round(esc($product['pr_Selling_Price'])) ?></span></div>
                    <p><?= esc($product['pr_Description']) ?></p>
                    <div class="product__details__button">
                        <div class="quantity">
                            <span>Quantity:</span>
                            <div class="pro-qty">
                                <input type="text" value="1">
                            </div>
                        </div>

                        <a href="javascript:void(0);" class="cart-btn" id="addToCartBtn">
                            <span class="icon_bag_alt"></span> Add to cart
                        </a>
                        <ul>
                            <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                            <li><a href="#"><span class="icon_adjust-horiz"></span></a></li>
                        </ul>
                    </div>
                    <div class="product__details__widget">
                        <ul>
                            <li>
                                <span>Availability:</span>
                                <div class="stock__checkbox">
                                    <label for="stockin">
                                        In Stock
                                        <input type="checkbox" id="stockin">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </li>
                            <li>

                                <span>Available color:</span>
                                <div class="color__checkbox">
                                    <?php if (!empty($product['colors'])): ?>
                                        <?php foreach ($product['colors'] as $index => $clr): ?>
                                            <label for="color-<?= $index ?>">
                                                <input type="radio" name="color__radio" id="color-<?= $index ?>"
                                                    data-pri-id="<?= esc($clr['pri_Id']) ?>"
                                                    data-color="<?= esc($clr['color']) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                                <span class="checkmark"
                                                    style="background-color: <?= esc($clr['color']) ?>;"></span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No colors available.</p>
                                    <?php endif; ?>
                                </div>

                            </li>
                            <li>
                                <span>Available size:</span>
                                <div class="size__btn">
                                    <?php if (!empty($product['sizes'])): ?>
                                        <?php foreach ($product['sizes'] as $i => $size): ?>
                                            <label for="xs-btn" class="active">
                                                <input type="radio" id="xs-btn">
                                                <?= esc($size) ?>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No sizes available</p>
                                    <?php endif; ?>

                                </div>
                            </li>
                            <li>
                                <span>Promotions:</span>
                                <p>Free shipping</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Specification</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Reviews
                                (<?= esc($product['review_count']) ?>)</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <h6>Description</h6>
                            <p><?= esc($product['pr_Description']) ?></p>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <h6>Specification</h6>
                            <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut loret fugit, sed
                                quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt loret.
                                Neque porro lorem quisquam est, qui dolorem ipsum quia dolor si. Nemo enim ipsam
                                voluptatem quia voluptas sit aspernatur aut odit aut loret fugit, sed quia ipsu
                                consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Nulla
                                consequat massa quis enim.</p>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget
                                dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes,
                                nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium
                                quis, sem.</p>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <h6>Reviews (<?= esc($product['review_count']) ?>)</h6>
                            <?php if (!empty($product['reviews'])): ?>
                                <div class="reviews-container">
                                    <?php foreach ($product['reviews'] as $index => $review): ?>
                                        <div class="review-box mb-3" data-index="<?= $index ?>"
                                            style="<?= $index >= 5 ? 'display:none;' : '' ?>">
                                            <p><b><?= esc(ucwords(strtolower($review['name']))) ?></b></p>
                                            <div class="rating">
                                                <?php
                                                $rating = (int) $review['rating'];
                                                for ($i = 1; $i <= 5; $i++):
                                                    if ($i <= $rating): ?>
                                                        <i class="fa fa-star text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="fa fa-star-o text-muted"></i>
                                                    <?php endif;
                                                endfor;
                                                ?>
                                            </div>

                                            <?php
                                            $rawReview = $review['review'];

                                            $fullReview = preg_replace_callback(
                                                '/(^[a-z])|(\. [a-z])/',
                                                fn($m) => isset($m[1]) ? strtoupper($m[1]) : strtoupper($m[2][0]) . substr($m[2], 1),
                                                $rawReview
                                            );

                                            $shortReview = strlen($fullReview) > 150 ? substr($fullReview, 0, 175) . '...' : $fullReview;
                                            ?>

                                            <p class="review-text">
                                                <?= $shortReview ?>
                                                <?php if (strlen($fullReview) > 150): ?>
                                                    <a href="javascript:void(0);" class="see-more text-primary">See more</a>
                                                    <span class="full-review d-none"><?= $fullReview ?></span>
                                                <?php endif; ?>
                                            </p>

                                            <small class="text-secondary">
                                                <?= esc(date('j F Y', strtotime($review['created_at']))) ?>
                                            </small>
                                            <hr>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($product['reviews']) > 5): ?>
                                    <button id="load-more-reviews" class="btn btn-primary btn-sm">See More Reviews >> </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <p>No reviews yet.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="related__title">
                    <h5>RELATED PRODUCTS</h5>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="img/product/related/rp-1.jpg">
                        <div class="label new">New</div>
                        <ul class="product__hover">
                            <li><a href="img/product/related/rp-1.jpg" class="image-popup"><span
                                        class="arrow_expand"></span></a></li>
                            <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                            <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">Buttons tweed blazer</a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price">$ 59.0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="img/product/related/rp-2.jpg">
                        <ul class="product__hover">
                            <li><a href="img/product/related/rp-2.jpg" class="image-popup"><span
                                        class="arrow_expand"></span></a></li>
                            <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                            <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">Flowy striped skirt</a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price">$ 49.0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="img/product/related/rp-3.jpg">
                        <div class="label stockout">out of stock</div>
                        <ul class="product__hover">
                            <li><a href="img/product/related/rp-3.jpg" class="image-popup"><span
                                        class="arrow_expand"></span></a></li>
                            <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                            <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">Cotton T-Shirt</a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price">₹ 59.0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="img/product/related/rp-4.jpg">
                        <ul class="product__hover">
                            <li><a href="img/product/related/rp-4.jpg" class="image-popup"><span
                                        class="arrow_expand"></span></a></li>
                            <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                            <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">Slim striped pocket shirt</a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price">$ 59.0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Details Section End -->

<div class="instagram">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg"
                    data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/footer-banyan/footer-7.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">Voyc Online Shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg"
                    data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/footer-banyan/footer-6.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">Voyc Online Shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg"
                    data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/footer-banyan/footer-5.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">Voyc Online Shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg"
                    data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/footer-banyan/footer-9.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">Voyc Online Shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg"
                    data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/footer-banyan/footer-8.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">Voyc Online Shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg"
                    data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/footer-banyan/footer-10.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">Voyc Online Shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>