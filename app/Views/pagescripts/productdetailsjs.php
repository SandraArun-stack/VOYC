<script>
    function showClickPopup(message, event) {
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
        const urlParts = window.location.pathname.split('/');
        const priIdFromUrl = urlParts[urlParts.length - 1]; // e.g. "28"

        // Find the matching color radio
        const $targetColor = $(`input[name="color__radio"][data-pri-id="${priIdFromUrl}"]`);

        if ($targetColor.length) {
            // Mark it checked visually + logically
            $targetColor.prop('checked', true);

            // Optional: highlight the corresponding checkmark (CSS effect)
            $('.color__checkbox .checkmark').removeClass('active');
            $targetColor.siblings('.checkmark').addClass('active');

            // Trigger its change event to load images/sizes dynamically
            $targetColor.trigger('change');
        } else {
            // fallback — if no matching pri_Id found, trigger the first one
            $('input[name="color__radio"]').first().prop('checked', true).trigger('change');
        }
        const userId = "<?= session()->get('user_id') ?? '' ?>";
        const authModal = new bootstrap.Modal($('#authModal')[0], {
            backdrop: true,
            keyboard: true
        });
        $('.login_close').on('click', function (e) {
            authModal.hide();
        });
        const selectedSizesByColor = {};
        // When a color is selected
        $('input[name="color__radio"]').on('change', function () {
            var priId = $(this).data('pri-id');

            //Update images
            $.ajax({
                url: '<?= base_url("getColorImage") ?>/' + priId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response && response.image_url) {
                        const bigContainer = $('.product__details__pic__slider');
                        const thumbContainer = $('.product__details__pic__left');

                        // ✅ Destroy existing carousel instance before replacing content
                        if (bigContainer.hasClass('owl-loaded')) {
                            bigContainer.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-hidden');
                            bigContainer.find('.owl-stage-outer').children().unwrap();
                        }

                        bigContainer.empty();
                        thumbContainer.empty();

                        const allImages = [response.image_url, ...response.small_image_urls];
                        if (response.video_url) {
                            allImages.push(response.video_url);
                        }

                        allImages.forEach((url, index) => {
                            const isActive = index === 0 ? 'active' : '';

                            // Check if URL is a video
                            const isVideo = url.match(/\.(mp4|webm|avi|mov|mkv)$/i);

                            if (isVideo) {
                                // 🖼️ Add video in main slider
                                bigContainer.append(`
                                    <div class="item ${isActive}">
                                        <video class="product__big__img" controls preload="metadata">
                                            <source src="${url}" type="video/mp4">
                                        </video>
                                    </div>
                                `);

                                // 🎬 Add same video as thumbnail
                                thumbContainer.append(`
                                    <div class="thumb-item ${isActive}" data-index="${index}">
                                        <video class="thumb-video" src="${url}" autoplay muted loop playsinline></video>
                                        
                                    </div>
                                `);
                            } else {
                                // 🖼️ Add image in main slider
                                bigContainer.append(`
                                    <div class="item ${isActive}">
                                        <img src="${url}" class="product__big__img" alt="">
                                    </div>
                                `);

                                // 🖼️ Add image thumbnail
                                thumbContainer.append(`
                                    <div class="thumb-item ${isActive}" data-index="${index}">
                                        <img src="${url}" alt="">
                                    </div>
                                `);
                            }
                        });


                        // ✅ Re-initialize Owl Carousel
                        bigContainer.owlCarousel({
                            loop: false,
                            margin: 0,
                            items: 1,
                            dots: false,
                            nav: true,
                            navText: [
                                "<i class='arrow_carrot-left'></i>",
                                "<i class='arrow_carrot-right'></i>"
                            ],
                            smartSpeed: 600,
                            autoplay: true,
                            autoplayTimeout: 1500,
                            autoplayHoverPause: true,
                            autoHeight: false
                        });
                        bigContainer.on('changed.owl.carousel', function (event) {
                            const currentIndex = event.item.index;
                            const currentItem = $(event.target).find('.owl-item').eq(currentIndex).find('.item');

                            // Check if this item has a video
                            const video = currentItem.find('video').get(0);

                            if (video) {
                                // Stop carousel autoplay
                                bigContainer.trigger('stop.owl.autoplay');

                                // Play video once
                                video.play();

                                // Optional: when video ends, you can decide what to do
                                video.onended = function () {
                                    console.log('Video ended');
                                    // Optionally: restart carousel autoplay
                                    // bigContainer.trigger('play.owl.autoplay', [1500]);
                                };
                            }
                        });


                        // ✅ Make thumbs clickable
                        $(document).off('click', '.thumb-item').on('click', '.thumb-item', function () {
                            const clickedIndex = $(this).data('index');
                            $('.thumb-item').removeClass('active');
                            $(this).addClass('active');

                            bigContainer.trigger('to.owl.carousel', [clickedIndex, 300]);
                        });
                    }
                },
                error: function () {
                    console.log('Error fetching image for this color.');
                }
            });

            //  Fetch sizes for the selected color
            $.ajax({
                url: '<?= base_url("getSizesByColor") ?>/' + priId,
                type: 'GET',
                dataType: 'json',
                success: function (sizes) {
                    var sizeGroup = $('.trendy-size-group');
                    sizeGroup.empty();

                    if (sizes.length) {

                        // sizes.sort((a, b) => a.prv_Size.localeCompare(b.prv_Size, undefined, { numeric: true }));

                        const sizeOrder = ["S", "M", "L", "XL", "XXL"];

                        sizes.sort((a, b) => {
                            return sizeOrder.indexOf(a.prv_Size) - sizeOrder.indexOf(b.prv_Size);
                        });

                        sizes.forEach(s => {
                            const sizeHtml = `
                        <div class="size-option" 
                             data-size-id="${s.prv_Id}" 
                             data-size="${s.prv_Size}" 
                             data-price="${s.prv_price}">
                            <input type="radio" name="product_size" id="size_${s.prv_Size}" value="${s.prv_Size}" hidden>
                            <label for="size_${s.prv_Size}" class="size-label">${s.prv_Size}</label>
                        </div>`;
                            sizeGroup.append(sizeHtml);
                        });

                        // ✅ Remember previously selected size for this color
                        const prevSelectedSize = selectedSizesByColor[priId];
                        let selectedOption;

                        // If a previous size exists for this color, select that
                        if (prevSelectedSize) {
                            selectedOption = $(`.size-option[data-size="${prevSelectedSize}"]`);
                        }

                        // Otherwise, pick the first (smallest) one
                        if (!selectedOption || !selectedOption.length) {
                            selectedOption = $('.size-option').first();
                        }

                        // ✅ Apply selection visually + logically
                        $('.size-option').removeClass('selected');
                        selectedOption.addClass('selected');
                        selectedOption.find('input[type=radio]').prop('checked', true);

                        // ✅ Update price correctly
                        updatePrice(selectedOption);

                        // ✅ On click, remember the selected size for this color
                        $(document).off('click', '.size-option').on('click', '.size-option', function () {
                            $('.size-option').removeClass('selected');
                            $(this).addClass('selected');
                            $(this).find('input[type=radio]').prop('checked', true);
                            updatePrice($(this));

                            // ✅ Save selection per color
                            const selectedSizeValue = $(this).data('size');
                            selectedSizesByColor[priId] = selectedSizeValue;
                        });


                        // Re-bind click for new size buttons
                        // $(".size__btn label").off('click').on('click', function () {
                        //     $(".size__btn label").removeClass('active');
                        //     $(this).addClass('active');
                        //     $(this).find("input[type='radio']").prop("checked", true);
                        //     updatePrice();
                        // });


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

        });


        //  addcart
        $('#addToCartBtn').on('click', function (e) {
            e.preventDefault();

            if (!userId) {
                $('#registerView').hide();
                $("#forgotPassView").addClass("d-none");
                $('#loginView').show();
                authModal.show();
                return;
            }

            //  Get selected size
            const selectedSize = $('input[name="product_size"]:checked');

            if (selectedSize.length === 0) {
                showClickPopup('Select a size', e);
                return;
            }



            const prvId = selectedSize.closest('.size-option').data('size-id');
            const price = selectedSize.closest('.size-option').data('price');
            // alert(price);
            const prId = "<?= $product['pr_Id'] ?>";
            const priId = $('input[name="color__radio"]:checked').data('pri-id');
            const designId = "<?= $product['design_Id'] ?? 0 ?>";

            //  Get the quantity selected by user
            const quantity = parseInt($('#quantity').val()) || 1;
            const sizeValue = selectedSize.val();

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
                    cart_Quantity: quantity, //  send the correct quantity
                    cart_Price: price,
                    cart_Size: sizeValue
                },
                success: function (response) {
                    if (response.status == 1) {
                        showMessage('Item added to cart successfully!', 'success');
                        if (response.cartCount !== undefined) {
                            $('#cartCount').text(response.cartCount);
                        }
                    } else {
                        showMessage(response.message || 'Failed to add to cart.', 'danger');
                    }
                },
                error: function () {
                    showMessage('Error adding to cart.', 'danger');
                }
            });
        });



        // Update price function
        function updatePrice() {
            var selected = $('.size-option input:checked').closest('.size-option');
            var price = selected.data('price') || '0';
            $('.product__details__price').html('₹ ' + price);
        }

        // Initial price update
        updatePrice();

        // Price update when size clicked initially
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

    $(document).on('click', '.video-wrapper .play-button', function () {
        const video = $(this).siblings('video').get(0);

        if (video.paused) {
            video.play();
            $(this).fadeOut();
        } else {
            video.pause();
            $(this).fadeIn();
        }
    });

    $(document).on('ended', '.video-wrapper video', function () {
        $(this).siblings('.play-button').fadeIn();
    });

    $(document).ready(function () {
        $('#qty-plus').click(function () {
            let val = parseInt($('#quantity').val());
            $('#quantity').val(val + 1);
        });

        $('#qty-minus').click(function () {
            let val = parseInt($('#quantity').val());
            if (val > 1) {
                $('#quantity').val(val - 1);
            }
        });

        function updatePrice(sizeOption) {
            let price = parseFloat(sizeOption.data('price')) || 0;
            let displayPrice = '₹ ' + price.toFixed(0);
            let discountPrice = Math.round(price + (price * 0.1)); // optional MRP style
            $('.product__details__price').html(`${displayPrice} `);
            // if we need to show the discount price reduction use this code <span>₹ ${discountPrice}</span>
        }

        // ✅ Automatically select the first size after DOM is ready
        setTimeout(function () {
            let firstSize = $('.size-option').first();
            if (firstSize.length) {
                $('.size-option').removeClass('selected');
                firstSize.addClass('selected');
                firstSize.find('input[type=radio]').prop('checked', true);
                updatePrice(firstSize);
            }
        }, 300); // delay ensures DOM is fully loaded

        // ✅ Handle size click event
        $(document).on('click', '.size-option', function () {
            $('.size-option').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('input[type=radio]').prop('checked', true);
            updatePrice($(this));
        });

        const $mainCarousel = $(".product__details__pic__slider");

        // Initialize Owl Carousel
        $mainCarousel.owlCarousel({
            loop: false, // keep this false — we’ll manually loop back later
            margin: 0,
            items: 1,
            dots: false,
            nav: true,
            navText: ["<i class='arrow_carrot-left'></i>", "<i class='arrow_carrot-right'></i>"],
            smartSpeed: 800,
            autoHeight: false,
            autoplay: false, // we’ll control autoplay manually
            mouseDrag: false,
            startPosition: 'URLHash'
        }).on('changed.owl.carousel', function (event) {
            const indexNum = event.item.index + 1;
            product_thumbs(indexNum);
        });

        // Thumbnail click: jump to corresponding image
        $(".product__thumb a").on("click", function (e) {
            e.preventDefault();
            const hash = $(this).attr("href"); // e.g. #product-2
            const targetIndex = parseInt(hash.split("-")[1], 10) - 1;
            $mainCarousel.trigger("to.owl.carousel", [targetIndex, 400]);
            product_thumbs(targetIndex + 1);
        });

        // Animate all images once after page load
        const totalItems = $mainCarousel.find('.owl-item').length;
        let currentIndex = 0;

        function animateThroughImages() {
            const interval = setInterval(() => {
                currentIndex++;
                if (currentIndex < totalItems) {
                    $mainCarousel.trigger("next.owl.carousel");
                } else {
                    clearInterval(interval);
                    // Return to the first image after finishing
                    setTimeout(() => {
                        $mainCarousel.trigger("to.owl.carousel", [0, 600]);
                    }, 800);
                }
            }, 2000); // 2 seconds per image
        }

        // Start auto animation once everything loads
        setTimeout(animateThroughImages, 1000);

        // Keep your existing extras
        $('.image-popup').magnificPopup({ type: 'image' });
        $(".nice-scroll").niceScroll({
            cursorborder: "",
            cursorcolor: "#dddddd",
            boxzoom: false,
            cursorwidth: 5,
            background: 'rgba(0, 0, 0, 0.2)',
            cursorborderradius: 50,
            horizrailenabled: false
        });
    });

    // Existing function stays the same
    function product_thumbs(num) {
        const thumbs = document.querySelectorAll('.product__thumb a');
        thumbs.forEach(function (e) {
            e.classList.remove("active");
            if (e.hash.split("-")[1] == num) {
                e.classList.add("active");
            }
        });
    }



</script>