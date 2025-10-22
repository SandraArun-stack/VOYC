<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(' '); ?>"><i class="fa fa-home"></i> Home</a>
                    <span><?= esc($breadcrumb) ?></span>
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
                            <h4>Categories</h4>
                        </div>
                        <div class="categories__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading active">
                                        <a data-toggle="collapse" data-target="#collapseOne">Women</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><a>Coats</a></li>
                                                <li><a>Jackets</a></li>
                                                <li><a>Dresses</a></li>
                                                <li><a>Shirts</a></li>
                                                <li><a>T-shirts</a></li>
                                                <li><a>Jeans</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseTwo">Men</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><a>Coats</a></li>
                                                <li><a>Jackets</a></li>
                                                <li><a>Dresses</a></li>
                                                <li><a>Shirts</a></li>
                                                <li><a>T-shirts</a></li>
                                                <li><a>Jeans</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__filter">
                        <div class="section-title">
                            <h4>Shop by price</h4>
                        </div>
                        <div class="filter-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="33" data-max="99"></div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <p>Price:</p>
                                    <input type="text" id="minamount">
                                    <input type="text" id="maxamount">
                                </div>
                            </div>
                        </div>
                        <a href="#">Filter</a>
                    </div>
                    <div class="sidebar__sizes">
                        <div class="section-title">
                            <h4>Shop by size</h4>
                        </div>
                        <div class="size__list">
                            <label for="xxs">
                                xxs
                                <input type="checkbox" id="xxs">
                                <span class="checkmark"></span>
                            </label>
                            <label for="xs">
                                xs
                                <input type="checkbox" id="xs">
                                <span class="checkmark"></span>
                            </label>
                            <label for="xss">
                                xs-s
                                <input type="checkbox" id="xss">
                                <span class="checkmark"></span>
                            </label>
                            <label for="s">
                                s
                                <input type="checkbox" id="s">
                                <span class="checkmark"></span>
                            </label>
                            <label for="m">
                                m
                                <input type="checkbox" id="m">
                                <span class="checkmark"></span>
                            </label>
                            <label for="ml">
                                m-l
                                <input type="checkbox" id="ml">
                                <span class="checkmark"></span>
                            </label>
                            <label for="l">
                                l
                                <input type="checkbox" id="l">
                                <span class="checkmark"></span>
                            </label>
                            <label for="xl">
                                xl
                                <input type="checkbox" id="xl">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="sidebar__color">
                        <div class="section-title">
                            <h4>Shop by size</h4>
                        </div>
                        <div class="size__list color__list">
                            <label for="black">
                                Blacks
                                <input type="checkbox" id="black">
                                <span class="checkmark"></span>
                            </label>
                            <label for="whites">
                                Whites
                                <input type="checkbox" id="whites">
                                <span class="checkmark"></span>
                            </label>
                            <label for="reds">
                                Reds
                                <input type="checkbox" id="reds">
                                <span class="checkmark"></span>
                            </label>
                            <label for="greys">
                                Greys
                                <input type="checkbox" id="greys">
                                <span class="checkmark"></span>
                            </label>
                            <label for="blues">
                                Blues
                                <input type="checkbox" id="blues">
                                <span class="checkmark"></span>
                            </label>
                            <label for="beige">
                                Beige Tones
                                <input type="checkbox" id="beige">
                                <span class="checkmark"></span>
                            </label>
                            <label for="greens">
                                Greens
                                <input type="checkbox" id="greens">
                                <span class="checkmark"></span>
                            </label>
                            <label for="yellows">
                                Yellows
                                <input type="checkbox" id="yellows">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="row product-list">
                    <?php if (!empty($display_item)): ?>
                        <?php foreach ($display_item as $item): ?>
                            <div class="col-lg-4 col-md-6 mb-4 product__card">
                                <div class="product__item"
                                    data-url="<?= base_url('productdetails/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg"
                                            data-setbg="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'])) ?>">
                                            <div class="label new">
                                                <?php if ($item['pr_custom'] == 1): ?>
                                                    <a href="<?= base_url('tshirt_Customisation/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                                        <img class="design_icon"
                                                            src="<?= base_url() . ASSET_PATH ?>assets/img/design.png" alt="">
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <ul class="product__hover">
                                                <li>
                                                    <a href="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'])) ?>"
                                                        class="image-popup">
                                                        <span class="arrow_expand"></span>
                                                    </a>
                                                </li>
                                                <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                                                <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                                            </ul>
                                        </div>
                                        <div class="product__item__text">
                                            <h6><a href="#"><?= esc($item['pr_Name']) ?></a></h6>
                                            <div class="rating">
                                                <?php
                                                $avg = (float) $item['average_rating'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= floor($avg)) {
                                                        echo '<i class="fa fa-star text-warning"></i>';
                                                    } elseif ($i == ceil($avg) && $avg - floor($avg) >= 0.5) {
                                                        echo '<i class="fa fa-star-half-o text-warning"></i>';
                                                    } else {
                                                        echo '<i class="fa fa-star-o text-muted"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="product__price">
                                                ₹ <?= esc($item['selected_price']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-lg-12 text-center">
                            <p>No products found for <?= esc($category) ?>.</p>
                        </div>
                    <?php endif; ?>

                    <div class="col-lg-12 text-center">
                        <div class="pagination__option">
                            <!-- <a href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#"><i class="fa fa-angle-right"></i></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shop Section End -->

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