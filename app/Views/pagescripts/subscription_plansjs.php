<script>
    $(document).on("click", ".razorpay-btn", function () {

        let planId = $(this).data("plan-id");
        let planName = $(this).data("plan-name");
        let planAmount = parseFloat($(this).data("plan-amount"));
        let planToken = $(this).data("plan-token");

        let amountInPaise = Math.round(planAmount * 100);
        
        $.ajax({
            url: "<?= base_url('subscription/createOrder') ?>",
            type: "POST",
            data: {
                amount: amountInPaise,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function (order) {

                let options = {
                    key: "rzp_test_us_RjrLmYhNbs9W32",
                    amount: order.amount,
                    currency: "USD",
                    name: planName,
                    description: "Subscription Payment",
                    order_id: order.id, // ✅ REQUIRED

                    handler: function (response) {
                        // Step 2: Save payment to DB
                        $.ajax({
                            url: "<?= base_url('subscription/savePayment') ?>",
                            type: "POST",
                            data: {
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                plan_id: planId,
                                amount: planAmount,
                                token: planToken,
                                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                            },
                            success: function (res) {
                                if (res.status === "success") {
                                    alert("Payment successful!");
                                    window.location.reload();
                                } else {
                                    alert("Error saving payment");
                                }
                            }
                        });
                    },
                    theme: {
                        color: "#000000"
                    }
                };

                let rzp = new Razorpay(options);
                //delete after razor pay account setup
                //rzp.handler({razorpay_payment_id: 'fake_payment_id', razorpay

                rzp.on('payment.failed', function (response) {
                    let error = response.error;

                    $.ajax({
                        url: "<?= base_url('subscription/saveFailedPayment') ?>",
                        type: "POST",
                        data: {
                            plan_id: planId,
                            amount: planAmount,
                            token: planToken,
                            error_code: error.code,
                            error_description: error.description,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        }
                    });
                });
                
                rzp.open();
            }
        });

    });
</script>