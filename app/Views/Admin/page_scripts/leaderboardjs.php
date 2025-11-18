<script>
// var baseUrl = "<?= rtrim(base_url(), '/'); ?>";

// $(document).ready(function () {

//     var csrfToken = "<?= csrf_token() ?>";
//     var csrfHash = "<?= csrf_hash() ?>";

//     $('#leaderboardList').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: baseUrl + "/admin/leaderboard/ajaxList",
//             type: "POST",
//             data: function (d) {
//                 d[csrfToken] = csrfHash;
//             }
//         },
//         columns: [
//             {  
//                 data: null,
//                 render: function (data, type, row, meta) {
//                     return meta.row + meta.settings._iDisplayStart + 1;
//                 },
//                 orderable: false
//             },
//             { data: 'date' },
//             { data: 'game_name' },
//             { data: 'winners' },
//             { data: 'turns' },
//             { data: 'actions' }
//         ],
//         columnDefs: [
//             {
//                 targets: 5,
//                 orderable: false,
//                 searchable: false
//             }
//         ]
//     });
// });

$(document).ready(function() {

    $('#leaderboardTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": base_url + "admin/leaderboard/ajaxList",
            "type": "POST",
            "data": function(d) {
                d[csrfName] = csrfHash; // important for CI4
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [0, 5] }
        ]
    });

});
</script>
