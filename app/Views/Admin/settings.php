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
                                                            Customization(₹)</label>
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
                                                            Customization(₹)</label>

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
                                                        <label for="">Leaderboard Count
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                           (Number of Players With Premium Access)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="leaderboard_count"
                                                            name="leaderboard_count"
                                                            value="<?= esc($leaderboard_count ?? '') ?>"
                                                            placeholder="Enter the Leaderboard Winner Count">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="">Winning Percentage
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                           (Specify What Percentage of Winners Get a Free Customized Tee)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="winning_percentage"
                                                            name="winning_percentage"
                                                            value="<?= esc($winning_percentage ?? '') ?>"
                                                            placeholder="Enter the Percent of Winners Receiving Free Tee">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
                                                    <div class="col-md-3">
                                                        <label for="">Extra Discount Percentage
                                                        </label>
                                                        <small class="settings_charge_text d-block">
                                                          (Discount Percentage for Players Outside the Top Winners)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                            id="extra_discount_percentage"
                                                            name="extra_discount_percentage"
                                                            value="<?= esc($extra_discount_percentage ?? '') ?>"
                                                            placeholder="Enter the Discount Percentage">
                                                    </div>

                                                </div>
                                                <div class="row mb-3 setting_row">
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

                                                </div>
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