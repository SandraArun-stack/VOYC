<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(' '); ?>"><i class="fa fa-home"></i>Home</a>
                    <span>Shopping cart</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Shop Cart Section Begin -->
<section class="shop-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shop__cart__table">
                    <table>
                        <?php if (!empty($cartItems)): ?>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            
                                <?php foreach ($cartItems as $item): ?>
                                    <tr data-cartid="<?= esc($item['cart_Id']) ?>">
                                        <td class="cart__product__item">
                                            <?php
                                            $designImage = !empty($item['front_Image']) ? $item['front_Image'] : null;
                                            $image = $designImage;
                                            ?>
                                            <?php if (!empty($image)): ?>
                                                <a href="javascript:void(0);" class="text-primary show-preview"
                                                    data-front="<?= base_url('uploads/designs/' . $item['front_Image']) ?>"
                                                    data-back="<?= base_url('uploads/designs/' . $item['back_Image']) ?>"
                                                    data-sleeve="<?= base_url('uploads/designs/' . $item['sleeve_Image']) ?>">
                                                    <img src="<?= base_url('uploads/designs/' . $image) ?>" alt="Product Image">
                                                </a>
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/productmedia/default.png') ?>" alt="Default Image">
                                            <?php endif; ?>

                                            <div class="cart__product__item__title">
                                                <h6><?= esc($item['pr_Name']) ?></h6>
                                                <div class="rating">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart__price">₹ <?= esc($item['prv_price']) ?></td>
                                        <td class="cart__quantity">
                                            <div class="pro-qty">
                                                <input type="text" value="<?= esc($item['cart_Quantity']) ?>">
                                            </div>
                                        </td>
                                        <td class="cart__total">₹
                                            <?= number_format($item['prv_price'] * $item['cart_Quantity'], 2) ?>
                                        </td>
                                        <td class="cart__close">
                                            <span class="icon_close cart-remove"
                                                data-cart-id="<?= esc($item['cart_Id']) ?>"></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                            </tbody>
                            <?php else: ?>
                                    <p>Your cart is empty.</p>
                            <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="cart__btn">
                    <a href="#">Continue Shopping</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="cart__btn update__btn">
                    <a href="#"><span class="icon_loading"></span> Update cart</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="discount__content">
                    <h6>Discount codes</h6>
                    <form action="#">
                        <input type="text" placeholder="Enter your coupon code">
                        <button type="submit" class="site-btn">Apply</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-2">
                <div class="cart__total__procced">
                    <h6>Cart total</h6>
                    <?php
                    $calculatedTotal = 0;
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            $calculatedTotal += $item['prv_price'] * $item['cart_Quantity'];
                        }
                    }
                    ?>
                    <!-- <ul>
                        <li>Subtotal <span id="subtotal-amount">₹ <?= number_format($calculatedTotal, 2) ?></span></li>
                        <li>Total <span id="total-amount">₹ <?= number_format($calculatedTotal, 2) ?></span></li>
                    </ul> -->
                    <ul>
                        <li>Subtotal <span id="subtotal-amount">₹ 0.00</span></li>
                        <li>Total <span id="total-amount">₹ 0.00</span></li>
                    </ul>

                    <a href="<?= base_url('orderdetails'); ?>" class="primary-btn proceed_check_out">Proceed to checkout</a>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Shop Cart Section End -->

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
<!-- Design Preview Modal -->
<div class="modal fade" id="designPreviewModal" tabindex="-1" aria-labelledby="designPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="designPreviewLabel">Design Preview</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body text-center row">
                <div class="col-md-4">
                    <p>Front View</p>
                    <img id="previewFront" src="" alt="Front" class="img-fluid border rounded" />
                </div>
                <div class="col-md-4">
                    <p>Back View</p>
                    <img id="previewBack" src="" alt="Back" class="img-fluid border rounded" />
                </div>
                <div class="col-md-4">
                    <p>Sleeve View</p>
                    <img id="previewSleeve" src="" alt="Sleeve" class="img-fluid border rounded" />
                </div>
            </div>
        </div>
    </div>
</div>