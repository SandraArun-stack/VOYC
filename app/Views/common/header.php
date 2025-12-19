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
    <!-- <link href="https://fonts.googleapis.com/css2?family=Playwrite+HU:wght@100..400&display=swap" rel="stylesheet"> -->
    <link
        href="https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/owl.theme.default.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/custom.css" type="text/css">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/aos.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

    <link rel="icon" href="<?= base_url() . ASSET_PATH; ?>assets/img/favicon.ico" type="image/x-icon">
    <script>var base_url = "<?= base_url() ?>";</script>
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
                    <!-- <div class="tip">2</div> -->
                </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="<?= base_url(' '); ?>"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg"
                    alt=""></a>
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
                <div class="col-xl-2 col-lg-2 col-2">
                    <a href="<?= base_url(' '); ?>">
                        <div class="header__logo"></div>
                    </a>

                </div>
                <div class="col-8 mt-4 main__icon">
                    <!-- <a href="#"><i class="bi bi-controller"></i></a> -->
                    <a href="<?= base_url('tshirt_Customisation'); ?>" class="design_icon"></a>
                </div>
                <div class="col-xl-7 col-lg-7 d-flex align-items-center justify-content-center text-center">
                    <nav class="header__menu">
                        <ul>
                            <li class=" "><a href="<?= base_url(); ?>" id="home">Home</a></li>
                            <li><a href="<?= base_url('women'); ?>" id="women">Women’s</a></li>
                            <li><a href="<?= base_url('men'); ?>" id="men">Men’s</a></li>
                            <!-- <li><a href="<?= base_url('game_arena'); ?>" id="game_arena">Game Arena</a></li> -->
                            <li><a href="<?= base_url('contact'); ?>" id="contact">Contact</a></li>
                        </ul>
                    </nav>

                    <!-- Search Icon -->
                    <div class="search-wrapper">
                        <span id="search-toggle" class="icon_search" style="cursor:pointer;"></span>
                        <input type="text" id="search-bar" placeholder="Search..." />
                    </div>
                </div>
                <?php $session = session(); ?>
                <div class="col-lg-3 col-2">
                    <div class="header__right">

                        <ul class="header__right__widget">
                             <?php if ($session->get('isLoggedIn')): ?>
                            <!-- <li>
                                 <?php $userId = $session->get('user_id'); 
                                 $userSubscription = $session->get('user_subscription'); ?>
                                <a href="<?= base_url('mywallet'); ?>" class="icon-with-text"><i class="bi bi-wallet2"></i>
                                    <div class="tip">2</div>
                                    <span class="icon-label">Wallet</span>
                                </a>
                            </li> -->
                            <li>
                                <a href="<?= base_url('cart/' . $userId); ?>" class="icon-with-text">
                                    <i class="bi bi-cart"></i>
                                    <span class="icon-label">Cart</span>
                                    <div class="tip" id="cartCount"><?= $cartCount ?? 0 ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="customization_icon_header">
                                <a href="<?= base_url('allCustomizableProducts'); ?>" class="icon-with-text">
                                    <i class="custom-icon-customisation"></i>
                                    <span class="icon-label">Design</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="leader_board" class="icon-with-text">
                                    <i class="bi bi-award-fill"></i>
                                    <span class="icon-label">Winners</span>
                                </a>
                            </li>
                        </ul>

                        <div class="header__right__auth">

                            <div class="dropdown" id="userDropDown">
                                <a class=" text-decoration-none icon-with-text" href="#" role="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-square profile-person"></i>
                                    <span class="icon-label">Profile</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end profile-small-container"
                                    style="display:none;" aria-labelledby="userDropdown" style="display:none;">
                                    <?php if ($session->get('isLoggedIn')): ?>
                                        <ul class="profile__container">
                                            <li class="py-1 px-4"><b>Hello, <?= esc($session->get('user_name')) ?></b></li>
                                            <ul class="profile__container__listing">
                                                <li>
                                                    <a class="dropdown-item drop-profile"
                                                        href="<?= base_url('myprofile'); ?>">
                                                        Profile
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item drop-profile"
                                                        href="<?= base_url('my_orders'); ?>">
                                                        My Orders
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item drop-profile"
                                                        href="<?= base_url('subscription_plans'); ?>">
                                                        Subscription Plans
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item drop-profile text-danger" href="#"
                                                        id="logoutBtn">
                                                        Logout
                                                    </a>
                                                </li>
                                            </ul>
                                        </ul>

                                    <?php else: ?>
                                        <p class="dropdown-item drop-profile hideActive" href="#"><b>Welcome</b></p>
                                        <p class="dropdown-item drop-profile welcome hideActive" href="#">
                                            To access account and manage orders</p>
                                        <div class="login-reg">
                                            <button class="login-sign-up-button" id="login-link" href="#"
                                                data-bs-toggle="modal" data-bs-target="#authModal">
                                                Login/Sign Up
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <!-- <a id="login-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Login</a>
                                <a id="register-link" href="#" data-bs-toggle="modal"
                                    data-bs-target="#authModal">Register</a> -->

                        </div>
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
                    <div class="modal-header border-0 position-relative p-2">
                        <button type="button" class="btn border-0 bg-transparent position-absolute login_close"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg text-dark"></i></i>
                        </button>

                        <div class="d-flex flex-column justify-content-center align-items-center w-100 pt-3 pb-0">
                            <a href="<?= base_url('/'); ?>">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">
                            </a>
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
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-center mt-3">
                                <small class=""> Don’t have an account? <a href="#" id="to-register">Sign Up</a></small>
                            </div>

                            <div class="text-center mt-3">
                                <small><a href="#" id="to-forgot-password">Forgot Password?</a></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REGISTER VIEW -->
                <div id="registerView">
                    <div class="modal-header border-0 position-relative p-2">
                        <button type="button" class="btn border-0 bg-transparent position-absolute login_close"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg text-dark"></i>
                        </button>

                        <div class="d-flex flex-column justify-content-center align-items-center w-100 pt-3 pb-0">
                            <a href="<?= base_url('/'); ?>">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">
                            </a>
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

                            <label>Phone Number</label><span>&nbsp;*</span>
                            <input type="number" name="phone_number" class="form-control mb-3"
                                placeholder="Enter the Phone Number" required>

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

                <div id="forgotPassView" class="d-none">
                    <div class="modal-header border-0 position-relative p-2">
                        <button type="button" class="btn border-0 bg-transparent position-absolute login_close"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg text-dark"></i>
                        </button>

                        <div class="d-flex flex-column justify-content-center align-items-center w-100 pt-3 pb-0">
                            <a href="<?= base_url('/'); ?>">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="">
                            </a>
                            <h3 class="auth-title mb-0 text-center">Forgot Password</h3>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-success m-1 p-2 w-auto d-none" id="forgotalert"></div>
                        <form id="forgotPassForm">
                            <label class="d-block">
                                Email <span>*</span>
                            </label>

                            <small class="forgot-text d-block mb-1">
                                Enter Your Registered Email to Receive a Password Reset Link.
                            </small>
                            <input type="email" name="forgot_email" class="form-control mb-3 forgot-input"
                                placeholder="Enter Your Email" required>

                            <div class="submit_forgot w-100 justify-content-between d-flex">
                                <button type="button" id="btn_forgot_password" class="btn btn-dark w-100"
                                    data-url="<?= base_url('forgotPassword') ?>">
                                    Send Reset Link
                                </button>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="#" id="to-login-from-forgot" class="text-center align-items-center d-flex">
                                    Back to Login
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="categoriesModal" class="d-none custom-modal" data-aos="zoom-in" data-aos-duration="600">

        <div class="custom-modal-content">
            <span class="close-btn">&times;</span>

            <div class="leaderboard-header text-center mb-2">
                <h3 class="leaderboard-title">PLAYERS OF THE DAY</h3>
            </div>
            <div class="leaders-scroll" id="playersScroll">

                <?php

                // Icon array for ranks
                $icons = [
                    1 => "🥇",
                    2 => "🥈",
                    3 => "🥉",
                    4 => "🏅"

                ];

                // Default image if none found
                $defaultImg = base_url() . ASSET_PATH . "assets/img/winner/kid-first.jpg";

                if (!empty($players)):
                    foreach ($players as $index => $row):
                        $rank = $row['player_rank'];
                        $playerName = $row['player_name'] ?? "Player " . $row['cust_Id'];
                        $score = $row['player_score'];
                        ?>
                        <div class="leaderboard-item winner-<?= strtolower($rank); ?>">
                            <div class="position-icon first">
                                <?= $icons[$rank] ?? "🏅"; ?>
                            </div>

                            <img src="<?= $defaultImg; ?>" alt="Winner" class="winner-img">

                            <div class="winner-info">
                                <h4><?= esc($playerName); ?></h4>
                                <p>Score: <?= esc($score); ?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else: ?>
                    <p class="text-center text-danger">No records found for Today.</p>
                <?php endif; ?>
                <?php if ($lastPlayer): ?>
                    <!-- <div class="leaderboard-item current-user">
                        <h4><?= esc($lastPlayer['player_name']) ?> (You)</h4>
                        <p>Score: <?= esc($lastPlayer['player_score']) ?></p>
                    </div> -->
                    <div class="leaderboard-item winner-<?= strtolower($rank); ?>">
                        <div class="position-icon first">
                            <?= $icons[$rank] ?? "🏅"; ?>
                        </div>

                        <img src="<?= $defaultImg; ?>" alt="Winner" class="winner-img">

                        <div class="winner-info">
                            <h4><?= esc($lastPlayer['player_name']) ?> (You)</h4>
                            <p>Score: <?= esc($lastPlayer['player_score']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
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