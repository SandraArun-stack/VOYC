<!DOCTYPE html>
<html>
<head>
    <title>Play Game</title>
</head>
<body>
<div class="game-container">
    <iframe 
        src="<?= base_url('public/games/' . $folderName . '/index.html'); ?>" 
        style="width:100vw;height:100vh;border:0;" 
        allowfullscreen>
    </iframe>

</div>
</body>
</html>
