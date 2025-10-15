<div class="col-lg-12 p-0 video-section">
    <video class="video-fullscreen" autoplay muted playsinline>
        <source src="<?= base_url() . ASSET_PATH; ?>assets/videos/intro.mp4" type="video/mp4">
    </video>
</div>
<div class="show-after categorie-container">
    <section class="categories">
        <div class="container-fluid">
            <div id="categoriesModal" class="custom-modal" data-aos="zoom-in" data-aos-duration="600">
                <div class="custom-modal-content">
                    <div class="custom-layer">
                        <span class="close-btn">&times;</span>
                        <div class="leaderboard-header text-center">
                            <h3 class="leaderboard-title">PLAYERS OF THE DAY</h3>
                        </div>
                        <div class="leaderboard-item winner-first">
                            <div class="position-icon first">🥇</div>
                            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/winner/kid-first.jpg" alt=" Winner"
                                class="winner-img">
                            <div class="winner-info">
                                <h4>John Doe</h4>
                                <p>Score: 1500</p>
                            </div>
                        </div>
                        <div class="leaderboard-item winner-second">
                            <div class="position-icon first">🥈</div>
                            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/winner/kid-first.jpg" alt=" Winner"
                                class="winner-img">
                            <div class="winner-info ">
                                <h4>John Doe</h4>
                                <p>Score: 1500</p>
                            </div>
                        </div>
                        <div class="leaderboard-item winner-third">
                            <div class="position-icon first">🥉</div>
                            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/winner/kid-first.jpg" alt=" Winner"
                                class="winner-img">
                            <div class="winner-info ">
                                <h4>John Doe</h4>
                                <p>Score: 1500</p>
                            </div>
                        </div>
                        <div class="leaderboard-item winner-fourth">
                            <div class="position-icon first">🏅</div>
                            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/winner/kid-first.jpg" alt=" Winner"
                                class="winner-img">
                            <div class="winner-info ">
                                <h4>John Doe</h4>
                                <p>Score: 1500</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="categories__item categories__large__item set-bg"
                        data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/categories/category-1.jpg">
                        <div class="categories__text">
                            <h1>Men’s fashion</h1>
                            <p>Sitamet, consectetur adipiscing elit, sed do eiusmod tempor incidid-unt labore
                                edolore magna aliquapendisse ultrices gravida.</p>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                            <div class="categories__item set-bg"
                                data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/categories/category-2.jpg">
                                <div class="categories__text">
                                    <h4>Men’s fashion</h4>
                                    <p>358 items</p>
                                    <a href="#">Shop now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                            <div class="categories__item set-bg"
                                data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/categories/category-3.jpg">
                                <div class="categories__text">
                                    <h4>Kid’s fashion</h4>
                                    <p>273 items</p>
                                    <a href="#">Shop now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                            <div class="categories__item set-bg"
                                data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/categories/category-4.jpg">
                                <div class="categories__text">
                                    <h4>Kid’s fashion</h4>
                                    <p>159 items</p>
                                    <a href="#">Shop now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                            <div class="categories__item set-bg"
                                data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/categories/category-5.jpg">
                                <div class="categories__text">
                                    <h4>Easy Wear</h4>
                                    <p>792 items</p>
                                    <a href="#">Shop now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="section-title">
                        <h4>New product</h4>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">All</li>
                        <li data-filter=".women">Women’s</li>
                        <!-- <li data-filter=".men">Men’s</li> -->
                        <li data-filter=".kid">Kid’s</li>
                        <!-- <li data-filter=".accessories">Accessories</li>
                            <li data-filter=".cosmetic">Cosmetics</li> -->
                    </ul>
                </div>
            </div>

            <?php if (!empty($newPrdImg)): ?>
                <div class="row property__gallery">
                    <?php foreach ($newPrdImg as $item): ?>
                        <?php
                        $firstImage = $item['pri_Thumbnail'] ?? null;
                        $prId = $item['pr_Id'];
                        $priId = $item['pri_Id'];
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="product__item" data-url="<?= base_url("productdetails/$prId/$priId"); ?>">
                                <div class="product__item__pic">
                                    <?php if ($firstImage): ?>
                                        <img class="product-img"
                                            src="<?= base_url('uploads/productmedia/' . ($firstImage ?: 'default.jpg')); ?>"
                                            alt="Product Image" />
                                    <?php else: ?>
                                        <img class="product-img" src="<?= base_url('assets/img/no-image.png'); ?>" alt="No Image" />
                                    <?php endif; ?>
                                    <div class="label new">

                                        <?php if ($item['pr_custom'] == 1): ?>
                                            <a href="<?= base_url('tshirt_Customisation/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                                <img class="design_icon_rounded" src="<?= base_url() . ASSET_PATH ?>assets/img/design-round.png"
                                                    alt="">
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <ul class="product__hover">
                                        <?php if ($firstImage): ?>
                                            <li>
                                                <a href="<?= base_url('uploads/productmedia/' . $firstImage); ?>"
                                                    class="image-popup"><span class="arrow_expand"></span></a>
                                            </li>
                                        <?php endif; ?>
                                        <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                                        <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><?= esc($item['pr_Name'] ?? 'Product'); ?></h6>
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
                                    <div class="product__price">₹ <?= round(esc($item['selected_price'] ?? '0')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>

        </div>
    </section>

    <!-- Banner Section Begin -->
    <section class="banner set-bg" data-setbg="<?= base_url() . ASSET_PATH; ?>assets/img/banner/banner-1.jpg">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-8 m-auto">
                    <div class="banner__slider owl-carousel">
                        <div class="banner__item">
                            <div class="banner__text">
                                <span>The Chloe Collection</span>
                                <h1>The Project Jacket</h1>
                                <a href="#">Shop now</a>
                            </div>
                        </div>
                        <div class="banner__item">
                            <div class="banner__text">
                                <span>The Chloe Collection</span>
                                <h1>The Project Jacket</h1>
                                <a href="#">Shop now</a>
                            </div>
                        </div>
                        <div class="banner__item">
                            <div class="banner__text">
                                <span>The Chloe Collection</span>
                                <h1>The Project Jacket</h1>
                                <a href="#">Shop now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Trend Section Begin -->
    <section class="trend spad">
        <div class="container">
            <div class="row">
                <?php if (!empty($bestSeller)): ?>
                    <?php
                    $chunks = array_chunk($bestSeller, 3);
                    $sections = ['Hot Trend', 'Popular Now', 'Spotlight'];
                    ?>

                    <?php foreach ($chunks as $index => $chunk): ?>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="trend__content">
                                <div class="section-title">
                                    <h4><?= $sections[$index] ?? 'Section'; ?></h4>
                                </div>

                                <?php foreach ($chunk as $item): ?>
                                    <?php $firstImage = $item['prd_first_image'] ?? null;
                                    $prId = $item['pr_Id'];
                                    $priId = $item['pri_Id']; ?>
                                    <div class="product__item" data-url="<?= base_url("productdetails/$prId/$priId"); ?>">
                                        <div class="trend__item d-flex">
                                            <div class="trend__item__pic">
                                                <?php if ($firstImage): ?>
                                                    <img class="product-img"
                                                        src="<?= base_url('uploads/productmedia/' . ($firstImage ?: 'default.jpg')); ?>"
                                                        alt="<?= esc($item['pr_Name'] ?? 'Product'); ?>" />
                                                <?php else: ?>
                                                    <img class="product-img" src="<?= base_url('assets/img/no-image.png'); ?>"
                                                        alt="No Image" />
                                                <?php endif; ?>
                                            </div>
                                            <div class="trend__item__text">
                                                <h6><?= esc($item['pr_Name'] ?? 'Product'); ?></h6>
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
                                                <div class="product__price">₹ <?= round(esc($item['selected_price'] ?? '0.0')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <!-- Trend Section End -->

    <!-- Discount Section Begin -->
    <section class="discount">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="discount__pic">
                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/discount.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="discount__text">
                        <div class="discount__text__title">
                            <span>Discount</span>
                            <h2>Summer 2025</h2>
                            <h5><span>Sale</span> 50%</h5>
                        </div>
                        <div class="discount__countdown" id="countdown-time">
                            <div class="countdown__item">
                                <span>22</span>
                                <p>Days</p>
                            </div>
                            <div class="countdown__item">
                                <span>18</span>
                                <p>Hour</p>
                            </div>
                            <div class="countdown__item">
                                <span>46</span>
                                <p>Min</p>
                            </div>
                            <div class="countdown__item">
                                <span>05</span>
                                <p>Sec</p>
                            </div>
                        </div>
                        <a href="#">Shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Discount Section End -->

    <!-- Services Section Begin -->
    <section class="services spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="services__item">
                        <i class="fa fa-car"></i>
                        <h6>Free Shipping</h6>
                        <p>For all oder over ₹99</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="services__item">
                        <i class="fa fa-money"></i>
                        <h6>Money Back Guarantee</h6>
                        <p>If good have Problems</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="services__item">
                        <i class="fa fa-support"></i>
                        <h6>Online Support 24/7</h6>
                        <p>Dedicated support</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="services__item">
                        <i class="fa fa-headphones"></i>
                        <h6>Payment Secure</h6>
                        <p>100% secure payment</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Instagram Begin -->
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
</div>