<script>
$(document).ready(function () {

    // Load game dropdown
    $.ajax({
        url: "<?= base_url('admin/game-details/get_games_dropdown') ?>",
        method: "GET",
        success: function (response) {
            let options = '<option value="">-- Select Game --</option>';
            response.forEach(function (game) {
                options += `<option value="${game.game_Id}">${game.game_name}</option>`;
            });
            $('#game_id').html(options);
        }
    });

    // Submit form using serialize
    $("#gamemapingform").on("submit", function (e) {
        e.preventDefault();

        let formData = $(this).serialize();  // ← IMPORTANT

        $.ajax({
            url: $(this).attr("action"),      // uses form action URL
            method: "POST",
            data: formData,
            success: function (response) {
                alert("Saved Successfully");
                window.location.href = "<?= base_url('admin/game-details'); ?>";
            },
            error: function (err) {
                console.log(err);
                alert("Error while saving");
            }
        });
    });

});
</script>
