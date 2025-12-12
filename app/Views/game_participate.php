<section class="game-arena-full">
    <img 
        src="<?= base_url('uploads/games/' . $game['game_name'] . '.jpeg'); ?>" 
        alt="<?= $game['game_name']; ?>"
        class="game-bg-image"
    >
    <div class="game-overlay">
        <h2 class="game-title"><?= $game['game_name']; ?></h2>
        <div class="token-box inside-image">
            <p><strong>Required Token:</strong> <?= $game['game_token']; ?></p>
            <p><strong>Your Balance Token:</strong> <?= $userToken; ?></p>
        </div>
        <?php if ($userToken >= $game['game_token']): ?>
            <a href="<?= base_url('play_game/' . $game['game_name'] . '?game_id=' . $game['game_Id']); ?>" 
                class="game-btn ml-20" id="start_game_btn">
                <i class="fa fa-play"></i> Play Now
            </a>



        <?php else: ?>
            <p class="text-danger mt-20">Not enough tokens to play this game.</p>
        <?php endif; ?>

    </div>

</section>
