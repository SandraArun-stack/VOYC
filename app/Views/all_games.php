<h2 style="text-align:center;margin-top:20px;">All Games</h2>

<div class="games-grid">

<?php foreach ($games as $game): ?>

    <?php
        $thumbnail = base_url('uploads/games/' . $game['game_name'] . '.jpeg');
        $isActive = in_array($game['game_Id'], $activeGameIds); 
    ?>

    <div class="game-card <?= $isActive ? '' : 'inactive' ?>">
        
        <?php if ($isActive): ?>
            <a href="<?= base_url('game_arena/' . $game['game_Id']); ?>">
                <img src="<?= $thumbnail ?>" alt="<?= $game['game_name'] ?>">
                <div class="game-title"><?= $game['game_name'] ?></div>
            </a>
        <?php else: ?>
            <img src="<?= $thumbnail ?>" alt="<?= $game['game_name'] ?>">
            <div class="game-title"><?= $game['game_name'] ?></div>
        <?php endif; ?>

    </div>

<?php endforeach; ?>


</div>
