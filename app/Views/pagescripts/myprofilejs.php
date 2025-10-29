<script>
$(document).ready(function() {

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
    $('a.btn-dark').on('click', function(e) {
        e.preventDefault();
        $('#editProfileModal').modal('show');
    });

    // Open Change Password Modal
    $('a.btn-outline-secondary').on('click', function(e) {
        e.preventDefault();
        $('#changePasswordModal').modal('show');
    });

    //  Handle Edit Profile form submission
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('myprofile/updateProfile') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editProfileModal').modal('hide');
                    showMessage('success', 'Profile updated successfully!');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showMessage('danger', 'Failed to update profile.');
                }
            },
            error: function() {
                showMessage('danger', 'Something went wrong!');
            }
        });
    });

    //  Handle Change Password form submission
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('myprofile/changePassword') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    $('#changePasswordModal').modal('hide');
                    $('#changePasswordForm')[0].reset();
                } else {
                    showMessage('danger', response.message);
                }
            },
            error: function() {
                showMessage('danger', 'Something went wrong!');
            }
        });
    });

    //  Toggle password visibility
    $(document).on('click', '.toggle-password', function () {
        const $icon = $(this);
        const $input = $('#' + $icon.data('target'));
        const isPassword = $input.attr('type') === 'password';
        $input.attr('type', isPassword ? 'text' : 'password');
        $icon.toggleClass('fa-eye fa-eye-slash');
    });

});
</script>
