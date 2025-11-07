<script>
    //24 hours
    //     $(document).ready(function () {
    //     const video = document.querySelector(".video-section video");

    //     const hasPlayedBefore = localStorage.getItem("videoPlayedOnce");

    //     if (hasPlayedBefore) {
    //         $(".video-section").hide();
    //         showMainContent();
    //     } else {
    //         localStorage.setItem("videoPlayedOnce", "true");

    //         if (video) {
    //             video.addEventListener("ended", runSequence);
    //         } else {
    //             setTimeout(runSequence, 2000);
    //         }
    //     }

    //     function runSequence() {
    //         $(".show-after").hide();
    //         $(".video-section").fadeOut(800, function () {
    //             showMainContent();
    //         });
    //     }

    //     function showMainContent() {
    //         $(".show-after").fadeIn(800, function () {
    //             $("#categoriesModal").fadeIn(600, function () {
    //                 AOS.refresh();
    //             });
    //         });
    //     }
    // });

    $(document).ready(function () {
        function runSequence() {
            $(".video-section").fadeOut(800, function () {
                showMainContent();
            });
        }

        function showMainContent() {
            sessionStorage.setItem("videoPlayedOnce", "true");
            $(".show-after").fadeIn(800, function () {
                $("#categoriesModal").fadeIn(600, function () {
                    AOS.refresh();
                });
            });
        }
        $(".show-after").hide();
        const video = document.querySelector(".video-section video");
        const skipBtn = $("#skipVideoBtn");

        const hasPlayedThisSession = sessionStorage.getItem("videoPlayedOnce");

        if (hasPlayedThisSession) {
            $(".video-section").hide();
            showMainContent();
        } else {
            setTimeout(() => {
                skipBtn.fadeIn(400);
            }, 2000);

            skipBtn.on("click", function () {
                runSequence();
            });
            if (video) {
                video.addEventListener("ended", runSequence);
            } else {
                setTimeout(runSequence, 2000);
            }
        }

        $(".filter__controls li").on("click", function () {

        let filter = $(this).data("filter");

        $(".filter__controls li").removeClass("active");
        $(this).addClass("active");

        if (filter === "all") {
            $(".product-box").show();
        } else {
            $(".product-box").hide();
            $("." + filter).show();
        }
    });

    });

</script>