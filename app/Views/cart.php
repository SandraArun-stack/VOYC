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
                <div class="shop__cart__table text-center">
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

                                    <tr data-cartid="<?= esc($item['cart_Id']) ?>" class=" clickable-row"
                                        data-url="<?= base_url('productdetails/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                        <td class="cart__product__item">
                                            <?php
                                            $designImage = !empty($item['front_Image']) ? $item['front_Image'] : null;
                                            $image = $designImage;
                                            ?>
                                            <?php if (!empty($image)): ?>
                                                <a href="javascript:void(0);" class="text-primary show-preview"
                                                    data-front="<?= base_url('uploads/designs/' . $item['front_Image']) ?>"
                                                    data-back="<?= base_url('uploads/designs/' . $item['back_Image']) ?>"
                                                    data-rsleeve="<?= base_url('uploads/designs/' . $item['RSleeve_Image']) ?>"
                                                    data-lsleeve="<?= base_url('uploads/designs/' . $item['LSleeve_Image']) ?>">
                                                    <img src="<?= base_url('uploads/designs/' . $image) ?>" alt="Product Image">
                                                </a>
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'] ?? 'default.jpg')) ?>"
                                                    alt="Product Image">
                                            <?php endif; ?>

                                            <div class="cart__product__item__title">
                                                <a
                                                    href="<?= base_url('productdetails/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                                    <h6><?= esc($item['pr_Name']) ?></h6>
                                                </a>
                                                <?php
                                                $sizeOptions = $item['size_options'];
                                                // $sizeOptions = json_decode($item['size_options'], true) ?? [];
                                                $currentSize = $item['cart_Size'] ?? '';
                                                ?>
                                                <div class="size-cart-drop">

                                                    <label>Size:</label>
                                                    <select class="form-select cart-size-dropdown px-3"
                                                        data-cart-id="<?= esc($item['cart_Id']) ?>"
                                                        data-pri-id="<?= esc($item['pri_Id']) ?>">
                                                        <?php foreach ($sizeOptions as $opt): ?>
                                                            <option value="<?= esc($opt['prv_Id']) ?>"
                                                                data-price="<?= esc($opt['prv_price']) ?>"
                                                                <?= ($opt['prv_Size'] == $currentSize) ? 'selected' : '' ?>>
                                                                <?= esc($opt['prv_Size']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- <div class="rating">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div> -->
                                                <div class="rating">
                                                    <?php
                                                    $avg = (float) $item['average_rating'];
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= floor($avg)) {
                                                            echo '<i class="fa fa-star text-warning mr-0"></i>';
                                                        } elseif ($i == ceil($avg) && $avg - floor($avg) >= 0.5) {
                                                            echo '<i class="fa fa-star-half-o text-warning mr-0"></i>';
                                                        } else {
                                                            echo '<i class="fa fa-star-o text-muted mr-0"></i>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart__price">₹
                                            <?= !empty($item['cart_Price']) ? esc($item['cart_Price']) : '0' ?>
                                        </td>

                                        <td class="cart__quantity">
                                            <div class="pro-qty">
                                                <input type="text" value="<?= esc($item['cart_Quantity']) ?>">
                                            </div>
                                        </td>
                                        <td class="cart__total" data-price="<?= esc($item['cart_Price']) ?>"
                                            data-quantity="<?= esc($item['cart_Quantity']) ?>">₹0.00</td>

                                        <td class="cart__close">

                                            <span class="cart-remove" data-cart-id="<?= esc($item['cart_Id']) ?>">
                                                <i class="bi bi-trash3-fill"></i>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>

                </div>
                <p class="empty-cart-block text-center"
                    style="<?= empty($cartItems) ? 'display:block;' : 'display:none;' ?>">
                    Your cart is empty.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="cart__btn">
                    <a href="<?= base_url(); ?>" id="continue_shopping">Continue Shopping</a>
                </div>
            </div>
            <?php if (!empty($cartCount) && $cartCount > 0): ?>
                <div class="col-lg-4 offset-lg-2">
                    <div class="cart__total__procced">
                        <h6>Cart total</h6>
                        <?php
                        $calculatedTotal = 0;
                        if (!empty($cartItems)) {
                            foreach ($cartItems as $item) {
                                $calculatedTotal += $item['cart_Price'] * $item['cart_Quantity'];
                            }
                        }
                        ?>
                        <ul>
                            <li>Subtotal <span id="subtotal-amount">₹ 0.00</span></li>
                            <li>Total <span id="total-amount">₹ 0.00</span></li>
                        </ul>

                        <a href="#" id="proceedCheckout" class="primary-btn proceed_check_out">
                            Proceed to checkout
                        </a>
                        <form id="goCheckoutForm" action="<?= base_url('orderdetails') ?>" method="POST"
                            style="display:none;">
                            <input type="hidden" name="totalAmount" id="hiddenTotal">

                        </form>

                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="discount__content">
                    <!-- <h6>Discount codes</h6>
                    <form action="#">
                        <input type="text" placeholder="Enter your coupon code">
                        <button type="submit" class="site-btn">Apply</button>
                    </form> -->
                </div>
            </div>
            <!-- <div class="col-lg-4 offset-lg-2">
                <div class="cart__total__procced">
                    <h6>Cart total</h6>
                    <?php
                    $calculatedTotal = 0;
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            $calculatedTotal += $item['cart_Price'] * $item['cart_Quantity'];
                        }
                    }
                    ?>
                    <ul>
                        <li>Subtotal <span id="subtotal-amount">₹ 0.00</span></li>
                        <li>Total <span id="total-amount">₹ 0.00</span></li>
                    </ul>

                    <a  href="#" id="proceedCheckout" class="primary-btn proceed_check_out">
                        Proceed to checkout
                    </a>
                    <form id="goCheckoutForm" action="<?= base_url('orderdetails') ?>" method="POST"
                        style="display:none;">
                        <input type="hidden" name="totalAmount" id="hiddenTotal">
                        
                    </form>

                </div>
            </div> -->

        </div>
    </div>
</section>
<!-- Shop Cart Section End -->

<!-- Design Preview Modal -->
<div class="modal fade" id="designPreviewModal" tabindex="-1" aria-labelledby="designPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Design Preview</h5>

                <button type="button" class="btn btn-sm close-preview"
                    style="font-size: 25px; background:none; border:none;" data-bs-dismiss="modal">
                    &times;
                </button>
            </div>


            <div class="modal-body text-center row">
                <div class="col-md-3">
                    <p class="mb-1 mt-2">Front View</p>
                    <img id="previewFront" src="" alt="Front" class="img-fluid border rounded" />
                </div>
                <div class="col-md-3">
                    <p class="mb-1 mt-2">Back View</p>
                    <img id="previewBack" src="" alt="Back" class="img-fluid border rounded" />
                </div>
                <div class="col-md-3">
                    <p class="mb-1 mt-2">Right Sleeve View</p>
                    <img id="previewRSleeve" src="" alt="RSleeve" class="img-fluid border rounded" />
                </div>
                <div class="col-md-3">
                    <p class="mb-1 mt-2">Left Sleeve View</p>
                    <img id="previewLSleeve" src="" alt="LSleeve" class="img-fluid border rounded" />
                </div>
            </div>
        </div>
    </div>
</div>