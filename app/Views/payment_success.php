<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
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

<div class="container">
    <div class="icon">✔️</div>
    <h1>Payment Successful</h1>
    <p style="text-align:center;color:#666;">Thank you for your purchase!</p>

    <div class="order-info">
        <p>Order ID <span>#DRS10245</span></p>
        <p>Dress Name <span>Floral Summer Dress</span></p>
        <p>Size <span>M</span></p>
        <p>Color <span>Blue</span></p>
        <p>Quantity <span>1</span></p>

        <div class="divider"></div>

        <p>Total Paid <span>₹2,499</span></p>
        <p>Payment Method <span>UPI</span></p>
    </div>

    <a href="index.html" class="btn">Continue Shopping</a>
</div>

</body>
</html>
