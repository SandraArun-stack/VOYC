<script>
$(document).ready(function() {
    $('input[name="color__radio"]').on('change', function() {
        var priId = $(this).data('pri-id');
        
        $.ajax({
            url: '<?= base_url("getColorImage") ?>/' + priId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.image_url) {
                    $('.product__big__img').attr('src', response.image_url);
                     $('.product__small__img').each(function(index) {
                        var smallImageUrl = response.small_image_urls[index] || response.image_url;  
                        $(this).attr('src', smallImageUrl);
                    });
                }
            },
            error: function() {
                console.log('Error fetching image for this color.');
            }
        });
    });
});
</script>
