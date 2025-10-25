<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Settings</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Settings</a>
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
                                        <div class="col-md-12">
                                        </div>


                                    </div>
                                </div>
                                <!-- <div class="card-block">
                                    <div class="card">
                                        
                                       
                                    </div>
                                </div> -->
                                <div class="card-block">
                                    <div class="card p-4 shadow-sm">
                                        <h5 class="mb-3">Customization Settings</h5>
                                        <div id="update_msg" class="alert alert-success mt-3 p-2 d-none"></div>

                                        <div class="form-group mb-3">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="customization_charge">Customization Price (₹)</label>

                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="customization_charge"
                                                        name="customization_charge"
                                                        value="<?= esc($customization_charge ?? '') ?>"
                                                        placeholder="Enter customization price">
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <button type="button" id="btnUpdateCharge"
                                                        class="btn btn-primary">Update</button>
                                                </div>
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