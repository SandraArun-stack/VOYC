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
        <form action="#" class="checkout__form">
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
                                <p>Country/State <span>*</span></p>
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
                        <!-- <div class="col-lg-12">
                                    <div class="checkout__form__checkbox">
                                        <label for="note">
                                            Note about your order, e.g, special note for delivery
                                            <input type="checkbox" id="note">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Oder notes <span>*</span></p>
                                        <input type="text"
                                        placeholder="Note about your order, e.g, special noe for delivery">
                                    </div>
                                </div> -->
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
                                <?php
                                $subtotal = 0;
                                if (!empty($cartItems)):
                                    $count = 1;
                                    foreach ($cartItems as $item):
                                        $total = $item['cart_Price'] * $item['cart_Quantity'];
                                        $subtotal += $total;
                                        ?>
                                        <input type="hidden" id="order-total" value="<?= $totalAmount ?>">
                                        <li data-prid="<?= $item['pr_Id'] ?>" data-priid="<?= $item['pri_Id'] ?>"
                                            data-price="<?= $item['cart_Price'] ?>" data-designid="<?= $item['design_Id'] ?>"
                                            data-size="<?= $item['cart_Size'] ?>" data-prcode="<?= $item['pr_Code'] ?>"
                                            data-prname="<?= $item['pr_Name'] ?>">
                                            <?= str_pad($count, 2, '0', STR_PAD_LEFT) ?>.
                                            <?= esc($item['pr_Name']) ?>
                                            <br /> <small>(Qty: <?= esc($item['cart_Quantity']) ?> ×
                                                ₹<?= number_format($item['cart_Price'], 2) ?>)</small>
                                            <span>₹ <?= number_format($total, 2) ?></span>
                                        </li>

                                        <?php
                                        $count++;
                                    endforeach;
                                else:
                                    ?>
                                    <li>Your cart is empty.</li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Order Totals -->
                        <div class="checkout__order__total">
                            <ul>
                                <li id="subtotal">Subtotal <span>₹ <?= number_format($subtotal, 2) ?></span></li>
                                <li id="total_of_all">Total <span>₹ <?= number_format($subtotal, 2) ?></span></li>
                            </ul>
                        </div>
                        <div class="coupon-box">
                            <input type="text" id="coupen_code" placeholder="Enter Coupon Code">
                            <i class="fa fa-paste paste-icon" id="pasteCoupon"></i>
                        </div>
                        <div class="coupon-box">
                            <button type="button" id="apply_coupen_code" class="btn apply-coupon-btn">
                                <i class="fa fa-tag"></i> Apply Coupon Code
                            </button>
                        </div>
                        <button type="submit" class="site-btn">Place order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Checkout Section End -->