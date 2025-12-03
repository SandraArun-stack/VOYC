<script>
    $(document).ready(function () {
        // alert("hai");
        var table = $('#UserWalletTable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            responsive: true,
            scrollX: false,

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
                { data: 'cust_name' },
                { data: 'uw_expiry' },
                { data: 'uw_tokens' },
                { data: 'uw_additional_token' },
                { data: 'uw_additional_token' },
                {
                    data: 'uw_status',
                    render: function (status) {
                        return status === '1'
                            ? `<span class="badge bg-success">Active</span>`
                            : `<span class="badge bg-danger">Inactive</span>`;
                    }
                }
            ],

            columnDefs: [
                { targets: [0, 3, 4, 5, 6], orderable: false } // Disable sorting
            ]
        });
    });
</script>