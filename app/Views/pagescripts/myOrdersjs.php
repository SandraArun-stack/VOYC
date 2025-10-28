<script>
    $(document).ready(function () {
        //search order

        $('#orderSearch').on('keyup', function () {
            const value = $(this).val().toLowerCase();
            $('.my__orders__container .card-block .row').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // status of order

        $('.order-status').each(function () {
            const status = parseInt($(this).data('status'));
            let statusText = '';

            switch (status) {
                case 1:
                    statusText = 'New';
                    break;
                case 2:
                    statusText = 'Order Confirmed';
                    break;
                case 3:
                    statusText = 'Packed';
                    break;
                case 4:
                    statusText = 'Dispatched';
                    break;
                case 5:
                    statusText = 'Delivered';
                    break;
                default:
                    statusText = 'Unknown';
            }

            $(this).append(' ' + statusText);
        });

        //star Rating 

        $(document).on('mouseenter', '.star-rating i', function () {
            const index = $(this).data('value');
            const $stars = $(this).parent().find('i');
            $stars.removeClass('hover');
            $stars.slice(0, index).addClass('hover');
        }).on('mouseleave', '.star-rating', function () {
            $(this).find('i').removeClass('hover');
        });

        $(document).on('click', '.star-rating i', function () {
            const rating = $(this).data('value');
            const pr_Id = $(this).closest('.star-rating').data('prid');
            const pri_Id = $(this).closest('.star-rating').data('priid');

            const $stars = $(this).parent().find('i');
            const orderId = $(this).closest('.star-rating').data('orderid');

            $stars.removeClass('active');
            $stars.slice(0, rating).addClass('active');

            $.ajax({
                url: '<?= base_url("saveRating") ?>',
                type: 'POST',
                data: {
                    pr_Id: pr_Id,
                    pri_Id: pri_Id,
                    order_id: orderId,
                    rating: rating
                },
                success: function (response) {
                    console.log('Rating saved:', response);
                },
                error: function () {
                    alert('Error saving rating.');
                }
            });
        });

        $(document).on('click', '.review-submit button', function (e) {
            var $alertBox = $('#review_msg_alert');
            e.preventDefault();

            const $block = $(this).closest('.review_adding_block');
            const reviewText = $block.find('.review-input').val().trim();

            const $ratingBox = $block.closest('.my_order_details').find('.star-rating');

            const rating = $ratingBox.find('i.active').length;
            const orderId = $ratingBox.data('orderid');
            const pr_Id = $ratingBox.data('prid');
            const pri_Id = $ratingBox.data('priid');


            if (!reviewText) {
                $alertBox
                    .removeClass('d-none alert-success')
                    .addClass('alert alert-danger')
                    .text('Please Write a Review Before Submitting.')
                    .fadeIn();

                setTimeout(function () {
                    $alertBox.fadeOut('slow', function () {
                        $(this).addClass('d-none');
                    });
                }, 3000);

                return;
            }

            $.ajax({
                url: '<?= base_url("saveRating") ?>',
                type: 'POST',
                data: {
                    pr_Id: pr_Id,
                    pri_Id: pri_Id,
                    order_id: orderId,
                    rating: rating,
                    review: reviewText
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $alertBox
                            .removeClass('d-none alert-danger')
                            .addClass('alert alert-success')
                            .text('Thank you! Your feedback has been submitted.')
                            .fadeIn();

                        setTimeout(function () {
                            $alertBox.fadeOut('slow', function () {
                                $(this).addClass('d-none');
                            });
                        }, 3000);

                        $block.addClass('d-none');
                    } else {
                        $alertBox
                            .removeClass('d-none alert-success')
                            .addClass('alert alert-danger')
                            .text(response.message || 'Failed to save review.')
                            .fadeIn();

                        setTimeout(function () {
                            $alertBox.fadeOut('slow', function () {
                                $(this).addClass('d-none');
                            });
                        }, 3000);
                    }
                },
                error: function () {
                    alert('Error saving rating.');
                }
            });
        });


        $(document).on('click', '.write_feedback', function (e) {
            e.preventDefault();

            const $orderCard = $(this).closest('.my_order_details');
            const $reviewBlock = $orderCard.find('.review_adding_block');

            $('.review_adding_block').not($reviewBlock).addClass('d-none').removeClass('d-block');
            $reviewBlock.toggleClass('d-none d-block');
        });

        $(document).on('click', '.see_feedback', function (e) {
            e.preventDefault();

            const $orderCard = $(this).closest('.my_order_details');
            const $seeBlock = $orderCard.find('.review_see_block');

            $('.review_see_block').not($seeBlock).addClass('d-none').removeClass('d-block');

            $seeBlock.toggleClass('d-none d-block');
        });

    });


</script>