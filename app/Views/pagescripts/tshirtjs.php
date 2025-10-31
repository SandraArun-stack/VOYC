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

            // Keep reference if you ever need it for tint/color
            shirtOverlay = img;
        }, { crossOrigin: 'anonymous' });
    }

    // Keep for backward compatibility but now does nothing
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

    // Optional: still usable for future tinting (color change)
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

    // Helper function to make image controls with icons
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

        $("#uploadImage").on("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (f) {
                fabric.Image.fromURL(f.target.result, function (img) {
                    img.scaleToWidth(150);
                    img.set({ left: 100, top: 150 });
                    canvas.add(img).setActiveObject(img);
                    canvas.renderAll();
                });

                $("#uploadImage").val("");
            };

            reader.readAsDataURL(file);
        });



        // Reset dress position
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
            const active = canvas.getActiveObject();
            if (active) {
                if (active === shirtOverlay) { return; }
                canvas.remove(active);
            }
        });

        let selectedSize = null;

        $(document).on('click', '.selectable-size', function () {
            $('.selectable-size').removeClass('selected');
            $(this).addClass('selected');
            selectedSize = $(this).data('prv-id');
        });

        $("#saveBtn").on("click", function () {

            // debugger;
            var $alertBox = $('#design_msg_alert');
            saveCurrentCanvasState();

            const designs = {};

            const exportAll = Object.keys(canvasStates).map(view => {
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

            const authModal = new bootstrap.Modal(document.getElementById('authModal'), {
                backdrop: true,
                keyboard: true
            });
            Promise.all(exportAll).then(() => {
                // console.log("Exported designs:", designs);

                // Optional: Send to backend

                $.ajax({
                    url: "<?= base_url('saveDesign') ?>",
                    method: "POST",
                    data: {
                        front: designs.front,
                        back: designs.back,
                        RSleeve_Img: designs.RSleeve_Img,
                        RSleeve_Img: designs.RSleeve_Img,
                        // sleeve: designs.sleeve,
                        prId: $('input[name="prId"]').val(),
                        priId: $('input[name="priId"]').val(),
                        prvId: selectedSize
                    },

                    success: function (response) {
                        if (response.status === 'login_required') {
                            authModal.show();
                            $('#loginView').show();
                            $('#registerView').hide();
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
            if (newView === currentView) return;
            saveCurrentCanvasState();      // save current canvas
            currentView = newView;         // update view
            loadCanvasState(currentView);  // load new view
            const src = $(this).data("src");
            addOverlay(src);
            highlightThumb(src);
        });
    }

    $('.sidebar-item, #customize_main_ui .option').on('click', function () {
        const viewId = $(this).data('view');

        $('#customize_main_ui').addClass('d-none');
        $('.view-section').addClass('d-none');
        $('#view-' + viewId).removeClass('d-none');
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
            img: canvasStates.front.overlay 
        },
        back: {
            price: backPrice,
            img: canvasStates.back.overlay
        },
        right: {
            price: RSleevePrice,
            img: canvasStates.RSleeve_Img.overlay
        },
        left: {
            price: LSleevePrice,
            img: canvasStates.LSleeve_Img.overlay
        }
    };
    $(document).ready(function () {



        // let totalText = $('#priceProduct').text();
        // let baseTotal = parseFloat(totalText.replace(/[^\d\.]/g, '')) || 0;

        function getBasePrice() {
            let totalText = $('#priceProduct').text();
            return parseFloat(totalText.replace(/[^\d\.]/g, '')) || 0;
        }

        let basePrice = getBasePrice();
        let quantity = 1;

        function updatePreview() {
            let total = basePrice * quantity;
            let previewHTML = "";

            $(".design-check:checked").each(function () {
                const type = $(this).closest(".design-option").data("type");
                const data = designData[type];

                if (!data || typeof data.price !== "number") {
                    console.warn("Missing designData for type:", type);
                    return;
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
                    .text(`+ ₹${data.price}`);
            });

            // Hide entries for unchecked items
            $(".design-option").each(function () {
                const type = $(this).data("type");
                const checkbox = $(this).find(".design-check");
                if (!checkbox.is(":checked")) {
                    $(`#${type}`)
                        .removeClass("active")
                        .find(`span[id^='price']`)
                        .text("+ ₹0");
                }
            });

            $(".selected-items").html(previewHTML);
            $("#priceTotal").text("₹" + total.toFixed(2));
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
    });


</script>