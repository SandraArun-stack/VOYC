<script>
    var baseUrl = "<?= base_url() ?>";
    var csrfTokenName = "<?= csrf_token() ?>";
    var csrfHash = "<?= csrf_hash() ?>";

    $('#productList').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: baseUrl + "admin/product/List",
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
            {
                data: 'pr_Name',
                render: function (data, type, row) {
                    return data.length > 20
                        ? '<span title="' + data + '">' + data.substring(0, 20) + '...' + '</span>'
                        : data;
                }
            },
              {
                data: 'pr_Code',
                render: function (data, type, row) {
                    return data.length > 20
                        ? '<span title="' + data + '">' + data.substring(0, 20) + '...' + '</span>'
                        : data;
                }
            },
            { data: 'pr_Stock' },
            { data: 'status_switch' },
            { data: 'actions' }
        ],
        columnDefs: [
            {
                targets: [4, 5], 
                orderable: false,
                searchable: false
            }
        ]
    });

    //Add product

    var baseUrl = "<?= base_url() ?>";

    $('#productSubmit').click(function (e) {
        e.preventDefault();
        var url = baseUrl + "admin/product/save";
        //debugger;
        $.post(url, $('#createProduct').serialize(), function (response) {
            $('html, body').animate({
                scrollTop: 0
            }, 'fast');

            if (response.status == 1) {
                $('#messageBox')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .text(response.msg || 'Product Created Successfully!')
                    .show();

                setTimeout(function () {
                    window.location.href = baseUrl + "admin/product/";
                }, 3000);
            } else {

                let message = response.message || 'Please Fill All The Required Fields.';
                if (response.field === 'product_name') {
                    message = response.message;
                }

                $('#messageBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text(message)
                    .show();
            }

            setTimeout(function () {
                $('#messageBox').empty().hide();
            }, 3000);
        }, 'json');
    });



    //Category and Subactegory listed the dropdown

    $(document).ready(function () {
        var selectedCatId = "<?= $product['cat_Id'] ?? '' ?>";
        var selectedSubId = "<?= $product['sub_Id'] ?? '' ?>";

        // Function to load subcategories based on category ID
        function loadSubcategories(catId, preselectSubId = '') {
            var subSelect = $('#subcategoryName');
            var messageElement = $('#noSubcategoryMsg');
            subSelect.empty();

            if (!catId) {
                subSelect.append('<option value="">-- Select Subcategory --</option>');
                return;
            }

            $.ajax({
                url: baseUrl + "admin/product/get-subcategories",
                type: "POST",
                data: {
                    cat_id: catId
                },
                dataType: "json",
                success: function (response) {
                    if (response.length === 0) {
                        subSelect.append('<option value="">-- No Subcategory Available --</option>');
                        if (messageElement) messageElement.show();
                    } else {
                        subSelect.append('<option value="">-- Select Subcategory --</option>');

                        $.each(response, function (index, sub) {
                            let selected = (sub.sub_Id == preselectSubId) ? 'selected' : '';
                            subSelect.append('<option value="' + sub.sub_Id + '" ' + selected +
                                '>' + sub.sub_Category_Name + '</option>');
                        });

                        if (messageElement) messageElement.hide();
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching subcategories:", xhr.responseText);
                }
            });
        }

        // Prepopulate (on edit)
        if (selectedCatId) {
            $('#categoryName').val(selectedCatId);
            loadSubcategories(selectedCatId, selectedSubId);
        }

        // Handle category change (on add)
        $('#categoryName').on('change', function () {
            var catId = $(this).val();
            loadSubcategories(catId);
        });
    });

    //File Upload



    //Open the modal
    function openProductModal(productId, productName) {
        document.getElementById('productId').value = productId;
        document.getElementById('productName').textContent = productName;
        loadProductImages(productId);
        $('#exampleModal').modal('show');
    }

    //modal does' not close properly
    $(document).ready(function () {
        $('#exampleModal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
            $('body').css('overflow', 'auto');
            $('.modal-backdrop').remove();
        });
    });
    //modal does' not close properly
    $(document).ready(function () {
        $('#videoModal').on('hidden.bs.modal', function () {
            $('#videoPreview').empty();
            $('body').removeClass('modal-open');
            $('body').css('overflow', 'auto');
            $('.modal-backdrop').remove();
        });
    });

    //Delete whole Product
    function confirmDelete(prId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this Product?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo base_url('admin/product/delete/'); ?>" + prId,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
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


    // function openvideoModal(productVideoId, productsName) {
    //     document.getElementById('productVideoId').value = productVideoId;
    //     document.getElementById('productsName').textContent = productsName;
    //     $('#videoModal').modal('show');
    // }

    $('#videoModal').on('hidden.bs.modal', function () {
        $('#filevideo').val('');
        $('#videoUploadForm')[0].reset();
    });




    // vide AJAX Upload
    $('#filevideo').on('change', function () {
        var file = this.files[0];

        const showMessage = (msg, type = 'danger') => {
            $('#UploadVideo')
                .removeClass('alert-danger alert-success')
                .addClass('alert alert-' + type)
                .html(msg)
                .fadeIn();

            setTimeout(() => {
                $('#UploadVideo').fadeOut();
            }, 3000);
        };

        if (file) {
            var maxSizeMB = 10;
            var allowedTypes = ['video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime', 'video/x-matroska'];

            if (file.size > maxSizeMB * 1024 * 1024) {
                showMessage('Your video size is too large. Please upload a video within 10MB.');
                this.value = '';
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                showMessage('Only video files are allowed. Please upload a valid video format.');
                this.value = '';
                return;
            }
        }

        var formData = new FormData($('#videoUploadForm')[0]);

        // Show progress bar
        $('#uploadProgressContainer').show();
        $('#uploadProgressBar').css('width', '0%').attr('aria-valuenow', 0).text('0%');

        $.ajax({
            url: '<?= base_url('admin/product/video') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,

            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        var percentComplete = Math.round((e.loaded / e.total) * 100);
                        $('#uploadProgressBar').css('width', percentComplete + '%')
                            .attr('aria-valuenow', percentComplete)
                            .text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },

            success: function (response) {
                $('#uploadProgressContainer').hide();

                if (response.status === 'success') {
                    showMessage(response.message || 'Video uploaded successfully!', 'success');
                    const productId = $('#productVideoId').val();
                    const productName = $('#productVideoName').val();
                    openvideoModal(productId, productName);
                } else {
                    showMessage(response.message || 'Upload failed.');
                }
            },

            error: function (xhr) {
                $('#uploadProgressContainer').hide();
                showMessage('Upload failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });

    //Load video on modal
    function openvideoModal(productId, productName) {
        $('#productVideoId').val(productId);
        $('#productVideoName').val(productName);
        $('#productsName').text(productName);

        // Clear previous state
        $('#filevideo').val(''); // Clear file input
        $('#videoUploadForm')[0].reset(); // Reset the form

        $('#videoPreview').empty();

        $.ajax({
            url: '<?= base_url('admin/product/getVideo') ?>',
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function (response) {
                if (response.status === 'success' && response.video) {
                    const videoUrl = '<?= base_url('uploads/productmedia/') ?>' + response.video;
                    const videoElement = `
                    <div class="position-relative video-file d-inline-block">
                        <video width="300" height="200" controls>
                            <source src="${videoUrl}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <span 
                            class="delete-video-btn" 
                            data-product-id="${productId}" 
                            data-video-name="${response.video}" 
                            title="Delete this video"
                            style="position: absolute;  right: -3px; top: -4px; cursor: pointer; color: red; font-size: 17px;">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                `;
                    $('#videoPreview').html(videoElement).show();
                    $('#uploadSection').hide();
                } else {
                    $('#videoPreview').hide();
                    $('#uploadSection').show();
                }

                $('#videoModal').modal('show');
            },
            error: function () {
                $('#videoPreview').hide();
                $('#uploadSection').show();
                $('#videoModal').modal('show');
            }
        });
    }



    //Delete video single video

    $(document).on('click', '.delete-video-btn', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const videoName = $(this).data('video-name');

        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this video?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin/product/deletevideo') ?>',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        video_name: videoName
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#videoPreview').empty().hide();
                            $('#uploadSection').show();

                            Swal.fire('Deleted!', 'Video has been deleted.', 'success');
                        } else {
                            Swal.fire('Error!', 'Failed to delete the video.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong while deleting the video.', 'error');
                    }
                });
            }
        });
    });


    //Active Inactive status Change

    var baseUrl = "<?= base_url() ?>";

    $(document).on('change', '.checkactive', function () {
        let prId = $(this).attr('id').split('-')[1]; // e.g., id="check-3" → prId=3
        let status = $(this).is(':checked') ? 1 : 2;

        $.ajax({
            url: baseUrl + 'admin/product/status', // Make sure route maps to controller
            type: 'POST',
            dataType: 'json',
            data: {
                pr_Id: prId,
                pr_Status: status
            },
            success: function (response) {
                if (response.success) {
                    $('#messageBox')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .text(response.message)
                        .show();
                } else {
                    $('#messageBox')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .text(response.message)
                        .show();
                }
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');


                setTimeout(() => {
                    $('#messageBox').fadeOut();
                }, 2000);
            },
            error: function (xhr, status, error) {
                $('#messageBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text('AJAX error: ' + error)
                    .show();
            }
        });
    });

    //Product popup
    document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (e) {
            if (e.target.closest('.view-large-image')) {
                e.preventDefault();
                const imageUrl = e.target.closest('.view-large-image').getAttribute('data-image');
                document.getElementById('largeImage').setAttribute('src', imageUrl);
            }
        });
    });
    function redirectToProductImage($pr_id) {
    window.location.href = baseUrl + "admin/productimage/viewimage/" + $pr_id;
}


</script>