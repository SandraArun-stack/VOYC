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
                shipping_charge: $('#shipping_charge').val(),
                minimum_amount_for_shipping_charge: $('#minimum_amount_for_shipping_charge').val(),
                token_price_for_per_piece: $('#token_price_for_per_piece').val()
                // token_price: $('#token_price').val()
            };

            function checkForChanges() {
                const frontVal = $('#front_Customization_Price').val();
                const backVal = $('#back_Customization_Price').val();
                const sleeveVal = $('#sleeve_Customization_Price').val();
                const shipping_chargeVal = $('#shipping_charge').val();
                const minimum_amount_for_shipping_chargeVal = $('#minimum_amount_for_shipping_charge').val();
                const token_price_for_per_pieceVal = $('#token_price_for_per_piece').val();
                // const token_priceVal = $('#token_price').val();

                if (
                    frontVal !== initialValues.front ||
                    backVal !== initialValues.back ||
                    sleeveVal !== initialValues.sleeve ||
                    shipping_chargeVal !== initialValues.shipping_charge ||
                    minimum_amount_for_shipping_chargeVal !== initialValues.minimum_amount_for_shipping_charge ||
                    token_price_for_per_pieceVal !== initialValues.token_price_for_per_piece
                    // token_priceVal !== initialValues.token_price

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