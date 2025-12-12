<script>
    var base_url = "<?= base_url(); ?>";
    var csrfName = "<?= csrf_token(); ?>";
    var csrfHash = "<?= csrf_hash(); ?>";

    $(document).ready(function () {

        function showMessage(message, type = 'success') {
            var box = $('#messageBox');
            box
                .removeClass('alert-success alert-danger alert-warning')
                .addClass(type === 'success' ? 'alert-success' :
                    type === 'error' ? 'alert-danger' : 'alert-warning')
                .html(message)
                .fadeIn(300);

            // Auto-hide after 4 seconds
            setTimeout(function () {
                box.fadeOut(400);
            }, 4000);
        }

        $('.checkout__form').on('submit', function (e) {
            e.preventDefault();

            //  Step 1: Validation before placing order
            let isValid = true;
            let message = '';

            const requiredFields = [
                { name: 'add_Name', label: 'First Name' },
                { name: 'add_LastName', label: 'Last Name' },
                { name: 'add_Country', label: 'Country' },
                { name: 'add_Street', label: 'Street Address' },
                { name: 'add_City', label: 'City' },
                { name: 'add_State', label: 'State' },
                { name: 'add_Pincode', label: 'Pincode' },
                { name: 'add_Phone', label: 'Phone' },
                { name: 'add_Email', label: 'Email' }
            ];

            // Reset previous borders
            $('input').css('border', '1px solid #e5e5e5');

            // Empty field validation
            requiredFields.forEach(field => {
                const input = $('[name="' + field.name + '"]');
                if ($.trim(input.val()) === '') {
                    isValid = false;
                    input.css('border', '1px solid red');
                    if (!message) message = field.label + ' is required.';
                }
            });

            // Email format validation
            const email = $('[name="add_Email"]').val();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailPattern.test(email)) {
                isValid = false;
                $('[name="add_Email"]').css('border', '1px solid red');
                message = 'Please enter a valid email address.';
            }

            const phoneInput = $('[name="add_Phone"]');
            let phone = phoneInput.val().trim();

            phone = phone.replace(/[\s\-()]/g, '');
            phone = phone.replace(/^\+?91/, '');
            phone = phone.replace(/^0/, '');
            phone = phone.slice(-10);


            // if (phone.length === 11 && phone.startsWith('0')) {
            //     phone = phone.substring(1);
            // }

            const indianPhoneRegex = /^[6-9]\d{9}$/;

            if (!indianPhoneRegex.test(phone)) {
                isValid = false;
                phoneInput.css('border', '1px solid red');
                message = 'Please enter a valid Indian phone number.';
            }

            phoneInput.val(phone);
            // If validation fails, stop submission
            if (!isValid) {
                showMessage('' + message, 'error');
                $('html, body').animate({ scrollTop: $('.checkout__form').offset().top - 300 }, 500);
                return false;
            }
            var finalOrderTotal = $("#order-total").val();

            // Step 2: Collect cart items
            var cartItems = [];
            $('.checkout__order__product ul li').each(function (index, el) {
                if (index === 0) return; // skip header
                var totalText = $(el).find('span').last().text().replace('₹', '').trim();
                var qtyMatch = $(el).text().match(/Qty: (\d+)/);
                var quantity = qtyMatch ? parseInt(qtyMatch[1]) : 1;

                cartItems.push({
                    design_Id: $(el).data('designid') || null,
                    pr_Id: $(el).data('prid') || null,
                    pri_Id: $(el).data('priid') || null,
                    od_Quantity: quantity,
                    od_Original_Price: $(el).data('price') || 0,
                    od_Selling_Price: $(el).data('price') || 0,
                    od_Size: $(el).data('size') || null,
                    pr_Code: $(el).data('prcode') || null,
                    pr_Name: $(el).data('prname') || null,
                    od_Grand_Total: finalOrderTotal
                });
            });

            //  Step 3: Collect all form data
            var formData = $(this).serializeArray();
            formData.push({ name: 'products', value: JSON.stringify(cartItems) });

            //  Step 4: Send AJAX request
            $.ajax({
                url: "<?= base_url('orderdetails/placeOrder') ?>",
                method: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function () {
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                    showMessage('⏳ Placing your order...', 'warning');

                },
                success: function (response) {
                    if (response.status === 'success') {
                        showMessage('' + response.message, 'success');
                        setTimeout(function () {
                            window.location.href = "<?= base_url('') ?>";
                        }, 2000);
                    } else {
                        showMessage(' ' + response.message, 'error');
                    }
                },
                error: function () {
                    showMessage('Something went wrong while placing your order!', 'error');
                }
            });


        });



        $('.checkout__form__free_tee').on('submit', function (e) {
            e.preventDefault();

            // 1️⃣ VALIDATION
            let isValid = true;
            let message = '';

            const requiredFields = [
                { name: 'add_Name', label: 'First Name' },
                { name: 'add_LastName', label: 'Last Name' },
                { name: 'add_Country', label: 'Country' },
                { name: 'add_Street', label: 'Street Address' },
                { name: 'add_City', label: 'City' },
                { name: 'add_State', label: 'State' },
                { name: 'add_Pincode', label: 'Pincode' },
                { name: 'add_Phone', label: 'Phone' },
                { name: 'add_Email', label: 'Email' }
            ];

            $('input').css('border', '1px solid #e5e5e5');

            requiredFields.forEach(field => {
                const input = $('[name="' + field.name + '"]');
                if ($.trim(input.val()) === '') {
                    isValid = false;
                    input.css('border', '1px solid red');
                    if (!message) message = field.label + ' is required.';
                }
            });

            const email = $('[name="add_Email"]').val();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailPattern.test(email)) {
                isValid = false;
                $('[name="add_Email"]').css('border', '1px solid red');
                message = 'Please enter a valid email address.';
            }

            const phoneInput = $('[name="add_Phone"]');
            let phone = phoneInput.val().trim();

            phone = phone.replace(/[\s\-()]/g, '');
            phone = phone.replace(/^\+?91/, '');
            phone = phone.replace(/^0/, '');
            phone = phone.slice(-10);

            const indianPhoneRegex = /^[6-9]\d{9}$/;

            if (!indianPhoneRegex.test(phone)) {
                isValid = false;
                phoneInput.css('border', '1px solid red');
                message = 'Please enter a valid Indian phone number.';
            }

            phoneInput.val(phone);

            if (!isValid) {
                showMessage(message, 'error');
                $('html, body').animate({ scrollTop: $('.checkout__form__free_tee').offset().top - 300 }, 500);
                return false;
            }

            // 2️⃣ FORM DATA
            var formData = $('.checkout__form__free_tee').serializeArray();

            formData.push({ name: 'pr_Id', value: $("#free_prid").val() });
            formData.push({ name: 'pr_Name', value: $("#free_prname").val() });
            formData.push({ name: 'pr_Code', value: $("#free_prcode").val() });
            formData.push({ name: 'od_Size', value: $("#free_size").val() });

            // 3️⃣ AJAX REQUEST
            $.ajax({
                url: "<?= base_url('orderdetails/placeFreeOrder') ?>",
                method: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function () {
                    showMessage("⏳ Processing your free order...", "warning");
                },
                success: function (response) {
                    if (response.status === "success") {
                        showMessage(response.message, "success");
                        setTimeout(function () {
                            window.location.href = "<?= base_url('') ?>";
                        }, 2000);
                    } else {
                        showMessage(response.message, "error");
                    }
                }
            });
        });


        $("#pasteCoupon").on("click", async function () {
            try {
                let text = await navigator.clipboard.readText();
                $("#coupen_code").val(text);

                // animate icon feedback
                $(this).removeClass("fa-paste").addClass("fa-check");
                setTimeout(() => {
                    $("#pasteCoupon").removeClass("fa-check").addClass("fa-paste");
                }, 1200);
            } catch (err) {
                alert("Unable to access clipboard. Please paste manually (Ctrl+V).");
            }
        });

        $(document).on("click", "#apply_coupen_code", function () {
            let coupen_code = $("#coupen_code").val().trim();
            validateCoupon(coupen_code);
        });

        function validateCoupon(coupen_code) {

            $.ajax({
                url: base_url + "orderdetails/validateCoupon",
                type: "POST",
                data: {
                    coupen_code: coupen_code,
                    [csrfName]: csrfHash
                },
                dataType: "json",

                success: function (response) {
                    if (response.status === "success") {
                        applyDiscount(response.discount);
                        alert("Coupon Valid!\n" + response.message);
                    } else {
                        alert("Invalid Coupon!\n" + response.message);
                    }
                },

                error: function () {
                    alert("Error!\nServer error. Try again later.");
                }
            });
        }

        function applyDiscount(discountPercent) {

            let newSubtotal = 0;

            $(".checkout__order__product ul li").each(function (index, el) {
                if (index === 0) return; // skip header

                let price = parseFloat($(el).data("price"));
                let qtyMatch = $(el).text().match(/Qty: (\d+)/);
                let quantity = qtyMatch ? parseInt(qtyMatch[1]) : 1;

                let originalTotal = price * quantity;
                let discountedTotal = originalTotal - (originalTotal * discountPercent / 100);

                newSubtotal += discountedTotal;

                // Update UI inside <li>
                $(el).find("span").last().html(`
                <span> &nbsp;${discountPercent}% OFF</span>
            <span style="text-decoration: line-through; color:#999;">
                ₹${originalTotal.toFixed(2)}
            </span><br>
            <span style="color:#28a745; font-weight:bold; font-size:15px;">
                ₹${discountedTotal.toFixed(2)}
            </span>
        `);

                // Update data attribute so final order calculation uses new value
                $(el).attr("data-price", (discountedTotal / quantity).toFixed(2));
            });

            // Update totals in summary
            // $(".checkout__order__total ul li:eq(0) span").text(${discountPercent}"% OFF" &nbsp; "₹ " + newSubtotal.toFixed(2));
            $(".checkout__order__total ul li:eq(0) span").html(
                `${discountPercent}% OFF &nbsp; ₹ ${newSubtotal.toFixed(2)}`
            );
            $(".checkout__order__total ul li:eq(1) span").text("₹ " + newSubtotal.toFixed(2));

            // Update hidden input
            $("#order-total").val(newSubtotal.toFixed(2));
        }


    });
</script>