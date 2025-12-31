<script>
    var base_url = "<?= base_url(); ?>";
    var csrfName = "<?= csrf_token(); ?>";
    var csrfHash = "<?= csrf_hash(); ?>";

    $(document).ready(function () {

        var finalOrderTotal = $("#order-total").val();

        let shippingCharge = parseFloat($('#shipping_charge_value').val());
        let minAmount = parseFloat($('#minimum_amount_for_shipping_charge').val());

        let finalShippingCharge = shippingCharge;
        let shippingText = $('#shipping_charge_text');
        if (finalOrderTotal >= minAmount) {
            shippingText.html(
                `<del class="small">₹ ${shippingCharge.toFixed(2)}</del> &nbsp;<span class="text-success">₹ 0.00</span>`
            );
            finalShippingCharge = 0;
        } else if (finalOrderTotal < minAmount) {

            shippingText.html(`₹ ${shippingCharge.toFixed(2)}`);

        }


        finalOrderTotal = parseFloat(finalOrderTotal);
        finalShippingCharge = parseFloat(finalShippingCharge);

        let grandTotal = finalOrderTotal + finalShippingCharge;

        $('#subtotal span').html(`₹ ${grandTotal.toFixed(2)}`);
        $('#total_of_all span').html(`₹ ${grandTotal.toFixed(2)}`);



        // const isSameAsShipping = $('#same_as_shipping').is(':checked');

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

        $('#checkout__form').on('submit', function (e) {
            e.preventDefault();
            const hasExistingBillingSelected =
                $('input[name="billing_address_id"]:checked').length > 0;
            const hasExistingShippingSelected =
                $('input[name="shipping_address_id"]:checked').length > 0;

            const isSameAsShipping =
                $('#same_as_shipping').is(':checked');

            //  Step 1: Validation before placing order
            let isValid = true;
            let message = '';

            if (!hasExistingBillingSelected) {
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
            }

            // If validation fails, stop submission
            if (!isValid) {
                showMessage('' + message, 'error');
                $('html, body').animate({ scrollTop: $('#checkout__form').offset().top - 300 }, 500);
                return false;
            }

            // ✅ Shipping validation ONLY if needed
            if (!isSameAsShipping && !hasExistingShippingSelected) {

                const shippingRequiredFields = [
                    { name: 'shipping_add_Name', label: 'Shipping First Name' },
                    { name: 'shipping_add_LastName', label: 'Shipping Last Name' },
                    { name: 'shipping_add_Street', label: 'Shipping Address' },
                    { name: 'shipping_add_City', label: 'Shipping City' },
                    { name: 'shipping_add_State', label: 'Shipping State' },
                    { name: 'shipping_add_Pincode', label: 'Shipping Pincode' },
                    { name: 'shipping_add_Phone', label: 'Shipping Phone' },
                    { name: 'shipping_add_Email', label: 'Shipping Email' }
                ];

                shippingRequiredFields.forEach(field => {
                    const input = $('[name="' + field.name + '"]');
                    if ($.trim(input.val()) === '') {
                        isValid = false;
                        input.css('border', '1px solid red');
                        if (!message) message = field.label + ' is required.';
                    }
                });

                if (!isValid) {
                    showMessage(message, 'error');
                    $('html, body').animate({ scrollTop: $('#checkout__form').offset().top - 300 }, 500);
                    return false;
                }
            }

            var finalOrderTotal = $("#order-total").val();

            // Step 2: Collect cart items
            var cartItems = [];
            $('.checkout__order__product ul li').each(function (index, el) {
                if (index === 0) return; // skip header
                var totalText = $(el).find('span').last().text().replace('₹', '').trim();
                var qtyMatch = $(el).text().match(/Qty: (\d+)/);
                var quantity = qtyMatch ? parseInt(qtyMatch[1]) : 1;

                let originalPrice = parseFloat($(el).data("original-price")) || parseFloat($(el).data("price"));
                let sellingPrice = parseFloat($(el).data("selling-price")) || originalPrice;

                cartItems.push({
                    design_Id: $(el).data('designid') || null,
                    pr_Id: $(el).data('prid') || null,
                    pri_Id: $(el).data('priid') || null,
                    prv_Id: $(el).data('prvid') || null,
                    od_Quantity: quantity,

                    od_Original_Price: originalPrice,
                    od_Selling_Price: sellingPrice,

                    od_DiscountValue: appliedDiscountPercent,
                    od_DiscountType: appliedDiscountPercent > 0 ? '%' : null,

                    od_Size: $(el).data('size') || null,
                    pr_Code: $(el).data('prcode') || null,
                    pr_Name: $(el).data('prname') || null,

                    od_Grand_Total: finalGrandTotal
                });

            });

            let applied_lb_Id = null;
            //  Step 3: Collect all form data
            var formData = $(this).serializeArray();
            formData.push({ name: 'products', value: JSON.stringify(cartItems) });
            formData.push({ name: 'lb_Id', value: applied_lb_Id });
            //<----------------------------------------------no razor payment---------------------------------------------------------------------------------------->
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
            //<----------------------------------------------no razor payment---------------------------------------------------------------------------------------->
            // Step 4: Create Razorpay order first

            //<----------------------------------------------razor payment---------------------------------------------------------------------------------------->

            // $.ajax({
            //     url: "<?= base_url('payment/createRazorpayOrder') ?>",
            //     method: "POST",
            //     data: {
            //         amount: finalOrderTotal, // in rupees
            //         <?= csrf_token() ?>: "<?= csrf_hash() ?>"
            //     },
            //     dataType: "json",
            //     success: function (res) {

            //         if (res.status !== 'success') {
            //             showMessage(res.message, 'error');
            //             return;
            //         }

            //         var options = {
            //             key: res.key,
            //             amount: res.amount,
            //             currency: "INR",
            //             name: "Voyc",
            //             description: "Order Payment",
            //             order_id: res.order_id,

            //             handler: function (response) {

            //                 // ✅ Payment success → now place order
            //                 formData.push({ name: 'razorpay_payment_id', value: response.razorpay_payment_id });
            //                 formData.push({ name: 'razorpay_order_id', value: response.razorpay_order_id });
            //                 formData.push({ name: 'razorpay_signature', value: response.razorpay_signature });

            //                 // 🔥 CALL YOUR EXISTING placeOrder()
            //                 $.ajax({
            //                     url: "<?= base_url('orderdetails/placeOrder') ?>",
            //                     method: "POST",
            //                     data: formData,
            //                     dataType: "json",
            //                     success: function (response) {

            //                         sessionStorage.setItem(
            //                             'paymentData',
            //                             JSON.stringify(response.data || {})
            //                         );

            //                         window.location.href = response.redirect;
            //                     },
            //                     error: function () {
            //                         window.location.href = "<?= base_url('payment/failure') ?>";
            //                     }
            //                 });
            //             }
            //         };

            //         var rzp = new Razorpay(options);

            //         rzp.on('payment.failed', function (err) {
            //             sessionStorage.setItem('paymentData', JSON.stringify({
            //                 order_id: err.error.metadata.order_id || '',
            //                 amount: finalOrderTotal
            //             }));
            //             window.location.href = "<?= base_url('payment/failure') ?>";
            //         });

            //         rzp.open();
            //     }
            // });

            //<----------------------------------------------razor payment---------------------------------------------------------------------------------------->

        });

        $('#checkout__form__free_tee').on('submit', function (e) {
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
                $('html, body').animate({ scrollTop: $('#checkout__form__free_tee').offset().top - 300 }, 500);
                return false;
            }

            // 2️⃣ FORM DATA
            var formData = $('#checkout__form__free_tee').serializeArray();

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
                        applied_lb_Id = response.lb_Id;
                        showAlert(response.message, 'success');
                    } else {
                        showAlert(response.message, 'error');
                    }
                },

                error: function () {
                    showAlert("Server error. Try again later.", 'error');
                }
            });
        }

        function showAlert(message, type = 'success') {
            const alertDiv = $('#alertPlaceOrder');
            alertDiv.removeClass('d-none alert-success alert-danger alert-warning')
                .addClass(type === 'success' ? 'alert-success' :
                    type === 'error' ? 'alert-danger' : 'alert-warning')
                .html(message);
            // Optionally auto-hide after 3 seconds
            setTimeout(() => {
                alertDiv.addClass('d-none').html('');
            }, 3000);
        }

        let appliedDiscountPercent = 0;
        let finalGrandTotal = 0;

        function applyDiscount(discountPercent) {

            appliedDiscountPercent = parseFloat(discountPercent) || 0;
            let newSubtotal = 0;
            let originalSubtotal = 0;

            $(".checkout__order__product ul li").each(function (index, el) {

                if (index === 0) return;

                let $el = $(el);

                let originalPrice = parseFloat($el.data("price"));
                let quantity = parseInt(
                    $el.find("small").text().match(/Qty:\s*(\d+)/)?.[1] || 1
                );

                let originalTotal = originalPrice * quantity;
                // alert(originalTotal);
                let discountAmount = (originalTotal * appliedDiscountPercent) / 100;
                alert(discountAmount);
                let discountedTotal = originalTotal - discountAmount;

                originalSubtotal += originalTotal;
                newSubtotal += discountedTotal;

                // Product row UI
                if (appliedDiscountPercent > 0) {
                    $el.find("span").html(`
                     <div style="font-size:11px;color: #ee2020;text-align:right;">
                        ${appliedDiscountPercent}% OFF
                    </div>
                <span style="white-space:nowrap;">
                    <del style="color:#999;font-size:12px;">
                        ₹ ${originalTotal.toFixed(2)}
                    </del>
                    &nbsp;
                    <strong>₹ ${discountedTotal.toFixed(2)}</strong>
                </span>
               
            `);
                }

                $el.data("selling-price", (discountedTotal / quantity).toFixed(2));
                $el.data("discount", appliedDiscountPercent);
            });

            // 🔥 SUBTOTAL UI (with discount)
            if (appliedDiscountPercent > 0) {
                $("#subtotal span").html(`
               
                <span style="white-space:nowrap;">
                    <del style="color:#999;font-size:12px;">
                        ₹ ${originalSubtotal.toFixed(2)}
                    </del>
                    &nbsp;
                    <strong>₹ ${newSubtotal.toFixed(2)}</strong>
                </span>
                <p style="font-size: 17px;
                            font-weight: 600;
                            color: #ee2020;
                            padding:0;
                            text-align:right;
                            margin:0;">
                        ${appliedDiscountPercent}% OFF
                    </p>
                
            `);
            } else {
                $("#subtotal span").text("₹ " + newSubtotal.toFixed(2));
            }

            // ❌ TOTAL stays unchanged
            $("#total_of_all span").text("₹ " + newSubtotal.toFixed(2));

            // Backend value
            $("#order-total").val(newSubtotal.toFixed(2));
            finalGrandTotal = newSubtotal;
        }

        function fetchShippingData() {
            return $.ajax({
                url: base_url + 'orderdetails/getShippingCharge', // Adjust the URL accordingly
                method: 'GET',
                dataType: 'json',
            });
        }



        $("#same_as_shipping").on("change", function () {
            if ($(this).is(":checked")) {
                $("#shipping_address_section").addClass("d-none");
            } else {
                $("#shipping_address_section").removeClass("d-none");
            }
        });

        // Add Shipping Address button
        $("#add_shipping_address_btn").on("click", function () {
            $("#same_as_shipping").prop("checked", false).trigger("change");
        });

        const $billingSection = $('#billingDetailsSection');
        const $existingSection = $('#existingAddressSection');

        const $billingToggle = $('#billingToggle');
        const $existingToggle = $('#existingAddressToggle');

        const billingCollapse = $billingSection.length ? new bootstrap.Collapse($billingSection[0], { toggle: false }) : null;
        const existingCollapse = $existingSection.length ? new bootstrap.Collapse($existingSection[0], { toggle: false }) : null;

        // Click: Add New Billing Detail
        $billingToggle.on('click', function () {
            billingCollapse?.show();
            existingCollapse?.hide();
        });

        // Click: Select Existing Address
        $existingToggle.on('click', function () {
            existingCollapse?.show();
            billingCollapse?.hide();
        });

        // Arrow rotation sync
        $('[data-bs-toggle="collapse"]').each(function () {
            const $toggle = $(this);
            const targetSelector = $toggle.attr('data-bs-target');
            const $target = $(targetSelector);
            const $icon = $toggle.find('.toggle-icon');

            if (!$target.length || !$icon.length) return;

            $target.on('shown.bs.collapse', function () {
                $icon.addClass('rotate');
            });

            $target.on('hidden.bs.collapse', function () {
                $icon.removeClass('rotate');
            });
        });



        $('#existingAddressToggle').on('click', function () {
            $('#existingAddressToggle').addClass('active');
            $('#billingToggle').removeClass('active');
        });

        $('.address-box').on('click', function (e) {
            if (!$(e.target).is('input[type="radio"]')) {
                $(this).find('.select-address-radio')
                    .prop('checked', true)
                    .trigger('change');
            }
        });

        $('.select-address-radio').on('change', function () {
            $('.address-box').removeClass('active');
            $(this).closest('.address-box').addClass('active');
        });

        // Clear selection when Add New Billing clicked
        $('#billingToggle').on('click', function () {
            $('.select-address-radio').prop('checked', false);
            $('.address-box').removeClass('active');
        });

        // const shippingExistingCollapse = new bootstrap.Collapse(
        //     document.getElementById('existingShippingAddressSection'),
        //     { toggle: false }
        // );

        // const shippingNewCollapse = new bootstrap.Collapse(
        //     document.getElementById('newShippingAddressSection'),
        //     { toggle: false }
        // );

        const existingShippingEl = document.getElementById('existingShippingAddressSection');
        const newShippingEl = document.getElementById('newShippingAddressSection');

        const shippingExistingCollapse = existingShippingEl
            ? new bootstrap.Collapse(existingShippingEl, { toggle: false })
            : null;

        const shippingNewCollapse = newShippingEl
            ? new bootstrap.Collapse(newShippingEl, { toggle: false })
            : null;

        // Add New Shipping
        $('#addShippingToggle').on('click', function () {
            // shippingNewCollapse.show();
            // shippingExistingCollapse.hide();

            shippingNewCollapse?.show();
            shippingExistingCollapse?.hide();

            $('#addShippingToggle').addClass('active');
            $('#existingShippingToggle').removeClass('active');

            $('#addShippingToggle .toggle-icon').addClass('rotate');
            $('#existingShippingToggle .toggle-icon').removeClass('rotate');

            $('.select-address-shipping-radio').prop('checked', false);
            $('.address-box-shipping').removeClass('active');
        });

        // Select Existing Shipping
        $('#existingShippingToggle').on('click', function () {
            shippingExistingCollapse.show();
            shippingNewCollapse.hide();

            $('#existingShippingToggle').addClass('active');
            $('#addShippingToggle').removeClass('active');

            $('#existingShippingToggle .toggle-icon').addClass('rotate');
            $('#addShippingToggle .toggle-icon').removeClass('rotate');
        });

        $('#add_shipping_address_btn').on('click', function () {
            $('#shipping_address_section').removeClass('d-none');

            // Default: show existing shipping if available
            $('#existingShippingAddressToggle').trigger('click');
        });
        // SHIPPING: Click anywhere in the card selects radio
        $('.address-box-shipping').on('click', function (e) {
            if (!$(e.target).is('input[type="radio"]')) {
                $(this).find('.select-address-shipping-radio')
                    .prop('checked', true)
                    .trigger('change');
            }
        });

        // SHIPPING: Highlight selected card
        $('.select-address-shipping-radio').on('change', function () {
            $('.address-box-shipping').removeClass('active');
            $(this).closest('.address-box-shipping').addClass('active');
        });


    });


</script>