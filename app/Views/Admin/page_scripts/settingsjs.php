<script>
    $(document).ready(function () {
        const initialValue = $('#customization_charge').val();
        const $btnUpdateCharge = $('#btnUpdateCharge');
        const $msg = $('#update_msg');

        $(document).on('click', '#btnUpdateCharge', function () {
            const form = $('#customisation_Price_form');
            const formData = form.serialize();
            const initialValues = {
                front: $('#front_Customization_Price').val(),
                back: $('#back_Customization_Price').val(),
                sleeve: $('#sleeve_Customization_Price').val(),
                leaderboard_count: $('#leaderboard_count').val(),
                winning_percentage: $('#winning_percentage').val(),
                extra_discount_percentage: $('#extra_discount_percentage').val()
            };

            function checkForChanges() {
                const frontVal = $('#front_Customization_Price').val();
                const backVal = $('#back_Customization_Price').val();
                const sleeveVal = $('#sleeve_Customization_Price').val();
                const leaderboard_countVal = $('#leaderboard_count').val();
                const winning_percentageVal = $('#winning_percentage').val();
                const extra_discount_percentageVal = $('#extra_discount_percentage').val();

                if (
                    frontVal !== initialValues.front ||
                    backVal !== initialValues.back ||
                    sleeveVal !== initialValues.sleeve ||
                    leaderboard_countVal !== initialValues.leaderboard_count ||
                    winning_percentageVal !== initialValues.winning_percentage ||
                    extra_discount_percentageVal !== initialValues.extra_discount_percentage

                ) {
                    $btnUpdateCharge.prop('disabled', false);
                } else {
                    $btnUpdateCharge.prop('disabled', true);
                }
            }

            $msg.removeClass('alert-danger alert-success').text('');

            $('#btnUpdateCharge').prop('disabled', true).text('Updating...');

            $.ajax({
                url: "<?= base_url('admin/settings/updateCustomizationCharge') ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    $('#btnUpdateCharge').prop('disabled', false).text('Update');

                    if (response.status === 'success') {
                        $msg.addClass('alert-success').text(response.message);
                    } else {
                        $msg.addClass('alert-danger').text(response.message);
                    }

                    $msg.removeClass('d-none').fadeIn();
                    setTimeout(function () {
                        $msg.fadeOut();
                    }, 3000);
                },
                error: function () {
                    $('#btnUpdateCharge').prop('disabled', false).text('Update');
                    $msg.addClass('alert-danger').text('Something went wrong. Please try again.');
                    $msg.removeClass('d-none').fadeIn();
                    setTimeout(function () {
                        $msg.fadeOut();
                    }, 3000);
                }
            });
        });

    });





</script>