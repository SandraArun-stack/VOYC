<script>
    const canvas = new fabric.Canvas('tshirtCanvas', {
        preserveObjectStacking: true,
        selection: false
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
                left: shirtBounds.left + 10, // padding from left
                top: shirtBounds.top + 10,   // padding from top
                width: shirtBounds.width - 20, // wrap inside shirt width
                fontSize: parseInt($("#fontSize").val(), 10),
                fontFamily: $("#fontFamily").val(),
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

        // Upload extra image (goes above dress by default)
        // Upload extra image
        $("#uploadImage").on("change", function (e) {
            const reader = new FileReader();
            reader.onload = function (f) {
                fabric.Image.fromURL(f.target.result, function (img) {
                    img.scaleToWidth(120);
                    img.set({ left: 140, top: 230 });

                    // DO NOT apply clip here!
                    canvas.add(img).setActiveObject(img);
                    if (shirtOverlay) canvas.moveTo(shirtOverlay, 1);
                    canvas.renderAll();
                });
            };
            if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
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


    

</script>