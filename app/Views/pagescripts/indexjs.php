<script>
    $(window).on("load", function () {
        setTimeout(function () {
            $(".show-after").hide()
            $(".video-section").fadeOut(800, function () {
                $(".show-after").fadeIn(800);
            });
        }, 1000);
    });
</script>