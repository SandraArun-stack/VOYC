<script>
var baseUrl = "<?= base_url() ?>";
var csrfTokenName = "<?= csrf_token() ?>";
var csrfHash = "<?= csrf_hash() ?>";
var pr_id = $('#pr_id').val();

$('#productList').DataTable({

    processing: true,
    serverSide: true,
    order: [],
    ajax: {
        url: baseUrl + "admin/productimage/ajaxList", // match your route
        type: "POST",
        data: function(d) {
            d[csrfTokenName] = csrfHash;
            d.pr_id = pr_id;
        }
    },

    columns: [{ // Serial number
            data: null,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
            orderable: false,
            searchable: false
        },
        {
            data: 'pr_Name',
            render: function(data, type, row) {
                return data.length > 20 ? data.substring(0, 20) + '...' : data;
            }
        },
        // Product Name
        {
            data: 'sizes'
        }, // Sizes
        { // Colors as colored boxes
            data: 'colors',
            render: function(data) {
                if (!data || data == 'N/A') return '-N/A-';
                let html = '';
                if (Array.isArray(data)) {
                    data.forEach(c => {
                        if (c) html += `<span title="${c}" style="display:inline-block;width:25px;height:25px;background:${c};border:1px solid #ccc;margin-right:5px;"></span>`;
                    });
                } else {
                    html += `<span title="${data}" style="display:inline-block;width:25px;height:25px;background:${data};border:1px solid #ccc;margin-right:5px;"></span>`;
                }
                return html;
            }
        },
        {
            data: 'stocks'
        }, // Stock
        {
            data: 'reset_stocks'
        }, // Reset Stock
        {
            data: 'prices'
        }, // Prices
        {
            data: 'status_switch'
        }, // Status toggle
        {
            data: 'actions'
        } // Action buttons
    ],
    columnDefs: [{
        targets: [1, 2, 3, 4, 5, 6, 7, 8],
        orderable: false,
        searchable: false
    }]
});


//add product

// $('#productImageForm').submit(function(e) {
//     e.preventDefault();

//     $(this).find(':input:disabled').prop('disabled', false);

//     var form = this;
//     var formData = new FormData(form);
//     formData.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");

//     $.ajax({
//         url: baseUrl + "admin/productimage/save",
//         type: "POST",
//         data: formData,
//         contentType: false,
//         processData: false,
//         dataType: 'json',
//         success: function(response) {
//             $('html, body').animate({
//                 scrollTop: 0
//             }, 'fast');

//             if (response.status === 'success') {
//                 $('#messageBox')
//                     .removeClass('alert-danger')
//                     .addClass('alert-success')
//                     .text(response.msg || 'Product image created successfully!')
//                     .show();

//                 setTimeout(function() {
//                     $('#messageBox').fadeOut('slow', function() {
//                         $(this).empty().hide(); // reset for next use
//                     });
//                     if (response.redirect) {
//                         window.location.href = response.redirect;
//                     }
//                 }, 3000);
//             } else {
//                 $('#messageBox')
//                     .removeClass('alert-success')
//                     .addClass('alert-danger')
//                     .text(response.msg || 'Please fill all the data.')
//                     .show();

//                 setTimeout(function() {
//                     $('#messageBox').fadeOut('slow', function() {
//                         $(this).empty().hide(); // reset for next use
//                     });
//                 }, 3000);
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error("AJAX Error:", error);
//             $('#messageBox')
//                 .removeClass('alert-success')
//                 .addClass('alert-danger')
//                 .text('Something went wrong! Please try again.')
//                 .show();

//             setTimeout(function() {
//                 $('#messageBox').fadeOut('slow', function() {
//                     $(this).empty().hide();
//                 });
//             }, 3000);
//         }
//     });
// });

$('#productImageForm').submit(function(e) {
    e.preventDefault();

    const $form = $(this);
    const $saveBtn = $form.find('button[type="submit"], #saveBtn'); // adjust selector if your button has a specific ID

    // Disable the Save button immediately
    $saveBtn.prop('disabled', true).text('Save');

    $form.find(':input:disabled').prop('disabled', false);

    var formData = new FormData(this);
    formData.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");

    $.ajax({
        url: baseUrl + "admin/productimage/save",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            $('html, body').animate({ scrollTop: 0 }, 'fast');

            if (response.status === 'success') {
                $('#messageBox')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .text(response.msg || 'Product image created successfully!')
                    .show();

                setTimeout(function() {
                    $('#messageBox').fadeOut('slow', function() {
                        $(this).empty().hide();
                    });
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                }, 3000);
            } else {
                $('#messageBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text(response.msg || 'Please fill all the data.')
                    .show();

                // Re-enable Save button if failed
                $saveBtn.prop('disabled', false).text('Save');

                setTimeout(function() {
                    $('#messageBox').fadeOut('slow', function() {
                        $(this).empty().hide();
                    });
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            $('#messageBox')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .text('Something went wrong! Please try again.')
                .show();

            // Re-enable Save button if AJAX error
            $saveBtn.prop('disabled', false).text('Save');

            setTimeout(function() {
                $('#messageBox').fadeOut('slow', function() {
                    $(this).empty().hide();
                });
            }, 3000);
        }
    });
});


$('#media_files').on('change', function(event) {
    $('#imagePreview').empty();
    const files = event.target.files;

    if (files.length > 0) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = $('<img />', {
                        src: e.target.result,
                        class: 'img-thumbnail',
                        width: 100,
                        height: 100,
                        style: 'object-fit: cover;'
                    });
                    $('#imagePreview').append(img);
                };

                reader.readAsDataURL(file);
            }
        });
    }
});

