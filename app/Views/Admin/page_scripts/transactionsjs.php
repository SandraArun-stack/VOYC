<script>
$(document).ready(function() {

    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";

    var table = $('#transactionList').DataTable({
        processing: true,
        serverSide: true,
        order: [],

        ajax: {
            url: baseUrl + "admin/transactions/list",
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
            { data: 'initiated_at' },
            { data: 'cust_Name' },
            { data: 'payment_method' },
            { data: 'transaction_amount' },
            { data: 'transaction_status' },
            {
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],

        columnDefs: [
            { targets: [5, 6], orderable: false, searchable: false }
        ]
    });
    $(document).ready(function () {
        $('#backToTransactions').on('click', function () {
            window.location.href = "<?= base_url('admin/transactions') ?>";
        });
    });

});
</script>
