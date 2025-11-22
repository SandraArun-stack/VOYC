<script>

    const fonts = [
        'Arial',
        'Georgia',
        'Courier New',
        'Times New Roman',
        'Comic Sans MS',
        'Verdana',
        'Impact',
        'Lucida Console'
    ];

    let selectedFont = 'Arial';
    let isActionInProgress = false;

    const canvas = new fabric.Canvas('tshirtCanvas', {
        preserveObjectStacking: true,
        selectable: false
    });

    const canvasStates = {
        front: { objects: [], overlay: '<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>' },
        back: { objects: [], overlay: '<?= base_url('uploads/productmedia/' . $cust_image['pri_File_Name'][0]); ?>' },
        RSleeve_Img: { objects: [], overlay: '<?= base_url('uploads/productmedia/' . $cust_image['RSleeve_Img']); ?>' },
        LSleeve_Img: { objects: [], overlay: '<?= base_url('uploads/productmedia/' . $cust_image['LSleeve_Img']); ?>' }
    };

    let currentView = 'front';

    let shirtOverlay = null;
    const defaultOverlay = "<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>";

    // Removed body.jpg — directly load product image
    addOverlay(defaultOverlay);
    highlightThumb(defaultOverlay);


    function addOverlay(src) {
        fabric.Image.fromURL(src, function (img) {
            img.scaleToWidth(canvas.width);
            img.scaleToHeight(canvas.height);
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                left: 0,
                top: 0,
                originX: 'left',
                originY: 'top'
            });

            shirtOverlay = img;
        }, { crossOrigin: 'anonymous' });
    }

    function resetOverlayToBackground() {
        if (shirtOverlay) {
            shirtOverlay.set({
                left: 0,
                top: 0,
                scaleX: 1,
                scaleY: 1,
                angle: 0
            });
            shirtOverlay.setCoords();
            canvas.renderAll();
        }
    }

    function applyDressColor(color) {
        if (!shirtOverlay) return;
        shirtOverlay.filters = [new fabric.Image.filters.BlendColor({
            color: color,
            mode: 'tint',
            alpha: 0.6
        })];
        shirtOverlay.applyFilters();
        canvas.renderAll();
    }

    fabric.Object.prototype.transparentCorners = false;
    fabric.Object.prototype.cornerColor = 'rgba(0,0,0,0)';
    fabric.Object.prototype.cornerStyle = 'circle';
    fabric.Object.prototype.cornerSize = 28;

    function createIconControl(iconUrl, offsetX, offsetY, actionHandler, cursor) {
        return new fabric.Control({
            x: offsetX,
            y: offsetY,
            offsetY: offsetY * 0,
            cursorStyle: cursor,
            mouseUpHandler: actionHandler,
            render: function (ctx, left, top, styleOverride, fabricObject) {
                const img = new Image();
                img.src = iconUrl;
                img.onload = () => {
                    ctx.drawImage(img, left - 12, top - 12, 24, 24);
                };
            },
            cornerSize: 24
        });
    }

    // 🗑 Delete handler
    function deleteObjectHandler(eventData, transform) {
        const target = transform.target;
        const canvas = target.canvas;
        canvas.remove(target);
        canvas.requestRenderAll();
        return true;
    }

    // ➕ Duplicate handler
    function duplicateObjectHandler(eventData, transform) {
        const target = transform.target;
        target.clone(function (cloned) {
            cloned.set({
                left: target.left + 30,
                top: target.top + 30
            });
            target.canvas.add(cloned);
            target.canvas.setActiveObject(cloned);
        });
        return true;
    }

    // 🔄 Flip horizontally handler
    function flipObjectHandler(eventData, transform) {
        const target = transform.target;
        target.toggle('flipX');
        target.canvas.requestRenderAll();
        return true;
    }



    function createBootstrapIconControl(iconChar, offsetX, offsetY, handler, cursor) {
        return new fabric.Control({
            x: offsetX,
            y: offsetY,
            cursorStyle: cursor,
            mouseUpHandler: handler,
            render: function (ctx, left, top, styleOverride, fabricObject) {
                const size = 18;
                ctx.save();

                // Draw circular background
                ctx.beginPath();
                ctx.arc(left, top, size / 1.5, 0, Math.PI * 2);
                ctx.fillStyle = "white";
                ctx.shadowColor = "rgba(0,0,0,0.2)";
                ctx.shadowBlur = 4;
                ctx.fill();
                ctx.closePath();

                // Draw Bootstrap icon (using Unicode glyph)
                ctx.font = `${size}px bootstrap-icons`;
                ctx.fillStyle = "#333";
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";
                ctx.fillText(iconChar, left, top);

                ctx.restore();
            },
            cornerSize: 28
        });
    }


    const ICONS = {
        delete: '\uF623',    // bi-x-square
        duplicate: '\uF759', // bi-copy
        flip: '\uF5A9'       // bi-symmetry-vertical
    };

    [fabric.Image.prototype, fabric.IText.prototype].forEach(proto => {
        proto.controls.deleteControl = createBootstrapIconControl(ICONS.delete, 0.5, -0.5, deleteObjectHandler, 'pointer');
        proto.controls.duplicateControl = createBootstrapIconControl(ICONS.duplicate, -0.5, 0.5, duplicateObjectHandler, 'pointer');
        proto.controls.flipControl = createBootstrapIconControl(ICONS.flip, -0.5, -0.5, flipObjectHandler, 'pointer');
    });

    // --- UI bindings ---
    $(document).ready(function () {
        const authModal = new bootstrap.Modal(document.getElementById('authModal'), {
            backdrop: true,
            keyboard: true
        });

        $('.login_close').on('click', function (e) {
            authModal.hide();
        });

        loadCanvasState(currentView);
        initThumbClick();

        // $(".shirt-thumb").on("click", function () {


        $("#colorPicker").on("input", function () {
            applyDressColor($(this).val());
        });


        $("#addText").on("click", function () {
            const text = new fabric.IText("Your Text", {
                left: 50,
                top: 100,
                fontSize: parseInt($("#fontSize").val(), 15),
                fill: $("#textColor").val(),
                fontFamily: selectedFont,
                editable: true
            });
            canvas.add(text).setActiveObject(text);
            canvas.renderAll();
        });


        $("#textColor").on("input", function () {
            const active = canvas.getActiveObject();
            if (active && active.type === "i-text") {
                active.set("fill", $(this).val());
                canvas.renderAll();
            }
        });

        $("#fontFamily").on("change", function () {
            const active = canvas.getActiveObject();
            if (active && active.type === "i-text") {
                active.set("fontFamily", $(this).val());
                canvas.renderAll();
            }
        });


        $("#boldToggle").on("change", function () {
            const a = canvas.getActiveObject();
            if (a && a.type === "i-text") {
                a.set("fontWeight", $(this).is(":checked") ? "bold" : "normal");
                canvas.renderAll();
            }
        });


        $("#italicToggle").on("change", function () {
            const a = canvas.getActiveObject();
            if (a && a.type === "i-text") {
                a.set("fontStyle", $(this).is(":checked") ? "italic" : "normal");
                canvas.renderAll();
            }
        });


        $("#fontSize").on("input", function () {
            const active = canvas.getActiveObject();
            if (active && active.type === "i-text") {
                active.set("fontSize", parseInt($(this).val(), 10));
                canvas.renderAll();
            }
        });

        let uploadedImagesBase64 = [];


        $("#resetOverlayBtn").on("click", resetOverlayToBackground);

        // Lock/unlock dress (to avoid accidental moves after you place it)
        $("#lockOverlay").on("change", function () {
            if (!shirtOverlay) return;
            const locked = $(this).is(":checked");
            shirtOverlay.set({
                selectable: !locked,
                evented: !locked
            });
            if (locked) canvas.discardActiveObject();
            canvas.renderAll();
        });

        // Delete selected
        $("#deleteBtn").on("click", function () {
            debugger;
            const active = canvas.getActiveObject();
            if (active) {
                if (active === shirtOverlay) { return; }
                canvas.remove(active);
            }
        });

        let selectedSize = null;

        let quantity = 1;

        function getBasePrice() {
            let totalText = $('#priceProduct').text();
            return parseFloat(totalText.replace(/[^\d\.]/g, '')) || 0;
        }

        let basePrice = getBasePrice();


        function updatePreview() {
            let total = basePrice * quantity;
            let previewHTML = "";
            let perItemDesignPrice = 0;

            $(".design-check:checked").each(function () {
                const type = $(this).closest(".design-option").data("type");
                const data = designData[type];

                if (data && typeof data.price === "number") {
                    perItemDesignPrice += data.price;
                }


                total += data.price * quantity;

                previewHTML += `
                <div class="text-center">
                    <img src="${data.img}" alt="${type}">
                    <p class="text-capitalize mb-0">${type} design</p>
                </div>
            `;

                $(`#${type}`)
                    .addClass("active")
                    .find(`span[id^='price']`)
                    .text(`₹${data.price}`);
            });

            // Hide entries for unchecked items
            $(".design-option").each(function () {
                const type = $(this).data("type");
                const checkbox = $(this).find(".design-check");
                if (!checkbox.is(":checked")) {
                    $(`#${type}`)
                        .removeClass("active")
                        .find(`span[id^='price']`)
                        .text("₹0");
                }
            });

            $(".selected-items").html(previewHTML);
            $("#priceTotal").text("₹" + total.toFixed(2));
            let displaySubtotal = basePrice + perItemDesignPrice;

            $("#priceSubtotal").text(`₹${displaySubtotal} × ${quantity}`);
        }

        $('.qty-btn-custom.plus-custom').click(function () {
            let $wrapper = $(this).closest('.quantity-wrapper-custom');
            let $value = $wrapper.find('.quantity-value-custom');
            quantity = parseInt($value.text()) + 1;
            $value.text(quantity);
            updatePreview(); // recalc total
        });

        // ✅ Quantity decrement
        $('.qty-btn-custom.minus-custom').click(function () {
            let $wrapper = $(this).closest('.quantity-wrapper-custom');
            let $value = $wrapper.find('.quantity-value-custom');
            let currentQty = parseInt($value.text());

            if (currentQty > 1) {
                quantity = currentQty - 1;
                $value.text(quantity);
                updatePreview(); // recalc total
            }
        });

        // Bind change event
        $(".design-check").on("change", function () {
            updatePreview();
        });



        // Initial update
        updatePreview();
        // const totalPrice = $("#priceTotal").text().replace(/[^\d\.]/g, '');

        $("#saveBtn").on("click", function () {
            if ($(".design-check:checked").length === 0) {

                $("#alertAddtocart")
                    .removeClass()
                    .addClass("alert alert-danger")
                    .text("Please Select Where You Want Your Design Applied.")
                    .fadeIn();

                setTimeout(() => {
                    $("#alertAddtocart").fadeOut();
                }, 2500);

                return;
            }

            var $btn = $(this);

            var $alertBox = $('#design_msg_alert');
            saveCurrentCanvasState();

            $btn.prop('disabled', true).text('Processing...');

            let totalText = $("#priceTotal").text();
            let totalPrice = parseFloat(totalText.replace(/[^\d\.]/g, '')) || 0;
            const designs = {};

            const visibleImagesBase64 = [];
            
            const selectedViews = $(".design-check:checked").map(function () {
                return $(this).closest(".design-option").data("type");
            }).get();

            // Instead of Object.keys(canvasStates).forEach
            selectedViews.forEach(view => {
                const viewState = canvasStates[view];
                if (viewState && viewState.objects) {
                    viewState.objects.forEach(obj => {
                        if (obj.type === 'image' && !obj.overlay) {
                            const tempCanvas = new fabric.StaticCanvas(null, {
                                width: canvas.width,
                                height: canvas.height
                            });

                            fabric.Image.fromURL(obj.src || obj._element.src, function (img) {
                                img.set({
                                    scaleX: obj.scaleX,
                                    scaleY: obj.scaleY,
                                    left: obj.left,
                                    top: obj.top,
                                    angle: obj.angle || 0
                                });
                                tempCanvas.add(img);

                                visibleImagesBase64.push(
                                    tempCanvas.toDataURL({
                                        format: 'png',
                                        quality: 0.7,
                                        enableRetinaScaling: false
                                    })
                                );

                            });
                        }
                    });
                }
            });



            const exportAll = selectedViews.map(view => {
                const tempCanvas = new fabric.Canvas(null, {
                    width: canvas.width,
                    height: canvas.height
                });

                return new Promise(resolve => {
                    tempCanvas.loadFromJSON({ objects: canvasStates[view].objects }, function () {
                        fabric.Image.fromURL(canvasStates[view].overlay, function (img) {
                            img.set({ selectable: false, evented: false });
                            img.scaleToWidth(tempCanvas.width);
                            img.scaleToHeight(tempCanvas.height);
                            tempCanvas.add(img);
                            tempCanvas.sendToBack(img);
                            tempCanvas.renderAll();

                            const dataURL = tempCanvas.toDataURL({ format: 'jpeg', quality: 0.7 });
                            designs[view] = dataURL;
                            resolve();
                        });
                    });
                });
            });


            Promise.all(exportAll).then(() => {

                $.ajax({
                    url: "<?= base_url('saveDesign') ?>",
                    method: "POST",
                    data: {
                        front: designs.front,
                        back: designs.back,
                        RSleeve_Img: designs.RSleeve_Img,
                        LSleeve_Img: designs.LSleeve_Img,
                        // uploadedImages: JSON.stringify(uploadedImagesBase64),
                        uploadedImages: JSON.stringify(visibleImagesBase64),
                        prId: $('input[name="prId"]').val(),
                        priId: $('input[name="priId"]').val(),
                        quantity: quantity,
                        totalPrice: totalPrice,
                        selectedSize: $("#summarySize").text().trim(),

                    },

                    success: function (response) {
                        if (response.status === 'login_required') {
                            authModal.show();
                            $('#loginView').show();
                            $('#registerView').hide();
                            $('#forgotPassView').hide();
                            return;
                        } else if (response.status === 'success') {
                            $alertBox
                                .removeClass('d-none alert-danger')
                                .addClass('alert alert-success')
                                .text(response.message || 'Registration successful!')
                                .fadeIn();

                            setTimeout(() => {
                                $alertBox.fadeOut(400);
                                window.location.href = response.redirect;
                            }, 2000);
                        } else if (response.status === 'error') {
                            $alertBox
                                .removeClass('d-none alert-success')
                                .addClass('alert alert-danger')
                                .text(response.message || 'Registration failed!')
                                .fadeIn();
                            setTimeout(() => {
                                $alertBox.fadeOut(400);
                            }, 2000);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error saving design:", error);
                    }
                });
            });
        });

        $(document).on('click', '.color-card', function () {
            const priId = $(this).data('priid');
            const prId = "<?= $prId ?>";

            window.location.href = "<?= base_url('tshirt') ?>/" + prId + "/" + priId;
        });

        const sizeOrder = ["XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "5XL", "6XL"];

        $(document).on('click', '.color-preview', function () {
            $('.color-preview').removeClass('selected');
            $(this).addClass('selected');

            const priId = $(this).data('priid');
            const allData = <?= json_encode($allData) ?>;

            const selected = allData.find(item => item.pri_Id == priId);

            if (selected) {
                const $sizeContainer = $('#sizeContainer');
                $sizeContainer.empty();

                const sortedVariants = selected.variants.sort((a, b) => {
                    return sizeOrder.indexOf(a.prv_Size) - sizeOrder.indexOf(b.prv_Size);
                });

                sortedVariants.forEach(v => {
                    $sizeContainer.append(`
                    <div class="size-box m-1 p-2 border rounded selectable-size" data-prv-id="${v.prv_Id}">
                        ${v.prv_Size} - ₹${v.prv_price}
                    </div>`);
                });


                const $thumbs = $('.thumbs');
                $thumbs.empty();

                if (selected.pri_Thumbnail) {
                    const thumbSrc = "<?= base_url('uploads/productmedia/') ?>" + selected.pri_Thumbnail;
                    $thumbs.append(`
                <img src="${thumbSrc}" 
                     data-src="${thumbSrc}" 
                     data-view="front" class="shirt-thumb" />`);

                    // ✅ Add front view to canvas and update view state
                    addOverlay(thumbSrc);
                    currentView = 'front';
                    canvasStates[currentView].overlay = thumbSrc;
                    highlightThumb(thumbSrc);
                }

                // --- Back Image(s)
                if (selected.pri_File_Name && Array.isArray(selected.pri_File_Name)) {
                    selected.pri_File_Name.forEach((image, index) => {
                        const imgSrc = "<?= base_url('uploads/productmedia/') ?>" + image;
                        $thumbs.append(` 
                         <img src="${imgSrc}" data-src="${imgSrc}" data-view="back"  class="shirt-thumb" />
                        `);
                    });
                }

                // --- Sleeve Image(s)
                if (selected.RSleeve_Img && Array.isArray(selected.RSleeve_Img)) {
                    selected.RSleeve_Img.forEach(image => {
                        const imgSrc = "<?= base_url('uploads/productmedia/') ?>" + image;
                        $thumbs.append(`
                    <img src="${imgSrc}" 
                         data-src="${imgSrc}" 
                         data-view="RSleeve" class="shirt-thumb" />`);
                    });
                }
                if (selected.LSleeve_Img && Array.isArray(selected.LSleeve_Img)) {
                    selected.LSleeve_Img.forEach(image => {
                        const imgSrc = "<?= base_url('uploads/productmedia/') ?>" + image;
                        $thumbs.append(`
                    <img src="${imgSrc}" 
                         data-src="${imgSrc}" 
                         data-view="LSleeve" class="shirt-thumb" />`);
                    });
                }
            }
            initThumbClick();
        });



    });

    function toggleCustomControls(object, visible) {
        object.setControlsVisibility({
            deleteControl: visible,
            duplicateControl: visible,
            flipControl: visible
        });
        canvas.renderAll();
    }

    canvas.on('object:moving', function (e) {
        const obj = e.target;
        if (obj && obj.type !== 'image') {
            toggleCustomControls(obj, false); // Hide icons while moving
        }
    });

    canvas.on('mouse:up', function () {
        const obj = canvas.getActiveObject();
        if (obj && obj.type !== 'image') {
            toggleCustomControls(obj, true); // Show icons again
        }
    });

    canvas.on('selection:created', function (e) {
        const obj = e.target;
        if (obj && obj.type !== 'image') {
            toggleCustomControls(obj, true);
        }
    });

    function highlightThumb(src) {
        $(".shirt-thumb").removeClass("active");
        $('.shirt-thumb').each(function () {
            if ($(this).data('src') === src) {
                $(this).addClass("active");
            }
        });
    }

    function saveCurrentCanvasState() {
        canvas.discardActiveObject();
        canvasStates[currentView].objects = canvas.toDatalessJSON().objects;
        //31-10
        const updatedImage = canvas.toDataURL({
            format: 'png',
            quality: 1.0
        });

        canvasStates[currentView].overlay = updatedImage;

        // if (designData[currentView]) {
        //     designData[currentView].img = updatedImage;
        // }
        //31-10
        const viewMap = {
            RSleeve: 'right',
            RSleeve_Img: 'right',
            LSleeve: 'left',
            LSleeve_Img: 'left'
        };

        const mappedKey = viewMap[currentView] || currentView;

        if (designData[mappedKey]) {
            designData[mappedKey].img = updatedImage;
        }

    }

    function loadCanvasState(view) {
        canvas.clear();

        const objects = canvasStates[view].objects || [];
        canvas.loadFromJSON({ objects: objects }, function () {
            canvas.renderAll();
        });

        addOverlay(canvasStates[view].overlay);
    }

    function initThumbClick() {
        $(".thumbs img").on("click", function () {
            const newView = $(this).data("view");
            const src = $(this).data("src");

            // ✅ remember which thumb we're editing
            currentThumbView = newView;

            if (newView === currentView) return;

            saveCurrentCanvasState();      // save current canvas
            currentView = newView;         // update view
            loadCanvasState(currentView);  // load new view
            addOverlay(src);
            highlightThumb(src);
        });
    }


    $('.sidebar-item, #customize_main_ui .option').on('click', function () {
        const viewId = $(this).data('view');

        $('#customize_main_ui').addClass('d-none');
        $('.view-section').addClass('d-none');
        $('#view-' + viewId).removeClass('d-none');

        if (viewId !== 'upload') {
            $('#view-spec-upload-image').addClass('d-none');
        }

        window.currentView = viewId;
    });

    $('#openFontPicker').on('click', function () {
        const container = $('#fontPickerContainer');
        const row = container.find('.row');
        row.empty(); // Clear existing items

        fonts.forEach(font => {
            const item = $(`
            <div class="col-12">
                <div class="font-sample border p-2 rounded font-option mb-2" id="fontFamily" 
                     data-font="${font}" style="cursor:pointer; font-family: '${font}';">
                    ${font}
                </div>
            </div>
        `);
            row.append(item);
        });

        container.toggleClass('d-none');
    });

    $('#fontPickerContainer').on('click', '.font-option', function () {
        selectedFont = $(this).data('font');

        $('.font-option').removeClass('active');
        $(this).addClass('active');
        const activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.type === 'i-text') {
            activeObject.set('fontFamily', selectedFont);
            canvas.renderAll();
        }

    });

    //price

    $(document).on('click', '#addSleeveBtn', function () {
        $(this).hide();
        $('#sleeveContainer').removeClass('d-none').addClass('d-flex');
    });

    var frontPrice = parseFloat($('#front_Customization_Price').val()) || 0;
    var backPrice = parseFloat($('#back_Customization_Price').val()) || 0;
    var RSleevePrice = parseFloat($('#sleeve_Customization_Price').val()) || 0;
    var LSleevePrice = parseFloat($('#sleeve_Customization_Price').val()) || 0;



    const designData = {
        front: {
            price: frontPrice,
            img: ''
        },
        back: {
            price: backPrice,
            img: ''
        },
        right: {
            price: RSleevePrice,
            img: ''
        },
        left: {
            price: LSleevePrice,
            img: ''
        }
    };



    function updateCheckboxState() {
        const viewMap = {
            RSleeve: 'right',
            RSleeve_Img: 'right',
            LSleeve: 'left',
            LSleeve_Img: 'left',
        };

        const typeKey = viewMap[currentView] || currentView;
        const hasObjects = canvas.getObjects().some(obj => !obj.isOverlay);
        const checkbox = $(`.design-option[data-type="${typeKey}"] .design-check`);

        checkbox.prop('disabled', !hasObjects);
        if (!hasObjects) checkbox.prop('checked', false);
    }

    canvas.on('object:added', updateCheckboxState);
    canvas.on('object:removed', updateCheckboxState);
    canvas.on('object:modified', updateCheckboxState);



    $(document).ready(function () {
        const dpi = 96;
        const cmPerInch = 2.54;
        let thumbImages = {};
        let currentThumbView = currentView;

        // --- Helper conversion functions ---
        const pxToCm = (px) => (px / dpi) * cmPerInch;
        const cmToPx = (cm) => (cm / cmPerInch) * dpi;

        // --- Helper: Update UI fields based on image size ---
        function updateImageDimensionsUI(img) {
            if (!img) return;
            const widthInCm = pxToCm(img.getScaledWidth());
            const heightInCm = pxToCm(img.getScaledHeight());
            $("#img-width").val(widthInCm.toFixed(2));
            $("#img-height").val(heightInCm.toFixed(2));
        }

        // --- Helper: Apply new dimensions from controls ---
        function applyDimensions(img, newWidthCm, newHeightCm) {
            if (!img) return;
            img.scaleToWidth(cmToPx(newWidthCm));
            img.scaleToHeight(cmToPx(newHeightCm));
            canvas.renderAll();
            updateImageDimensionsUI(img);
        }

        // --- Handle image uploads ---
        // --- Handle image uploads ---
        $("#uploadImage").on("change", function (e) {
            const files = e.target.files;
            if (!files.length) return;

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function (f) {
                    const base64Data = f.target.result;

                    // ✅ if an image is selected, replace its source
                    if (canvas.getActiveObject() && canvas.getActiveObject().type === "image") {
                        const active = canvas.getActiveObject();
                        active.setSrc(base64Data, function () {
                            active.setCoords();
                            canvas.renderAll();
                        });
                    } else {
                        // ✅ otherwise, create a *new image*
                        fabric.Image.fromURL(base64Data, function (img) {
                            img.set({
                                left: 100 + index * 20,
                                top: 150 + index * 20,
                                hasControls: true,
                                selectable: true
                            });
                            img.scaleToWidth(150);
                            canvas.add(img).setActiveObject(img);
                            canvas.renderAll();
                        });
                    }
                };

                reader.readAsDataURL(file);
            });

            $("#uploadImage").val(""); // reset input
        });

        // --- When user clicks/selects an image ---
        // canvas.on("selection:created", function (e) {
        //     if (!e.selected || e.selected.length === 0) return;
        //     activeImage = e.selected[0];
        //     updateImageDimensionsUI(activeImage);
        //     $("#view-spec-upload-image").removeClass("d-none");
        // });

        // // --- When user switches selection between objects ---
        // canvas.on("selection:updated", function (e) {
        //     if (!e.selected || e.selected.length === 0) return;
        //     activeImage = e.selected[0];
        //     updateImageDimensionsUI(activeImage);
        //     $("#view-spec-upload-image").removeClass("d-none");
        // });

        // // --- When deselecting all ---
        // canvas.on("selection:cleared", function () {
        //     activeImage = null;
        //     $("#view-spec-upload-image").addClass("d-none");
        // });

        canvas.on("selection:created", function (e) {
            if (!e.selected || e.selected.length === 0) return;
            activeImage = e.selected[0];
            updateImageDimensionsUI(activeImage);

            // ✅ Show image properties ONLY in upload view
            if (window.currentView === "upload") {
                $("#view-spec-upload-image").removeClass("d-none");
            }
        });

        canvas.on("selection:updated", function (e) {
            if (!e.selected || e.selected.length === 0) return;
            activeImage = e.selected[0];
            updateImageDimensionsUI(activeImage);

            // ✅ Show image properties ONLY in upload view
            if (window.currentView === "upload") {
                $("#view-spec-upload-image").removeClass("d-none");
            }
        });

        canvas.on("selection:cleared", function () {
            activeImage = null;
            $("#view-spec-upload-image").addClass("d-none");
        });

        // --- Update UI when resizing using Fabric controls (real-time) ---
        canvas.on("object:scaling", function (e) {
            if (!e.target || e.target.type !== "image") return;
            updateImageDimensionsUI(e.target);
        });

        // --- Also update once scaling ends to ensure final values ---
        canvas.on("object:modified", function (e) {
            if (!e.target || e.target.type !== "image") return;
            updateImageDimensionsUI(e.target);
        });

        // --- Width & Height button handlers ---
        $("#increase-width").on("click", function () {
            if (!activeImage) return;
            const w = parseFloat($("#img-width").val());
            const h = parseFloat($("#img-height").val());
            applyDimensions(activeImage, w * 1.1, h * 1.1);
        });

        $("#decrease-width").on("click", function () {
            if (!activeImage) return;
            const w = parseFloat($("#img-width").val());
            const h = parseFloat($("#img-height").val());
            applyDimensions(activeImage, w * 0.9, h * 0.9);
        });

        $("#increase-height").on("click", function () {
            if (!activeImage) return;
            const w = parseFloat($("#img-width").val());
            const h = parseFloat($("#img-height").val());
            applyDimensions(activeImage, w * 1.1, h * 1.1);
        });

        $("#decrease-height").on("click", function () {
            if (!activeImage) return;
            const w = parseFloat($("#img-width").val());
            const h = parseFloat($("#img-height").val());
            applyDimensions(activeImage, w * 0.9, h * 0.9);
        });

        // --- Handle custom control visibility ---
        function toggleCustomControls(object, visible) {
            object.setControlsVisibility({
                deleteControl: visible,
                duplicateControl: visible,
                flipControl: visible
            });
            canvas.renderAll();
        }

        $("#center-image").on("click", function () {
            const active = canvas.getActiveObject();

            if (!active) {
                alert("Please select an image first.");
                return;
            }

            // ✅ Center the image on the canvas
            active.set({
                left: canvas.getWidth() / 2,
                top: canvas.getHeight() / 2,
                originX: "center",
                originY: "center"
            });

            // Refresh position
            active.setCoords();
            canvas.renderAll();
        });

        // --- Enable/Disable Layer Buttons depending on image count ---
        function updateLayerButtonsState() {
            const imageCount = canvas.getObjects().filter(obj => obj.type === "image").length;
            const hasMultiple = imageCount > 1;

            $("#layer-up, #layer-down").prop("disabled", !hasMultiple);
        }

        // --- Check at load ---
        updateLayerButtonsState();

        // --- Re-check whenever images are added or removed ---
        canvas.on("object:added", updateLayerButtonsState);
        canvas.on("object:removed", updateLayerButtonsState);

        // --- Re-enable both when something changes on canvas (selection, modification, etc.) ---
        canvas.on("selection:created", updateLayerButtonsState);
        canvas.on("selection:updated", updateLayerButtonsState);
        canvas.on("object:modified", updateLayerButtonsState);

        // --- Layer Up ---
        $("#layer-up").on("click", function () {
            const active = canvas.getActiveObject();
            if (!active || active.type !== "image") return;

            const allObjects = canvas.getObjects();
            const currentIndex = allObjects.indexOf(active);

            if (currentIndex < allObjects.length - 1) {
                canvas.bringForward(active);
                canvas.renderAll();
            }

            // Disable temporarily until user triggers another change
            $(this).prop("disabled", true);
            $("#layer-down").prop("disabled", false);
        });

        // --- Layer Down ---
        $("#layer-down").on("click", function () {
            const active = canvas.getActiveObject();
            if (!active || active.type !== "image") return;

            const currentIndex = canvas.getObjects().indexOf(active);
            if (currentIndex > 0) {
                canvas.sendBackwards(active);
                canvas.renderAll();
            }

            // Disable temporarily until user triggers another change
            $(this).prop("disabled", true);
            $("#layer-up").prop("disabled", false);
        });

        // Add a custom property to track background removal
        canvas.on('selection:created', updateRemoveBgToggle);
        canvas.on('selection:updated', updateRemoveBgToggle);
        canvas.on('selection:cleared', () => {
            $("#toggle-bg-remove").prop("checked", false);
        });

        function updateRemoveBgToggle() {
            const active = canvas.getActiveObject();
            if (active && active.type === "image") {
                $("#toggle-bg-remove").prop("checked", !!active.bgRemoved);
            } else {
                $("#toggle-bg-remove").prop("checked", false);
            }
        }

        // When toggle changes
        $("#toggle-bg-remove").on("change", function () {
            const active = canvas.getActiveObject();

            if (!active || active.type !== "image") {
                alert("Please select an image first.");
                $(this).prop("checked", false);
                return;
            }

            if (this.checked) {
                // Remove background (simulation)
                active.filters.push(new fabric.Image.filters.RemoveColor({
                    color: '#ffffff', // target color
                    distance: 0.2
                }));
                active.applyFilters();
                active.bgRemoved = true; // store custom flag
                canvas.renderAll();
            } else {
                // Reset background
                active.filters = [];
                active.applyFilters();
                active.bgRemoved = false;
                canvas.renderAll();
            }
        });

        // --- Flip functionality ---
        $("#horizontal__flip").on("click", function () {
            const active = canvas.getActiveObject();
            if (!active) {
                alert("Please select an image to flip horizontally.");
                return;
            }

            // Toggle horizontal flip
            active.set("flipX", !active.flipX);
            canvas.renderAll();
        });

        $("#vertical__flip").on("click", function () {
            const active = canvas.getActiveObject();
            if (!active) {
                alert("Please select an image to flip vertically.");
                return;
            }

            // Toggle vertical flip
            active.set("flipY", !active.flipY);
            canvas.renderAll();
        });



    });

</script>