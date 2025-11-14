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
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $index => $cat): ?>
                                        <?php
                                        $collapseId = 'collapse' . $index;
                                        $isFirst = $index === 0;
                                        ?>
                                        <div class="card">
                                            <?php if (!empty($cat['subcategories'])): ?>
                                                <div class="card-heading <?= $isFirst ? 'active' : '' ?>">
                                                    <a data-toggle="collapse" data-target="#<?= $collapseId; ?>">
                                                        <?= esc($cat['cat_Name']); ?>
                                                    </a>
                                                </div>
                                                <div id="<?= $collapseId; ?>" class="collapse <?= $isFirst ? 'show' : '' ?>"
                                                    data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <?php foreach ($cat['subcategories'] as $sub): ?>
                                                            <li class="form-check">
                                                                <input class="form-check-input custom-check subcategory-filter"
                                                                    type="checkbox" name="subcategories[]"
                                                                    value="<?= esc($sub['sub_Id']); ?>" id="sub_<?= $sub['sub_Id']; ?>"
                                                                    data-subcategory="<?= esc($sub['sub_Id']); ?>">
                                                                <label class="form-check-label" for="sub_<?= $sub['sub_Id']; ?>">
                                                                    <?= esc($sub['sub_Category_Name']); ?>
                                                                </label>
                                                            </li>

                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No Categories Available.</p>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <div class="sidebar__filter">
                        <div class="section-title">
                            <h4>Shop by price</h4>
                        </div>
                        <div class="filter-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="200" data-max="10000"></div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <p>Price:</p>
                                    <input type="text" id="minamount">
                                    <input type="text" id="maxamount">
                                </div>
                            </div>
                        </div>
                        <div class="filter__price">
                            <!-- <a href= "#">Filter</a> -->
                            <button type="button" id="filterPriceBtn">Filter</button>
                        </div>
                    </div>
                    <!-- <div class="sidebar__sizes">
                        <div class="section-title">
                            <h4>Shop by size</h4>
                        </div>
                        <div class="size__list">
                            <label for="s">
                                S
                                <input type="checkbox" id="s">
                                <span class="checkmark"></span>
                            </label>
                            <label for="m">
                                M
                                <input type="checkbox" id="m">
                                <span class="checkmark"></span>
                            </label>
                            <label for="ml">
                                L
                                <input type="checkbox" id="ml">
                                <span class="checkmark"></span>
                            </label>
                            <label for="l">
                                XL
                                <input type="checkbox" id="l">
                                <span class="checkmark"></span>
                            </label>
                            <label for="xl">
                                XXL
                                <input type="checkbox" id="xl">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div> -->
                    <div class="sidebar__sizes">
                        <div class="section-title">
                            <h4>Shop by Size</h4>
                        </div>

                        <ul class="size__list list-unstyled">
                            <li class="form-check form-check-size-filter">
                                <input class="form-check-input custom-check size-filter" type="checkbox" name="sizes[]"
                                    value="S" id="size_s" data-size="S">
                                <label class="form-check-label" for="size_s">S</label>
                            </li>

                            <li class="form-check form-check-size-filter">
                                <input class="form-check-input custom-check size-filter" type="checkbox" name="sizes[]"
                                    value="M" id="size_m" data-size="M">
                                <label class="form-check-label" for="size_m">M</label>
                            </li>

                            <li class="form-check form-check-size-filter">
                                <input class="form-check-input custom-check size-filter" type="checkbox" name="sizes[]"
                                    value="L" id="size_l" data-size="L">
                                <label class="form-check-label" for="size_l">L</label>
                            </li>

                            <li class="form-check form-check-size-filter">
                                <input class="form-check-input custom-check size-filter" type="checkbox" name="sizes[]"
                                    value="XL" id="size_xl" data-size="XL">
                                <label class="form-check-label" for="size_xl">XL</label>
                            </li>

                            <li class="form-check form-check-size-filter">
                                <input class="form-check-input custom-check size-filter" type="checkbox" name="sizes[]"
                                    value="XXL" id="size_xxl" data-size="XXL">
                                <label class="form-check-label" for="size_xxl">XXL</label>
                            </li>
                        </ul>
                    </div>

                    
                </div>
            </div>
            
            <div class="col-lg-9 col-md-9">
                <div class="row product-list">
                    <?php if (!empty($display_item)): ?>

                        <?php foreach ($display_item as $item): ?>
                            <div class="col-lg-4 col-md-6 mb-4 product__card" style="opacity:1;">
                                <div class="product__item"
                                    data-url="<?= base_url('productdetails/' . $item['pr_Id'] . '/' . $item['pri_Id']); ?>">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg"
                                            data-setbg="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'])) ?>">
                                            
                                            <!-- <ul class="product__hover">
                                                <li>
                                                    <a href="<?= base_url('uploads/productmedia/' . ($item['pri_Thumbnail'])) ?>"
                                                        class="image-popup">
                                                        <span class="arrow_expand"></span>
                                                    </a>
                                                </li>
                                                <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                                                <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                                            </ul> -->
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
                        <div class="pagination__option"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shop Section End -->



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
</div> -->