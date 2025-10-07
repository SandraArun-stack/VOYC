<script>
$(document).ready(function() {
    // alert('hi');
    $('input[name="color__radio"]').on('change', function() {
        var priId = $(this).data('pri-id');
        
        $.ajax({
            url: '<?= base_url("getColorImage") ?>/' + priId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.image_url) {
                    // Update the main product image
                    $('.product__big__img').attr('src', response.image_url);
                }
            },
            error: function() {
                console.log('Error fetching image for this color.');
            }
        });
    });
});
</script>
