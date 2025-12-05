<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Manage Daily Game Counter</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Daily Game Counter</a>
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
                                <div class="card-block">
                                    <div class="card">
                                        <div class="card-block table-border-style">
                                            <div id="message" class="alert" style="display:none;"></div>
                                            <div id="messageBox" class="alert" style="display: none;"></div>
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="counterList">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.No.</th>
                                                            <th>Date</th>
                                                            <th>Game Name</th>
                                                            <th>Total Players</th>
                                                            <th>Total Winners</th>
                                                            <th>Winning Percentage</th>
                                                        </tr>
                                                    </thead>
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
            <div id="styleSelector"> </div>
        </div>
    </div>
</div>
