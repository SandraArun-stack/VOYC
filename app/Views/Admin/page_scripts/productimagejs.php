<script>
$(document).ready(function() {
    $('#productList').DataTable({
        "processing": true,
        "serverSide": false,
        "searching": true,
        "paging": true,
        "ordering": true,
        "info": true,

    });
});

//Add product

var baseUrl = "<?= base_url() ?>";

$('#productimageSubmit').click(function(e) {
    e.preventDefault();

    var form = $('#createProductImage')[0];
    var formData = new FormData(form);
    // Append CSRF token manually
    formData.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");

    $.ajax({
        url: baseUrl + "productimage/save",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            $('html, body').animate({
                scrollTop: 0
            }, 'fast');

            if (response.status == 1) {
                $('#messageBox')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .text(response.msg || 'Product created successfully!')
                    .show();
                setTimeout(function() {
                    window.location.href = baseUrl + "productimage/";
                }, 3000);
            } else {
                $('#messageBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text(response.msg || 'Please fill all the data.')
                    .show();
            }
            setTimeout(function() {
                $('#messageBox').empty().hide();
            }, 2000);
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
                <label class="col-sm-2 col-form-label">Upload Images (min 1)</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control image-input" name="colors[${index}][images][]" multiple accept="image/*" required>
                    <div class="image-preview mt-2" style="display:flex; flex-wrap:wrap; gap:10px;"></div>
                </div>
            </div>
        </div>
    `;
}

$(document).ready(function(){
    // Add new color block
    $('#addColorBtn').click(function(){
        $('#colorContainer').append(createColorBlock(colorIndex));
        colorIndex++;
    });

    // Remove color block
    $(document).on('click', '.remove-color', function(){
        $(this).closest('.color-block').remove();
    });

    // Preview images for each color
    $(document).on('change', '.image-input', function(){
        const previewDiv = $(this).siblings('.image-preview');
        previewDiv.empty();
        const files = this.files;
        for (let i=0; i<files.length; i++){
            if(files[i].type.startsWith('image/')){
                const reader = new FileReader();
                reader.onload = function(e){
                    previewDiv.append('<img src="'+e.target.result+'" style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;border-radius:5px;">');
                }
                reader.readAsDataURL(files[i]);
            }
        }
    });
});
</script>


<script>
$(document).ready(function(){

    let colorGroupCount = 0;

    // Function to create a color group
// Function to create a color group
function createColorGroup(index){
    return `
        <div class="color-group card p-3 mb-3" data-index="${index}">
            <h5>Color Group ${index+1} 
                <button type="button" class="btn btn-danger btn-sm float-right remove-color-group">Remove Color</button>
            </h5>

            <!-- Color Picker -->
            <div class="form-group col-md-6">
                <label>Choose Color:</label>
                <input type="color" class="form-control col-md-2" name="colors[${index}][color]" required>
            </div>

            <!-- Sizes with Price -->
            <div class="form-group col-md-12">
                <label>Available Sizes with Price:</label>
                <div class="row">
                    <div class="col-md-3">
                        <label><input type="checkbox" name="colors[${index}][sizes][]" value="S"> S</label>
                        <input type="number" class="form-control mt-1" name="colors[${index}][prices][S]" placeholder="Price for S">
                    </div>
                    <div class="col-md-3">
                        <label><input type="checkbox" name="colors[${index}][sizes][]" value="M"> M</label>
                        <input type="number" class="form-control mt-1" name="colors[${index}][prices][M]" placeholder="Price for M">
                    </div>
                    <div class="col-md-3">
                        <label><input type="checkbox" name="colors[${index}][sizes][]" value="L"> L</label>
                        <input type="number" class="form-control mt-1" name="colors[${index}][prices][L]" placeholder="Price for L">
                    </div>
                    <div class="col-md-3">
                        <label><input type="checkbox" name="colors[${index}][sizes][]" value="XL"> XL</label>
                        <input type="number" class="form-control mt-1" name="colors[${index}][prices][XL]" placeholder="Price for XL">
                    </div>
                    <div class="col-md-3">
                        <label><input type="checkbox" name="colors[${index}][sizes][]" value="XXL"> XXL</label>
                        <input type="number" class="form-control mt-1" name="colors[${index}][prices][XXL]" placeholder="Price for XXL">
                    </div>
                </div>
            </div>

            <!-- File Inputs -->
            <div class="fileInputContainer col-md-6" data-color-index="${index}">
                <div class="file-input-row mb-2" data-input-index="0">
                    <input type="file" class="form-control image-input" name="colors[${index}][images][]" multiple accept="image/*">
                </div>
            </div>

            <!-- Image Preview -->
            <div class="imagePreview" style="display:flex; flex-wrap: wrap; gap:10px;"></div>
        </div>
    `;
}





    // Add first color group initially
    addColorGroup();

    // Add Color Group Button
    $('#addColorBtn').click(function(){
        addColorGroup();
    });

    function addColorGroup(){
        const index = colorGroupCount++;
        $('#colorGroupsContainer').append(createColorGroup(index));
    }

    // Remove Color Group
 $(document).on('click', '.remove-color-group', function () {
    // Count color groups
    let groupsCount = $('.color-group').length;

    if (groupsCount <= 1) {
        // Show error if user tries to remove the last one
        showMessage('At least one color group is required.', 'error');
        return false;
    }

    // Otherwise remove
    $(this).closest('.color-group').remove();
});


    // Add More Images within a color group
    $(document).on('click', '.addImageBtn', function(){
        const container = $(this).siblings('.fileInputContainer');
        const colorIndex = container.data('color-index');
        const newInputIndex = container.children().length;
        container.append(`
            <div class="file-input-row mb-2" data-input-index="${newInputIndex}">
                <input type="file" class="form-control image-input" name="colors[${colorIndex}][images][]" multiple accept="image/*">
            </div>
        `);
    });

    // Track files for all color groups
    let allFiles = {}; // { colorIndex: [ {file: File, inputIndex} ] }

    // Handle file input change
    $(document).on('change', '.image-input', function(){
        const colorGroup = $(this).closest('.color-group');
        const colorIndex = colorGroup.data('index');
        const inputIndex = $(this).closest('.file-input-row').data('input-index');

        if(!allFiles[colorIndex]) allFiles[colorIndex] = [];

        Array.from(this.files).forEach(file => {
            allFiles[colorIndex].push({file:file, inputIndex: inputIndex});
        });

        renderPreview(colorGroup, colorIndex);
    });

    // Render preview for a color group
    function renderPreview(colorGroup, colorIndex){
        const preview = colorGroup.find('.imagePreview');
        preview.empty();
        allFiles[colorIndex].forEach((obj, index) => {
            const reader = new FileReader();
            reader.onload = function(e){
                const imgDiv = $(`
                    <div style="position:relative; display:inline-block;">
                        <img src="${e.target.result}" style="width:100px; height:100px; object-fit:cover; border:1px solid #ddd; border-radius:5px;">
                        <span class="remove-image" data-color-index="${colorIndex}" data-index="${index}" style="position:absolute; top:2px; right:2px; cursor:pointer; background:red; color:white; padding:2px 5px; border-radius:50%;">x</span>
                    </div>
                `);
                preview.append(imgDiv);
            }
            reader.readAsDataURL(obj.file);
        });
    }

    // Remove image
$(document).on('click', '.remove-image', function () {
    const colorIndex = $(this).data('color-index');
    const index = $(this).data('index');

    // Check total images in this color group
    if (allFiles[colorIndex].length <= 1) {
        showMessage('At least one image is required for each color.', 'error');
        return false;
    }

    const removedInputIndex = allFiles[colorIndex][index].inputIndex;
    allFiles[colorIndex].splice(index, 1);

    // Remove file input if no files left for that input
    const anyLeft = allFiles[colorIndex].some(f => f.inputIndex === removedInputIndex);
    if (!anyLeft) {
        $(`.color-group[data-index="${colorIndex}"] .file-input-row[data-input-index="${removedInputIndex}"]`).remove();
    }

    // Re-render preview
    renderPreview($(`.color-group[data-index="${colorIndex}"]`), colorIndex);
});


    // Submit form
$('#productImageForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Append all files from allFiles object (if exists)
    if (typeof allFiles !== "undefined") {
        Object.keys(allFiles).forEach(colorIndex => {
            allFiles[colorIndex].forEach(obj => {
                formData.append(`colors[${colorIndex}][images][]`, obj.file);
            });
        });
    }
$.ajax({
    url: $(this).attr('action'),
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
        if (response.status === 'success') {
            showMessage('Dress saved successfully!', 'success');

            // redirect after 3s
            setTimeout(function () {
                let pr_id = $('input[name="pr_id"]').val();
                window.location.href = base_url + 'admin/productimage/viewimage/' + pr_id;
            }, 3000);

        } else {
            showMessage('Something went wrong while saving.', 'error');
        }
    },
    error: function (xhr, status, error) {
        console.error(xhr.responseText);
        showMessage('Error uploading images: ' + error, 'error');
    }
});

// helper function
function showMessage(msg, type) {
    let box = $('#messageBox');
    box.stop(true, true).hide().removeClass('success error');

    if (type === 'success') {
        box.css({'background':'#d4edda','color':'#155724','border':'1px solid #c3e6cb'});
    } else {
        box.css({'background':'#f8d7da','color':'#721c24','border':'1px solid #f5c6cb'});
    }

    box.text(msg).fadeIn();
     $('html, body').animate({ scrollTop: 0 }, 'slow');

    setTimeout(function () {
        box.fadeOut();
    }, 3000);
}



});

});
</script>


