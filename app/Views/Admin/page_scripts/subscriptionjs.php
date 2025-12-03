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
            { data: 'sp_token' },
            { data: 'actions' }
        ],

        columnDefs: [
            {
                targets: [6], 
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
    });
    $('#createsubscription').on('click', '#subscriptionSubmit', function(e) {
        e.preventDefault();
        var form = $('#createsubscription');
        $.ajax({
            url: baseUrl + 'admin/subscription/save',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('#messageBox').html(response.message).show();
                    setTimeout(function(){ location.href = baseUrl + 'admin/subscription'; }, 3000);
                }
            }
        });
    });
});
</script>
