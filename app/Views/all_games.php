<!DOCTYPE html>
<html lang="en">
<head>
    <title>Game Arena</title>
</head>
<body>
    <div class="page-container" style="padding-top: 120px;">
        <h2 class="page-title" style="text-align: center; margin-bottom: 25px; font-size: 28px; font-weight: 700;">All Games</h2>

        <div class="games-grid">
            <?php if (!empty($games)) : ?>
                <?php foreach ($games as $game) : ?>
                    <?php
                        $thumbnail = base_url('uploads/games/' . $game['game_name'] . '.jpeg');
                        $isActive  = in_array($game['game_Id'], $activeGameIds);
                    ?>
                    <div class="game-card <?= $isActive ? '' : 'inactive' ?>">
                        <?php if ($isActive) : ?>
                            <a href="<?= base_url('game_arena/' . $game['game_Id']); ?>">
                                <img src="<?= $thumbnail ?>" alt="<?= esc($game['game_name']); ?>">
                                <div class="game-title-all"><?= esc($game['game_name']); ?></div>
                            </a>
                        <?php else : ?>
                            <img src="<?= $thumbnail ?>" alt="<?= esc($game['game_name']); ?>">
                            <div class="game-title-all"><?= esc($game['game_name']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p style="text-align:center;">No games available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
