<script>
    $(document).ready(function () {
        var baseUrl = "<?= base_url() ?>";
        var csrfName = "<?= csrf_token() ?>";
        var csrfHash = "<?= csrf_hash() ?>";

        $('#userLeaderboard').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            language: {
                info: "Showing _START_ to _END_",
                infoEmpty: "Showing 0 to 0",
                zeroRecords: "No matching records found"
            },
            ajax: {
                url: baseUrl + "userLeaderboard/userLeaderboardListAjax",
                type: "POST",
                data: function (d) {
                    d[csrfName] = csrfHash;
                },
                dataSrc: function (json) {
                    return json.data;
                }
            },

            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Auto Index
                    }
                },
                {
                    data: "lb_date",

                },
                {
                    data: "cust_name",

                },

                { data: "game_name", defaultContent: "N/A" },

                { data: "lb_score", defaultContent: "0" },
                { data: "lb_score", defaultContent: "-" },
                {
                    data: "player_winning_status",
                    render: function (status) {
                        if (status == 1) {
                            return `<span class="badge bg-success p-2">Win</span>`;
                        }
                        if (status == 2) {
                            return `<span class="badge bg-danger p-2">Lose</span>`;
                        }
                        return `<span class="badge bg-secondary p-2">Pending</span>`;
                    }
                }
            ]
        });
    });
</script>