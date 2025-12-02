<script>
$(document).ready(function() {
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";

    $('#leaderboardList').DataTable({
        processing: true,
        serverSide: true,
        order: [],

        ajax: {
            url: baseUrl + "admin/leaderboard/list",
            type: "POST",
            data: function (d) {
                d[csrfTokenName] = csrfHash;
            }
        },

        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {

                    let slNo = meta.row + meta.settings._iDisplayStart + 1;
                    if (row.lb_status == 1) {
                        return slNo +'👑 ' ;
                    }

                    return slNo;
                },
                orderable: false,
                searchable: false
            },
            { data: 'lb_date' },
            { data: 'game_name' },
            { data: 'player' },
            { data: 'lb_score' },
            { data: 'lb_rank' }
        ],

        columnDefs: [
            {
                targets: [5],
                orderable: false,
                searchable: false
            }
        ]
    });
});
</script>
