<script>
    function showClickPopup(message, event) {
        // debugger;
        $('.click-popup').remove();

        const popup = $('<div class="click-popup"></div>').text(message);
        $('body').append(popup);
        popup.css({
            top: event.pageY - 40 + 'px',
            left: event.pageX - (popup.width() / 2) + 'px'
        });

        setTimeout(() => {
            popup.fadeOut(300, function () {
                $(this).remove();
            });
        }, 1500);
    }


    $(document).ready(function () {
        const userId = "<?= session()->get('user_id') ?? '' ?>";
        const authModal = new bootstrap.Modal($('#authModal')[0], {
            backdrop: true,
            keyboard: true
        });

        // When a color is selected
        $('input[name="color__radio"]').on('change', function () {
            var priId = $(this).data('pri-id');

            // 1️⃣ Update images
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

            // 2️⃣ Fetch sizes for the selected color
            $.ajax({
                url: '<?= base_url("getSizesByColor") ?>/' + priId,
                type: 'GET',
                dataType: 'json',
                success: function (sizes) {
                    var sizeGroup = $('.trendy-size-group');
                    sizeGroup.empty();

                    if (sizes.length) {
                        sizes.forEach(function (s) {
                            var sizeHtml = `<div class="size-option" data-size-id="${s.prv_Id}" data-size="${s.prv_Size}" data-price="${s.prv_price}">
                            <input type="radio" name="product_size" id="size_${s.prv_Size}" value="${s.prv_Size}" hidden>
                            <label for="size_${s.prv_Size}" class="size-label">${s.prv_Size}</label>
                        </div>`;
                            sizeGroup.append(sizeHtml);
                        });

                        // Re-bind click for new size buttons
                        $(".size__btn label").off('click').on('click', function () {
                            $(".size__btn label").removeClass('active');
                            $(this).addClass('active');
                            $(this).find("input[type='radio']").prop("checked", true);
                            updatePrice();
                        });

                    } else {
                        sizeGroup.html('<p>No sizes available for this color.</p>');
                        $('.product__details__price').html('₹ 0');
                    }

                    // Update price for first available size automatically
                    updatePrice();
                },
                error: function () {
                    console.log('Error fetching sizes for this color.');
                }
            });
        });

        function checkSelections() {
            const sizeSelected = $('input[name="product_size"]:checked').length > 0;
            const colorSelected = $('input[name="color__radio"]:checked').length > 0;

            if (sizeSelected && colorSelected) {
                $('#addToCartBtn').removeClass('disabled');
            } else {
                $('#addToCartBtn').addClass('disabled');
            }
        }

        $(document).on('change', 'input[name="product_size"], input[name="color__radio"]', checkSelections);

        $(document).ready(checkSelections);

        $('#addToCartBtn').on('click', function (e) {
            e.preventDefault();

            // if ($(this).hasClass('disabled')) return;

            if (!userId) {
                $('#registerView').hide();
                $('#loginView').show();
                authModal.show();
                return;
            }

            const selectedSize = $('input[name="product_size"]:checked');
            const prvId = selectedSize.closest('.size-option').data('size-id');
            const price = selectedSize.closest('.size-option').data('price');

            const prId = "<?= $product['pr_Id'] ?>";
            const priId = $('input[name="color__radio"]:checked').data('pri-id');
            const designId = "<?= $product['design_Id'] ?? 0 ?>";
            const quantity = 1;

            if (selectedSize.length === 0) {
                showClickPopup('Select a size', e);
                return;
            }
            
            $.ajax({
                url: "<?= base_url('addToCart') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    cust_Id: userId,
                    pr_Id: prId,
                    pri_Id: priId,
                    prv_Id: prvId,
                    design_Id: designId,
                    cart_Quantity: quantity,
                    price: price
                },
                success: function (response) {
                    if (response.status == 1) {
                        showMessage('Item added to cart successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = "<?= base_url('cart') ?>/" + userId;
                        }, 1500);
                    } else {
                        showMessage(response.message || 'Failed to add to cart.', 'danger');
                    }
                },
                error: function () {
                    showMessage('Error adding to cart.', 'danger');
                }
            });
        });



        function updatePrice() {
            var selected = $('.size-option input:checked').closest('.size-option');
            var price = selected.data('price') || '0';
            $('.product__details__price').html('₹ ' + price);
        }

        updatePrice();

        $(document).on('change', '.size-option input', function () {
            $('.size-option').removeClass('active');
            $(this).closest('.size-option').addClass('active');
            updatePrice();
        });
        $('input[name="color__radio"]:checked').trigger('change');
    });

    function showMessage(message, type = 'success') {
        const box = $('#messageBox');
        box.removeClass('alert-success alert-danger alert-warning').addClass('alert-' + type);
        box.html(message).fadeIn(300);

        // Auto-hide after 2 seconds
        setTimeout(() => {
            box.fadeOut(300);
        }, 2000);
    }


    $('#customizeTshirtBtn').on('click', function (e) {
        const prId = "<?= $product['pr_Id'] ?>";
        const priId = $('input[name="color__radio"]:checked').data('pri-id');
        const sizeSelected = $('input[name="product_size"]:checked').length > 0;

        if (!priId) {
            showClickPopup('Select a color', e);
            return;
        }

        if (!sizeSelected) {
            showClickPopup('Select a Size', e);
            return;
        }

        const customizeUrl = "<?= base_url('tshirt_Customisation') ?>/" + prId + "/" + priId;
        window.location.href = customizeUrl;
    });



</script>