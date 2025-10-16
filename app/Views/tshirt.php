<div class="breadcrumb-option">
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
</div>
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

                        <div class="main-content" id="controls">
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

                            <!-- Product Colors View -->
                            <!-- <div id="view-product_colors" class="view-section d-none">
                                <h2>Choose Product Color</h2>
                                <div class="">
                                    <lable>Available Colors for this Product:</lable>
                                    <div class="card">
                                        color
                                    </div>
                                </div>
                            </div> -->
                            <!-- Product Colors View -->
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

                                            $currentColorArray = array_filter($allData, function($item) use ($priId) {
                                                return $item['pri_Id'] == $priId;
                                            });

                                            if (!empty($currentColorArray)) {
                                                $currentColor = array_values($currentColorArray)[0];

                                                $variants = $currentColor['variants'];
                                                usort($variants, function($a, $b) use ($sizeOrder) {
                                                    return array_search($a['prv_Size'], $sizeOrder) - array_search($b['prv_Size'], $sizeOrder);
                                                });

                                                foreach ($variants as $variant) {
                                                    ?>
                                                    <div class="size-box m-1 p-2 border rounded selectable-size" data-prv-id="<?= esc($variant['prv_Id']) ?>">
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

                                <div class="col-md-2 text-center"  id="imageContainer">
                                    <div class="thumbs d-flex flex-column align-items-center">
                                        <?php if (isset($cust_image) && !empty($cust_image)): ?>
                                            <img src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>"
                                                data-src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>"
                                                data-view="front" class="shirt-thumb" />
                                            <img src="<?= base_url('uploads/productmedia/' . $cust_image['pri_File_Name'][0]); ?>"
                                                data-src="<?= base_url('uploads/productmedia/' . $cust_image['pri_File_Name'][0]); ?>"
                                                data-view="back" class="shirt-thumb" />
                                            <img src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Sleev_Name'][0]); ?>"
                                                data-src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Sleev_Name'][0]); ?>"
                                                data-view="sleeve" class="shirt-thumb" />
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