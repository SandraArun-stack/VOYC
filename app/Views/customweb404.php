<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found | Voyc</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Comic Sans MS', Arial, sans-serif;
            background: linear-gradient(135deg, #000 0%, #fff 100%);
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 100px;
            color: #000;
            animation: bounce 1.5s infinite;
        }

        p {
            font-size: 20px;
            margin: 20px 0;
        }

        a.home-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            font-size: 18px;
            text-decoration: none;
            background-color: #000;
            color: #fff;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        a.home-btn:hover {
            background-color: #fff;
            color: #000;
            border: 2px solid #000;
        }

        .game-hint {
            margin-top: 15px;
            font-size: 16px;
            color: #555;
        }

        /* Bouncing animation for 404 */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        /* Decorative playful circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: #000;
            opacity: 0.1;
            animation: float 6s infinite ease-in-out;
        }

        .circle:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 20%; animation-delay: 0s;}
        .circle:nth-child(2) { width: 50px; height: 50px; top: 70%; left: 10%; animation-delay: 2s;}
        .circle:nth-child(3) { width: 100px; height: 100px; top: 30%; left: 80%; animation-delay: 4s;}
        .circle:nth-child(4) { width: 60px; height: 60px; top: 80%; left: 70%; animation-delay: 1s;}

        @keyframes float {
            0% { transform: translateY(0px);}
            50% { transform: translateY(-20px);}
            100% { transform: translateY(0px);}
        }

        @media(max-width: 600px){
            h1 { font-size: 70px; }
            p { font-size: 16px; }
            a.home-btn { font-size: 16px; padding: 10px 20px; }
        }

    </style>
</head>
<body>

    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>

    <div class="container">
        <img src="<?= base_url() . ASSET_PATH; ?>assets/img/logo-black.jpg" alt="Voyc Logo" class="logo">
        <h1>404</h1>
        <p>Oops! The page you’re looking for is lost in our game world.</p>
        <p class="game-hint">Explore Voyc’s fun games while you’re here!</p>
        <a href="<?= base_url('/'); ?>" class="home-btn">Return Home</a>
    </div>

</body>
</html>
