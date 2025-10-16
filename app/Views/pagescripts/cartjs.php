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
    $(document).on('click', '.show-preview', function () {
        const front = $(this).data('front');
        const back = $(this).data('back');
        const sleeve = $(this).data('sleeve');

        $('#previewFront').attr('src', front || '<?= base_url("uploads/productmedia/default.png") ?>');
        $('#previewBack').attr('src', back || '<?= base_url("uploads/productmedia/default.png") ?>');
        $('#previewSleeve').attr('src', sleeve || '<?= base_url("uploads/productmedia/default.png") ?>');

        $('#designPreviewModal').modal('show');
    });
    $(document).ready(function () {
        updateGrandTotal();
    });

</script>