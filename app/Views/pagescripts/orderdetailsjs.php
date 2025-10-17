<script>
$(document).ready(function () {

    // Intercept form submission
    $('.checkout__form').on('submit', function (e) {
        e.preventDefault(); // Stop default page reload

        $.ajax({
            url: "<?= base_url('orderdetails/saveAddress') ?>",
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === 'success') {
                    alert('✅ Address saved successfully!');
                    // Optionally redirect or trigger next step
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function () {
                alert('⚠️ Something went wrong while saving address!');
            }
        });
    });

});
</script>
