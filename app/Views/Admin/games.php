<div class="pcoded-content">

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Game Details </h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Game Details</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-12 d-flex justify-content-end p-2">
                                            <a href="<?= base_url('admin/add_game/add'); ?>" class="btn btn-primary">
                                                Add Game
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-block">

                                    <div class="table-responsive">
                                        <table class="table table-hover w-100" id="gameMappings">
                                            <thead>
                                                <tr>
                                                    <th>Sl.No.</th>
                                                    <th>Date</th>
                                                    <th>Game Name</th>
                                                    <th>Tokens</th>
                                                    <th>Leaderboard Count</th>
                                                    <th>Win Tee Percentage</th>
                                                    <th>Win Coupen Percentage</th>
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