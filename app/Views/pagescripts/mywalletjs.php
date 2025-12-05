<script>

$(document).ready(function () {
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";

    var table = $('#tokenTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: baseUrl + "mywallet/walletListAjax",
            type: "POST",
            data: function (d) {
                d[csrfTokenName] = csrfHash;
            },
            dataSrc: function (json) {
                console.log("SERVER RESPONSE:", json);
                return json.data;
            },
            error: function (xhr) {
                console.log("AJAX ERROR:", xhr.responseText);
            }
        },

        columns: [
            { data: 'plan_name', defaultContent: 'N/A' },
            { data: 'validity', defaultContent: 'N/A' },
            { data: 'uw_subscription_token', defaultContent: 0 },
            { data: 'uw_purchased_token', defaultContent: 0 },
            { data: 'uw_bonus_token', defaultContent: 0 },
            { data: 'usersub_expiry', defaultContent: 'N/A' },
            { data: 'status', orderable: false, searchable: false }
        ]
    });

    $.get(baseUrl + "mywallet/getUserTokens", function (res) {
        $('#userTokens').text(res.tokens);
    });

});
</script>
