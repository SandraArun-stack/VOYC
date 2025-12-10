<script>
    var loggedUserId = "<?= session()->get('user_id'); ?>";
    const authModal = new bootstrap.Modal(document.getElementById('authModal'), {
        backdrop: true,
        keyboard: true
    });
    $('.login_close').on('click', function (e) {
        authModal.hide();
    });
    $('#try_now_game_arena').on('click', function (e) {
        e.preventDefault();

        // Check if user is logged in
        if (loggedUserId === "" || loggedUserId === null) {

            // User not logged in → show login modal
            $('#registerView').hide();
            $('#forgotPassView').hide();
            $('#loginView').show();
            authModal.show();

        } else {

            // User logged in → redirect to play_game route
            window.location.href = base_url + "play_game";
        }
    });

</script>