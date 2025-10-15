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
        preserveObjectStacking: true
        // selection: false
    });

    let shirtOverlay = null;
    const defaultOverlay = "<?= base_url('uploads/productmedia/' . $cust_image['pri_Thumbnail']); ?>";

    // Removed body.jpg — directly load product image
    addOverlay(defaultOverlay);
    highlightThumb(defaultOverlay);

    // 1) Add dress overlay as a static image (not movable/resizable)
    function addOverlay(src) {
        fabric.Image.fromURL(src, function (img) {
            img.scaleToWidth(canvas.width);
            img.scaleToHeight(canvas.height);
            img.set({
                left: 0,
                top: 0,
                selectable: false,
                evented: false
            });

            // Replace old overlay
            if (shirtOverlay) canvas.remove(shirtOverlay);
            shirtOverlay = img;

            canvas.add(shirtOverlay);
            canvas.sendToBack(shirtOverlay);
            canvas.renderAll();
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
    // Image selector
    const iconDelete = '<?= base_url(ASSET_PATH . "assets/img/customize/iconDelete.png") ?>';
    const iconDuplicate = '<?= base_url(ASSET_PATH . "assets/img/customize/iconDuplicate.png") ?>';
    const iconFlip = '<?= base_url(ASSET_PATH . "assets/img/customize/iconFlip.png") ?>';

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

    fabric.Image.prototype.controls.deleteControl =
        createIconControl(iconDelete, 0.5, -0.5, deleteObjectHandler, 'pointer');

    fabric.Image.prototype.controls.duplicateControl =
        createIconControl(iconDuplicate, -0.5, 0.5, duplicateObjectHandler, 'pointer');

    fabric.Image.prototype.controls.flipControl =
        createIconControl(iconFlip, -0.5, -0.5, flipObjectHandler, 'pointer');


    // --- UI bindings ---
    $(document).ready(function () {

        $(".thumbs img").on("click", function () {
            const src = $(this).data("src");
            addOverlay(src);
            highlightThumb(src);
        });

        $("#colorPicker").on("input", function () {
            applyDressColor($(this).val());
        });

        $("#addText").on("click", function () {
            if (!shirtOverlay) return;

            const shirtBounds = shirtOverlay.getBoundingRect(true);

            const text = new fabric.IText("Your Text", {
                left: shirtBounds.left + 10,
                top: shirtBounds.top + 10,
                width: shirtBounds.width - 20,
                fontSize: parseInt($("#fontSize").val(), 10),
                fontFamily: selectedFont,
                fill: $("#textColor").val(),
                editable: true
            });

            canvas.add(text).setActiveObject(text);

            canvas.renderAll();
        });

        // Text styling
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
            const a = canvas.getActiveObject();
            if (a && a.type === "i-text") {
                a.set("fontSize", parseInt($(this).val(), 10));
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
                    img.set({
                        left: 120,
                        top: 200,
                        selectable: true,
                        hasControls: true,
                        hasBorders: true
                    });

                    canvas.add(img).setActiveObject(img);
                    if (shirtOverlay) canvas.sendToBack(shirtOverlay);
                    canvas.renderAll();
                }, { crossOrigin: 'anonymous' });
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

        $("#saveBtn").on("click", function () {
            if (shirtOverlay) canvas.moveTo(shirtOverlay, 1);
            const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
            var $alertBox = $('#design_msg_alert');
            const link = document.createElement("a");
            link.href = dataURL;
            // link.download = "tshirt_design.png";
            link.click();

            const prId = $('input[name="prId"]').val();
            const priId = $('input[name="priId"]').val();
            const authModal = new bootstrap.Modal(document.getElementById('authModal'), {
                backdrop: true,
                keyboard: true
            });

            // 👉 2. Send to backend via AJAX
            $.ajax({
                url: "<?= base_url('saveDesign') ?>",
                type: "POST",
                data: {
                    image: dataURL,
                    prId: prId,
                    priId: priId
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

    function highlightThumb(src) {
        $(".thumbs img").removeClass('active');
        $('.thumbs img').each(function () {
            if ($(this).data('src') === src) $(this).addClass('active');
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
        selectedFont = $(this).data('font'); // Update selected font

        $('.font-option').removeClass('active'); // Remove previous
        $(this).addClass('active');              // Highlight current

    });

</script>