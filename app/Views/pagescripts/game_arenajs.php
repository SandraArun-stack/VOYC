<script>
    var loggedUserId = "<?= session()->get('user_id'); ?>";
    const authModal = new bootstrap.Modal(document.getElementById('authModal'), {
        backdrop: true,
        keyboard: true
    });

    $('.login_close').on('click', function () {
        authModal.hide();
    });

    $('#parcipate_in_game_arena').on('click', function (e) {
        e.preventDefault();

        if (loggedUserId === "" || loggedUserId === null) {
            $('#registerView').hide();
            $('#forgotPassView').hide();
            $('#loginView').show();
            authModal.show();
        }
    });
</script>
