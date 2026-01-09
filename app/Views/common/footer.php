<!-- Footer Section Begin -->
<footer class="footer show-after">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-7">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="<?= base_url('/'); ?>"><img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo.jpg"
                                alt=""></a>
                    </div>
                    <p>Location</p>
                    <span class="Location__text__footer"> The Praveen Mills,</span>
                        <span class="Location__text__footer">12, A.K Nagar, Seiyankadu,</span>
                        <span class="Location__text__footer">Karumarampalayam, Mannarai P.O,</span>
                       <span class="Location__text__footer"> Tiruppur, Tamil Nadu - 641607</span>
                    <!-- <div class="footer__payment">
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
                    </div> -->
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-5">
                <div class="footer__widget">
                    <h6>Quick links</h6>
                    <ul>
                        <!-- <li><a href="<?= base_url(' '); ?>">Game Arena</a></li> -->
                        <li><a href="<?= base_url('men'); ?>">Men's</a></li>
                        <li><a href="<?= base_url('men'); ?>">Women's</a></li>
                        <li><a href="<?= base_url('allCustomizableProducts'); ?>">Customization</a></li>
                        <li><a href="<?= base_url('contact'); ?>">About</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Account</h6>
                    <ul>
                        <li><a href="<?= base_url('myprofile'); ?>">My Account</a></li>
                        <li><a href="<?= base_url('cart'); ?>">My Cart</a></li>
                        <li><a href="<?= base_url('my_orders') ?>">My Orders</a></li>
                        <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                        <!-- <li><a href="#">Checkout</a></li> -->
                        <!-- <li><a href="#">Wishlist</a></li> -->
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="footer__newslatter">
                    <!-- <h6>NEWSLETTER</h6>
                    <form action="#">
                        <input type="text" placeholder="Email">
                        <button type="submit" class="site-btn">Subscribe</button>
                    </form> -->
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
<!-- datatable-->
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.dataTables.min.js"></script>

<script src="<?= base_url() . ASSET_PATH; ?>assets/js/bootstrap.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.magnific-popup.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/mixitup.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.countdown.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.slicknav.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/owl.carousel.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/jquery.nicescroll.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/main.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/aos.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/fabric.min.js"></script>
<script src="<?= base_url() . ASSET_PATH; ?>assets/js/custom.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script> -->


</body>

</html>