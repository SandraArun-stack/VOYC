<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manage Orders</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!"> Orders</a>
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
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex justify-content-end p-2">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="card">
                                        <div class="card-block table-border-style">
                                            <div id="message" style="display:none;"></div>
                                            <div id="messageBox" class="alert" style="display: none;"></div>
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-end align-items-center mb-3">
                                                    <label>Search by Status: &nbsp;</label>
                                                    <select id="statusFilter" class="form-select" style="width:200px;">
                                                        <option value="">All Status</option>
                                                        <option value="1">New</option>
                                                        <option value="2">Confirmed</option>
                                                        <option value="3">Packed</option>
                                                        <option value="4">Dispatched</option>
                                                        <option value="5">Delivered</option>
                                                    </select>
                                                </div>
                                                <table class="table table-hover" id="orderList">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.No.</th>
                                                            <th>Order Number</th>
                                                            <th>Customer Name</th>
                                                            <th>Email</th>
                                                            <th>Contact Number</th>
                                                            <th>Order Date</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: 14px;"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>