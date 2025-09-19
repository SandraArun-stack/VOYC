<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Product Images</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="index.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Product Images</a>
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
                        <div class="col-sm-12">

                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-2">

                                        </div>
                                        <div class="col-md-7">
                                            <div id="message" style="display:none;"></div>
                                            <div id="messageBox" class="alert" style="display: none;"></div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex justify-content-end p-2">
                                                    <a href="<?= base_url('admin/productimage/add/' . $pr_id); ?>"
                                                        class="btn btn-primary">
                                                        Add Product Image
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="card">
                                        <div class="card-block table-border-style">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="productList">
                                                    <thead>
                                                        <tr>
                                                            <th>Slno</th>
                                                            <th>Product Name</th>
                                                            <th>Size</th>
                                                            <th>Color</th>
                                                            <th>Stock</th>
                                                            <th>Reset Stock</th>
                                                            <th>Price</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                        <?php foreach ($productimages as $index => $prodimg): ?>
                                                            <tr>
                                                                <td><?= $index + 1; ?></td>
                                                                <td><?= ucwords($prodimg->pr_Name); ?></td>
                                                                <td>
                                                                    <?php
                                                                    $sizes = !empty($prodimg->sizes) ? explode(',', $prodimg->sizes) : [];
                                                                    $prices = !empty($prodimg->prices) ? explode(',', $prodimg->prices) : [];
                                                                    if (!empty($sizes)) {
                                                                        foreach ($sizes as $i => $size) {
                                                                            $price = $prices[$i] ?? '-';
                                                                            echo "<div>{$size}</div>";
                                                                        }
                                                                    } else {
                                                                        echo '-N/A-';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $colors = json_decode($prodimg->color_details, true);
                                                                    if (!empty($colors)) {
                                                                        // Handle single color object or array of colors
                                                                        if (isset($colors['color'])) {
                                                                            // Single color
                                                                            $colorCode = $colors['color'];
                                                                            echo '<span title="' . htmlspecialchars($colorCode) . '" style="
                                                                        display:inline-block;
                                                                        width:25px;
                                                                        height:25px;
                                                                        background:' . $colorCode . ';
                                                                        border:1px solid #ccc;
                                                                        margin-right:5px;
                                                                        vertical-align:middle;
                                                                    "></span>';
                                                                        } elseif (is_array($colors)) {
                                                                            // Multiple colors stored as array of objects
                                                                            foreach ($colors as $color) {
                                                                                if (isset($color['color'])) {
                                                                                    $colorCode = $color['color'];
                                                                                    echo '<span title="' . htmlspecialchars($colorCode) . '" style="
                                                                                display:inline-block;
                                                                                width:25px;
                                                                                height:25px;
                                                                                background:' . $colorCode . ';
                                                                                border:1px solid #ccc;
                                                                                margin-right:5px;
                                                                                vertical-align:middle;
                                                                            "></span>';
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo '-N/A-';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $stocks = !empty($prodimg->stock) ? explode(',', $prodimg->stock) : [];
                                                                    if (!empty($sizes)) {
                                                                        foreach ($sizes as $i => $size) {
                                                                            $stock = $stocks[$i] ?? 0;
                                                                            echo "<div>{$stock}</div>";
                                                                        }
                                                                    } else {
                                                                        echo '-N/A-';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $reset_stocks = !empty($prodimg->reset_stock) ? explode(',', $prodimg->reset_stock) : [];
                                                                    if (!empty($sizes)) {
                                                                        foreach ($sizes as $i => $size) {
                                                                            $reset = $reset_stocks[$i] ?? 0;
                                                                            echo "<div>{$reset}</div>";
                                                                        }
                                                                    } else {
                                                                        echo '-N/A-';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <!-- Price -->
                                                                <td>
                                                                    <?php
                                                                    if (!empty($prodimg->prices)) {
                                                                        $prices = explode(',', $prodimg->prices);
                                                                        foreach ($prices as $price) {
                                                                            echo "<div>₹{$price}</div>";
                                                                        }
                                                                    } else {
                                                                        echo '-N/A-';
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <!-- Status -->
                                                                <td><?= $prodimg->pri_Status ?? '-N/A-'; ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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