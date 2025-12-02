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

                let selectedGameId = "<?= $game_map_Details['game_Id'] ?? '' ?>";
                if (selectedGameId) {
                    $('#game_Id').val(selectedGameId);
                }
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
                            .fadeIn()
                            .delay(3000)
                            .fadeOut();
                        return;
                    }

                    if (response.status === "success") {

                        $("#messageBox")
                            .removeClass()
                            .addClass("alert alert-success")
                            .text(response.message)
                            .fadeIn()
                            .delay(3000)
                            .fadeOut();

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
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();;
                }
            });
        });

        var table = $('#gameMappings').DataTable({
            processing: true,
            serverSide: true,
            order: [], 
            responsive: true,
            scrollX: false,

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
                           <i class="bi bi-trash text-danger deleteMapping" data-id="${id}"></i>

                         </a>
                   
                `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],

            columnDefs: [
                {
                    targets: [7],
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $(document).on("click", ".deleteMapping", function () {
            let id = $(this).data("id");
            // alert(id);
            confirmDelete(id);
        });


        function confirmDelete(id) {
            // alert(id);
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this game mapping?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= base_url('admin/game/delete'); ?>",
                        method: "POST",
                        dataType: "json",
                        data: { id: id },
                        success: function (response) {

                            if (response.success) {
                                Swal.fire('Deleted!', response.msg, 'success');

                                let table = $('#gameMappings').DataTable();
                                let currentPage = table.page();

                                table.ajax.reload(() => {
                                    if (table.data().count() === 0 && currentPage > 0) {
                                        table.page(currentPage - 1).draw(false);
                                    }
                                }, false);

                            } else {
                                Swal.fire('Error', response.msg, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }

    });
</script>