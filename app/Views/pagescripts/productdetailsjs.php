<script>
    $(document).ready(function () {
        $('input[name="color__radio"]').on('change', function () {
            var priId = $(this).data('pri-id');

            $.ajax({
                url: '<?= base_url("getColorImage") ?>/' + priId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response && response.image_url) {
                        $('.product__big__img').attr('src', response.image_url);
                        $('.product__small__img').each(function (index) {
                            var smallImageUrl = response.small_image_urls[index] || response.image_url;
                            $(this).attr('src', smallImageUrl);
                        });
                    }
                },
                error: function () {
                    console.log('Error fetching image for this color.');
                }
            });
        });

        // const userId = "<?= session()->get('user_id') ?? '' ?>";
        // const authModal = new bootstrap.Modal($('#authModal')[0], {
        //     backdrop: true,
        //     keyboard: true
        // });

        // $('#addToCartBtn').on('click', function (e) {
        //     e.preventDefault();
        //     if (!userId) {
        // $('#registerView').hide();
        // $('#loginView').show();
        // authModal.show();
        //     } else {
        //         const selectedSize = $('input[name="product_size"]:checked').val();
        //         if (!selectedSize) {
        //             alert("Please select a size before adding to cart.");
        //             return;
        //         }

        //         const cartUrl = "<?= base_url('cart'); ?>/" + userId;
        //         window.location.href = cartUrl;
        //     }
        // });
        
        const userId = "<?= session()->get('user_id') ?? '' ?>";
        const authModal = new bootstrap.Modal($('#authModal')[0], {
            backdrop: true,
            keyboard: true
        });

        $(".size__btn label").on('click', function () {
            $(".size__btn label").removeClass('active');
            $(this).addClass('active');
            $(this).find("input[type='radio']").prop("checked", true);
        });

        $('#addToCartBtn').on('click', function (e) {
            e.preventDefault();

            if (!userId) {
                $('#registerView').hide();
                $('#loginView').show();
                authModal.show();
                return;
            }

            const selectedSize = $('input[name="product_size"]:checked').val();
            if (!selectedSize) {
                alert("Please select a size before adding to cart.");
                return;
            }

            const cartUrl = "<?= base_url('cart'); ?>/" + userId + "?size=" + encodeURIComponent(selectedSize);
            window.location.href = cartUrl;
        });
    });
</script>