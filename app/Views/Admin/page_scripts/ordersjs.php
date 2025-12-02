<script>
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";
    let designIcon = "<?= base_url() . ASSET_PATH ?>assets/img/design-round.png";

    // 🔥 FIX: Define openedOrders properly
    let openedOrders = JSON.parse(localStorage.getItem("openedOrders")) || [];

    function getStatusBadge(status) {
        switch (parseInt(status)) {
            case 1: return '<span class="badge" style="padding:7px; font-size:12px; background:green;">New</span>';
            case 2: return '<span class="badge" style="padding:7px; font-size:12px; background:yellow;color:#000;">Confirmed</span>';
            case 3: return '<span class="badge" style="padding:7px; font-size:12px; background:blue;">Packed</span>';
            case 4: return '<span class="badge" style="padding:7px; font-size:12px; background:orange;">Dispatched</span>';
            case 5: return '<span class="badge" style="padding:7px; font-size:12px; background:black;">Delivered</span>';
            default: return '<span class="badge bg-secondary">Unknown</span>';
        }
    }

    var table = $('#orderList').DataTable({
        processing: true,
        serverSide: true,
        // scrollX: true,
        order: [[6, 'desc']],
        ajax: {
            url: baseUrl + "admin/orders/List",
            type: "POST",
            data: function (d) {
                d[csrfTokenName] = csrfHash;
                d.statusFilter = $('#statusFilter').val();
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
                data: 'od_number',
                render: function (data, type, row) {
                    let count = row.item_count;

                    let badge = '';
                    if (!openedOrders.includes(data)) {
                        badge = `<span class="badge bg-primary ms-2 notification_order">${count}</span>`;
                    }

                    return `<span>${data}</span> ${badge}`;
                }
            },
            {
                data: 'cust_Name',
                render: function (data) {
                    if (!data) return 'N/A';
                    return data.length > 25 ? data.substring(0, 25) + '...' : data;
                }
            },
            { data: 'add_Email' },
            { data: 'add_Phone' },

            { data: 'od_createdon' },
            {
                data: 'od_Status',
                render: function (data) {
                    return getStatusBadge(data);
                }

            },
            {
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        columnDefs: [
            { targets: [6, 7], orderable: false, searchable: false }
        ],
        createdRow: function (row, data) {

            $(row).css('cursor', 'pointer');

            if (!openedOrders.includes(data.od_number)) {
                $(row).addClass("unopened-row");
            }

            $(row).on('click', function () {

                if (!openedOrders.includes(data.od_number)) {
                    openedOrders.push(data.od_number);
                    localStorage.setItem("openedOrders", JSON.stringify(openedOrders));
                }

                table.ajax.reload(null, false);
                window.location.href = baseUrl + "admin/orders/view/" + data.od_number;
            });
        }
    });
    $('#statusFilter').on('change', function () {
        table.ajax.reload();
    });
</script>