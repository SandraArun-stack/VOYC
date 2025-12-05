<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <nav class="pcoded-navbar">
            <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
            <div class="pcoded-inner-navbar main-menu">
                <div class="">

                    <div class="main-menu-header">
                        <!-- <img class="img-80 img-radius" src="<?php echo base_url() . ASSET_PATH; ?>Admin/assets/images/avatar-4.jpg" alt="User-Profile-Image"> -->
                        <img class="mt-2" src="<?= base_url() . ASSET_PATH; ?>Admin/assets/images/logo.png"
                            alt="User-Profile-Image" />
                        <div class="user-details">

                            <span id="more-details"><i class="fa fa-caret"></i></span>
                        </div>
                    </div>
                    <div class="main-menu-content">

                    </div>
                </div>
                <div class="p-15 p-b-0">

                </div>
                <div class="pcoded-navigation-label" data-i18n="nav.category.navigation"></div>
                <?php
                $uri = service('uri');
                $segment = $uri->getSegment(2); // Gets the first segment of the URI
                ?>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= ($segment == 'dashboard') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/dashboard') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?= ($segment == 'staff') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/staff') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-person-add"></i><b>FC</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.form-components.main">Staff</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?= ($segment == 'customer') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/customer') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-people"></i><b>FC</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.form-components.main">Customer</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?= ($segment == 'orders') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/orders') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-bag-heart"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Orders</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="pcoded-hasmenu <?= in_array($segment, ['leaderboard','games','players','subscription','usersubscriptions','wallet']) ? 'active pcoded-trigger' : '' ?>">
                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-controller"></i></span>
                            <span class="pcoded-mtext">Game Arena</span>
                            <span class="pcoded-mcaret"></span>
                        </a>

                        <ul class="pcoded-submenu">
                            <li class="<?= ($segment == 'leaderboard') ? 'active' : '' ?>">
                                <a href="<?= base_url('admin/leaderboard') ?>">
                                    <span class="pcoded-mtext">Leaderboard</span>
                                </a>
                            </li>
                            <li class="<?= ($segment == 'games') ? 'active' : '' ?>">
                                <a href="<?= base_url('admin/games') ?>">
                                    <span class="pcoded-mtext">Game Mapping</span>
                                </a>
                            </li>
                            <li class="<?= ($segment == 'players') ? 'active' : '' ?>">
                                <a href="<?= base_url('admin/players') ?>">
                                    <span class="pcoded-mtext">Game Players</span>
                                </a>
                            </li>
                            <li class="<?= ($segment == 'subscription') ? 'active' : '' ?>">
                                <a href="<?= base_url('admin/subscription') ?>">
                                    <span class="pcoded-mtext">Manage Subscriptions</span>
                                </a>
                            </li>

                            <li class="<?= ($segment == 'usersubscriptions') ? 'active' : '' ?>">
                                <a href="<?= base_url('admin/usersubscriptions') ?>">
                                    <span class="pcoded-mtext">User Subscriptions</span>
                                </a>
                            </li>

                            <li class="<?= ($segment == 'wallet') ? 'active' : '' ?>">
                                <a href="<?= base_url('admin/wallet') ?>">
                                    <span class="pcoded-mtext">User Wallet</span>
                                </a>
                            </li>

    
                        </ul>
                    </li>


                    <!-- <li class="<?= ($segment == 'profile') ? 'active' : '' ?>">
                                    <a href="<?php echo base_url('admin/profile') ?>" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="bi bi-person-circle"></i></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Profile</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li> -->
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= ($segment == 'category') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/category') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-bookmark"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Category</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>

                    <li class="<?= ($segment == 'subcategory') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/subcategory') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-bookmark-plus"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Sub Category</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?= ($segment == 'product') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/product') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-box-seam"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Products</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <!-- <li class="<?= ($segment == 'themes') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/themes') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-circle-half"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Themes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li> -->
                    <li class="<?= ($segment == 'settings') ? 'active' : '' ?>">
                        <a href="<?php echo base_url('admin/settings') ?>" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="bi bi-gear"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Settings</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
        </nav>