<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">

                        <li class="breadcrumb-item"><a href="#">Dashboard</a>
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
                    <div class="row align-items-stretch">
                        <!-- task, page, download counter  start -->
                        <div class="col-xl-3 col-md-6">
                            <a href="<?= base_url('admin/orders') ?>" style="text-decoration: none; color: inherit;">
                                <div class="card  h-75">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h5 class="text-c-purple"><?= esc($last7days_orders); ?></h4>
                                                    <h6 class="text-muted m-b-0">Latest Orders (7 days)</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class="bi bi-bag-heart f-28"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <a href="<?= base_url('admin/orders') ?>" style="text-decoration: none; color: inherit;">
                                <div class="card  h-75">

                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h5 class="text-c-green"><?= esc($totalOrderCount); ?></h4>
                                                    <h6 class="text-muted m-b-0">Total Orders</h6>

                                            </div>
                                            <div class="col-4 text-right">
                                                <i class="bi bi-bag-heart f-28"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <a href="<?= base_url('admin/customer') ?>" style="text-decoration: none; color: inherit;">
                                <div class="card h-75" style="cursor: pointer;">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h5 class="text-c-red"><?= esc($totalCustomerCount); ?></h4>
                                                    <h6 class="text-muted m-b-0">Total Customers</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class="bi bi-eyeglasses f-28"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card  h-75">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h5 class="text-c-blue">
                                                <i class="bi bi-currency-rupee"></i>
                                                <?= number_format($annualRevenue, 2); ?>
                                            </h5>
                                            <h6 class="text-muted m-b-0">Annual Revenue
                                                (<?= date('Y', strtotime('-3 months')) ?>-<?= date('Y', strtotime('+9 months')) ?>)
                                            </h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="bi bi-wallet2 f-28"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="card table-card">
                                <div class="card-header">
                                    <h5>Today's Order</h5>
                                </div>

                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover w-100">
                                            <thead>
                                                <tr>
                                                    <th>Order Number</th>
                                                    <th>Customer Name</th>
                                                    <th>Total Quantity</th>
                                                    <th>Grand Total</th>
                                                    <th>Order Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php if (!empty($todaysOrders)): ?>
                                                    <?php foreach ($todaysOrders as $order): ?>
                                                        <tr>
                                                            <!-- Order Number -->
                                                            <td>#<?= esc($order->od_Number); ?></td>
                                                            <!-- Customer Name -->
                                                            <td><?= esc($order->customer_name); ?></td>

                                                            <!-- Total Quantity -->
                                                            <td><?= esc($order->total_quantity); ?></td>

                                                            <!-- Grand Total -->
                                                            <td>
                                                                <i class="bi bi-currency-rupee"></i>
                                                                <?= esc(number_format($order->total_grand, 2)); ?>
                                                            </td>

                                                            <!-- Order Status -->
                                                            <td>
                                                                <?php
                                                                $statusLabels = [
                                                                    '1' => 'New',
                                                                    '2' => 'Confirmed',
                                                                    '3' => 'Packed',
                                                                    '4' => 'Dispatched',
                                                                    '5' => 'Delivered'
                                                                ];
                                                                $statusText = $statusLabels[$order->od_Status] ?? 'New';
                                                                ?>
                                                                <a href="<?= base_url('admin/orders/view/' . $order->od_Number); ?>"
                                                                style="text-decoration: none;">
                                                                    <span class="badge badge-info">
                                                                        <?= esc($statusText); ?>
                                                                    </span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No orders today.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                        <div class="text-right m-r-20">
                                            <a href="<?= base_url('admin/orders'); ?>"
                                            class="b-b-primary text-primary">View all Orders</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!--  project and team member end -->

                        <div class="col-xl-12 col-md-12">
                        <div class="card table-card">
                            <div class="card-header">
                                <h5>Latest Products</h5>
                            </div>
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table table-hover w-100">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>Product Image</th>
                                                <th>MRP</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (!empty($latestProducts)): ?>
                                                <?php $i = 1; foreach ($latestProducts as $product): ?>

                                                    <?php
                                                        // Safe image handling
                                                        $imageSrc = !empty($product->main_image ?? $product->pri_Thumbnail ?? null)
                                                            ? base_url('uploads/productmedia/' . ($product->main_image ?? $product->pri_Thumbnail))
                                                            : base_url('public/Admin/assets/images/default.jpg');

                                                        //  Safe price handling (supports product-wise & variant-wise query)
                                                        $price = $product->min_price 
                                                                ?? $product->prv_price 
                                                                ?? 0;
                                                    ?>

                                                    <tr>
                                                        <td><?= $i++; ?></td>

                                                        <td><?= esc($product->pr_Code ?? '-'); ?></td>

                                                        <td>
                                                            <?= esc($product->pr_Name ?? '-'); ?>

                                                            <?php if (!empty($product->prv_Color)): ?>
                                                                <br>
                                                                <small class="text-muted">
                                                                    Color: <?= esc($product->prv_Color); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <img src="<?= $imageSrc; ?>"
                                                                class="img-thumbnail view-image"
                                                                data-img="<?= $imageSrc; ?>"
                                                                style="height: 80px; cursor:pointer;">
                                                        </td>

                                                        <td>
                                                            <i class="bi bi-currency-rupee"></i>
                                                            <?= esc(number_format($price, 2)); ?>
                                                        </td>

                                                        <td>
                                                            <a href="<?= base_url('admin/product/view/' . ($product->pr_Id ?? 0).'/'.($product->pri_Id ?? 0)); ?>">
                                                                View Details 
                                                            </a>
                                                        </td>
                                                    </tr>

                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No products found.</td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>

                                    <div class="text-right m-r-20">
                                        <a href="<?= base_url('admin/product') ?>" class="b-b-primary text-primary">
                                            View all Products
                                        </a>
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
<!-- Image Modal -->
<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Product Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).on('click', '.view-image', function () {
            let imgSrc = $(this).data('img');
            $('#modalImage').attr('src', imgSrc);
            let imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        });
    });
</script>