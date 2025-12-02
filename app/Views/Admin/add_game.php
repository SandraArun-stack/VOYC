<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">
                            <?= isset($game_map_Details) ? 'Update Leaderboard' : 'Add Leaderboard'; ?>
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
                                <?= isset($game_map_Details) ? 'Update Leaderboard' : 'Add Leaderboard'; ?>
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

                                    <form id="gamemapingform">
                                        <!-- Hidden ID -->
                                        <input type="hidden" name="gm_Id"
                                            value="<?= isset($game_map_Details['gm_Id']) ? $game_map_Details['gm_Id'] : '' ?>">
                                        <!-- Date -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Date <span
                                                    style="color:red">*</span></label>
                                            <div class="mt-2 col-sm-6">
                                                <input type="date" name="gm_date" id="gm_date" class="form-control"
                                                    value="<?= $game_map_Details['gm_date'] ?? '' ?>" required>
                                            </div>
                                        </div>

                                        <!-- Game Name Dropdown -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Game Name <span
                                                    style="color:red">*</span></label>
                                            <div class="mt-2 col-sm-6">
                                                <select class="form-control" name="game_Id" id="game_Id" required>
                                                    <option value="">-- Select Game --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- No. of Turns -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">No. of Tokens <span
                                                    style="color:red">*</span></label>
                                            <div class="mt-2 col-sm-6">
                                                <input type="number" class="form-control" name="tokens" value="<?= $game_map_Details['gm_tokens'] ?? '' ?>" >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Leaderboard Count <span
                                                    style="color:red">*</span>
                                                <small class="text-muted d-block">(Top Winners + Discound
                                                    Winners)</small>
                                            </label>
                                            <div class="mt-2 col-sm-6">
                                                <input type="number" class="form-control" name="leaderboard_count"  value="<?= $game_map_Details['gm_leaderboard_count'] ?? '' ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Winning Percentage
                                                <span style="color:red">*</span>
                                                <small class="text-muted d-block">(Specify What Percentage of Winners
                                                    Get a Free Customized Tee)</small>
                                            </label>
                                            <div class="mt-2 col-sm-6">
                                                <input type="number" class="form-control" name="winning_percentage" step="any" value="<?= $game_map_Details['gm_free_tee_percentage'] ?? '' ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Extra Discount Percentage
                                                <span style="color:red">*</span>
                                                <small class="text-muted d-block">(Discount Percentage for Players
                                                    Outside the Top Winners)</small>
                                            </label>
                                            <div class="mt-2 col-sm-6">
                                                <input type="number" class="form-control" step="any"
                                                    name="extra_discount_percentage" value="<?= $game_map_Details['gm_extra_discount'] ?? '' ?>">
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="row justify-content-center">
                                            <div class="button-group">

                                                <!-- Discard -->
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="window.location.href='<?= base_url('admin/games'); ?>'">
                                                    <i class="bi bi-x-circle"></i> Discard
                                                </button>

                                                <!-- Save / Update -->
                                                <button type="submit" class="btn btn-primary" id="lbSubmit">
                                                    <i class="bi bi-check-circle"></i>
                                                    <?=isset($game_map_Details['gm_Id'])  ? 'Update' : 'Save'; ?>
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