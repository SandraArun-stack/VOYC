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
        $(".show-after").hide();
        const video = document.querySelector(".video-section video");

        const hasPlayedThisSession = sessionStorage.getItem("videoPlayedOnce");

        if (hasPlayedThisSession) {
            $(".video-section").hide();
            showMainContent();
        } else {
            if (video) {
                video.addEventListener("ended", runSequence);
            } else {
                setTimeout(runSequence, 2000);
            }
        }

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
    });

</script>