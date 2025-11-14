<script>
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

            if (phone.length === 11 && phone.startsWith('0')) {
                phone = phone.substring(1);
            }

            const indianPhoneRegex = /^(?:\+91|91)?[6-9]\d{9}$/;

            if (!indianPhoneRegex.test(phone)) {
                isValid = false;
                phoneInput.css('border', '1px solid red');
                message = 'Please enter a valid Indian phone number.';
            }

            // If validation fails, stop submission
            if (!isValid) {
                showMessage('' + message, 'error');
                $('html, body').animate({ scrollTop: $('.checkout__form').offset().top - 100 }, 500);
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
                            window.location.href = "<?= base_url('') ?>"; // redirect after success
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

    });
</script>