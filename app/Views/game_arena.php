<?php if (!empty($todayGame)): ?>
<section class="game-arena-full">

    <img 
        src="<?= base_url('uploads/games/' . $todayGame['game_name'] . '.jpeg'); ?>" 
        alt="Game Image"
        class="game-bg-image"
    >

    <div class="game-overlay">
        <!-- Try Now Link -->
        <a href="<?= base_url('play_game/' . $todayGame['game_name']); ?>" 
           class="game-btn" id="try_now_game_arena">
            <i class="fa fa-play"></i> Try Now
        </a>

        <a href="<?= base_url('participate'); ?>" class="game-btn ml-20 require-login" id="parcipate_in_game_arena">
            <i class="fa fa-users"></i> Participate
        </a>
    </div>
</section>
<?php else: ?>

<div class="no-game">
    <p>No game available today</p>
</div>

<?php endif; ?>




<!-- <section class="game-arena-full">
    <img 
        src="<?= base_url('uploads/games/thumbnail1.jpeg'); ?>" 
        alt="Game Image"
        class="game-bg-image"
    >
    <div class="game-overlay">
        <a href="<?= base_url('play_game'); ?>" class="game-btn" id="try_now_game_arena">
            <i class="fa fa-play"></i> Try Now
        </a>
        <a href="<?= base_url('participate'); ?>" class="game-btn ml-20 require-login" id="parcipate_in_game_arena">
            <i class="fa fa-users"></i> Participate
        </a>

    </div>
</section> -->