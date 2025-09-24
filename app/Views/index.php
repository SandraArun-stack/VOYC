<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Voyc Online Shop">
    <meta name="keywords" content="Voyc Online Shop">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voyc - The Online Shop</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="icon" href="<?= base_url() . ASSET_PATH; ?>assets/img/favicon.ico" type="image/x-icon">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="#"><span class="icon_heart_alt"></span>
                    <div class="tip">2</div>
                </a></li>
            <li><a href="#"><span class="icon_bag_alt"></span>
                    <div class="tip">2</div>
                </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            <a href="#">Login</a>
            <a href="#">Register</a>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-lg-2">
                    <div class="header__logo">
                        <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-7">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#">Women’s</a></li>
                            <li><a href="#">Men’s</a></li>
                            <li><a href="#">Game Arena</a></li>
                            <!-- <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./product-details.html">Product Details</a></li>
                                    <li><a href="./shop-cart.html">Shop Cart</a></li>
                                    <li><a href="./checkout.html">Checkout</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                </ul>
                            </li> -->
                            <!-- <li><a href="./blog.html">Blog</a></li> -->
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__right">
                        <div class="header__right__auth">
                            <a href="#">Login</a>
                            <a href="#">Register</a>
                        </div>
                        <ul class="header__right__widget">
                            <li><span class="icon_search search-switch"></span></li>
                            <li><a href="#"><i class="bi bi-wallet2"></i>
                                    <div class="tip">2</div>
                                </a></li>
                            <li><a href="#"><i class="bi bi-cart"></i>
                                    <div class="tip">2</div>
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="canvas__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container-fluid">
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
                        <?php $firstImage = $item['prd_first_image'] ?? null; ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4"> <!-- 4 per row on large screens -->
                            <div class="product__item">
                                <div class="product__item__pic">
                                    <?php if ($firstImage): ?>
                                        <img class="product-img" src="<?= base_url('uploads/productmedia/' . $firstImage); ?>"
                                            alt="Product Image" />
                                    <?php else: ?>
                                        <img class="product-img" src="<?= base_url('assets/img/no-image.png'); ?>" alt="No Image" />
                                    <?php endif; ?>
                                    <div class="label new">New</div>
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
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="product__price">₹ <?= round(esc($item['pr_Selling_Price'] ?? '0.0')); ?></div>
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
                                    <?php $firstImage = $item['prd_first_image'] ?? null; ?>
                                    <div class="trend__item d-flex">
                                        <div class="trend__item__pic">
                                            <?php if ($firstImage): ?>
                                                <img class="product-img" src="<?= base_url('uploads/productmedia/' . $firstImage); ?>"
                                                    alt="<?= esc($item['pr_Name'] ?? 'Product'); ?>" />
                                            <?php else: ?>
                                                <img class="product-img" src="<?= base_url('assets/img/no-image.png'); ?>"
                                                    alt="No Image" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="trend__item__text">
                                            <h6><?= esc($item['pr_Name'] ?? 'Product'); ?></h6>
                                            <div class="rating">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <div class="product__price">₹ <?= round(esc($item['pr_Selling_Price'] ?? '0.0')); ?>
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
    <!-- Instagram End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-7">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo.png" alt=""></a>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                            cilisis.</p>
                        <div class="footer__payment">
                            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/payment/payment-1.png"
                                    alt=""></a>
                            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/payment/payment-2.png"
                                    alt=""></a>
                            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/payment/payment-3.png"
                                    alt=""></a>
                            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/payment/payment-4.png"
                                    alt=""></a>
                            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/payment/payment-5.png"
                                    alt=""></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-5">
                    <div class="footer__widget">
                        <h6>Quick links</h6>
                        <ul>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Blogs</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <div class="footer__widget">
                        <h6>Account</h6>
                        <ul>
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">Orders Tracking</a></li>
                            <li><a href="#">Checkout</a></li>
                            <li><a href="#">Wishlist</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-8">
                    <div class="footer__newslatter">
                        <h6>NEWSLETTER</h6>
                        <form action="#">
                            <input type="text" placeholder="Email">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form>
                        <div class="footer__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-youtube-play"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright__text">
                        <p>Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script> All rights reserved | This
                            template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a
                                href="https://www.smartlounge.online/" target="_blank">Smartlounge</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery-ui.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/mixitup.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.countdown.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.slicknav.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/owl.carousel.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.nicescroll.min.js"></script>
    <script src="<?= base_url() . ASSET_PATH; ?>assets/js/main.js"></script>
</body>

</html>