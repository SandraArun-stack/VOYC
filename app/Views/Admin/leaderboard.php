<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">
                            <?= isset($leaderboard) ? 'Update Leaderboard' : 'Add Leaderboard'; ?>
                        </h5>
                        <p class="m-b-0">Welcome to VOYC</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/dashboard'); ?>"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">
                                <?= isset($leaderboard) ? 'Update Leaderboard' : 'Add Leaderboard'; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-block">

                                    <div id="messageBox" class="alert alert-success" style="display:none;"></div>

                                    <form id="leaderboardForm" method="post" action="<?= base_url('admin/leaderboard/save'); ?>">


                                        <!-- Hidden ID -->
                                        <input type="hidden" name="leaderboard_id"
                                               value="<?= isset($leaderboard['leaderboard_id']) ? $leaderboard['leaderboard_id'] : '' ?>">
                                        <!-- Date -->
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Date <span style="color:red">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="date" name="date" id="date" class="form-control"
                                                        value="<?= isset($leaderboard) ? $leaderboard['date'] : '' ?>"
                                                        required>
                                                </div>
                                            </div>

                                            <!-- Game Name Dropdown -->
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Game Name <span style="color:red">*</span></label>
                                                <div class="col-sm-6">
                                                    <select class="form-control" name="game_id" id="game_id" required>
                                                        <option value="">-- Select Game --</option>
                                                        <?php foreach ($games as $g): ?>
                                                            <option value="<?= $g['game_id']; ?>"
                                                                <?= isset($leaderboard) && $leaderboard['game_id'] == $g['game_id'] ? 'selected' : '' ?>>
                                                                <?= $g['game_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- No. of Turns -->
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">No. of Turns <span style="color:red">*</span></label>
                                                <div class="col-sm-6">
                                                    <input type="number" class="form-control" name="turns"
                                                        value="<?= isset($leaderboard) ? $leaderboard['turns'] : '' ?>"
                                                        required min="1" placeholder="Enter number of turns">
                                                </div>
                                            </div>


                                        <!-- Buttons -->
                                        <div class="row justify-content-center">
                                            <div class="button-group">

                                                <!-- Discard -->
                                                <button type="button" class="btn btn-secondary"
                                                        onclick="window.location.href='<?= base_url('admin/leaderboard'); ?>'">
                                                    <i class="bi bi-x-circle"></i> Discard
                                                </button>

                                                <!-- Save / Update -->
                                                <button type="submit" class="btn btn-primary" id="lbSubmit">
                                                    <i class="bi bi-check-circle"></i>
                                                    <?= isset($leaderboard['leaderboard_id']) ? 'Update' : 'Save'; ?>
                                                </button>

                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>
</div>
