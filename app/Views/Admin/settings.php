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
                                        <form id="customisation_Price_form">
                                            <div class="form-group mb-3">
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="front_Customization_Price">Front
                                                            Customization (₹)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="front_Customization_Price"
                                                            name="front_Customization_Price"
                                                            value="<?= esc($front_Customization_Price ?? '') ?>""
                                                            placeholder=" Enter customization price">
                                                    </div>
                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="back_Customization_Price">Back
                                                            Customization (₹)</label>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="back_Customization_Price"
                                                            name="back_Customization_Price"
                                                            value="<?= esc($back_Customization_Price ?? '') ?>"
                                                            placeholder="Enter customization price">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="sleeve_Customization_Price">Sleeve Customization(₹)
                                                        </label>
                                                        <small class="settings_charge_text d-block">(Charge Per Sleeve Customization)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="sleeve_Customization_Price"
                                                            name="sleeve_Customization_Price"
                                                            value="<?= esc($sleeve_Customization_Price ?? '') ?>"
                                                            placeholder="Enter customization price">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="">Shipping Charge (₹)
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                           (Customer Shipping Fee)</small>
                                                       
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="shipping_charge"
                                                            name="shipping_charge"
                                                            value="<?= esc($shipping_charge ?? '') ?>"
                                                            placeholder="Enter Customer Shipping Fee">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="">Minimum Amount for Free Shipping (₹)
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                           (Please Enter the Amount up to Which Shipping Charge Applies)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="minimum_amount_for_shipping_charge"
                                                            name="minimum_amount_for_shipping_charge"
                                                            value="<?= esc($minimum_amount_for_shipping_charge ?? '') ?>"
                                                            placeholder="Enter the Percent of Winners Receiving Free Tee">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="">Token Price (₹)
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                          (Price per Token in)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="token_price_for_per_piece"
                                                            name="token_price_for_per_piece"
                                                            value="<?= esc($token_price_for_per_piece ?? '') ?>"
                                                            placeholder="Enter the Discount Percentage">
                                                    </div>

                                                </div>
                                                <!-- <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="">Token Price 
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                          (Price per Token in ₹)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="token_price"
                                                            name="token_price"
                                                            value="<?= esc($token_price ?? '') ?>"
                                                            placeholder="Enter the price per token">
                                                    </div>

                                                </div> -->
                                               
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <a href="<?= base_url('admin/dashboard') ?>"
                                                            class="btn btn-secondary">
                                                            <i class="bi bi-x-circle"></i>Discard</a>
                                                        <button type="button" id="btnUpdateCharge"
                                                            class="btn btn-primary">
                                                            <i class="bi bi-check-circle"></i>Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>



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