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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col d-flex justify-content-between align-items-center">

                                            <!-- Left Heading -->
                                            <h5>Order Details</h5>

                                            <!-- Right Side: Status + Dropdown -->
                                            <div class="d-flex align-items-center">
                                                <h5 class="mb-0 me-2">Status Update:</h5>

                                                <!-- Dropdown -->
                                                <select class="form-select orderStatusSelect"
                                                    data-id="<?= $od_number ?>" style="width:150px;">
                                                    <option value="1" <?= ($status == 1) ? 'selected' : '' ?>>New</option>
                                                    <option value="2" <?= ($status == 2) ? 'selected' : '' ?>>Confirmed
                                                    </option>
                                                    <option value="3" <?= ($status == 3) ? 'selected' : '' ?>>Packed
                                                    </option>
                                                    <option value="4" <?= ($status == 4) ? 'selected' : '' ?>>Dispatched
                                                    </option>
                                                    <option value="5" <?= ($status == 5) ? 'selected' : '' ?>>Delivered
                                                    </option>
                                                </select>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="card-block" id="order-details"></div>
                            </div>
                        </div>
                    </div>
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


                        </div>
                        <div class="col-md-6">

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
                    </div>




                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="designModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Design Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="designModalBody">
                <p class="text-center">Loading...</p>


            </div>
        </div>
    </div>
</div>