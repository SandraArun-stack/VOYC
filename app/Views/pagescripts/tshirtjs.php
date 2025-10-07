<script>
    const canvas = new fabric.Canvas('tshirtCanvas', {
        preserveObjectStacking: true
    });

    // references
    let backgroundImg = null;
    let shirtOverlay = null;

    // 1) Load body.jpg as immovable background
    fabric.Image.fromURL("<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/body.jpg", function (img) {
        img.scaleToWidth(canvas.width);
        img.scaleToHeight(canvas.height);
        img.set({ left: 0, top: 0, selectable: false, evented: false });
        backgroundImg = img;
        canvas.setBackgroundImage(img, function () {
            canvas.renderAll();
            // Load a default dress overlay after background is ready
            addOverlay("<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/06_1.png");
            // highlight the default thumb
            highlightThumb("<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/06_1.png");
        });
    });

    // 2) Add dress overlay ABOVE body.jpg; make it manually movable/resizable
    function addOverlay(src) {
        fabric.Image.fromURL(src, function (img) {
            // start aligned to background
            if (backgroundImg) {
                img.set({
                    left: backgroundImg.left,
                    top: backgroundImg.top,
                    scaleX: backgroundImg.scaleX,
                    scaleY: backgroundImg.scaleY
                });
            } else {
                img.scaleToWidth(canvas.width);
                img.scaleToHeight(canvas.height);
                img.set({ left: 0, top: 0 });
            }

            // Make overlay manually adjustable
            img.set({
                selectable: true,
                evented: true,
                hasControls: true,
                perPixelTargetFind: true,   // click-through transparent areas
                objectCaching: false,
                lockUniScaling: true,       // keep aspect ratio
                transparentCorners: false,
                cornerStyle: 'circle',
                cornerColor: '#3b82f6',
                borderColor: '#3b82f6'
            });

            // Replace old overlay
            if (shirtOverlay) canvas.remove(shirtOverlay);
            shirtOverlay = img;

            canvas.add(shirtOverlay);

            if (shirtOverlay) {
                canvas.moveTo(backgroundImg, 0);  // body stays bottom
                canvas.moveTo(shirtOverlay, 1);   // shirt always right above body
            }

            // Keep stacking: background (0) → overlay (1) → everything else
            canvas.moveTo(shirtOverlay, 1);
            canvas.setActiveObject(shirtOverlay);
            canvas.renderAll();

            // apply current color tint (if any)
            applyDressColor(document.getElementById('colorPicker').value);
        }, { crossOrigin: 'anonymous' });
    }

    // 3) Reset dress to exactly match the background again
    function resetOverlayToBackground() {
        if (shirtOverlay && backgroundImg) {
            shirtOverlay.set({
                left: backgroundImg.left,
                top: backgroundImg.top,
                scaleX: backgroundImg.scaleX,
                scaleY: backgroundImg.scaleY,
                angle: 0
            });
            shirtOverlay.setCoords();
            canvas.moveTo(shirtOverlay, 1);
            canvas.renderAll();
        }
    }

    // 4) Tint/fill dress color
    function applyDressColor(color) {
        if (!shirtOverlay) return;
        shirtOverlay.filters = [new fabric.Image.filters.BlendColor({
            color: color,
            mode: 'tint',
            alpha: 0.6 // stronger/lighter color fill → tweak as needed
        })];
        shirtOverlay.applyFilters();
        canvas.renderAll();
    }

    // --- UI bindings ---
    $(document).ready(function () {

        // Thumbnails click → load that overlay, keep it adjustable
        $(".thumbs img").on("click", function () {
            const src = $(this).data("src");
            addOverlay(src);
            highlightThumb(src);
        });

        // Change dress color
        $("#colorPicker").on("input", function () {
            applyDressColor($(this).val());
        });

        // Add text above the dress
        // Add text
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
                // prevent deleting the dress with this button? comment out if you want to allow
                if (active === shirtOverlay) { return; }
                canvas.remove(active);
            }
        });

        // Save PNG
        // $("#saveBtn").on("click", function () {
        //     if (shirtOverlay) canvas.moveTo(shirtOverlay, 1);
        //     const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
        //     const link = document.createElement("a");
        //     link.href = dataURL;
        //     link.download = "tshirt_design.png";
        //     link.click();
        // });
        $("#saveBtn").on("click", function () {
            if (shirtOverlay) canvas.moveTo(shirtOverlay, 1);

            const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });

            const link = document.createElement("a");
            link.href = dataURL;
            link.download = "tshirt_design.png";
            link.click();

            // 👉 2. Send to backend via AJAX
            $.ajax({
                url: "<?= base_url('saveDesign') ?>",
                type: "POST",
                data: { image: dataURL },
                success: function (response) {
                    console.log("Design saved to server:", response);
                    alert("Design saved successfully!");
                },
                error: function (xhr, status, error) {
                    console.error("Error saving design:", error);
                }
            });
        });

    });

    // helper: highlight current thumb
    function highlightThumb(src) {
        $(".thumbs img").removeClass('active');
        $('.thumbs img').each(function () {
            if ($(this).data('src') === src) $(this).addClass('active');
        });
    }



    canvas.on('object:scaling', function (e) {
        const obj = e.target;

        if (obj === shirtOverlay) {
            const shirtBounds = shirtOverlay.getBoundingRect(true);
            canvas.getObjects().forEach(o => {
                if (o.type === 'i-text') {
                    // calculate a scale factor based on shirt width
                    const scaleFactor = shirtBounds.width / shirtOverlay.width;
                    o.scaleX = scaleFactor;
                    o.scaleY = scaleFactor;
                    o.setCoords();
                }
            });
            canvas.renderAll();
        }
    });

    canvas.on('object:moving', restrictInsideShirt);
    canvas.on('object:scaling', restrictInsideShirt);

    function restrictInsideShirt(e) {
        if (!shirtOverlay) return;
        const obj = e.target;
        if (obj === shirtOverlay) return; // don't restrict shirt itself

        const shirtBounds = shirtOverlay.getBoundingRect(true);
        const objBounds = obj.getBoundingRect(true);

        // Horizontal restriction
        if (objBounds.left < shirtBounds.left) {
            obj.left += (shirtBounds.left - objBounds.left);
        }
        if (objBounds.left + objBounds.width > shirtBounds.left + shirtBounds.width) {
            obj.left -= (objBounds.left + objBounds.width - (shirtBounds.left + shirtBounds.width));
        }

        // Vertical restriction
        if (objBounds.top < shirtBounds.top) {
            obj.top += (shirtBounds.top - objBounds.top);
        }
        if (objBounds.top + objBounds.height > shirtBounds.top + shirtBounds.height) {
            obj.top -= (objBounds.top + objBounds.height - (shirtBounds.top + shirtBounds.height));
        }

        obj.setCoords();
    }
    canvas.on('object:moving', restrictInsideShirt);
    canvas.on('object:scaling', restrictInsideShirt);

</script>