<script>
 $(document).ready(function () {
    // Store the initial value of the customization charge field
    const initialValue = $('#customization_charge').val();
    const $btnUpdateCharge = $('#btnUpdateCharge');
    const $msg = $('#update_msg');
    
    // Disable the Update button if there are no changes
    $btnUpdateCharge.prop('disabled', true);

    // Listen for changes in the input field
    $('#customization_charge').on('input', function () {
        const currentValue = $(this).val();

        // Enable the button if the value has changed and is not the same as initial value
        if (currentValue !== initialValue) {
            $btnUpdateCharge.prop('disabled', false);
        } else {
            $btnUpdateCharge.prop('disabled', true);
        }
    });

    // Handle button click to send the update
    $(document).on('click', '#btnUpdateCharge', function () {
        const chargeValue = $('#customization_charge').val();

        $msg.removeClass('alert-danger alert-success').text('');  // Reset the message, remove alert classes

        if (!chargeValue) {
            $msg.addClass('alert-danger').text('Please enter a customization price.');
            $msg.removeClass('d-none').fadeIn();  // Show the message box
            setTimeout(function () {
                $msg.fadeOut();  // Fade out the message after 3 seconds
            }, 3000);
            return;
        }

        // Disable the button and change text while sending the request
        $btnUpdateCharge.prop('disabled', true).text('Updating...');

        $.ajax({
            url: "<?= base_url('admin/settings/updateCustomizationCharge') ?>",
            type: "POST",
            data: { customization_charge: chargeValue },
            dataType: "json",
            success: function (response) {
                // Re-enable the button and reset the text after success
                $btnUpdateCharge.prop('disabled', true).text('Updated');

                // Show success or error message based on the response
                if (response.status === 'success') {
                    $msg.addClass('alert-success').text(response.message);
                } else {
                    $msg.addClass('alert-danger').text(response.message);
                }

                // Show the message box and fade it out after 3 seconds
                $msg.removeClass('d-none').fadeIn();
                setTimeout(function () {
                    $msg.fadeOut();
                }, 3000);
            },
            error: function () {
                // Re-enable the button if there's an error
                $btnUpdateCharge.prop('disabled', false).text('Update');
                $msg.addClass('alert-danger').text('Something went wrong. Please try again.');
                
                // Show the message box and fade it out after 3 seconds
                $msg.removeClass('d-none').fadeIn();
                setTimeout(function () {
                    $msg.fadeOut(); 
                }, 3000);
            }
        });
    });
});





</script>