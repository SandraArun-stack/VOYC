<script>
$(document).ready(function() {
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";
    $('#counterList').DataTable({
        processing: true,
        serverSide: true,
        order: [],

        ajax: {
            url: baseUrl + "admin/daily_counter/list",
            type: "POST",
            data: function (d) {
                d[csrfTokenName] = csrfHash;
            }
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
            { data: 'dgc_date' },
            { data: 'game_name' },
            { data: 'dgc_player_count' },
            { data: 'dgc_winner_count' },
            { data: 'dgc_winning_percentage' }
        ],
        columnDefs: [
            {
                targets: [0,5],
                orderable: false,
                searchable: false
            }
        ]
    });
});
</script>
