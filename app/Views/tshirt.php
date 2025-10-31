<!-- <div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 pl-0">
                <div class="breadcrumb__links">
                    <a href="<?= base_url(' '); ?>"><i class="fa fa-home"></i> Home</a>
                    <span>Design</span>
                </div>
            </div>
        </div>
    </div>
</div> -->
<section class="custom_design">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tshirt-container">
                    <div class="row">
                        <div class="col-md-12 pl-0">
                            <h5 class="custome_design_heading"><b>Customize Your Tee</b></h5>
                            <div class="alert alert-danger d-none" id="design_msg_alert"></div>
                        </div>
                    </div>
                    <input type="hidden" name="prId" value="<?= isset($prId) ? esc($prId) : ''; ?>">
                    <input type="hidden" name="priId" value="<?= isset($priId) ? esc($priId) : ''; ?>">
                    <div class="row">
                        <div class="col-md-6 w-100 pr-0 pb-3 my_design_btn">
                            <button id="saveBtn" class="btn btn-dark text-end">Save My Design</button>

                        </div>
                    </div>
                    <div class="row">
                        <div class="sidebar">
                            <div class="sidebar-item" data-view="upload">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/upload-w.png"
                                    alt="Upload Icon" class="sidebar-icon" /><br />
                                <p class="m-0">
                                    Upload
                                </p>
                            </div>
                            <div class="sidebar-item" data-view="add_text">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/text-w.png"
                                    alt="Add Text Icon" class="sidebar-icon" /><br />
                                <p class="m-0">
                                    Add Text
                                </p>
                            </div>
                            <div class="sidebar-item" data-view="add_art">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/landscape-w.png"
                                    alt="Add Art Icon" class="sidebar-icon" /><br />
                                <p class="m-0">
                                    Add Art
                                </p>
                            </div>
                            <div class="sidebar-item" data-view="product_colors">
                                <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/change-w.png"
                                    alt="Product Colors Icon" class="sidebar-icon" /><br />
                                <p class="m-0">
                                    Product<br>Colors
                                </p>
                            </div>
                        </div>

                        <div class="main-content p-3" id="controls">
                            <div id="customize_main_ui">
                                <h2>What's next for you?</h2>
                                <div class="options">
                                    <div class="option" data-view="upload">
                                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/upload.png"
                                            alt="Upload">
                                        <div>Upload</div>
                                    </div>
                                    <div class="option" data-view="add_text">
                                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/text.png"
                                            alt="Add Text">
                                        <div>Add Text</div>
                                    </div>
                                    <div class="option" data-view="add_art">
                                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/landscape.png"
                                            alt="Add Art">
                                        <div>Add Art</div>
                                    </div>
                                    <div class="option" data-view="product_colors">
                                        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/customize/change.png"
                                            alt="Change Products">
                                        <div>Change Products Color</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload View -->
                            <div id="view-upload" class="view-section d-none p-4">
                                <h2 class="mb-4">Choose File to Upload</h2>
                                <div class="d-flex justify-content-center">
                                    <div class="upload__image text-center">
                                        <img src="<?= base_url(ASSET_PATH . 'assets/img/customize/upload.png'); ?>"
                                            alt="Upload" class="mb-3" style="max-width: 80px;">
                                        <div>
                                            <label for="uploadImage" class="custom-file-upload">
                                                Browse Image
                                            </label>
                                            <input type="file" id="uploadImage" accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Text View -->
                            <div id="view-add_text" class="view-section d-none p-4 w-100">
                                <h2 class="mb-4">Add Text</h2>
                                <p class="mb-3">Enter your message on selected Text.</p>

                                <button id="addText" class="btn btn-dark mt-2 w-100">+ Add Text</button>


                                <div class="">
                                    <label for="fontFamily" class="form-label fw-semibold text-center">Font</label>
                                    <button id="openFontPicker" class="btn btn-outline-secondary w-100">Choose Font
                                        Style</button>
                                </div>
                                <!-- Available font listing -->
                                <div id="fontPickerContainer" class="d-none p-4 border rounded" style="">
                                    <div class="row g-3">
                                        <!-- Font items will be injected here -->
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap">
                                    <label class="form-label">Text Color &nbsp;</label>
                                    <input type="color" id="textColor" value="#000000"
                                        class="form-control form-control-color" title="Choose your color">

                                </div>
                                <div class="d-flex flex-wrap">
                                    <label for="fontSize" class="form-label">Font Size &nbsp; </label>
                                    <input type="range" id="fontSize" class="form-range" min="10" max="80" value="20">
                                </div>
                                <div class="mb-3 d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="boldToggle">
                                        <label class="form-check-label fw-semibold" for="boldToggle">
                                            Bold
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="italicToggle">
                                        <label class="form-check-label fw-semibold" for="italicToggle">
                                            Italic
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Art View -->
                            <div id="view-add_art" class="view-section d-none">
                                <h2>Select Artwork</h2>
                                <div class="options">
                                    <div class="option">
                                        <img src="<?= base_url(ASSET_PATH . 'assets/img/customize/landscape.png'); ?>"
                                            alt="Add Art">
                                        <div>Choose from our gallery or upload your own.</div>
                                    </div>
                                </div>
                            </div>


                            <div id="view-product_colors" class="view-section d-none">
                                <h2>Choose Product Color</h2>
                                <div class="mt-3">
                                    <label>Available Colors for this Product:</label>
                                    <div class="card p-3 shadow-sm">
                                        <div class="d-flex flex-wrap color_small_container">
                                            <?php if (!empty($allData)): ?>
                                                <?php foreach ($allData as $item): ?>
                                                    <?php
                                                    $colorData = json_decode($item['color_details'], true);
                                                    $colorHex = isset($colorData['color']) ? $colorData['color'] : '#ccc';
                                                    ?>
                                                    <div class="color-preview colors__round"
                                                        data-priid="<?= esc($item['pri_Id']) ?>"
                                                        style="background-color: <?= esc($colorHex) ?>;">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>No colors available for this product.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sizes -->
                                <div class="mt-3">
                                    <label>Available Sizes</label>
                                    <div class="card p-3 shadow-sm">
                                        <div class="d-flex flex-wrap size_container" id="sizeContainer">
                                            <?php
                                            $sizeOrder = ["XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "5XL", "6XL"];

                                            $currentColorArray = array_filter($allData, function ($item) use ($priId) {
                                                return $item['pri_Id'] == $priId;
                                            });

                                            if (!empty($currentColorArray)) {
                                                $currentColor = array_values($currentColorArray)[0];

                                                $variants = $currentColor['variants'];
                                                usort($variants, function ($a, $b) use ($sizeOrder) {
                                                    return array_search($a['prv_Size'], $sizeOrder) - array_search($b['prv_Size'], $sizeOrder);
                                                });

                                                foreach ($variants as $variant) {
                                                    ?>
                                                    <div class="size-box m-1 p-2 border rounded selectable-size"
                                                        data-prv-id="<?= esc($variant['prv_Id']) ?>">
                                                        <?= esc($variant['prv_Size']) ?> - ₹<?= esc($variant['prv_price']) ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- <div class="bottom-text">
                                💡 Drag & drop a file anywhere to upload.
                            </div> -->
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="row">
                                <div class="col-md-10 text-center">
                                    <div class="designer-wrapper d-flex flex-column align-items-center">
                                        <div id="designer-container">
                                            <canvas id="tshirtCanvas" width="400" height="500"></canvas>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 text-center px-0" id="imageContainer">
                                    <div class="thumbs d-flex flex-column align-items-center">
                                        <?php if (isset($cust_image) && !empty($cust_image)): ?>
                                            <img src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>"
                                                data-src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>"
                                                data-view="front" class="shirt-thumb" />
                                            <small>Front View</small>
                                            <img src="<?= base_url('uploads/productmedia/' . $cust_image['pri_File_Name'][0]); ?>"
                                                data-src="<?= base_url('uploads/productmedia/' . $cust_image['pri_File_Name'][0]); ?>"
                                                data-view="back" class="shirt-thumb" />
                                            <small>Back View</small>

                                            <div id="addSleeveBtn"
                                                class="border rounded py-2 px-3 mt-2 mb-2 text-center bg-light fw-semibold"
                                                style="cursor:pointer; font-size: 14px;">
                                                <small> <i class="bi bi-plus-lg text-dark plus_Symbol"></i> <br />Add Sleeve
                                                    Design</small>
                                            </div>
                                            <div id="sleeveContainer" class="d-none flex-column align-items-center">
                                                <img src="<?= base_url('uploads/productmedia/' . $cust_image['RSleeve_Img']); ?>"
                                                    data-src="<?= base_url('uploads/productmedia/' . $cust_image['RSleeve_Img']); ?>"
                                                    data-view="RSleeve_Img" class="shirt-thumb" />
                                                <small>Right Sleeve</small>
                                                <img src="<?= base_url('uploads/productmedia/' . $cust_image['LSleeve_Img']); ?>"
                                                    data-src="<?= base_url('uploads/productmedia/' . $cust_image['LSleeve_Img']); ?>"
                                                    data-view="LSleeve_Img" class="shirt-thumb" />
                                                <small>Left Sleeve</small>
                                            </div>
                                        <?php else: ?>
                                            <p>No images found for this product.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="summary__customisation py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header customisation__summary_header">
                        <h4 class="fw-bolb">
                            Customisation Summary
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Product Name:</strong> <span id="summaryProductName"
                                        class="text-muted"><?php echo $variants[0]['pr_Name']; ?></span></p>
                                <p class="mb-2"><strong>Product Code:</strong> <span id="summaryProductCode"
                                        class="text-muted"><?php echo $variants[0]['pr_Code']; ?></span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Size:</strong>
                                    <span id="summarySize" class="text-muted">
                                        <?php echo $variants[0]['prv_Size']; ?>
                                    </span>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">

                                        <p class="mb-2"><strong>Quantity:</strong>
                                        <div class="quantity-wrapper-custom ">
                                            <button class="qty-btn-custom minus-custom">−</button>
                                            <span class="quantity-value-custom">1</span>
                                            <button class="qty-btn-custom plus-custom">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="customisation__price  p-3 rounded-3">
                                    <h5 class="fw-semibold mb-3"><b>Your design zone</b></h5>

                                    <div class="design-option d-flex justify-content-between align-items-center py-2 mb-1"
                                        data-type="front">
                                        <label class="mb-0 d-flex align-items-center">
                                            <input type="checkbox" class="design-check me-2"> Front Design
                                        </label>
                                    </div>

                                    <div class="design-option d-flex justify-content-between align-items-center py-2 mb-1"
                                        data-type="back">
                                        <label class="mb-0 d-flex align-items-center">
                                            <input type="checkbox" class="design-check me-2"> Back Design
                                        </label>
                                    </div>

                                    <div class="design-option d-flex justify-content-between align-items-center py-2 mb-1"
                                        data-type="right">
                                        <label class="mb-0 d-flex align-items-center">
                                            <input type="checkbox" class="design-check me-2"> Right Sleeve
                                        </label>
                                    </div>

                                    <div class="design-option d-flex justify-content-between align-items-center py-2 mb-1"
                                        data-type="left">
                                        <label class="mb-0 d-flex align-items-center">
                                            <input type="checkbox" class="design-check me-2"> Left Sleeve
                                        </label>
                                    </div>

                                    <input type="hidden" id="front_Customization_Price"
                                        value="<?= esc($customisationPrice['front_Customization_Price']) ?>">
                                    <input type="hidden" id="back_Customization_Price"
                                        value="<?= esc($customisationPrice['back_Customization_Price']) ?>">
                                    <input type="hidden" id="sleeve_Customization_Price"
                                        value="<?= esc($customisationPrice['sleeve_Customization_Price']) ?>">

                                    <div class="preview-section border-top pt-3">
                                        <div class="selected-items"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="customisation__price  p-3 rounded-3">
                                    <h5 class="fw-semibold mb-3"><b>Customization Pricing Details</b></h5>
                                    <div class="d-flex justify-content-between py-2">
                                        <span>Product Rate:</span>

                                        <span class="fw-semibold text-success" id="priceProduct">+
                                            ₹<?php echo $variants[0]['prv_price']; ?> </span>
                                    </div>
                                    <div class="price-section" id="front">
                                        <div class="d-flex justify-content-between py-2">
                                            <span>Front Design</span>
                                            <span class="fw-semibold text-success" id="priceFront">+ </span>
                                        </div>
                                    </div>
                                    <div class="price-section" id="back">
                                        <div class="d-flex justify-content-between py-2">
                                            <span>Back Design</span>
                                            <span class="fw-semibold text-success" id="priceBack">+ </span>
                                        </div>
                                    </div>
                                    <div class="price-section" id="right">
                                        <div class="d-flex justify-content-between py-2">
                                            <span>Right Sleeve</span>
                                            <span class="fw-semibold text-success" id="priceRightSleeve">+ </span>
                                        </div>
                                    </div>
                                    <div class="price-section" id="left">
                                        <div class="d-flex justify-content-between py-2">
                                            <span>Left Sleeve</span>
                                            <span class="fw-semibold text-success" id="priceLeftSleeve">+ </span>
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-between pt-3 mt-3 border-top">
                                        <span class="fw-bold">Total:</span>
                                        <span class="fw-bold text-primary" id="priceTotal"></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>