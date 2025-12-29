<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(' '); ?>"><i class="fa fa-home"></i>Home</a>
                    <a href="<?= base_url('cart'); ?>">
                        Cart
                    </a>
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
        <form action="#" class="checkout__form" id="checkout__form">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Select a Existing Address</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
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
                                        <p>Country <span>*</span></p>
                                        <input type="text" name="add_Country">
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Postcode/Zip <span>*</span></p>
                                        <input type="text" name="add_Pincode" placeholder="Zipcode">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="checkout__form__input">
                                        <p>Phone <span>*</span></p>
                                        <input type="text" name="add_Phone" placeholder="Phone" value="<?= esc($cust_Phone  ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="checkout__form__input">
                                        <p>Email <span>*</span></p>
                                        <input type="text" name="add_Email" placeholder="abc@gmail.com" value="<?= esc($cust_Email ?? '') ?>">
                                    </div>
                                </div>
                            
                            </div>
                            <div class="row">
                                <div class="col-lg-12 d-flex  justify-content-between">
                                    <div class="d-flex w-100">
                                        <input type="checkbox" id="same_as_shipping" name="same_as_shipping" checked> &nbsp;
                                        <span class="text-center align-items-center justify-content-center d-flex">Billing address is same as shipping address?</span>
                                    </div>
                                    <div class="add_shipping_address">
                                            <button type="button" class="text-white black btn-sm" id="add_shipping_address_btn">
                                                <span class="plus_shipping_text">Add Shipping Address</span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 d-none" id="shipping_address_section" >
                            <h5>Shipping detail</h5>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="checkout__form__input">
                                        <p>First Name <span>*</span></p>
                                        <input type="text" name="shipping_add_Name" placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="checkout__form__input">
                                        <p>Last Name <span>*</span></p>
                                        <input type="text" name="shipping_add_LastName">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    
                                    <div class="checkout__form__input">
                                        <p>Address <span>*</span></p>
                                        <input type="text" name="shipping_add_Street" placeholder="Street Address">
                                        <input type="text" name="shipping_add_Landmark"
                                            placeholder="Apartment, Suite, Unit, etc. (optional)">
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Town/City <span>*</span></p>
                                        <input type="text" name="shipping_add_City" placeholder="City">
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>State <span>*</span></p>
                                        <input type="text" name="shipping_add_State" placeholder="State">
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Country <span>*</span></p>
                                        <input type="text" name="shipping_add_Country">
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Postcode/Zip <span>*</span></p>
                                        <input type="text" name="shipping_add_Pincode" placeholder="Zipcode">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="checkout__form__input">
                                        <p>Phone <span>*</span></p>
                                        <input type="text" name="shipping_add_Phone" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="checkout__form__input">
                                        <p>Email <span>*</span></p>
                                        <input type="text" name="shipping_add_Email" placeholder="abc@gmail.com">
                                    </div>
                                </div>
                            
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
                                <?php
                                $subtotal = 0;
                                if (!empty($cartItems)):
                                    $count = 1;
                                    foreach ($cartItems as $item):
                                        $total = $item['cart_Price'] * $item['cart_Quantity'];
                                        $subtotal += $total;
                                        ?>
                                        <input type="hidden" id="order-total" value="<?= $totalAmount ?>">
                                        <li data-prid="<?= $item['pr_Id'] ?>" data-priid="<?= $item['pri_Id'] ?>" data-prvid="<?= $item['prv_Id'] ?>"
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
                                <li id="shipping_charge_order_detail">Shipping Charge <span id="shipping_charge_text">₹ <?= number_format($shipping_charge, 2) ?></span></li>
                                    <input type="hidden" id="shipping_charge_value" value="<?= $shipping_charge ?>">
                                    <input type="hidden" id="minimum_amount_for_shipping_charge" value="<?= $minimum_amount_for_shipping_charge ?>">
                            </ul>
                            
                        </div>
                        
                        <!-- Order Totals -->
                        <div class="checkout__order__total">
                            <ul>
                                <li id="subtotal">Subtotal <span>₹ <?= number_format($subtotal, 2) ?></span></li>
                                <li id="total_of_all">Total <span>₹ <?= number_format($subtotal, 2) ?></span></li>
                            </ul>
                        </div>
                        <div class="alert d-none small" id="alertPlaceOrder"></div>
                        <div class="text_coupen">
                        <span class="small"> Already have a coupon? Enter your coupon code below to apply the discount to your order.</span>

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