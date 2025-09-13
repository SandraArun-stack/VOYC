<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                         <h5 class="m-b-10"><?= isset($product) ? 'Update Product' : 'Add Product'; ?></h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                         <li class="breadcrumb-item"><a
                                href="#"><?= isset($product) ? 'Update Product' : 'Add Product'; ?></a>
                            </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">

                                        <div id="messageBox" class="alert alert-success" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="card-block">

                                    <form name="createProduct" id="createProduct" method="post" style="font-size:14px;">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Product Name <span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" name="product_name" id="productName"
                                                    class="form-control"  maxlength="30" 
                                                    value="<?= isset($product) ? ($product['pr_Name']) : '' ?>"
                                                    placeholder="Enter the Product Name" style="font-size:14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Product Code <span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="product_code"
                                                    id="productCode"
                                                    value="<?= isset($product) ? ($product['pr_Code']) : '' ?>"
                                                    placeholder="Enter the Product Code" style="font-size:14px;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Product Description <span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <textarea rows="5" cols="5" class="form-control"
                                                    name="product_description" id="productDes"
                                                    placeholder=""style="font-size:14px;"><?= isset($product) ? $product['pr_Description'] : '' ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Maximum Retail Price(MRP)<span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"style="font-size:14px;"
                                                    value="<?= isset($product) ? ($product['mrp']) : '' ?>" name="mrp"
                                                    id="mRp" placeholder="Enter the MRP"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Discount Type</label>
                                            <div class="col-sm-7">
                                                <select class="form-control fs-13" name="discount_type"
                                                    id="discountType" style="font-size:14px;" required>
                                                    <option value="">-- Select Discount Type --</option>
                                                    <option value="%"
                                                        <?= (isset($product) && $product['pr_Discount_Type'] == '%') ? 'selected' : '' ?>>
                                                        %</option>
                                                    <option value="Rs"
                                                        <?= (isset($product) && $product['pr_Discount_Type'] == 'Rs') ? 'selected' : '' ?>>
                                                        Rs</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Discount Value</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" style="font-size:14px;"
                                                    value="<?= isset($product) ? ($product['pr_Discount_Value']) : '' ?>"
                                                    name="discount_value" id="discountValue"
                                                    placeholder="Enter the Discount value"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Selling Price</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" style="font-size:14px;"
                                                    value="<?= isset($product) ? ($product['pr_Selling_Price']) : '' ?>"
                                                    name="selling_price" id="sellingPrice" readonly placeholder="0">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Category Name<span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <select class="form-control fs-13" name="cat_id" id="categoryName" style="font-size:14px;"
                                                    required>
                                                    <option value="">-- Select Category --</option>
                                                    <?php foreach ($categories as $cate): ?>
                                                    <option value="<?= esc($cate->cat_Id); ?>"
                                                        <?= isset($product) && $product['cat_Id']  == $cate->cat_Id ? 'selected' : '' ?>>
                                                        <?= esc($cate->cat_Name); ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Subcategory Name</label>
                                            <div class="col-sm-7">
                                                <select class="form-control fs-13" name="sub_id" id="subcategoryName" style="font-size:14px;">
                                                    <option value="">-- Select Subcategory --</option>
                                                </select>
                                               
                                            </div>

                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Product Stock<span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" style="font-size:14px;" required
                                                    value="<?= isset($product) ? ($product['pr_Stock']) : '' ?>"
                                                    name="product_stock" id="productStock"
                                                    placeholder="Enter the Product Stock" 
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Product Reset Stock<span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" style="font-size:14px;" 
                                                    value="<?= isset($product) ? ($product['pr_Reset_Stock']) : '' ?>"
                                                    name="reset_stock" id="resetStock" 
                                                    placeholder="Enter the Product Reset Stock"
                                                  >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Available Colors <span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" style="font-size:14px;"
                                                    value="<?= isset($product) ? ($product['pr_Aval_Colors']) : '' ?>"
                                                    name="aval_colors" id="avalColors"
                                                    placeholder="Enter avalilabe colors (Comma Seperated)" />
                                                <small>Colors seperated with comma. Hash code not required.</small>
                                            </div>
                                        </div>

                                        <?php
                                        $sizes = isset($product['pr_Size']) ? 
                                        array_map('trim', explode(',',$product['pr_Size'])) 
                                        : [];
                                        ?>


                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Available Size <span
                                                    style="color: red;">*</span></label>
                                            <div class="col-sm-7 prod-size" style="font-size:14px;">
                                                <input type="checkbox" name="size[]" value="S"
                                                    <?= in_array('S', $sizes) ? 'checked' : '' ?> />&nbsp;S
                                                <input type="checkbox" name="size[]" value="M"
                                                    <?= in_array('M', $sizes) ? 'checked' : '' ?> />&nbsp;M
                                                <input type="checkbox" name="size[]" value="L"
                                                    <?= in_array('L', $sizes) ? 'checked' : '' ?> />&nbsp;L
                                                <input type="checkbox" name="size[]" value="XL"
                                                    <?= in_array('XL', $sizes) ? 'checked' : '' ?> />&nbsp;XL
                                                <input type="checkbox" name="size[]" value="XXL"
                                                    <?= in_array('XXL', $sizes) ? 'checked' : '' ?> />&nbsp;XXL
                                                <input type="checkbox" name="size[]" value="Free Size"
                                                    <?= in_array('Free Size', $sizes) ? 'checked' : '' ?> />&nbsp;Free
                                                Size
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Sleeve Style</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="<?= isset($product) ? ($product['pr_Sleeve_Style']) : '' ?>"
                                                    name="sleeve_style" id="sleeveStyle"
                                                    placeholder="Enter the sleeve Style"style="font-size:14px;">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Fabric</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="<?= isset($product) ? ($product['pr_Fabric']) : '' ?>"
                                                    name="fabric" id="fabric" placeholder="Enter the Fabric" style="font-size:14px;">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Stitching Type</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="<?= isset($product) ? ($product['pr_Stitch_Type']) : '' ?>"
                                                    name="stitching" id="stichStyle"
                                                    placeholder="Enter the Stitching Style" style="font-size:14px;">
                                            </div>
                                        </div>
										
                                        <div class="row justify-content-center">
                                            <input type="hidden" name="pr_id" id="pr_id" value="<?= isset($product) ? $product['pr_Id'] : '' ?>">

                                            <div class="button-group">
                                                <button type="button" class="btn btn-secondary" style="font-size:14px;"
                                                    onclick="window.location.href='<?= base_url('admin/product'); ?>'">
                                                    <i class="bi bi-x-circle"></i> Discard
                                                </button>
                                                <button type="button" class="btn btn-primary" id="productSubmit"
                                                    name="productSubmit" style="font-size:14px;">
                                                    <i class="bi bi-check-circle"></i>
                                                    <?= isset($product) ? 'Update' : 'Save'; ?>
                                                </button>

                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>
</div>