<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Detail Page</h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Detail Page</a></li>
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
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-9"></div>
                                        <!-- <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex justify-content-end p-2">
                                                    <a href="<?= base_url('admin/leaderboard/add'); ?>" class="btn btn-primary">Add Game</a>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="card">
                                        <div class="card-block table-border-style">
                                            <div id="message" class="alert" style="display:none;"></div>
                                            <div id="messageBox" class="alert" style="display: none;"></div>
                                            <div class="table-responsive">
                                                <table class="table table-hove w-100r" id="leaderboardList">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.No.</th>
                                                            <th>User Id</th>
                                                            <th>Score</th>
                                                            <!-- <th>No of Winners</th> -->
                                                            <th>No of Turns</th>
                                                            <th>Action</th>
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
        </div>
    </div>
</div>

