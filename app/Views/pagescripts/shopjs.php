<script>
    var rangeSlider = $(".price-range"),
        minamount = $("#minamount"),
        maxamount = $("#maxamount"),
        minPrice = rangeSlider.data('min'),
        maxPrice = rangeSlider.data('max');

    rangeSlider.slider({
        range: true,
        min: minPrice,
        max: maxPrice,
        values: [minPrice, maxPrice],
        slide: function (event, ui) {
            minamount.val('₹' + ui.values[0]);
            maxamount.val('₹' + ui.values[1]);
        }
    });
    minamount.val('₹' + rangeSlider.slider("values", 0));
    maxamount.val('₹' + rangeSlider.slider("values", 1));

    function generateStars(avgRating) {
        let html = '';
        const avg = parseFloat(avgRating);
        for (let i = 1; i <= 5; i++) {
            if (i <= Math.floor(avg)) {
                html += '<i class="fa fa-star text-warning"></i>';
            } else if (i === Math.ceil(avg) && avg - Math.floor(avg) >= 0.5) {
                html += '<i class="fa fa-star-half-o text-warning"></i>';
            } else {
                html += '<i class="fa fa-star-o text-muted"></i>';
            }
        }
        return html;
    }


    $(document).ready(function () {
        $(document).on('click', '.product__item', function (e) {
            if ($(e.target).closest('a').length) return;
            const url = $(this).data('url');
            if (url) window.location.href = url;
        });

        const itemsPerPage = 12;
        const $cards = $(".product__card");
        const totalItems = $cards.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const $pagination = $(".pagination__option");

        function showPage(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            $cards.hide().slice(start, end).fadeIn(400);

            $pagination.find("a").removeClass("active");
            $pagination.find(`[data-page="${page}"]`).addClass("active");
        }

        for (let i = 1; i <= totalPages; i++) {
            $pagination.append(`<a href="#" data-page="${i}">${i}</a>`);
        }

        if (totalPages > 1) {
            $pagination.append(`<a href="#" class="next"><i class="fa fa-angle-right"></i></a>`);
        }

        $pagination.on("click", "a[data-page]", function (e) {
            e.preventDefault();
            const page = $(this).data("page");
            showPage(page);
        });

        $pagination.on("click", ".next", function (e) {
            e.preventDefault();
            const currentPage = parseInt($pagination.find("a.active").data("page"));
            if (currentPage < totalPages) {
                showPage(currentPage + 1);
            }
        });

        showPage(1);
        // const minPrice = parseInt($('#minamount').val().replace('₹', '').trim()) || 200;
        // const maxPrice = parseInt($('#maxamount').val().replace('₹', '').trim()) || 10000;
        // var minPriceValue = parseFloat(minamount.val().replace('₹', '').trim());
        // var maxPriceValue = parseFloat(maxamount.val().replace('₹', '').trim());
        const mainCategory = "<?= esc($category) ?>";


        setTimeout(() => {
            const originalProductList = $('.product-list').html();


            $(document).on('change', '.subcategory-filter, .size-filter', function () {
                const checkedSubcats = $('.subcategory-filter:checked')
                    .map(function () {
                        return $(this).data('subcategory');
                    })
                    .get();

                const selectedSizes = $('.size-filter:checked')
                    .map(function () {
                        return $(this).data('size');
                    })
                    .get();
                // alert(selectedSizes);

                if (checkedSubcats.length === 0) {
                    $('.product-list').html(originalProductList);
                    $('.set-bg').each(function () {
                        const bg = $(this).data('setbg');
                        $(this).css('background-image', 'url(' + bg + ')');
                    });
                }
                var minPriceValue = parseFloat(minamount.val().replace('₹', '').trim());
                var maxPriceValue = parseFloat(maxamount.val().replace('₹', '').trim());

                $.ajax({
                    url: "<?= base_url('fetchProductsBySubcategory'); ?>",
                    type: "POST",
                    data: {
                        "subcategory_id[]": checkedSubcats,
                        main_category: mainCategory,
                        min_price: minPriceValue,
                        max_price: maxPriceValue,
                        "sizes[]": selectedSizes
                    },
                    traditional: true,
                    dataType: "json",
                    beforeSend: function () {
                        $('.product-list').html('<div class="col-12 text-center"><p>Loading...</p></div>');
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            let html = '';
                            response.filtered_products.forEach(item => {
                                html += `
                                    <div class="col-lg-3 col-md-6 mb-4 product__card" style="opacity:1;">
                                        <div class="product__item" data-url="<?= base_url('productdetails'); ?>/${item.pr_Id}/${item.pri_Id}">
                                            <div class="product__item__pic set-bg"
                                                data-setbg="<?= base_url('uploads/productmedia/'); ?>/${item.pri_Thumbnail}">
                                                
                                                <ul class="product__hover">
                                                    <li>
                                                        <a href="<?= base_url('uploads/productmedia/'); ?>/${item.pri_Thumbnail}" class="image-popup">
                                                            <span class="arrow_expand"></span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                                                    <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                                                </ul>
                                            </div>
                                            <div class="product__item__text">
                                                <h6 class="product_name_text"><a href="#">${item.pr_Name}</a></h6>
                                                <div class="rating">
                                                    ${generateStars(item.average_rating)}
                                                </div>
                                                <div class="product__price">₹ ${item.selected_price}</div>
                                            </div>
                                        </div>
                                    </div>`;
                            });

                            $('.product-list').html(html);

                            // Reapply background images
                            $('.set-bg').each(function () {
                                const bg = $(this).data('setbg');
                                $(this).css('background-image', 'url(' + bg + ')');
                            });

                        } else if (response.status === 'empty') {
                            $('.product-list').html('<div class="col-12 text-center"><p>No products found.</p></div>');
                        } else {
                            $('.product-list').html('<div class="col-12 text-center text-danger"><p>Error loading products.</p></div>');
                        }
                    },
                    error: function () {
                        $('.product-list').html('<div class="col-12 text-center text-danger"><p>Server error occurred.</p></div>');
                    }
                });
            });

        }, 1000);

        // filter by price-range
        function getProduct() {

        }

        $("#filterPriceBtn").on("click", function () {
            var minPriceValue = parseFloat(minamount.val().replace('₹', '').trim());
            var maxPriceValue = parseFloat(maxamount.val().replace('₹', '').trim());

            var selectedSubcategories = [];
            $('.subcategory-filter:checked').each(function () {
                selectedSubcategories.push($(this).val());
            });

            if (selectedSubcategories.length === 0) {
                $('.subcategory-filter').each(function () {
                    selectedSubcategories.push($(this).val());
                });
            }

            $.ajax({
                url: "<?= base_url('fetchProductsBySubcategory'); ?>",
                method: 'POST',
                data: {
                    min_price: minPriceValue,
                    max_price: maxPriceValue,
                    subcategory_id: selectedSubcategories,
                    main_category: mainCategory
                },
                success: function (response) {
                    if (response.status === 'success') {
                        let html = '';

                        response.filtered_products.forEach(item => {
                            html += `
                                    <div class="col-lg-3 col-md-6 mb-4 product__card" style="opacity:1;">
                                        <div class="product__item" data-url="<?= base_url('productdetails'); ?>/${item.pr_Id}/${item.pri_Id}">
                                            <div class="product__item__pic set-bg"
                                                data-setbg="<?= base_url('uploads/productmedia/'); ?>/${item.pri_Thumbnail}">
                                               
                                                <ul class="product__hover">
                                                    <li>
                                                        <a href="<?= base_url('uploads/productmedia/'); ?>/${item.pri_Thumbnail}" class="image-popup">
                                                            <span class="arrow_expand"></span>
                                                        </a>
                                                    </li>
                                                    <li><a href="#"><span class="icon_heart_alt"></span></a></li>
                                                    <li><a href="#"><span class="icon_bag_alt"></span></a></li>
                                                </ul>
                                            </div>
                                            <div class="product__item__text">
                                                <h6 class="product_name_text"><a href="#">${item.pr_Name}</a></h6>
                                                <div class="rating">
                                                    ${generateStars(item.average_rating)}
                                                </div>
                                                <div class="product__price">₹ ${item.prv_price}</div>
                                            </div>
                                        </div>
                                    </div>`;
                        });

                        $('.product-list').html(html);

                        // Reapply background images
                        $('.set-bg').each(function () {
                            const bg = $(this).data('setbg');
                            $(this).css('background-image', 'url(' + bg + ')');
                        });

                    } else if (response.status === 'empty') {
                        $('.product-list').html('<div class="col-12 text-center"><p>No products found.</p></div>');
                    } else {
                        $('.product-list').html('<div class="col-12 text-center text-danger"><p>Error loading products.</p></div>');
                    }
                },
                error: function () {
                    $('.product-list').html('<div class="col-12 text-center text-danger"><p>Server error occurred.</p></div>');
                }
            });
        });
    });
</script>