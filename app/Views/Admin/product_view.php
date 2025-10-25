<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">View Product</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">View Product</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-100  width: 102%; max-width: 111%;">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-white text-dark border-bottom">
                        <h4 class="mb-0"><i class="bi bi-box-seam"></i> <?= esc($product->pr_Name); ?></h4>
                    </div>
                    <div class="card-body p-3">
                        <div id="messageBox" class="alert alert-success d-none"></div>

                        <div class="row">
    <!-- LEFT COLUMN: Basic product details -->
    <div class="col-md-6 mb-4">
        <p style="font-size: 15px;"><strong>Code:</strong> <?= esc($product->pr_Code); ?></p>
        <p style="font-size: 14px;">
            <strong>Description:</strong>
            <?= ucwords(strtolower(esc($product->pr_Description))); ?>
        </p>
        <p style="font-size: 14px;"><strong>Category:</strong> <?= esc($product->cat_Name); ?></p>
        <p style="font-size: 14px;"><strong>Subcategory:</strong> <?= esc($product->sub_Category_Name); ?></p>
        <p style="font-size: 14px;">
            <strong>Sleeve Style:</strong>
            <?= !empty($product->pr_Sleeve_Style) ? esc($product->pr_Sleeve_Style) : 'N/A'; ?>
        </p>
        <p style="font-size: 14px;">
            <strong>Fabric:</strong>
            <?= !empty($product->pr_Fabric) ? esc($product->pr_Fabric) : 'N/A'; ?>
        </p>
        <p style="font-size: 14px;">
            <strong>Stitch Type:</strong>
            <?= !empty($product->pr_Stitch_Type) ? esc($product->pr_Stitch_Type) : 'N/A'; ?>
        </p>
    </div>

    <!-- RIGHT COLUMN: Sizes and Colors -->
    <div class="col-md-6 mb-4">
        
            <p style="font-size: 14px;"><strong>Sizes with Price:</strong></p>
            <?php if (!empty($product->sizes)): ?>
                <?php foreach ($product->sizes as $size => $price): ?>
                    <p><?= esc($size) ?> - ₹<?= number_format($price, 2) ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>N/A</p>
            <?php endif; ?>

            

            <p style="font-size: 14px;"><strong>Available Colors:</strong></p>
            <?php if (!empty($product->colors)): ?>
                <div class="d-flex flex-wrap align-items-center">
                    <?php foreach ($product->colors as $color): ?>
                        <?php 
                            $decodedColor = trim($color);
                            if (strpos($decodedColor, '#') === false) {
                                $decodedColor = '#' . $decodedColor;
                            }
                        ?>
                        <span title="<?= esc($decodedColor) ?>" 
                              style="display:inline-block; width:20px; height:20px; border-radius:50%; background-color:<?= esc($decodedColor) ?>; border:1px solid #ccc; margin:3px;">
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>N/A</p>
            <?php endif; ?>
      
    </div>
</div>

                    </div>
                    <div class="card-footer text-end bg-light">
                        <a href="<?= base_url('admin/product'); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>