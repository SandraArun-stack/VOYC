<script>
$(document).ready(function() {
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";

    $('#subscriptionList').DataTable({
        processing: true,
        serverSide: true,
        order: [], // Disable ordering

        ajax: {
            url: baseUrl + "admin/subscription/list",
            type: "POST",
            data: function(d) {
                d[csrfTokenName] = csrfHash;
            }
        },

        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            { data: 'sp_plan_name' },
            { data: 'sp_amount' },
            { data: 'sp_validity' },
            { data: 'sp_discount' },
            { data: 'actions' }
        ],

        columnDefs: [
            {
                targets: [5], 
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
    });
});
</script>
