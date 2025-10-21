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
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+HU:wght@100..400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/custom.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/aos.css" type="text/css">

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
            <li><a href="#"><i class="bi bi-award"></i>
                    <div class="tip">2</div>
                </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="#"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo.jpg" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            <a>Login/</a>
            <a>Register</a>
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
                    <a href="<?= base_url('tshirt_Customisation'); ?>" class="design_icon"></a>
                </div>
                <div class="col-xl-6 col-lg-7 ">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="<?= base_url(' '); ?>" id="home">Home</a></li>
                            <li><a href="<?= base_url('women'); ?>" id="women">Women’s</a></li>
                            <li><a href="<?= base_url('men'); ?>" id="men">Men’s</a></li>
                            <li><a href="#" id="game_arena">Game Arena</a></li>
                            <li><a href="<?= base_url('contact'); ?>" id="contact">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-2">
                    <div class="header__right">
                        <!-- <div class="header__right__auth">
                            <a id="login-link">Login</a>
                            <a id="register-link">Register</a>
                        </div> -->

                        <div class="header__right__auth">
                            <?php $session = session(); ?>

                            <?php if ($session->get('isLoggedIn')): ?>
                                <div class="dropdown" id="userDropDown">
                                    <a class="dropdown-toggle text-decoration-none" href="#" role="button" id="userDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= esc($session->get('user_name')) ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="#">Profile</a></li>
                                        <li><a class="dropdown-item text-danger" href="#" id="logoutBtn">Logout</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a id="login-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Login</a>
                                <a id="register-link" href="#" data-bs-toggle="modal"
                                    data-bs-target="#authModal">Register</a>
                            <?php endif; ?>
                        </div>

                        <ul class="header__right__widget">
                            <li><span class="icon_search search-switch"></span></li>
                            <li>
                                <a href="#"><i class="bi bi-wallet2"></i>
                                    <div class="tip">2</div>
                                </a>
                            </li>
                            <?php if ($session->get('isLoggedIn')): ?>
                                <li>
                                    <?php $userId = $session->get('user_id'); ?>
                                    <a href="<?= base_url('cart/' . $userId); ?>"><i class="bi bi-cart"></i>
                                        <div class="tip">2</div>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?= base_url('allCustomizableProducts'); ?>"><img class="design_icon"
                                        src="<?= base_url() . ASSET_PATH; ?>assets/img/design.png" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#" id="leader_board"><i class="bi bi-award-fill"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="canvas__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-2">
                <div id="loginView">
                    <!-- <div class="modal-header border-0 d-flex justify-content-center align-items-center flex-column pb-0 position-relative">
                        <button type="button" class="btn border-0 bg-transparent position-absolute top-0 end-0 m-2"
                            data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
                            <i class="bi bi-x-square text-dark"></i>
                        </button>
                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">
                        <h3 class="auth-title mb-0 w-100 text-center">Sign In</h3>

                    </div> -->
                    <div class="modal-header border-0 position-relative p-2">
                        <button type="button" class="btn border-0 bg-transparent position-absolute login_close"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-square text-dark"></i>
                        </button>

                        <div class="d-flex flex-column justify-content-center align-items-center w-100 pt-3 pb-2">
                            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">
                            <h3 class="auth-title mb-0 text-center">Sign In</h3>
                        </div>
                    </div>

                    <div class="alert alert-success m-3 p-2 w-auto d-none" id="login_msg_alert"></div>
                    <div class="modal-body">
                        <form id="loginForm">
                            <label>Email</label><span>&nbsp;*</span>
                            <input type="text" name="login_email" class="form-control mb-3"
                                placeholder="Enter Your Email" required>
                            <label>Password</label><span>&nbsp;*</span>
                            <div class="eye_icon mb-3">
                                <input type="password" name="login_password" class="form-control"
                                    id="login_toggle_password" placeholder="Enter the Password" required>
                                <i class="bi bi-eye-slash toggle-password toggle_eye_icon_register"
                                    data-target="#login_toggle_password"></i>
                            </div>

                            <label>Verification</label>
                            <div class="g-recaptcha mb-3" data-sitekey="6Le-VXcrAAAAAFdEqJLtM5DxM6GoGl7cJdV6hknL"></div>
                            <button type="button" id="btn_login" class="btn btn-primary w-100"
                                data-url="<?= base_url('loginUser') ?>">Sign In</button>
                        </form>
                        <div class="text-center mt-3">
                            Don’t have an account? <a href="#" id="to-register">Sign Up</a>
                        </div>
                    </div>
                </div>

                <!-- REGISTER VIEW -->
                <div id="registerView">
                    <!-- <div class="modal-header border-0 d-flex justify-content-center align-items-center flex-column pb-0">
                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">

                        <h3 class="auth-title mb-0  w-100  text-center">Step Into Your Style</h3>

                    </div> -->
                    <div class="modal-header border-0 position-relative p-2">
                        <button type="button" class="btn border-0 bg-transparent position-absolute login_close"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-square text-dark"></i>
                        </button>

                        <div class="d-flex flex-column justify-content-center align-items-center w-100 pt-3 pb-2">
                            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">
                            <h3 class="auth-title mb-0 text-center">Step Into Your Style</h3>
                        </div>
                    </div>
                    <div class="alert alert-success  m-3 p-2 w-auto d-none" id="reg_msg_alert"></div>
                    <div class="modal-body">
                        <form id="registerForm">
                            <label>Name</label><span>&nbsp;*</span>
                            <input type="text" name="fullname" class="form-control mb-3"
                                placeholder="Enter Your Full Name" required>
                            <label>Email</label><span>&nbsp;*</span>
                            <input type="email" name="email" class="form-control mb-3" placeholder="Enter the Email"
                                required>

                            <div class="eye_icon  mb-3">
                                <label>Password</label><span>&nbsp;*</span>
                                <input type="password" id="reg_password" name="reg_password" class="form-control"
                                    placeholder="Enter a Password" required>
                                <i class="bi bi-eye-slash toggle-password toggle_eye_icon"
                                    data-target="#reg_password"></i>
                            </div>

                            <div class="eye_icon  mb-3">
                                <label>Confirm Password</label><span>&nbsp;*</span>
                                <input type="password" id="reg_confirm_password" name="reg_confirm_password"
                                    class="form-control" placeholder="Confirm Your Password" required>
                                <i class="bi bi-eye-slash toggle-password toggle_eye_icon"
                                    data-target="#reg_confirm_password"></i>
                            </div>

                            <button type="button" class="btn btn-primary w-100"
                                data-url="<?= base_url('registerUser') ?>" id="btn_register">
                                Sign Up
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            Already have an account? <a href="#" id="to-login">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-body text-center p-4">
                    <h5 class="mb-3">Are you sure you want to log out?</h5>
                    <div class="d-flex justify-content-center logout_btns_container">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="cancel_log_out">Cancel</button>
                        <button type="button" class="btn" id="confirmLogout"
                            data-url="<?= base_url('logoutUser') ?>">Yes, Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>