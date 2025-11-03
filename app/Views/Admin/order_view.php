<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Order Details</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url('admin/dashboard') ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Order Details</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col text-end">
                            <button class="btn btn-secondary" id="backToOrders">Back to list</button>
                        </div>
                    </div><br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Ordered By</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block" id="customer-details"></div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Delivery Address</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block" id="delivery-details"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Order Details</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block" id="order-details"></div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($Customisation_image)): ?>

                        <?php
                        $userUploads = $Customisation_image['User_Upload_Image'] ?? [];
                        unset($Customisation_image['User_Upload_Image']); // remove it for clean loop
                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5>Customized Design</h5>
                                    </div>
                                    <div class="card-block" id="customised-Details">
                                        <div class="d-flex flex-wrap gap-3 p-3">
                                            <?php foreach ($Customisation_image as $key => $img): ?>
                                                <?php if (!empty($img)): ?>
                                                    <div class="text-center">
                                                        <img src="<?= base_url('uploads/designs/' . esc($img)) ?>"
                                                            alt="<?= esc($key) ?>" class="img-fluid rounded shadow-sm"
                                                            style="max-width:150px;">
                                                        <p class="mt-2 mb-0 text-capitalize">
                                                            <?= str_replace('_', ' ', $key) ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (!empty($userUploads)): ?>
                                            <div class="d-flex flex-wrap gap-3 p-3">
                                                <?php foreach ($userUploads as $img): ?>
                                                    <?php if (!empty($img)): ?>
                                                        <?php if (!empty($img)): ?>
                                                            <div class="text-center">
                                                                <img src="<?= base_url('uploads/designs/' . esc($img)) ?>" alt="User Upload"
                                                                    class="img-fluid rounded shadow-sm" style="max-width:150px;">
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                         <div class="d-flex flex-wrap gap-3 p-3">
                                            <button class="btn btn-primary" id="DownloadCustomImages">Download</button>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>
</div>