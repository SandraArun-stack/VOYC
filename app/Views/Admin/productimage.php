<style>
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
        gap: 8px;
        margin-top: 15px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        white-space: nowrap !important;
    }
</style>
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
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Product Images</a></li>
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
                        <div class="col-sm-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="messageBox" class="alert" style="display:none;"></div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                                            <h5 id="productNameHeading" class="mb-3"></h5>

                                            <div>
                                                <a href="<?= base_url('admin/product'); ?>" class="btn btn-secondary">
                                                    Back to List
                                                </a>
                                                <a href="<?= base_url('admin/product/image/add/' . $pr_id); ?>"
                                                    class="btn btn-primary">
                                                    Add Product Image
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block table-border-style">
                                        <input type="hidden" id="pr_id" value="<?= isset($pr_id) ? $pr_id : '' ?>">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="productList">
                                                <thead>
                                                    <tr>
                                                        <th>Slno</th>
                                                        <!-- <th>Product Name</th> -->
                                                        <th>Size</th>
                                                        <th>Color</th>
                                                        <th>Stock</th>
                                                        <th>Reset Stock</th>
                                                        <th>Price</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody> <!-- AJAX will populate -->
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>