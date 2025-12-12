<section class="game-arena-full">
    <img src="<?= base_url('uploads/games/' . $game['game_name'] . '.jpeg'); ?>" alt="<?= $game['game_name']; ?>"
        class="game-bg-image">
    <div class="game-overlay">
        <div class="cute-game-box">
            <h2 class="game-title"><?= $game['game_name']; ?></h2>

            <div class="token-box inside-image cute-token-box">
                <p><strong>Required Token:</strong>
                    <span class="token-highlight"><?= $game['game_token']; ?></span>
                </p>
                <p><strong>Your Balance Token:</strong>
                    <span
                        class="token-highlight <?= ($userToken < $game['game_token']) ? 'low-token' : 'high-token' ?>">
                        <?= $userToken; ?>
                    </span>
                </p>
            </div>

            <?php if ($userToken >= $game['game_token']): ?>
                <a href="<?= base_url('play_game/' . $game['game_name']); ?>" class="game-btn cute-play-btn"
                    id="start_game_btn">
                    <i class="fa fa-play"></i> Play Now
                </a>
            <?php else: ?>
                <p class="text-danger mt-20 cute-warning">Not enough tokens to play this game.</p>
            <?php endif; ?>
        </div>
    </div>

</section>