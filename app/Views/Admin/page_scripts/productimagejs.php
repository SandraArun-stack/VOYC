<script>
    var baseUrl = "<?= base_url() ?>";
    // alert('haii')
    // debugger;
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
            data: function (d) {
                d[csrfTokenName] = csrfHash;
                 d.pr_id = pr_id;
            }
        },
 
        columns: [
            { // Serial number
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'pr_Name',
                render: function (data, type, row) {
                    return data.length > 20 ? data.substring(0, 20) + '...' : data;
                }
            },
            // Product Name
            { data: 'sizes' },          // Sizes
            { // Colors as colored boxes
                data: 'colors',
                render: function (data) {
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
            { data: 'stocks' },         // Stock
            { data: 'reset_stocks' },   // Reset Stock
            { data: 'prices' },         // Prices
            { data: 'status_switch' },  // Status toggle
            { data: 'actions' }         // Action buttons
        ],
        columnDefs: [
            {
                targets: [1, 2, 3, 4, 5, 6, 7, 8],
                orderable: false,
                searchable: false
            }
        ]
    });
 
 
    //add product
 
    $('#productImageForm').submit(function (e) {
    e.preventDefault();
 
    var form = this;
    var formData = new FormData(form);
    formData.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");
 
    $.ajax({
        url: baseUrl + "admin/productimage/save",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            $('html, body').animate({ scrollTop: 0 }, 'fast');
 
            if (response.status === 'success') {
                $('#messageBox')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .text(response.msg || 'Product created successfully!')
                    .show();
 
                setTimeout(function () {
                    $('#messageBox').fadeOut('slow', function () {
                        $(this).empty().show(); // reset for next use
                    });
                    window.location.href = baseUrl + "admin/productimage/";
                }, 3000);
            } else {
                $('#messageBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text(response.msg || 'Please fill all the data.')
                    .show();
 
                setTimeout(function () {
                    $('#messageBox').fadeOut('slow', function () {
                        $(this).empty().show(); // reset for next use
                    });
                }, 3000);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            $('#messageBox')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .text('Something went wrong! Please try again.')
                .show();
 
            setTimeout(function () {
                $('#messageBox').fadeOut('slow', function () {
                    $(this).empty().show();
                });
            }, 3000);
        }
    });
});
 
 
    $('#media_files').on('change', function (event) {
        $('#imagePreview').empty();
        const files = event.target.files;
 
        if (files.length > 0) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
 
                    reader.onload = function (e) {
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
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Deleted!', response.msg, 'success');
 
                            let table = $('#productList').DataTable();
                            let currentPage = table.page();
 
                            table.ajax.reload(function () {
                                if (table.data().count() === 0 && currentPage > 0) {
                                    table.page(currentPage - 1).draw(false);
                                }
                            }, false);
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }
 
 
</script>
 
<script>
    let colorIndex = 0;
 
    function createColorBlock(index) {
        return `
        <div class="card p-3 mb-3 color-block" data-index="${index}">
            <h5>Color ${index + 1}
                <button type="button" class="btn btn-danger btn-sm float-right remove-color">Remove</button>
            </h5>
           
            <!-- Color Picker -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Color Code</label>
                <div class="col-sm-4">
                    <input type="color" class="form-control" name="colors[${index}][color]" required>
                </div>
            </div>
 
            <!-- Sizes Checkboxes -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Available Sizes</label>
                <div class="col-sm-10">
                    <label><input type="checkbox" name="colors[${index}][sizes][]" value="S"> S</label>
                    <label><input type="checkbox" name="colors[${index}][sizes][]" value="M"> M</label>
                    <label><input type="checkbox" name="colors[${index}][sizes][]" value="L"> L</label>
                    <label><input type="checkbox" name="colors[${index}][sizes][]" value="XL"> XL</label>
               <label><input type="checkbox" name="colors[${index}][sizes][]" value="XXL"> XL</label>
                    </div>
            </div>
 
            <!-- Multiple Images Upload -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Upload Thumbnail (min 1)</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control image-input" name="colors[${index}][images][]" multiple accept="image/*" required>
                    <div class="image-preview mt-2" style="display:flex; flex-wrap:wrap; gap:10px;"></div>
                </div>

                <div class="form-group row">
                <label class="col-sm-2 col-form-label">Upload Sideimages (min 1)</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control image-input" name="colors[${index}][images][]" multiple accept="image/*" required>
                    <div class="image-preview mt-2" style="display:flex; flex-wrap:wrap; gap:10px;"></div>
                </div>
            </div>
            </div>
        </div>
    `;
    }
 
 
    $(document).ready(function () {
        // Add new color block
        // $('#addColorBtn').click(function () {
        //     $('#colorContainer').append(createColorBlock(colorIndex));
        //     colorIndex++;
        // });
 
        $("#addColorBtn").click(function () {
    colorIndex++;
    let newGroup = $(".color-group").first().clone();
    newGroup.attr('data-index', colorIndex);
    newGroup.find("h5").text("Color Group " + (colorIndex + 1));
 
    // Update name attributes for all inputs
    newGroup.find("input, select, textarea").each(function () {
        let name = $(this).attr("name");
        if (name) {
            name = name.replace(/\[0\]/g, "[" + colorIndex + "]");
            $(this).attr("name", name);
        }
        $(this).val("");
        $(this).prop("checked", false);
    });
 
    newGroup.find("input[type=number]").prop("disabled", true);
    newGroup.find(".remove-color").removeClass("d-none");
    $("#colorGroupsContainer").append(newGroup);
});
 
 
        // Remove color block
        $(document).on('click', '.remove-color', function () {
            $(this).closest('.color-block').remove();
        });
 
        // Preview images for each color
        $(document).on('change', '.image-input', function () {
            const previewDiv = $(this).siblings('.image-preview');
            previewDiv.empty();
            const files = this.files;
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewDiv.append('<img src="' + e.target.result + '" style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;border-radius:5px;">');
                    }
                    reader.readAsDataURL(files[i]);
                }
            }
        });
    });
</script>
 
<!-- Script for dynamic color groups -->
<script>
    $(document).ready(function () {
        let colorIndex = 1;
 
        // Enable/Disable inputs when checkbox clicked
        $(document).on("change", ".size-checkbox", function () {
            let container = $(this).closest(".col-md-3");
            container.find("input[type=number]").prop("disabled", !this.checked);
        });
 
        // Add new color group
        $("#addColorBtn").click(function () {
            colorIndex++;
            let newGroup = $(".color-group").first().clone();
            newGroup.find("h5").text("Color Group " + colorIndex);
            newGroup.find("input").val(""); // reset values
            newGroup.find(".form-check-input").prop("checked", false);
            newGroup.find("input[type=number]").prop("disabled", true);
            newGroup.find(".remove-color").removeClass("d-none");
            $("#colorGroupsContainer").append(newGroup);
        });
 
        // Remove color group
        $(document).on("click", ".remove-color", function () {
            $(this).closest(".color-group").remove();
        });
    });
</script>