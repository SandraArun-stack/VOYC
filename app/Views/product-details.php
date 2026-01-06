<?php
$userId = session()->get('user_id');
$pr_for = strtolower($product['pr_for']);
?>
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(' '); ?>"><i class="fa fa-home"></i>Home</a>
                    <a href="<?= base_url($pr_for) ?>"><?= esc($product['pr_for']) ?> </a>
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
        <div id="messageBox" class="alert alert-success" style="display: none;"></div>
        <div class="row">
            <div class="col-lg-6">
                <div class="product__details__pic">
                    <!-- Thumbnails -->
                    <div class="product__details__pic__left product__thumb nice-scroll">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $i => $img): ?>
                                <div class="thumb-item <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>">
                                    <img src="<?= base_url('uploads/productmedia/' . $img); ?>" alt="">
                                </div>
                            <?php endforeach; ?>
                            <?php if (!empty($video_url)): ?>
                                <div class="thumb-item" data-index="<?= !empty($images) ? count($images) : 0 ?>">
                                    <div class="video-thumb">
                                        <video src="<?= $video_url ?>" preload="metadata" muted></video>
                                        <!-- <div class="video-overlay">
                                            <i class="fas fa-play"></i>
                                        </div> -->
                                    </div>

                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="thumb-item active" data-index="0">
                                <img src="<?= base_url('uploads/productmedia/' . ($product['prd_first_image'] ?? 'default.jpg')); ?>"
                                    alt="">
                            </div>
                        <?php endif; ?>


                    </div>

                    <!-- ✅ KEEP only ONE carousel -->
                    <div class="product__details__slider__content">
                        <div class="product__details__pic__slider owl-carousel">
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $img): ?>
                                    <div class="item">
                                        <img src="<?= base_url('uploads/productmedia/' . $img); ?>" class="product__big__img"
                                            alt="">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="item">
                                    <img src="<?= base_url('uploads/productmedia/' . ($product['prd_first_image'] ?? 'default.jpg')); ?>"
                                        class="product__big__img" alt="">
                                </div>
                            <?php endif; ?>

                            <!-- Show video at the end if it exists -->
                            <?php if (!empty($video_url)): ?>
                                <div class="item video-slide">
                                    <div class="video-wrapper">
                                        <video class="product__big__video" src="<?= $video_url ?>"
                                            preload="metadata"></video>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                <div class="product__details__text">
                    <h3 class="product-title">
                        <?= nl2br(wordwrap(esc($product['pr_Name']), 30, "\n", true)) ?>
                    </h3>
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
                    <div class="product__details__price">₹
                        <span>₹ </span>
                    </div>
                    <p>Inclusive of all taxes</p>
                    <?php if (!empty($product['pr_custom']) && $product['pr_custom'] == 1): ?>
                        <div class="customize__container">
                            <small class="user__Customize">
                                🎨Personalize your T-shirt with your own design, text, or image before adding to cart.
                            </small>
                            <div class="customise__btn">
                                <button class="btn  customise__Tee" id="customizeTshirtBtn">
                                    <i class="bi bi-palette-fill"></i>
                                    Customize Tee >>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="product__details__button">
                        <div class="quantity">
                            <span>Quantity:</span>
                            <div class="quantity-box d-flex align-items-center">
                                <button type="button" class="qty-btn" id="qty-minus">−</button>
                                <input type="text" id="quantity" value="1" readonly>
                                <button type="button" class="qty-btn" id="qty-plus">+</button>
                            </div>
                        </div>
                        <!-- <a href="javascript:void(0);" class="cart-btn" id="addToCartBtn">
                            <span class="icon_bag_alt"></span> Add to cart
                        </a> -->

                        <a href="javascript:void(0);" id="addToCartBtn" class="cart-btn">
                            <span class="icon_bag_alt"></span>
                            Add to Cart
                        </a>

                    </div>
                    <div class="product__details__widget">
                        <ul>

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
                            <li class="size-section">
                                <span class="size-title">Available size:</span>
                                <div class="size__btn trendy-size-group">
                                    <?php foreach ($product['sizes'] as $variant): ?>
                                        <div class="size-option" data-size="<?= $variant['prv_Size']; ?>"
                                            data-price="<?= $variant['prv_price']; ?>"
                                            data-size-id="<?= $variant['prv_Id']; ?>">
                                            <input type="radio" name="product_size" id="size_<?= $variant['prv_Size']; ?>"
                                                value="<?= $variant['prv_Id']; ?>" hidden>
                                            <label for="size_<?= $variant['prv_Size']; ?>" class="size-label">
                                                <?= $variant['prv_Size'];?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </li>

                            <!-- <li>
                                <span>Promotions:</span>
                                <p>Free shipping</p>
                            </li> -->
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
                             <p><?= nl2br(esc($product['pr_Description'])) ?></p>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <h6>Specification</h6>
                            <p>Stiching Type:
                                <?= !empty($product['pr_Stitch_Type']) ? esc($product['pr_Stitch_Type']) : '&nbsp;' ?>
                            </p>
                            <p>Sleeve:
                                <?= !empty($product['pr_Sleeve_Style']) ? esc($product['pr_Sleeve_Style']) : '&nbsp;' ?>
                            </p>
                            <p>Fabric: <?= !empty($product['pr_Fabric']) ? esc($product['pr_Fabric']) : '&nbsp;' ?></p>


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
        <?php if (!empty($relatedProducts)): ?>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="related__title">
                        <h5>RELATED PRODUCTS</h5>
                    </div>
                </div>

                <?php foreach ($relatedProducts as $rp): ?>
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <div class="">
                            <a href="<?= base_url("productdetails/{$rp->pr_Id}/{$rp->pri_Id}"); ?>">
                                <div class="product__item__pic set-bg"
                                    data-setbg="<?= base_url('uploads/productmedia/' . $rp->pri_Thumbnail); ?>">
                                </div>
                                <div class="product__item__text mt-2">
                                    <h6 class="product_name_text"><?= $rp->pr_Name; ?></h6>
                                    <div class="rating">
                                        <?php
                                        $avg = (float) $rp->avg_rating; // FIXED
                                
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
                                    <div class="product__price">₹ <?= number_format($rp->min_price); ?>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>




    </div>
</section>
<!-- Product Details Section End -->