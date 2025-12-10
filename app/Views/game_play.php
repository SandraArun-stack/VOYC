<!DOCTYPE html>
<html>
<head>
    <title>Play Game</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

    <!-- <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }

        .game-container {
            width: 100vw;
            height: 100vh;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style> -->
</head>
<body>

<div class="game-container">
    <iframe 
        src="<?= base_url('public/games/WebglCheckingGame/index.html'); ?>" 
        style="width:100vw;height:100vh;border:0;"
        allowfullscreen>
    </iframe>

</div>

</body>
</html>
