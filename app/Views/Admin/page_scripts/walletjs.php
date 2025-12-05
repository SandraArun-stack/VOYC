<script>
$(document).ready(function () {

    $('#UserWalletTable').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        responsive: true,

        ajax: {
            url: "<?= base_url('admin/wallet/ajaxList'); ?>",
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
            { data: 'cust_Name', defaultContent: '-' },
            { data: 'uw_expiry', defaultContent: '-' },
            { data: 'uw_subscription_token', defaultContent: '0' },
            { data: 'uw_bonus_token', defaultContent: '0' },
            { data: 'uw_purchased_token', defaultContent: '0' },

            {
                data: 'uw_status',
                render: function (status) {
                    return status == '1'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }
            }
        ],

        columnDefs: [
            { targets: [0, 3, 4, 5, 6], orderable: false }
        ]
    });

});
</script>
