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
                    loggedUserId = json.loggedUserId;
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
                    data: null,
                    render: function (data, type, row) {

                        // 🟡 Show Redeem button ONLY if logged-in user matches row user
                        if (row.cust_Id == loggedUserId) {
                            return `
                        <button class="btn btn-warning btn-sm redeem-btn"
                                data-id="${row.lb_Id}">
                            <i class="fa fa-gift"></i> Redeem
                        </button>`;
                        }

                        // 🟢 Otherwise show status labels
                        if (row.player_winning_status == 1) {
                            return `<span class="badge bg-success p-2">Win</span>`;
                        }
                        if (row.player_winning_status == 2) {
                            return `<span class="badge bg-danger p-2">Lose</span>`;
                        }

                        return `<span class="badge bg-secondary p-2">Pending</span>`;
                    }
                }

            ]
        });
    });
</script>