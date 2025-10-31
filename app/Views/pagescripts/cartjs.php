<script>

    function updateGrandTotal() {
        var grandTotal = 0;
        $('.cart__total').each(function () {
            grandTotal += parseFloat($(this).text().replace(/[^\d.]/g, ''));
        });
        $('#grandTotal').text('₹ ' + grandTotal.toFixed(2));
    }

    function recalcCartTotal() {
        var subtotal = 0;

        $('.cart__total').each(function () {
            // Get the text, remove currency symbol and commas
            var totalText = $(this).text().replace(/[^\d.]/g, '');
            subtotal += parseFloat(totalText) || 0;
        });

        // Update subtotal and total
        $('#subtotal-amount').text('₹ ' + subtotal.toFixed(2));
        $('#total-amount').text('₹ ' + subtotal.toFixed(2));
    }


    $(document).ready(function () {

        $(document).on('click', '.cart-remove', function () {
            const cartId = $(this).attr('data-cart-id');
            if (!cartId) {
                alert('Cart ID missing');
                return;
            }
            const row = $(this).closest('tr');

            $.ajax({
                url: "<?= base_url('cart/remove') ?>",
                method: "POST",
                data: {
                    cart_Id: cartId
                },
                success: function (response) {
                    if (response.status === 'success') {
                        row.remove();
                    } else {
                        alert('Failed to remove item.');
                    }
                },
                error: function () {
                    alert('Error occurred while removing item.');
                }
            });
        });

        $(document).on('click', '.show-preview', function () {
            debugger;
            const front = $(this).data('front');
            const back = $(this).data('back');
            const Rsleeve = $(this).data('rsleeve');
            const Lsleeve = $(this).data('lsleeve');

            $('#previewFront').attr('src', front || '<?= base_url("uploads/productmedia/default.jpg") ?>');
            $('#previewBack').attr('src', back || '<?= base_url("uploads/productmedia/default.jpg") ?>');
            // $('#previewSleeve').attr('src', sleeve || '<?= base_url("uploads/productmedia/default.jpg") ?>');
            $('#previewRSleeve').attr('src', Rsleeve || '<?= base_url("uploads/productmedia/default.jpg") ?>');
            $('#previewLSleeve').attr('src', Lsleeve || '<?= base_url("uploads/productmedia/default.jpg") ?>');

            $('#designPreviewModal').modal('show');
        });


        var proQty = $('.pro-qty');
        proQty.prepend('<span class="dec qtybtn">-</span>');
        proQty.append('<span class="inc qtybtn">+</span>');

        recalcCartTotal();
        updateGrandTotal();

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

        $(document).on('click', '.cart-remove', function () {
            var $row = $(this).closest('tr');
            var cartId = $row.data('cartid');

            $.post("<?= base_url('cart/remove') ?>", { cart_id: cartId }, function (res) {
                if (res.status === 'success') {
                    $row.remove();
                    recalcCartTotal();
                } else {
                    alert(res.message || 'Failed to remove item');
                }
            }, 'json');
        });

    });


</script>