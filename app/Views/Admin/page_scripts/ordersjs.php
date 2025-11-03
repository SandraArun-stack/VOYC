<script>
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";
    let designIcon = "<?= base_url() . ASSET_PATH ?>assets/img/design-round.png";

    var table = $('#orderList').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        order: [[6, 'desc']],
        ajax: {
            url: baseUrl + "admin/orders/List",
            type: "POST",
            data: function (d) {
                d[csrfTokenName] = csrfHash;
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    let sno = meta.row + meta.settings._iDisplayStart + 1;
                    if (row.design_Id && row.design_Id != 0) {
                        return `
                        ${sno}
                    
                        <img src="${designIcon}" 
                             alt="Design" title="Has Design" 
                             style="width:20px;height:20px;margin-left:5px;vertical-align:middle;">
                    `;
                    }
                    return sno;
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'cust_Name',
                render: function (data, type, row) {
                    if (!data) return 'N/A';
                    const displayName = data.length > 25 ? data.substring(0, 25) + '...' : data;
                    return displayName; // plain text, no <a> tag
                }
            },


            { data: 'add_Email' },
            { data: 'add_Phone' },
            { data: 'pr_Code' },
            { data: 'od_Quantity' },
            { data: 'od_createdon' },
            {
                data: 'od_Status',
                render: function (data, type, row) {
                    if (!data) data = 'New'; // default value if empty
                    return data; // just show plain text, no color or button
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'actions',
                orderable: false,
                searchable: false
                // remove render.html() here
            }
        ],
        columnDefs: [
            { targets: [7, 8], orderable: false, searchable: false }
        ],
        createdRow: function (row, data, dataIndex) {
            // Add click event on the entire row
            $(row).css('cursor', 'pointer'); // show pointer cursor
            $(row).on('click', function () {
                window.location.href = baseUrl + "admin/orders/view/" + data.od_Id;
            });
        }

    });

</script>