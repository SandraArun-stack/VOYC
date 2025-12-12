<script>
    var base_url = "<?= base_url(); ?>";
    var csrfName = "<?= csrf_token(); ?>";
    var csrfHash = "<?= csrf_hash(); ?>";

$(document).on('click', '.viewCouponBtn', function () {
    var couponCode = $(this).data('coupon');
    $('#couponText').text(couponCode);
    $('#couponModal').modal('show');
});

// Copy coupon to clipboard
$('#copyCouponBtn').click(function () {
    var coupon = $('#couponText').text().trim();

    navigator.clipboard.writeText(coupon).then(function () {
        $('#copyCouponBtn').html('<i class="fa fa-check"></i> Copied!');

        setTimeout(function () {
            $('#copyCouponBtn').html('<i class="fa fa-copy"></i> Copy');
        }, 1500);

    }).catch(function () {
        alert('Failed to copy!');
    });
});

$(document).on('click', '.redeemFreeTeeBtn', function () {

    var couponCode = $(this).data('coupon');
    var lbId = $(this).data('id');

    $.ajax({
        url: base_url + "/setFreeTeeSession",
        type: "POST",
        data: {
            coupon: couponCode,
            lb_id: lbId,
            [csrfName]: csrfHash
        },
        success: function (response) {
            window.location.href = base_url + "allCustomizableProducts";
        }
    });
});

</script>