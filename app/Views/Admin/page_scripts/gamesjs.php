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
                $('#game_Id').html(options);
            }
        });

        // Submit form using serialize
        $("#gamemapingform").on("submit", function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "<?= base_url('admin/game/saveGameMapping'); ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {

                    if (response.status === "error") {
                        $("#messageBox")
                            .removeClass()
                            .addClass("alert alert-danger")
                            .text(response.message)
                            .fadeIn();
                        return;
                    }

                    if (response.status === "success") {

                        $("#messageBox")
                            .removeClass()
                            .addClass("alert alert-success")
                            .text(response.message)
                            .fadeIn();

                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);
                    }
                },
                error: function (err) {
                    console.log(err);

                    $("#messageBox")
                        .removeClass()
                        .addClass("alert alert-danger")
                        .text("Error while saving.")
                        .fadeIn();
                }
            });
        });

        var table = $('#gameMappings').DataTable({
            processing: true,
            serverSide: true,
            order: [], // disable default ordering (same as staff list)
            responsive: true, // auto adjust columns
            scrollX: false, // REMOVE horizontal scroll

            ajax: {
                url: "<?= base_url('admin/game/ajaxList'); ?>",
                type: "POST"
            },

            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false
                },

                { data: 'gm_date' },
                { data: 'game_name' },
                { data: 'gm_tokens' },
                { data: 'gm_leaderboard_count' },
                { data: 'gm_free_tee_percentage' },
                { data: 'gm_extra_discount' },

                {
                    data: 'gm_Id',
                    render: function (id) {
                        return `
                    <a href="<?= base_url('admin/game/edit/'); ?>${id}" >
                        <i class="bi bi-pencil-square"></i>
                    </a>&nbsp;

             
                         <a data-id="${id}">
                            <i class="bi bi-trash text-danger" ></i>
                         </a>
                   
                `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],

            columnDefs: [
                {
                    targets: [7], // Disable ordering & searching for action column
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });
</script>