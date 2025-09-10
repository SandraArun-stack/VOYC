
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
                                                <h4 class="text-c-purple"><?= esc($latestOrderCount); ?></h4>
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
                                                <h4 class="text-c-green"><?= esc($totalOrderCount); ?></h4>
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
                                                <h4 class="text-c-red"><?= esc($totalCustomerCount); ?></h4>
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
                                            <h4 class="text-c-blue">
                                                <i class="bi bi-currency-rupee"></i>
                                                <?= number_format($annualRevenue, 2); ?>
                                            </h4>
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
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>

                                                    <th>Order Id</th>
                                                    <th>Customer Name</th>
                                                    <th>Product Name</th>
                                                    <th>Total Price</th>
                                                    <th>Selling Price</th>
                                                    <th>Discount</th>
                                                    <th>Grand Total</th>
                                                    <th>Order Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($todaysOrders)): ?>
                                                <?php foreach ($todaysOrders as $order): ?>
                                                <tr>
                                                    <td>#<?= esc($order->od_Id); ?></td>
                                                   


                                                    <td>
  
        <?= esc($order->customer_name); ?>

</td>

                                                    <td><?= esc($order->product_name); ?></td>
                                                    <td><i
                                                            class="bi bi-currency-rupee"></i><?= esc(number_format($order->od_Grand_Total, 2)); ?>
                                                    </td>
                                                    <td><i
                                                            class="bi bi-currency-rupee"></i><?= esc(number_format($order->od_Selling_Price, 2)); ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($order->od_DiscountValue); ?>
                                                        <?= esc($order->od_DiscountType); ?>
                                                    </td>
                                                    <td><i
                                                            class="bi bi-currency-rupee"></i><?= esc(number_format($order->od_Grand_Total, 2)); ?>
                                                    </td>
                                                  <td>
    <?php
        $statusLabels = [
            1 => 'New',
            2 => 'Confirmed',
            3 => 'Packed',
            4 => 'Dispatched'
        ];
        $statusText = $statusLabels[$order->od_Status] ?? 'Unknown';
    ?>
    <a href="<?= base_url('admin/orders/view/' . $order->od_Id); ?>" style="text-decoration: none;">
        <span class="badge badge-info"><?= esc($statusText); ?></span>
    </a>
</td>

                                                </tr>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No orders today.</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>

                                        </table>
                                        <div class="text-right m-r-20">
                                            <a href="<?php echo base_url('admin/orders') ?>"
                                                class=" b-b-primary text-primary">View all Orders</a>
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
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Product Image</th>
                                                    <th>MRP</th>
                                                    <th>Selling Price</th>
                                                    <th>Product Stock</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; foreach ($latestProducts as $product): ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= esc($product->pr_Code); ?></td>
                                                    <td><?= esc($product->pr_Name); ?></td>

                                                    <td>
                                                        <?php
                                                            $imageSrc = !empty($product->main_image)
                                                                ? base_url('uploads/productmedia/' . esc($product->main_image))
                                                                : base_url('public/Admin/assets/images/default.jpg');
                                                            ?>

                                                        <img src="<?= $imageSrc ?>" alt="<?= esc($product->pr_Name); ?>"
                                                            class="img-thumbnail view-image" data-img="<?= $imageSrc ?>"
                                                            style="height: 80px; cursor: pointer;">
                                                    </td>


                                                    <td><i class="bi bi-currency-rupee"></i><?= esc($product->mrp); ?>
                                                    </td>
                                                    <td><i
                                                            class="bi bi-currency-rupee"></i><?= esc($product->pr_Selling_Price); ?>
                                                    </td>
                                                    <td><?= esc($product->pr_Stock); ?></td>
                                                    <td><a
                                                            href="<?= base_url('admin/product/view/' . $product->pr_Id); ?>">View
                                                            Details</a>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                        <div class="text-right m-r-20">
                                            <a href="<?= base_url('admin/product') ?>"
                                                class="b-b-primary text-primary">View all Products</a>
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
