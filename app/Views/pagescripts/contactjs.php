<script>
    $(document).ready(function () {

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidIndianPhone(phone) {
            const phoneRegex = /^(0[6-9]\d{9}|[6-9]\d{9})$/;
            return phoneRegex.test(phone);
        }



        function showAlert(message, type = 'success') {
            // type can be 'success', 'danger', 'warning', 'info'
            $('#formAlert')
                .removeClass('alert-success alert-danger alert-warning alert-info')
                .addClass('alert-' + type)
                .text(message)
                .fadeIn()
                .delay(3000)
                .fadeOut();
        }

        $("#contactForm button").on("click", function () {

            let fullname = $("#contactForm input[name='fullname']").val().trim();
            let email = $("#contactForm input[name='email']").val().trim();
            let contact_no = $("#contactForm input[name='contact_no']").val().trim();
            let message = $("#contactForm textarea[name='message']").val().trim();

            // alert(fullname);
            // if (fullname === "" || email === "" || contact_no === "" || message === "") {
            //      $('html, body').animate({ scrollTop: 0 }, 'fast');
            //     showAlert("Please Fill in All Fields!", 'danger');
            //     return;
            // }
            $("#contactForm input, #contactForm textarea").css("border", "1px solid #ccc");

            let hasError = false;

            //  Empty field check + red border 
            if (fullname === "") {
                $("#contactForm input[name='fullname']").css("border", "2px solid red");
                hasError = true;
            }
            if (email === "") {
                $("#contactForm input[name='email']").css("border", "2px solid red");
                hasError = true;
            }
            if (contact_no === "") {
                $("#contactForm input[name='contact_no']").css("border", "2px solid red");
                hasError = true;
            }
            if (message === "") {
                $("#contactForm textarea[name='message']").css("border", "2px solid red");
                hasError = true;
            }

            if (hasError) {
                $('html, body').animate({ scrollTop: 0 }, 'fast');
                showAlert("Please Fill in All Fields!", 'danger');
                return;
            }
            // Validation
            if (!isValidEmail(email)) {
                $('html, body').animate({ scrollTop: 0 }, 'fast');
                showAlert("Please Enter a Valid Email Address!", 'danger');
                return;
            }

            if (!isValidIndianPhone(contact_no)) {
                $('html, body').animate({ scrollTop: 0 }, 'fast');
                showAlert("Enter Valid Indian Mobile Number", 'danger');
                return;
            }

            // AJAX request
            $.ajax({
            url: "<?= base_url('contact/save'); ?>",
            type: "POST",
            data: { fullname, email, contact_no, message },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                    showAlert("Thank you! Your Enquiry Has Been Sent. We Will Get Back to You Soon.", 'success');
                    $("#contactForm")[0].reset();
                    $("#contactForm input, #contactForm textarea").css("border", "1px solid #ccc");
                } else {
                    let errorMsg = response.msg || 'Failed to send message';
                    showAlert(errorMsg, 'danger');
                }
            },
            error: function () {
                showAlert("Something went wrong. Please try again later.", 'danger');
            }
        });
    });

    //  remove red border while typing
    $("#contactForm input, #contactForm textarea").on("input", function () {
        $(this).css("border", "1px solid #ccc");
    });

});
</script>