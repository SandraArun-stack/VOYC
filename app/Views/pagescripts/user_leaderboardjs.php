<script>
    $(document).ready(function () {

        // var baseUrl = "<?= base_url() ?>";
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
                url: base_url + "userLeaderboard/userLeaderboardListAjax",
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
                    //  className: "text-center",
                    render: function (data, type, row) {

                        if (row.cust_Id == loggedUserId && row.lb_status == '2' && row.lb_redeemed_status == '1') {

                            return `
                        <span class="badge bg-leaderboard-loss">${row.lb_discount}% Off</span>
                        <button class="btn btn-warning btn-sm redeem-btn-discound-coupen"
                                data-id="${row.lb_Id}"
                                data-coupon="${row.lb_coupen_code}">
                            <i class="fa fa-gift"></i> Redeem
                        </button>`;
                        }

                        // Default badges
                        if (row.cust_Id == loggedUserId && row.lb_status == '1' && row.lb_redeemed_status == '1') {
                            return `<span class="badge bg-leaderboard-win">Win</span>
                             <button class="btn btn-warning btn-sm redeem-btn-free-tee"
                                data-id="${row.lb_Id}">
                                <i class="fa fa-gift"></i> Redeem
                            </button>`;
                        }
                        if (row.lb_status == '2') {
                            return `<span class="badge bg-leaderboard-loss">${row.lb_discount}% Off</span>`;
                        }
                        if (row.lb_status == '1') {
                            return `<span class="badge bg-leaderboard-win">Win</span>`;
                        }
                        return `<span class="badge bg-secondary">${row.lb_status}</span>`;
                    }
                }

            ]
        });

        $(document).on("click", ".redeem-btn-discound-coupen", function () {
            var coupon = $(this).data("coupon");

            $("#couponText").text(coupon);
            $("#couponModal").modal("show");
        });

            let coupon = $("#couponText").text().trim();
            navigator.clipboard.writeText(coupon);

            $(this).html('<i class="fa fa-check"></i> Copied!');
            setTimeout(() => {
                $("#copyCouponBtn").html('<i class="fa fa-copy"></i> Copy');
            }, 1500);
        });
        

    // });
</script>