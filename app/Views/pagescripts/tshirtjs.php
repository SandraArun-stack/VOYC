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
        sleeve: { objects: [], overlay: '<?= base_url('uploads/productmedia/' . $cust_image['pri_Sleev_Name'][0]); ?>' }
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



        // $("#uploadImage").on("change", function (e) {
        //     const file = e.target.files[0];
        //     const reader = new FileReader();

        //     reader.onload = function (f) {
        //         fabric.Image.fromURL(f.target.result, function (img) {
        //             img.scaleToWidth(150);
        //             img.set({ left: 100, top: 150 });
        //             canvas.add(img).setActiveObject(img);
        //             canvas.renderAll();
        //         });
        //     };
        //     reader.readAsDataURL(file);
        // });
        
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
                        sleeve: designs.sleeve,
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

            // Redirect to same design page but with different color (pri_Id)
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
                if (selected.pri_Sleev_Name && Array.isArray(selected.pri_Sleev_Name)) {
                    selected.pri_Sleev_Name.forEach(image => {
                        const imgSrc = "<?= base_url('uploads/productmedia/') ?>" + image;
                        $thumbs.append(`
                    <img src="${imgSrc}" 
                         data-src="${imgSrc}" 
                         data-view="sleeve" class="shirt-thumb" />`);
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

</script>