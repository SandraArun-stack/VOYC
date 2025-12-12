<!DOCTYPE html>
<html lang="en">
<head>
    <title>Game Arena</title>
</head>
<body>
    <?php if (!empty($todayGame)): ?>
    <section class="game-arena-full">
    <img 
        src="<?= base_url('uploads/games/' . $todayGame['game_name'] . '.jpeg'); ?>"
        alt="<?= $todayGame['game_name']; ?>"
        class="game-bg-image"
    >

    <div class="game-overlay">
        <?php if (!session()->get('user_id')): ?>
            <a href="<?= base_url('play_game/' . $todayGame['game_name']); ?>"
               class="game-btn">
                <i class="fa fa-play"></i> Try Now
            </a>
            <a href="#"
               class="game-btn ml-20 require-login" id="parcipate_in_game_arena">
                <i class="fa fa-users"></i> Participate
            </a>
        <?php else: ?>

            <a href="<?= base_url('participate/' . $todayGame['game_Id']); ?>"
               class="game-btn ml-20">
                <i class="fa fa-users"></i> Participate
            </a>

        <?php endif; ?>

    </div>
</section>

    <?php else: ?>

    <div class="no-game">
        <p>No game available today</p>
    </div>
    <?php endif; ?>
</body>
</html>





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