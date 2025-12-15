<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(' '); ?>"><i class="fa fa-home"></i>Home</a>
                    <span>Checkout</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">
        <div class="row">
            <!-- <div class="col-lg-12">
                    <h6 class="coupon__link"><span class="icon_tag_alt"></span> <a href="#">Have a coupon?</a> Click
                    here to enter your code.</h6>
                </div> -->
        </div>
        <div id="messageBox" class="alert alert-success" style="display: none;"></div>
        <span class="error-msg text-danger"></span>
        <form action="#" class="checkout__form checkout__form__free_tee" id="checkout__form__free_tee">
            <div class="row">
                <div class="col-lg-8">
                    <h5>Billing detail</h5>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="checkout__form__input">
                                <p>First Name <span>*</span></p>
                                <input type="text" name="add_Name" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="checkout__form__input">
                                <p>Last Name <span>*</span></p>
                                <input type="text" name="add_LastName">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout__form__input">
                                <p>Country <span>*</span></p>
                                <input type="text" name="add_Country">
                            </div>
                            <div class="checkout__form__input">
                                <p>Address <span>*</span></p>
                                <input type="text" name="add_Street" placeholder="Street Address">
                                <input type="text" name="add_Landmark"
                                    placeholder="Apartment, Suite, Unit, etc. (optional)">
                            </div>
                            <div class="checkout__form__input">
                                <p>Town/City <span>*</span></p>
                                <input type="text" name="add_City" placeholder="City">
                            </div>
                            <div class="checkout__form__input">
                                <p>State <span>*</span></p>
                                <input type="text" name="add_State" placeholder="State">
                            </div>
                            <div class="checkout__form__input">
                                <p>Postcode/Zip <span>*</span></p>
                                <input type="text" name="add_Pincode" placeholder="Zipcode">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="checkout__form__input">
                                <p>Phone <span>*</span></p>
                                <input type="text" name="add_Phone" placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="checkout__form__input">
                                <p>Email <span>*</span></p>
                                <input type="text" name="add_Email" placeholder="abc@gmail.com">
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkout__order">
                        <h5>Your order</h5>

                        <!-- Order Products -->
                        <div class="checkout__order__product">
                            <ul>
                                <li>
                                    <span class="top__text">Product</span>
                                    <span class="top__text__right">Total</span>
                                </li>

                                <?php if (!empty($cartItems)): ?>
                                    <?php $count = 1; ?>
                                    <?php foreach ($cartItems as $item): ?>

                                        <li>
                                            <?= str_pad($count, 2, '0', STR_PAD_LEFT) ?>.
                                            <?= esc($item['pr_Name']) ?>
                                            <br>
                                            <small>(Qty: <?= esc($item['cart_Quantity']) ?> × ₹0 )</small>

                                            <span>₹ 0</span>
                                        </li>

                                        <?php $count++; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>No product found for free order.</li>
                                <?php endif; ?>
                            </ul>
                        </div>


                        <!-- Order Totals -->
                        <div class="checkout__order__total">
                            <ul>
                                <li>Subtotal <span>₹ 0</span></li>
                                <li>Total <span>₹ 0</span></li>
                            </ul>
                        </div>

                        <button type="submit" class="site-btn">Place order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