function confirmDelete(priId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this Product Image?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseUrl + 'admin/productimage/delete/' + priId,
                method: 'POST',
                data: {
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.msg, 'success');

                        let table = $('#productList').DataTable();
                        let currentPage = table.page();

                        table.ajax.reload(function() {
                            if (table.data().count() === 0 && currentPage > 0) {
                                table.page(currentPage - 1).draw(false);
                            }
                        }, false);
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            });
        }
    });
}
</script>

<script>
$(document).ready(function() {
    let colorIndex = $(".color-group").length || 0; // Start from existing groups count

    // Function to create a new color group HTML
    function createColorBlock(index) {
        return `
        <div class="card mb-3 color-group" data-index="${index}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Color Group ${index + 1}</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-color ${index === 0 ? 'd-none' : ''}">Remove Color</button>
                </div>

                <!-- Color Picker -->
                <div class="mb-3">
                    <label class="form-label">Choose Color:</label>
                    <input type="color" class="form-control form-control-color" name="colors[${index}][color]" value="#000000">
                </div>

                <!-- Sizes -->
                <div class="row g-3 mb-3">
                    ${['S','M','L','XL','XXL'].map(size => `
                        <div class="col-md-2">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input size-checkbox" name="colors[${index}][sizes][]" value="${size}">
                                <label class="form-check-label fw-bold">${size}</label>
                            </div>
                            <input type="hidden" name="colors[${index}][prv_id][${size}]" value="">
                            <input type="number" class="form-control mb-1" placeholder="Price for ${size}" name="colors[${index}][prices][${size}]" disabled>
                            <input type="number" class="form-control mb-1" placeholder="Stock for ${size}" name="colors[${index}][stock][${size}]" disabled>
                            <input type="number" class="form-control" placeholder="Reset Stock for ${size}" name="colors[${index}][reset_stock][${size}]" disabled>
                        </div>
                    `).join('')}
                </div>

                <!-- Thumbnail Upload -->
                <div class="mb-3">
                    <label class="form-label">Upload Thumbnail (min 1)</label>
                    <input type="file" class="form-control image-input" name="colors[${index}][images][]" multiple accept="image/*">
                    <div class="image-preview mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <!-- Side Images Upload -->
                <div class="mb-3">
                    <label class="form-label">Upload Side Images (min 1)</label>
                    <input type="file" class="form-control image-input" name="colors[${index}][side_image][]" multiple accept="image/*">
                    <div class="image-preview mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <!-- Sleev Images Upload -->
                <div class="mb-3">
                    <label class="form-label">Upload Sleev Images (min 1)</label>
                    <input type="file" class="form-control image-input" name="colors[${index}][sleev_image][]" multiple accept="image/*">
                    <div class="image-preview mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <!-- Hidden pri_id -->
                <input type="hidden" name="colors[${index}][pri_id]" value="">
            </div>
        </div>`;
    }

    // Add new color group
    $("#addColorBtn").click(function() {
        let newHtml = createColorBlock(colorIndex);
        $("#colorGroupsContainer").append(newHtml);
        colorIndex++;
    });

    // Remove color group
    $(document).on("click", ".remove-color", function() {
        $(this).closest(".color-group").remove();
    });

    // Enable/Disable size inputs
    $(document).on("change", ".size-checkbox", function() {
        let container = $(this).closest(".col-md-2");
        container.find("input[type=number]").prop("disabled", !this.checked);
    });

    // Preview images (both thumbnail & side images)
    $(document).on("change", ".image-input", function() {
        const previewDiv = $(this).siblings(".image-preview");
        previewDiv.empty();
        const files = this.files;

        Array.from(files).forEach(file => {
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.append(`
                        <img src="${e.target.result}" style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;border-radius:5px;">
                    `);
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>




<script>
    $(document).ready(function() {
        let colorIndex = 1;

        // Enable/Disable inputs when checkbox clicked
        $(document).on("change", ".size-checkbox", function() {
            let container = $(this).closest(".col-md-3");
            container.find("input[type=number]").prop("disabled", !this.checked);
        });

        // Remove color group
        $(document).on("click", ".remove-color", function() {
            $(this).closest(".color-group").remove();
        });
    }); 
</script>