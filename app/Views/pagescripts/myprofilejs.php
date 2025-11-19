<script>
    $(document).ready(function () {

        //  Reusable show message function
        function showMessage(type, text) {
            const box = $('#messageBox');
            box.removeClass('alert-success alert-danger alert-warning alert-info')
                .addClass('alert-' + type)
                .text(text)
                .fadeIn();

            // Auto-hide after 3 seconds
            setTimeout(() => box.fadeOut(), 3000);
        }


        // Open Edit Profile Modal
        $('a.btn-dark').on('click', function (e) {
            e.preventDefault();
            $('#editProfileModal').modal('show');
        });

        // Open Change Password Modal
        $('a.btn-outline-secondary').on('click', function (e) {
            e.preventDefault();
            $('#changePasswordModal').modal('show');
        });


        $("#editProfileBtn").on("click", function () {
            $(".editable-field").prop("disabled", false);   // enable inputs
            $("#editProfileBtn").addClass("d-none");          // hide Edit
            $("#saveProfileBtn").removeClass("d-none").prop("disabled", true); // show Save
        });


        // Enable Save button only if anything changes
        // $(".editable-field").on("input", function () {
        //     $("#saveProfileBtn").prop("disabled", false);
        // });

        const originalData = {
            name: $("#input_name").val(),
            phone: $("#input_phone").val()
        };
        // Enable edit mode
        $("#editProfileBtn").on("click", function () {
            $(".editable-field").prop("disabled", false);  // Enable inputs

            $("#editProfileBtn").addClass("d-none");
            $("#saveProfileBtn").removeClass("d-none").prop("disabled", true);
            $("#discardProfileBtn").removeClass("d-none");
        });

        // Detect changes
        $(".editable-field").on("input", function () {
            let nameChanged = $("#input_name").val() !== originalData.name;
            let phoneChanged = $("#input_phone").val() !== originalData.phone;

            $("#saveProfileBtn").prop("disabled", !(nameChanged || phoneChanged));
        });

        $("#discardProfileBtn").on("click", function () {

            // Restore old values
            $("#input_name").val(originalData.name);
            $("#input_phone").val(originalData.phone);

            // Disable fields again
            $(".editable-field").prop("disabled", true);

            // Hide Save + Discard, show Edit
            $("#saveProfileBtn").addClass("d-none").prop("disabled", true);
            $("#discardProfileBtn").addClass("d-none");
            $("#editProfileBtn").removeClass("d-none");
        });

        $("#input_phone").on("input", function () {

            const phone = $(this).val().trim();

            // Accept: 9876543210, 09876543210, +91 9876543210, +919876543210
            const isValid = /^(?:0|\+91\s?)?[6-9]\d{9}$/.test(phone);

            if (!isValid) {
                $("#input_phone").addClass("is-invalid");
                $("#saveProfileBtn").prop("disabled", true);
            } else {
                $("#input_phone").removeClass("is-invalid");
            }

            // Detect input changes
            let nameChanged = $("#input_name").val() !== originalData.name;
            let phoneChanged = phone !== originalData.phone;

            // Enable Save only if valid + changed
            $("#saveProfileBtn").prop("disabled", !(isValid && (nameChanged || phoneChanged)));
        });


        $("#inlineProfileForm").on("submit", function (e) {

            e.preventDefault(); // Prevent page reload

            $.ajax({
                url: "<?= base_url('myprofile/updateProfile') ?>", // No data in URL
                type: "POST",
                data: $(this).serialize(), // Send POST body
                dataType: "json",
                success: function (response) {
                    if (response.success) {

                        showMessage("success", "Profile updated successfully!");

                        // Disable inputs again
                        $(".editable-field").prop("disabled", true);

                        // Update stored original values
                        originalData.name = $("#input_name").val();
                        originalData.phone = $("#input_phone").val();

                        // Reset UI buttons
                        $("#saveProfileBtn").addClass("d-none").prop("disabled", true);
                        $("#discardProfileBtn").addClass("d-none");
                        $("#editProfileBtn").removeClass("d-none");
                    } else {
                        showMessage("danger", "Failed to update profile.");
                    }
                }
            });
        });


        // Show password fields
        $("#showChangePassword").on("click", function (e) {
            e.preventDefault();
            $("#passwordFormContainer").removeClass("d-none");
            $(this).addClass("d-none"); // hide "Change Password" button
        });

        // Cancel button
        $("#cancelChangePassword").on("click", function () {
            $("#changePasswordForm")[0].reset();
            $("#passwordFormContainer").addClass("d-none");
            $("#showChangePassword").removeClass("d-none");
        });

        // AJAX Submit
        $("#changePasswordForm").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url('myprofile/changePassword') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        $("#passwordFormContainer").addClass("d-none");
                        $("#showChangePassword").removeClass("d-none");
                        $("#changePasswordForm")[0].reset();
                    } else {
                        showMessage('danger', response.message);
                    }
                },
                error: function () {
                    showMessage('danger', 'Something went wrong!');
                }
            });
        });

        //  Toggle password visibility
        document.querySelectorAll('.toggle-password-profile-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                if (input.type === "password") {
                    input.type = "text";
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    input.type = "password";
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });
    });
</script>