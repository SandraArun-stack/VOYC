<script>

    function updateGrandTotal() {
        //     var grandTotal = 0;
        //     $('.cart__total').each(function () {
        //         grandTotal += parseFloat($(this).text().replace(/[^\d.]/g, ''));
        //     });
        //     $('#grandTotal').text('₹ ' + grandTotal.toFixed(2));
        // }
        let grandTotal = 0;

        $('.cart__total').each(function () {
            let price = parseFloat($(this).data('price')) || 0;
            let qty = parseInt($(this).data('quantity')) || 0;
            let total = price * qty;

            $(this).text('₹ ' + total);
            grandTotal += total;
        });

        $('#grandTotal').text('₹ ' + grandTotal);
    }



    function recalcCartTotal() {
        let subtotal = 0;

        $('.cart__total').each(function () {
            let value = parseFloat($(this).text().replace(/[^\d.]/g, '')) || 0;
            subtotal += value;
        });

        $('#subtotal-amount').text('₹ ' + subtotal.toFixed(2));
        $('#total-amount').text('₹ ' + subtotal.toFixed(2));
    }


    $(document).ready(function () {
        $('.cart-size-dropdown').on('change', function () {
            const $dropdown = $(this);
            const cartId = $dropdown.data('cart-id');
            const selected = $dropdown.find('option:selected');
            const prvId = selected.val();
            const cartSize = selected.text().split('(')[0].trim(); // "M"
            const cartPrice = parseFloat(selected.data('price')) || 0;

            // Row and quantity
            const $row = $dropdown.closest('tr');
            const $qtyInput = $row.find('.pro-qty input');
            const qty = parseInt($qtyInput.val(), 10) || 1;

            // Update price cell (visible)
            $row.find('.cart__price').text('₹ ' + cartPrice.toFixed(2));

            // Update cart__total: set text, attr and jQuery data for price & quantity
            const total = cartPrice * qty;
            const $totalCell = $row.find('.cart__total');

            $totalCell.text('₹ ' + total.toFixed(2));
            $totalCell.attr('data-price', cartPrice);
            $totalCell.attr('data-quantity', qty);
            $totalCell.data('price', cartPrice);      // update jQuery data cache
            $totalCell.data('quantity', qty);

            // Recalculate totals UI
            updateGrandTotal();
            recalcCartTotal();

            // AJAX update DB
            $.ajax({
                url: "<?= base_url('cart/updateCartSize') ?>",
                type: 'POST',
                data: {
                    cart_Id: cartId,
                    prv_Id: prvId,
                    cart_Size: cartSize,
                    cart_Price: cartPrice
                },
                dataType: 'json'
            }).done(function (res) {
                if (res.status !== 1) {
                    alert(res.message || 'Failed to update size.');
                }
            }).fail(function () {
                alert('Error updating cart.');
            });
        });

        $(document).on("click", ".close-preview", function () {
            $("#designPreviewModal").modal("hide");
        });

        $(document).on('click', '.cart-remove', function (e) {
            e.stopPropagation();
            e.preventDefault();
           const cartId = $(this).attr('data-cart-id');

            
            const row = $(this).closest('tr');

            $.ajax({
                url: "<?= base_url('cart/remove') ?>",
                method: "POST",
                data: { cart_Id: cartId },
                dataType: "json",
                success: function (response) {
                    if (response.status === 'success') {
                        row.remove();
                        updateGrandTotal();
                        recalcCartTotal();

                        let cartCount = response.cartCount ?? 0;

                        $("#cartCount").text(cartCount);

                        if (cartCount == 0) {

                            $('.cart__total__procced').closest('.col-lg-4').hide();
                            $('.shop__cart__table table').remove();

                            $('.empty-cart-block').show();
                        }
                    } else {
                        alert('Failed to remove item.');
                    }
                },
                error: function () {
                    alert('Error occurred while removing item.');
                }
            });
        });

        function hideIfInvalid(selector, value) {
            const block = $(selector).closest('.col-md-3');

            // Case 1: empty, null, undefined → hide
            if (!value || value === "null" || value === "undefined" || value.trim() === "") {
                block.hide();
                return;
            }

            // Case 2: set src and hide if file does not exist (404)
            $(selector)
                .attr('src', value)
                .off('error')                 // clear previous listener to avoid stacking
                .on('error', function () {    // hide if broken image
                    block.hide();
                });

            block.show();
        }

        $(document).on('click', '.show-preview', function () {

            hideIfInvalid('#previewFront', $(this).data('front'));
            hideIfInvalid('#previewBack', $(this).data('back'));
            hideIfInvalid('#previewRSleeve', $(this).data('rsleeve'));
            hideIfInvalid('#previewLSleeve', $(this).data('lsleeve'));

            $('#designPreviewModal').modal('show');
        });


        var proQty = $('.pro-qty');
        proQty.prepend('<span class="dec qtybtn">-</span>');
        proQty.append('<span class="inc qtybtn">+</span>');

        // recalcCartTotal();
        updateGrandTotal();
        setTimeout(() => {
            recalcCartTotal();
        }, 100);

        $('.pro-qty').on('click', '.qtybtn', function () {
            var $button = $(this);
            var $input = $button.parent().find('input');
            var oldValue = parseInt($input.val());
            var newVal = 1;

            if ($button.hasClass('inc')) {
                newVal = oldValue + 1;
            } else {
                newVal = oldValue > 1 ? oldValue - 1 : 1;
            }

            $input.val(newVal);

            var $row = $button.closest('tr');
            var price = parseFloat($row.find('.cart__price').text().replace(/[^\d.]/g, ''));
            var rowTotal = price * newVal;
            $row.find('.cart__total').text('₹ ' + rowTotal.toFixed(2));

            recalcCartTotal();

            var cartId = $row.data('cartid');
            $.post("<?= base_url('cart/updateQuantity') ?>", { cart_id: cartId, quantity: newVal });
        });

        // $(document).on('click', '.cart-remove', function () {
        //     var $row = $(this).closest('tr');
        //     var cartId = $row.data('cartid');

        //     $.post("<?= base_url('cart/remove') ?>", { cart_id: cartId }, function (res) {
        //         if (res.status === 'success') {
        //             $row.remove();
        //             recalcCartTotal();
        //         } else {
        //             alert(res.message || 'Failed to remove item');
        //         }
        //     }, 'json');
        // });

        $('#proceedCheckout').click(function (e) {
            e.preventDefault();

            let totalAmount = $('#total-amount').text().replace('₹', '').trim();

            // put total into hidden field
            $('#hiddenTotal').val(totalAmount);

            // submit form via POST
            $('#goCheckoutForm').submit();
        });

        // $(".clickable-row").on("click", function (e) {

        //     if (
        //         $(e.target).closest(".cart-size-dropdown").length ||
        //         $(e.target).closest(".cart-remove").length ||
        //         $(e.target).closest(".show-preview").length ||
        //         e.target.tagName === "SELECT" ||
        //         e.target.tagName === "OPTION"
        //     ) {
        //         return; // Skip redirect
        //     }

        //     window.location.href = $(this).data("url");
        // });

    });


</script>