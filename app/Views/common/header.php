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
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/custom.css" type="text/css">
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

    <header class="header show-after">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-lg-2 col-2">
                    <div class="header__logo">
                        <a href="<?= base_url(' '); ?>"></a>
                    </div>

                </div>
                <div class="col-8 mt-4 main__icon">
                    <a href="#"><i class="bi bi-controller"></i></a>
                    <a href="#"><i class="bi bi-alexa"></i></a>
                </div>
                <div class="col-xl-6 col-lg-7 ">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="<?= base_url(' '); ?>" id="home">Home</a></li>
                            <li><a href="<?= base_url('women'); ?>" id="women">Women’s</a></li>
                            <li><a href="<?= base_url('men'); ?>" id="men">Men’s</a></li>
                            <li><a href="#" id="game_arena">Game Arena</a></li>
                            <li><a href="#" id="contact">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-2">
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