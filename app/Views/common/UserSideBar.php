<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(); ?>"><i class="fa fa-home"></i> Home</a>
                    <span id="breadcrumb-current">
                        <?= isset($breadcrumb) ? esc($breadcrumb) : 'Shop'; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->


<!-- Shop Section Begin -->
<section class="shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="shop__sidebar">
                    <div class="sidebar__categories">
                        <div class="section-title">
                            <h4>Over View</h4>
                        </div>
                        <div class="dashboard_side_bar">
                            <div class="main_dashboard_items">
                                <div class="head__list">
                                    <h5>Orders</h5>
                                </div>
                                <div class="sud__head__list">
                                    <a href="<?= base_url('my_orders'); ?>">
                                        <p>Orders & Returns</p>
                                    </a>
                                    <p>My Wishlist</p>
                                </div>
                            </div>
                            <hr />
                            <div class="main_dashboard_items">
                                <div class="head__list">
                                    <h5>Game Arena</h5>
                                </div>
                                <div class="sud__head__list">
                                    <p>Leader Board</p>
                                </div>
                            </div>
                            <hr />
                            <div class="main_dashboard_items">
                                <div class="head__list">
                                    <h5>Accounts</h5>
                                </div>
                                <div class="sud__head__list">
                                    <a href="<?= base_url('myprofile'); ?>">
                                        <p>My Profile</p>
                                    </a>
                                    <p>My Wallet</p>
                                    <p>My Address</p>
                                    <p>My Wishlist</p>
                                    <p>Delete My Account</p>
                                </div>
                            </div>
                            <hr />

                            <div class="main_dashboard_items">
                                <div class="head__list">
                                    <h5>Legal</h5>
                                </div>
                                <div class="sud__head__list">
                                    <p>Terms & conditions</p>
                                    <p>Privacy and Policy</p>
                                </div>
                            </div>
                            <hr />
                        </div>

                    </div>

                </div>
            </div>
