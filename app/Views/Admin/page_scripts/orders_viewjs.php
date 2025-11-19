<script>

    $(document).on('change', '.orderStatusSelect', function () {
         let originalStatus = '';

        let currentStatus = $(this).val();
        let od_number = $(this).data('id');
        // let tracker = $('#trackingUrl').val();

        if (currentStatus === originalStatus) {
            $('#alertBox')
                .removeClass()
                .addClass('alert alert-warning p-2')
                .text("No change in status to update.")
                .fadeIn()
                .delay(2000)
                .fadeOut();
            return;
        }

        $.ajax({
            url: '<?= base_url('admin/orders/orderStatusUpdation/') ?>' + od_number,
            type: 'POST',
            dataType: 'json',
            data: {
                // tracker: tracker,
                status: currentStatus
            },

            success: function (response) {
                if (response.status === true) {
                    $('#alertBox')
                        .removeClass()
                        .addClass('alert alert-success p-2')
                        .text(response.message)
                        .fadeIn()
                        .delay(2000)
                        .fadeOut();

                    originalStatus = currentStatus;

                    // 🔥 Update all dropdowns having same od_number
                    $('.orderStatusSelect[data-id="' + od_number + '"]').val(currentStatus);
                } else {
                    $('#alertBox')
                        .removeClass()
                        .addClass('alert alert-danger p-2')
                        .text(response.message)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();
                }
            },

            error: function (xhr) {
                $('#alertBox')
                    .removeClass()
                    .addClass('alert alert-danger p-2')
                    .text('Failed to Update Status: ' + xhr.responseText)
                    .fadeIn()
                    .delay(3000)
                    .fadeOut();
            }
        });

    });


    // });


    $(document).ready(function () {
        var orderId = <?= json_encode($od_number) ?>;
        console.log("Order ID:", orderId);

        $.ajax({
            url: '<?= base_url('admin/orders/view/') ?>' + orderId,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                console.log("AJAX response:", res);

                if (res.status) {

                    const orders = res.data.orders;
                    const customer = res.data.customer;
                    const address = res.data.address;

                    // ----------------------------
                    // PRODUCT TABLE
                    // ----------------------------
                    let productTable = `
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Customized</th>
                                <th>Price Per piece</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                    let grandTotal = 0;

                    orders.forEach(function (o) {
                        grandTotal += parseFloat(o.od_Grand_Total);

                        productTable += `
                        <tr>
                            <td>${o.pr_Code}</td>
                            <td>${o.pr_Name}</td>
                            <td>${o.od_Quantity}</td>
                             <td>
                                ${(!o.design_Id || o.design_Id == 0)
                                ? `
                                <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                                    <span>No</span>
                                    
                                </div>
                                `
                                : `
                                <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                                    <span>Yes</span>
                                    <button class="btn btn-warning btn-sm viewDesignBtn" data-id="${o.design_Id}">
                                        View Design
                                    </button>
                                </div>
`
                            }

                            </td>
                            <td>${Math.round(o.od_Selling_Price)}</td>
                             <td>${o.od_Quantity * Math.round(o.od_Selling_Price)}</td>

                        </tr>
                    `;
                    });

                    // -------- Add Grand Total Row --------
                    productTable += `
                        <tr style="font-weight:bold; background:#f7f7f7;">
                            <td colspan="5" class="text-end">Grand Total:</td>
                            <td>${Math.round(grandTotal)}</td>
                        </tr>
                    </tbody>
                </table>`;

                    $('#order-details').html(productTable);

                    // ----------------------------
                    // CUSTOMER DETAILS
                    // ----------------------------
                    $('#customer-details').html(`
                    <p><strong>Name:</strong> ${customer.cust_Name}</p>
                    <p><strong>Email:</strong> ${customer.cust_Email}</p>
                    <p><strong>Phone:</strong> ${customer.cust_Phone || 'N/A'}</p>
                    <p><strong>Date of Birth:</strong> ${customer.cust_Dob || 'N/A'}</p>
                `);

                    // ----------------------------
                    // DELIVERY ADDRESS
                    // ----------------------------
                    $('#delivery-details').html(`
                    <p><strong>Name:</strong> ${address.add_Name}</p>
                    <p>
                        ${address.add_BuldingNo || ''} ${address.add_Street || ''},<br>
                        ${address.add_Landmark || ''},<br>
                        ${address.add_City || ''}, ${address.add_State || ''},<br>
                        ${address.add_Pincode || ''}
                    </p>
                    <p><strong>Phone:</strong> ${address.add_Phone}</p>
                    <p><strong>Email:</strong> ${address.add_Email}</p>
                `);
                }
            }
        });
    });



    $(document).on('click', '.viewDesignBtn', function () {
        let design_Id = $(this).data('id');

        $("#designModalBody").html("<p class='text-center'>Loading...</p>");
        $("#designModal").modal("show");

        $.ajax({
            url: "<?= base_url('admin/getDesign') ?>",
            type: "POST",
            data: { design_Id: design_Id },
            dataType: "json",
            success: function (res) {
                if (!res.status) {
                    $("#designModalBody").html("<p class='text-danger'>No design found!</p>");
                    return;
                }

                let d = res.data;
                let html = `<div class="row"><div class="col-md-12">
                <div class=" mb-3">
                    <div class="">
                        <div class='d-flex flex-wrap gap-3 p-3'>`;

                // --- Normal design images ---
                Object.keys(d)
                    .filter(k => k !== 'User_Upload_Image')
                    .forEach(function (key) {
                        if (d[key] !== null && d[key] !== "") {
                            html += `
                        <div class="text-center">
                            <img src="<?= base_url('uploads/designs/') ?>${d[key]}" 
                                 class="img-fluid rounded shadow-sm" style="max-width:150px;">
                            <p>${key.replace('_', ' ')}</p>
                        </div>`;
                        }
                    });

                html += `</div>`;

                // --- User Uploaded Images ---
                if (d.User_Upload_Image && d.User_Upload_Image.length > 0) {
                    html += `<div class="d-flex flex-wrap gap-3 p-3">`;
                    d.User_Upload_Image.forEach(function (img) {
                        html += `
                    <div class="text-center">
                        <img src="<?= base_url('uploads/designs/') ?>${img}" 
                             class="img-fluid rounded shadow-sm" style="max-width:150px;">
                    </div>`;
                    });
                    html += `</div>`;
                }

                html += `
                <div class="p-3">
                    <button class="btn btn-primary" id="DownloadCustomImages">Download</button>
                </div>
            </div></div></div></div>`;

                $("#designModalBody").html(html);
            }
        });
    });


    $(document).on('click', '#backToOrders', function () {
        window.location.href = "<?= base_url('admin/orders') ?>";
    });

    $(document).ready(function () {
        $(document).on('click', '#DownloadCustomImages', function () {

            var images = [];
            $("#designModalBody img").each(function () {
                var src = $(this).attr('src');
                if (src && src.trim() !== '') {
                    images.push(src);
                }
            });

            if (images.length === 0) {
                alert('No images available to download.');
                return;
            }

            alert(images.length + ' image(s) will be downloaded.');

            // Loop through and trigger download for each image
            $.each(images, function (index, url) {
                var fileName = url.split('/').pop().split('?')[0]; // Extract filename
                fetch(url)
                    .then(response => response.blob())
                    .then(blob => {
                        var a = document.createElement('a');
                        var blobUrl = window.URL.createObjectURL(blob);
                        a.href = blobUrl;
                        a.download = fileName || 'image_' + (index + 1) + '.jpg';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(blobUrl);
                    })
                    .catch(err => console.error('Failed to download:', url, err));
            });
        });
    });




</script>