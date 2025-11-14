<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Link Expired</title>

    <style>
        /* Page Center Wrapper */
        .expired-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f7f7f7;
            padding: 20px;
        }

        /* Box styling */
        .expired-box {
            width: 100%;
            max-width: 420px;
            padding: 35px 30px;
            /* background: #ffffff; */
            border-radius: 16px;
            text-align: center;
            box-shadow: 0px 10px 35px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.4s ease;
        }

        /* Fade animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Brand Logo */
        .voyc-logo {
            width: 130px;
            margin-bottom: 5px;
            opacity: 0.95;
        }

        /* Expired Image */
        .expired-img {
            width: 150px;
            margin: 15px auto;
            display: block;
        }

        /* Heading */
        .expired-box h3 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }

        /* Text */
        .expired-box p {
            font-size: 14px;
            color: #666;
            margin-top: 8px;
            line-height: 1.5;
        }

        /* Home Button */
        .go-home {
            display: block;
            background: #000;
            color: #fff !important;
            padding: 12px 0;
            border-radius: 8px;
            margin-top: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.25s ease;
        }

        .go-home:hover {
            background: #333;
        }
    </style>
</head>

<body>

    <div class="expired-wrapper">
        <div class="expired-box">

            <img src="<?= base_url(ASSET_PATH . 'assets/img/logo-black.jpg'); ?>" 
                 class="voyc-logo" alt="VOYC Logo">

            <img src="<?= base_url(ASSET_PATH . 'assets/img/link-expired.jpg'); ?>" 
                 class="expired-img" alt="Expired">

            <h3>Reset Link Expired</h3>
            <p>Your password reset link has expired or has already been used.</p>

            <a href="<?= base_url('/') ?>" class="go-home">Go to Home</a>
        </div>
    </div>

</body>
</html>
