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
                    row.remove(); // remove the row from the table
                } else {
                    alert('Failed to remove item.');
                }
            },
            error: function () {
                alert('Error occurred while removing item.');
            }
        });
    });
</script>
