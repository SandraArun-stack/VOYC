<div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="messageBox" class="alert alert-success" style="display: none;"></div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="heading-content-my-order">
                    <h4>My Profile</h4>
                    <small class="text-muted">Manage your account details</small>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="card mb-4 shadow-sm" data-aos="fade-up" data-aos-duration="600">
                <div class="card-header bg-dark text-white">
                    <strong>Profile Information</strong>
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Profile Picture -->
                        <!-- <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img src="<?= base_url('uploads/profile/default.png'); ?>" 
                                 alt="Profile Picture" 
                                 class="img-fluid rounded-circle mb-3 border border-2 border-secondary" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            <p><small class="text-muted">Profile Picture</small></p>
                        </div> -->

                        <!-- Profile Info -->
                        <div class="col-md-9">
                            <div class="mb-2">
                                <strong>Name:</strong> <?= esc($user['cust_Name']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> <?= esc($user['cust_Email']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Phone:</strong> <?= esc($user['cust_Phone']) ?>
                            </div>
                            <!-- <div class="mb-2">
                                <strong>Account Status:</strong>
                                <?php if ($user['cust_Status'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </div> -->
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="#" class="btn btn-dark btn-sm">Edit Profile</a>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="card mb-4 shadow-sm" data-aos="fade-up" data-aos-duration="600">
                <div class="card-header bg-dark text-white">
                    <strong>Security</strong>
                </div>
                <div class="card-body">
                    <p><strong>Password:</strong> ********</p>
                    <a href="#" class="btn btn-outline-secondary btn-sm">Change Password</a>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>
</section>
<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <input type="hidden" name="cust_Id" value="<?= esc($user['cust_Id']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="cust_Name" class="form-control" value="<?= esc($user['cust_Name']) ?>"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="cust_Email" class="form-control"
                            value="<?= esc($user['cust_Email']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="cust_Phone" class="form-control"
                            value="<?= esc($user['cust_Phone']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">

                    <!-- Current Password -->
                    <div class="form-group mb-3 position-relative">
                        <label>Current Password <span style="color: red;">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-control pe-5"
                            maxlength="15" minlength="6" placeholder="Current Password" style="font-size:14px;"
                            required>
                        <i class="fa fa-eye-slash toggle-password" data-target="current_password"
                            style="position: absolute; top: 38px; right: 15px; cursor: pointer; z-index: 10; color: #666;"></i>
                    </div>

                    <!-- New Password -->
                    <div class="form-group mb-3 position-relative">
                        <label>New Password <span style="color: red;">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control pe-5"
                            maxlength="15" minlength="6" placeholder="New Password" style="font-size:14px;" required>
                        <i class="fa fa-eye-slash toggle-password" data-target="new_password"
                            style="position: absolute; top: 38px; right: 15px; cursor: pointer; z-index: 10; color: #666;"></i>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-3 position-relative">
                        <label>Confirm Password <span style="color: red;">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control pe-5"
                            maxlength="15" minlength="6" placeholder="Confirm Password" style="font-size:14px;"
                            required>
                        <i class="fa fa-eye-slash toggle-password" data-target="confirm_password"
                            style="position: absolute; top: 38px; right: 15px; cursor: pointer; z-index: 10; color: #666;"></i>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>