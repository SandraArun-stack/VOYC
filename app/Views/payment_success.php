<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css" type="text/css">

    <link rel="icon" href="<?= base_url() . ASSET_PATH; ?>assets/img/favicon.ico" type="image/x-icon">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .container {
            max-width: 420px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .icon {
            text-align: center;
            font-size: 64px;
            color: #28a745;
        }

        h1 {
            text-align: center;
            color: #28a745;
            margin: 10px 0;
        }

        .order-info {
            margin-top: 20px;
        }

        .order-info p {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            color: #555;
        }

        .order-info span {
            font-weight: 600;
            color: #000;
        }

        .divider {
            border-top: 1px dashed #ddd;
            margin: 15px 0;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            text-align: center;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 20px;
        }

        .btn:hover {
            background: #218838;
        }

        @media (max-width: 480px) {
            .container {
                margin: 20px;
            }
        }
    </style>
</head>

<body>

    <script>
        const data = JSON.parse(sessionStorage.getItem('paymentData'));
    </script>

    <div class="container">
        <div class="icon">✔️</div>
        <h1>Payment Successful</h1>
        <p>Thank you for your purchase!</p>

        <div class="order-info">
            <p>Order ID <span id="orderId"></span></p>
            <p>Total Paid <span id="amount"></span></p>
            <p>Payment Method <span id="method"></span></p>
        </div>

        <a href="<?= base_url('/') ?>" class="btn">Continue Shopping</a>
    </div>

    <script>
        if (data) {
            document.getElementById('orderId').innerText = data.order_number;
            document.getElementById('amount').innerText = '₹' + data.amount;
            document.getElementById('method').innerText = data.payment_method;
        }
    </script>


</body>

</html>