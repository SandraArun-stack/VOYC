<script>
    $(document).ready(function () {

        const phoneInput = document.querySelector("#phone");
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "in",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });

        // Before submitting form, attach full number
        $('#updateProfileForm').on('submit', function (e) {
            const fullNumber = iti.getNumber();
            $('#phone').val(fullNumber);
        });


        $('input[name="us_Email"]').on('input', function () {
            this.value = this.value.toLowerCase();
        });


        $('#phone').on('input', function () {
            let value = $(this).val();
            let filtered = value.replace(/[^0-9+\-\s]/g, '');
            if (filtered.length > 15) filtered = filtered.slice(0, 15);
            $(this).val(filtered);
        });

        $('#updateProfileForm').on('submit', function (e) {
            const phoneVal = $('#phone').val().replace(/\s/g, '');
            if (phoneVal) {
                if (phoneVal.length < 6) {
                    e.preventDefault();
                    alert('Phone number must be at least 6 characters long.');
                    return false;
                }
            }


            const fullNumber = iti.getNumber();
            $('#phone').val(fullNumber);
        });

        $('#updateProfileForm').on('submit', function (e) {
            e.preventDefault();

            const $form = $(this);
            const $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                success: function (response) {
                    let alertBox = $('#tog-alert');

                    if (!alertBox.length) {
                        $form.before('<div class="alert" id="tog-alert"></div>');
                        alertBox = $('#tog-alert');
                    }

                    if (response.status === 1) {
                        $('#headerName').text(response.ad_name);
                        alertBox
                            .removeClass('alert-danger')
                            .addClass('alert-success')
                            .text(response.msg)
                            .fadeIn();

                        setTimeout(function () {
                            alertBox.fadeOut();
                            window.location.href = "<?= base_url('admin/dashboard'); ?>";
                        }, 2000);
                    } else {
                        alertBox
                            .removeClass('alert-success')
                            .addClass('alert-danger')
                            .text(response.msg)
                            .fadeIn();
                    }
                },
                error: function () {
                    alert('An Error Occurred While Updating The Profile.');
                },
                complete: function () {
                    $btn.prop('disabled', false).html('Update Profile');
                }
            });
        });


        $('#passUpdate').on('click', function (e) {
            e.preventDefault();
            var formData = $('#changePasswordForm').serialize();

            $.ajax({
                url: "<?= base_url('admin/profile/change_password'); ?>",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    var messageBox = $('#messageBox');
                    messageBox.removeClass('alert-success alert-danger');

                    if (response.status == 1) {
                        messageBox.addClass('alert alert-success').text(response.msg).fadeIn();
                        $('#changePasswordForm')[0].reset();
                    } else {
                        messageBox.addClass('alert alert-danger').text(response.msg).fadeIn();
                    }

                    setTimeout(function () {
                        messageBox.fadeOut();
                    }, 3000);
                }
            });
        });

        document.querySelectorAll(".toggle-password").forEach(function (icon) {
            icon.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                const input = document.getElementById(targetId);
                const isPassword = input.getAttribute("type") === "password";
                input.setAttribute("type", isPassword ? "text" : "password");
                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        });


        setTimeout(function () {
            let alertEl = document.querySelector('#tog-alert');
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('fade');
                setTimeout(() => alertEl.remove(), 500);
            }
        }, 3000);
    });
</script>