<script>
$(document).on("click", ".razorpay-btn", function () {

    let planId = $(this).data("plan-id");
    let planName = $(this).data("plan-name");
    let planAmount = $(this).data("plan-amount");
    let planToken = $(this).data("plan-token");

    let options = {
        "key": "YOUR_RAZORPAY_KEY",        // Replace with your Razorpay API key
        "amount": planAmount * 100,         // In paise
        "currency": "INR",
        "name": planName,
        "description": "Subscription Payment",
        "handler": function (response) {

            $.ajax({
                url: "<?= base_url('subscription/savePayment') ?>",
                type: "POST",
                data: {
                    razorpay_payment_id: response.razorpay_payment_id,
                    plan_id: planId,
                    amount: planAmount,
                    token: planToken,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function (data) {
                    let res = JSON.parse(data);
                    if (res.status === "success") {
                        alert("Payment successful!");
                        window.location.reload();
                    } else {
                        alert("Error saving payment");
                    }
                }
            });

        },
        "theme": {
            "color": "#000000"
        }
    };

    let rzp = new Razorpay(options);
    rzp.open();
});
</script>
