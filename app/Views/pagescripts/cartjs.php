<script>
    $(document).on('click', '.cart-remove', function () {
        const cartId = $(this).data('cart-id');
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
        const front = $(this).data('front');
        const back = $(this).data('back');
        const sleeve = $(this).data('sleeve');

        $('#previewFront').attr('src', front || '<?= base_url("uploads/productmedia/default.png") ?>');
        $('#previewBack').attr('src', back || '<?= base_url("uploads/productmedia/default.png") ?>');
        $('#previewSleeve').attr('src', sleeve || '<?= base_url("uploads/productmedia/default.png") ?>');

        $('#designPreviewModal').modal('show');
    });

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
        var proQty = $('.pro-qty');
        proQty.prepend('<span class="dec qtybtn">-</span>');
        proQty.append('<span class="inc qtybtn">+</span>');
 recalcCartTotal();
        updateGrandTotal();

       


    });
    // When quantity changes
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

        // Update row total
        var $row = $button.closest('tr');
        var price = parseFloat($row.find('.cart__price').text().replace(/[^\d.]/g, ''));
        var rowTotal = price * newVal;
        $row.find('.cart__total').text('₹ ' + rowTotal.toFixed(2));

        // Update totals
        recalcCartTotal();

        // Optional: send AJAX to update DB
        var cartId = $row.data('cartid');
        $.post("<?= base_url('cart/updateQuantity') ?>", { cart_id: cartId, quantity: newVal });
    });

    // When item is removed
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

    // Initial calculation on page load
    $(document).ready(function () {
       
    });

</script>