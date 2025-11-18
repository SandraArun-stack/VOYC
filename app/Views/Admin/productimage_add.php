<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"><?= isset($pri_id) ? 'Edit Product Image' : 'Add Product Image' ?></h5>
                        <p class="m-b-0">Welcome to VOYC </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard') ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a
                                href="#!"><?= isset($pri_id) ? 'Edit Product Image' : 'Add Product Image' ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
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
                                    <form id="productImageForm" method="post"
                                        action="<?= base_url('admin/productimage/save') ?>"
                                        enctype="multipart/form-data">

                                        <!-- Container for dynamic color groups -->
                                        <div id="colorGroupsContainer">

                                            <?php
                                            $sizes = ["S", "M", "L", "XL", "XXL"];

                                            if (!empty($productimages)):
                                                foreach ($productimages as $index => $img):
                                                    $colorData = json_decode($img->color_details, true);
                                                    $color = $colorData['color'] ?? '#000000';
                                                    $prices = $img->prices ?? [];
                                                    $stocks = $img->stocks ?? [];
                                                    $reset_stocks = $img->reset_stocks ?? [];
                                                    $images = json_decode($img->pri_File_Name, true) ?? [];
                                                    $sizesExisting = !empty($img->sizes) ? (array) $img->sizes : [];

                                                    ?>
                                                    <div class="card mb-3 color-group" data-index="<?= $index ?>">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                <h5 class="mb-0">Color Group <?= $index + 1 ?></h5>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-color <?= $index == 0 ? 'd-none' : '' ?>">Remove
                                                                    Color</button>
                                                            </div>

                                                            <!-- Choose Color -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Choose Color:   
                                                                   
                                                                </label>
                                                                
                                                                <input type="color" class="form-control form-control-color"
                                                                    name="colors[<?= $index ?>][color]" value="<?= $color ?>">
                                                            </div>

                                                            <!-- Sizes -->
                                                            <div class="row g-3">
                                                                <?php
                                                                $variantMap = [];
                                                                if (!empty($variants)) {
                                                                    foreach ($variants as $v) {
                                                                        $variantMap[$v['prv_Size']] = [
                                                                            'prv_id'     => $v['prv_Id'] ?? '',
                                                                            'price'      => $v['prv_price'] ?? '',
                                                                            'stock'      => $v['stock'] ?? '',
                                                                            'reset_stock'=> $v['reset_stock'] ?? ''
                                                                        ];
                                                                    }
                                                                }

                                                                foreach ($sizes as $size):
                                                                    // ✅ Safely get variant data
                                                                    $variant = $variantMap[$size] ?? null;

                                                                    $checked       = $variant ? 'checked' : '';
                                                                    $priceVal      = $variant['price'] ?? '';
                                                                    $stockVal      = $variant['stock'] ?? '';
                                                                    $resetStockVal = $variant['reset_stock'] ?? '';
                                                                    $prvId         = $variant['prv_id'] ?? '';
                                                                ?>
                                                                    <div class="col-md-3">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input size-checkbox"
                                                                                type="checkbox"
                                                                                name="colors[<?= $index ?>][sizes][]"
                                                                                value="<?= $size ?>" <?= $checked ?>>
                                                                            <label class="form-check-label fw-bold"><?= $size ?></label>
                                                                        </div>

                                                                        <!-- Hidden prv_id (safe) -->
                                                                        <input type="hidden"
                                                                            name="colors[<?= $index ?>][prv_id][<?= $size ?>]"
                                                                            value="<?= $prvId ?>">

                                                                        <!-- Price -->
                                                                        <input type="number" class="form-control mb-2"
                                                                            placeholder="Price for <?= $size ?>"
                                                                            name="colors[<?= $index ?>][prices][<?= $size ?>]"
                                                                            value="<?= $priceVal ?>" <?= $checked ? '' : 'disabled' ?>>

                                                                        <!-- Stock -->
                                                                        <input type="number" class="form-control mb-2"
                                                                            placeholder="Stock for <?= $size ?>"
                                                                            name="colors[<?= $index ?>][stock][<?= $size ?>]"
                                                                            value="<?= $stockVal ?>" <?= $checked ? '' : 'disabled' ?>>

                                                                        <!-- Reset Stock -->
                                                                        <input type="number" class="form-control"
                                                                            placeholder="Reset Stock for <?= $size ?>"
                                                                            name="colors[<?= $index ?>][reset_stock][<?= $size ?>]"
                                                                            value="<?= $resetStockVal ?>" <?= $checked ? '' : 'disabled' ?>>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>


                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mt-3">
                                                                        <label class="form-label"><b>Uploaded Front Image:</b></label><small>(choose a single image)</small>
                                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                                            <?php if (!empty($productimages) && !empty($productimages[$index]->pri_Thumbnail)): ?>
                                                                                <img src="<?= base_url('uploads/productmedia/' . $productimages[$index]->pri_Thumbnail) ?>"
                                                                                    width="80" class="border p-1">
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <input type="file" class="form-control" name="colors[<?= $index ?>][images][]" multiple>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <div class="mt-3">
                                                                        <label class="form-label"><b>Uploaded Back Image:</b></label><small>(choose a single image)</small>
                                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                                            <?php 
                                                                            if (!empty($productimages) && !empty($productimages[$index]->pri_File_Name)) {
                                                                                $sideImages = json_decode($productimages[$index]->pri_File_Name, true);
                                                                                foreach ($sideImages as $img): ?>
                                                                                    <img src="<?= base_url('uploads/productmedia/' . $img) ?>" width="80" class="border p-1">
                                                                                <?php endforeach; 
                                                                            } ?>
                                                                        </div>
                                                                        <input type="file" class="form-control" name="colors[<?= $index ?>][side_image][]" multiple>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- File Upload -->
                                                           

            
                                                          
                                                           
                                                            <?php if (!empty($pr_custom['pr_custom']) && $pr_custom['pr_custom'] == 1): ?> 
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="mt-3">
                                                                            <label class="form-label"><b>Right Sleeve Image:</b></label><small>(Choose a Single Image)</small>
                                                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                                                <?php if (!empty($productimages) && !empty($productimages[$index]->RSleeve_Img)): ?>
                                                                                    <img src="<?= base_url('uploads/productmedia/' . $productimages[$index]->RSleeve_Img) ?>"
                                                                                        width="80" class="border p-1">
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <input type="file" class="form-control"  name="colors[<?= $index ?>][RSleeve_Img][]" multiple>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mt-3">
                                                                            <label class="form-label"><b>Left Sleeve Image:</b></label><small>(Choose a Single Image)</small>
                                                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                                                <?php if (!empty($productimages) && !empty($productimages[$index]->LSleeve_Img)): ?>
                                                                                    <img src="<?= base_url('uploads/productmedia/' . $productimages[$index]->LSleeve_Img) ?>"
                                                                                        width="80" class="border p-1">
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <input type="file" class="form-control" name="colors[<?= $index ?>][LSleeve_Img][]" multiple>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                

                                                                
                                                            
                                                            <?php else: ?>
                                                             <!-- <div class="mt-3">
                                                                <label class="form-label">Additional Images</label><small>(You may choose multiple images)</small><br>
                                                                 <small>You will loss the current images while choose a file to add</small>
                                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                                   
                                                                    <?php 
                                                                    if (!empty($productimages) && !empty($productimages[$index]->pri_Sleev_Name)) {
                                                                        $sideImages = json_decode($productimages[$index]->pri_Sleev_Name, true);
                                                                        foreach ($sideImages as $img): ?>
                                                                            <img src="<?= base_url('uploads/productmedia/' . $img) ?>" width="80" class="border p-1">
                                                                        <?php endforeach; 
                                                                    } ?>
                                                                    </div>
                                                                <input type="file" class="form-control" name="colors[<?= $index ?>][sleev_image][]" multiple>
                                                            </div> -->
                                                            <?php endif; ?>
                                                              <div class="mt-3">
                                                                <label class="form-label"><b>Additional Images:</b></label><small>(You may choose multiple images)</small><br>
                                                                 <small>You will loss the current images while choose a image to add</small>
                                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                                   
                                                                    <?php 
                                                                    if (!empty($productimages) && !empty($productimages[$index]->pri_Sleev_Name)) {
                                                                        $sideImages = json_decode($productimages[$index]->pri_Sleev_Name, true);
                                                                        foreach ($sideImages as $img): ?>
                                                                            <img src="<?= base_url('uploads/productmedia/' . $img) ?>" width="80" class="border p-1">
                                                                        <?php endforeach; 
                                                                    } ?>
                                                                    </div>
                                                                <input type="file" class="form-control" name="colors[<?= $index ?>][sleev_image][]" multiple>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="colors[<?= $index ?>][pri_id]" value="<?= $img->pri_id ?? '' ?>">
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <!-- If no existing data, show 1 empty color group -->
                                                <div class="card mb-3 color-group" data-index="0">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h5 class="mb-0">Color Group 1</h5>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-color d-none">Remove
                                                                Color</button>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Choose Color:</label>
                                                            <input type="color" class="form-control form-control-color"
                                                                name="colors[0][color]" value="#000000">
                                                        </div>

                                                        <div class="row g-3">
                                                            <?php foreach ($sizes as $size): ?>
                                                                <div class="col-md-3">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input size-checkbox"
                                                                            type="checkbox" name="colors[0][sizes][]"
                                                                            value="<?= $size ?>">
                                                                        <label
                                                                            class="form-check-label fw-bold"><?= $size ?></label>
                                                                    </div>
                                                                    <input type="number" class="form-control mb-2"
                                                                        placeholder="Price for <?= $size ?>"
                                                                        name="colors[0][prices][<?= $size ?>]" disabled>
                                                                    <input type="number" class="form-control mb-2"
                                                                        placeholder="Stock for <?= $size ?>"
                                                                        name="colors[0][stock][<?= $size ?>]" disabled>
                                                                    <input type="number" class="form-control"
                                                                        placeholder="Reset Stock for <?= $size ?>"
                                                                        name="colors[0][reset_stock][<?= $size ?>]" disabled>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>

                                                        <div class="col-6 ">
                                                             <div class="mt-3 d-flex align-items-center ">
                                                                <label class="form-label upload__Image">Product Video</label>
                                                                  <input 
                                                                        type="file" 
                                                                        class="form-control video-input" 
                                                                        name="colors[0][videos][]" 
                                                                        accept="video/*" 
                                                                        
                                                                    >
                                                            </div>
                                                            <div class="mt-3 d-flex align-items-center ">
                                                                <label class="form-label upload__Image">Upload Front Image</label>
                                                                <input type="file" class="form-control image-input" name="colors[0][images][]" multiple>
                                                            </div>
                                                            <div class="mt-3 d-flex align-items-center">
                                                                <label class="form-label upload__Image">Upload Back Image</label>
                                                                <input type="file" class="form-control image-input" name="colors[0][side_image][]" multiple>
                                                            </div>
                                                            
                                                            <?php if (!empty($pr_custom['pr_custom']) && $pr_custom['pr_custom'] == 1): ?>
                                                            <div class="mt-3 d-flex align-items-center">
                                                                <label class="form-label upload__Image">Right Sleeve Image</label>
                                                                <input type="file" class="form-control image-input" name="colors[0][RSleeve_Img][]" >
                                                            </div>
                                                            <div class="mt-3 d-flex align-items-center">
                                                                <label class="form-label upload__Image">Left Sleeve Image</label>
                                                                <input type="file" class="form-control image-input" name="colors[0][LSleeve_Img][]" >
                                                            </div>
                                                            <?php else: ?>
                                                                <div class="mt-3 d-flex align-items-center">
                                                                    <label class="form-label upload__Image">Upload Sleeve Image</label>
                                                                    <input type="file" class="form-control image-input" name="colors[0][sleev_image][]" multiple>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        </div>

                                        <!-- Hidden Product ID -->
                                        <input type="hidden" name="pr_id" value="<?= $pr_id ?? '' ?>">
                                        <input type="hidden" name="pri_id" value="<?= $pri_id ?? '' ?>">


                                        <!-- Buttons -->
                                        
                                        <?php if (!isset($pr_id) || empty($productimages)): ?>
                                            <!-- <button type="button" class="btn btn-success mb-3" id="addColorBtn">+ Add Color</button> -->
                                        <?php endif; ?>

                                        <div>
                                           <button type="button" class="btn btn-secondary" style="font-size:14px;"
                                                    onclick="window.location.href='<?= base_url('admin/product/image/' . $pr_id); ?>'">
                                                <i class="bi bi-x-circle"></i> Discard
                                            </button>

                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle"></i>
                                                <?= isset($pr_id) && !empty($productimages) ? 'Update' : 'Save' ?>
                                            </button>
                                        </div>
                                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="styleSelector"></div>
    </div>
</div>
<!-- Image Dimension Error Modal -->
<div class="modal fade" id="imageErrorModal" tabindex="-1" aria-labelledby="imageErrorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white p-2">
        <h5 class="modal-title" id="imageErrorModalLabel">Invalid Image</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-2" id="imageErrorMsg">
        <!-- Error message will appear here -->
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
