<section class="game-arena-full" style="position: relative; width: 100%; height: 100vh; overflow: hidden;">

    <!-- Full screen image -->
    <img 
        src="<?= base_url('uploads/games/thumbnail1.jpeg'); ?>" 
        alt="Game Image"
        style="width: 100vw; height: 100vh; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;"
    >

    <!-- Buttons overlay -->
    <div style="
        position: absolute; top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        display: flex; justify-content: center; align-items: center;
        z-index: 2;
    ">

        <!-- PLAY NOW Button with icon + shadow -->
        <a href="<?= base_url('try-now'); ?>" 
           style="
               padding: 15px 35px;
               background: #af4dffff;
               color: #fff;
               font-size: 22px;
               border-radius: 50px;
               box-shadow: 0 0 20px rgba(24, 21, 21, 0.8);
               display: inline-flex;
               align-items: center;
               gap: 10px;
               text-decoration: none;
               font-weight: bold;
           ">
            <i class="fa fa-play"></i> Play Now
        </a>

        <!-- Participate button -->
        <a href="<?= base_url('participate'); ?>" 
           style="
               padding: 15px 35px;
               background: #af4dffff;
               color: #fff;
               font-size: 22px;
               border-radius: 50px;
               margin-left: 20px;
               box-shadow: 0 0 20px rgba(24, 21, 21, 0.8);
               text-decoration: none;
               font-weight: bold;
               display: inline-flex;
               align-items: center;
               gap: 10px;
           ">
            <i class="fa fa-users"></i> Participate
        </a>

    </div>

</section>
