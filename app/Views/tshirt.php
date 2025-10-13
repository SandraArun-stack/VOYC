<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
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
                        <div class="col-md-6">
                            <h5 class="custome_design_heading"><b>Customize Your Tee</b></h5>

                            <div class="thumbs">
                                <?php if (isset($cust_image) && !empty($cust_image)): ?>
                                    <img src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>"
                                        data-src="<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>" />
                                    <?php foreach ($cust_image['pri_File_Name'] as $file): ?>
                                        <img src="<?= base_url('uploads/productmedia/' . $file); ?>"
                                            data-src="<?= base_url('uploads/productmedia/' . $file); ?>" />
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <p>No images found for this product.</p>
                                <?php endif; ?>
                            </div>

                            <div id="designer-container">
                                <canvas id="tshirtCanvas" width="400" height="500"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-danger d-none" id="design_msg_alert"></div>
                            <input type="hidden" name="prId" value="<?= isset($prId) ? esc($prId) : ''; ?>">
                            <input type="hidden" name="priId" value="<?= isset($priId) ? esc($priId) : ''; ?>">

                            <div id="controls">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="addText" class="btn btn-primary">Add Text</button>
                                        <label>Text Color:
                                            <input type="color" id="textColor" value="#000000">
                                        </label>
                                    </div>
                                </div>
                                <!-- <label>Dress Color: <input type="color" id="colorPicker" value="#9e9e9e"></label> -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Font:
                                            <select class="form-select form-select-lg mb-3" id="fontFamily">
                                                <option value="Arial">Arial</option>
                                                <option value="Times New Roman">Times New Roman</option>
                                                <option value="Courier New">Courier New</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Comic Sans MS">Comic Sans</option>
                                            </select>
                                        </label>

                                        <label><input type="checkbox" id="boldToggle"> Bold</label>
                                        <label><input type="checkbox" id="italicToggle"> Italic</label>

                                        <label>Size: <input type="range" id="fontSize" min="10" max="80"
                                                value="20"></label>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="file" id="uploadImage" accept="image/*">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="cropBtn" class="btn" disabled title="(Not implemented in this snippet)">Crop Image</button>
                                        <button id="resetOverlayBtn" class="btn btn-warning">Reset Dress Position</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="small"><input type="checkbox" id="lockOverlay"> Lock Dress</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="deleteBtn" class="btn btn-danger">Delete Selected</button>
                                        <button id="saveBtn" class="btn btn-dark">Save Design</button>
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