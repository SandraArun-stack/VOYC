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
                        <div class="col-md-12">
                            <form id="inlineProfileForm">
                                <input type="hidden" name="cust_Id" value="<?= esc($user['cust_Id']) ?>">

                                <!-- Name -->
                                <div class="mb-2">
                                    <strong>Name:</strong>
                                    <input type="text" name="cust_Name" id="input_name"
                                        value="<?= esc($user['cust_Name']) ?>" class="form-control editable-field"
                                        disabled>
                                </div>

                                <!-- Email (NEVER editable) -->
                                <div class="mb-2">
                                    <strong>Email:</strong>
                                    <input type="email" name="cust_Email" id="input_email"
                                        value="<?= esc($user['cust_Email']) ?>" class="form-control" readonly>
                                </div>

                                <!-- Phone -->
                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    <input type="text" name="cust_Phone" id="input_phone"
                                        value="<?= esc($user['cust_Phone']) ?>" class="form-control editable-field"
                                        disabled>
                                    <div class="invalid-feedback">Enter a valid 10-digit Indian phone number</div>
                                </div>

                                <button type="button" id="editProfileBtn" class="btn  mt-3">Edit Profile
                                </button>
                                <button type="button" id="discardProfileBtn" class="btn btn-danger mt-3 d-none">
                                    <i class="bi bi-x-circle"></i>
                                    Cancel
                                </button>
                                <button type="submit" id="saveProfileBtn" class="btn mt-3 d-none" disabled>
                                    <i class="bi bi-check-circle"></i>
                                    Save Changes
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mb-4 shadow-sm" data-aos="fade-up" data-aos-duration="600">
                <div class="card-header bg-dark text-white">
                    <strong>Security</strong>
                </div>

                <div class="card-body">
                    <p><strong>Password:</strong> ********</p>

                    <!-- Change Password Button -->
                    <button id="showChangePassword" class="btn btn-outline-secondary btn-sm">Change Password</button>

                    <!-- Inline Password Form (Hidden by Default) -->
                    <div id="passwordFormContainer" class="mt-3 d-none">

                        <form id="changePasswordForm">
                            <div class="mb-2 position-relative">
                                <strong>Current Password <span style="color:red">*</span></strong>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control small-input" required>
                                <i class="fa fa-eye-slash toggle-password-profile-password" data-target="current_password"></i>
                            </div>

                            <div class="mb-2 position-relative">
                                <strong>New Password <span style="color:red">*</span></strong>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control small-input" minlength="6" maxlength="15" required>
                                <i class="fa fa-eye-slash toggle-password-profile-password" data-target="new_password"></i>
                            </div>

                            <div class="mb-2 position-relative">
                                <strong>Confirm Password <span style="color:red">*</span></strong>
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="form-control small-input" minlength="6" maxlength="15" required>
                                <i class="fa fa-eye-slash toggle-password-profile-password" data-target="confirm_password"></i>
                            </div>

                            <!-- Current Password
                            <div class=" position-relative">
                                <label>Current Password <span style="color:red">*</span></label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control pe-5" minlength="6" maxlength="15" required>
                            </div>

                            <div class=" position-relative">
                                <label>New Password <span style="color:red">*</span></label>
                                <input type="password" name="new_password" id="new_password" class="form-control pe-5"
                                    minlength="6" maxlength="15" required>
                            </div>

                            <div class=" position-relative">
                                <label>Confirm Password <span style="color:red">*</span></label>
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="form-control pe-5" minlength="6" maxlength="15" required>
                            </div> -->

                            <button type="button" id="cancelChangePassword" class="btn btn-danger mt-2"> <i
                                    class="bi bi-x-circle"></i> Cancel</button>
                            <button type="submit" class="btn btn-dark mt-2"> <i class="bi bi-check-circle"></i> Update
                                Password</button>

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>
</section>

<!-- Change Password Modal -->