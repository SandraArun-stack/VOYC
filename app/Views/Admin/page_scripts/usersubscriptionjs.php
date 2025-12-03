<script>
var baseUrl = "<?= base_url() ?>";

$(document).ready(function () {

    $('#userSubList').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: baseUrl + "admin/usersubscriptions/list",
            type: "POST",
            data: function (d) {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
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
            { data: 'user_name' },
            { data: 'plan_name' },
            { data: 'usersub_discount' },
            { data: 'usersub_created_at' },
            { data: 'usersub_expiry' },
            { data: 'status_badge' }
        ],

        columnDefs: [
            { targets: [0,2,5,6], orderable: false, searchable:false }
        ]
    });

});
</script>
