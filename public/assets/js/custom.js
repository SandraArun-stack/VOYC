window.onload = function () {
    AOS.init({
        duration: 1000,
        once: false,
        mirror: true
    });

};

$(document).ready(function () {

    var path = window.location.pathname
        .toLowerCase()
    // .replace(/\/+$/, "");

    // $(".header__menu ul li").removeClass("active");

    if (path.includes("women")) {
        $("#women").parent().addClass("active");
    }
    else if (path.includes("men")) {
        $("#men").parent().addClass("active");
    }
    else if (path.includes("game_arena")) {
        $("#game_arena").parent().addClass("active");
    }
    else if (path.includes("contact")) {
        $("#contact").parent().addClass("active");
    }
    else if (path === "/voyc" || path === "" || path === "/voyc/") {
        $("#home").parent().addClass("active");
    }

    $(".header__menu ul li a").on("click", function () {
        $(".header__menu ul li").removeClass("active");
        $(this).parent().addClass("active");
    });


    $("#leader_board").on("click", function (e) {
        e.preventDefault();

        $("#categoriesModal").addClass("d-block");

        setTimeout(() => {
            // AOS.refreshHard();
            // autoScrollPlayers();
        }, 50);
    });


    $(".close-btn").on("click", function () {
        $("#categoriesModal").removeClass("d-block");
    });

    $(".product__item").on("click", function (e) {
        if (!$(e.target).closest(".product__hover").length) {
            window.location.href = $(this).data("url");
        }
    });

    $('.see-more').on('click', function () {
        var link = $(this);
        var reviewContainer = link.closest('.review-text');
        var fullReview = reviewContainer.find('.full-review').text();
        var isExpanded = link.text() === 'See less';

        if (isExpanded) {
            reviewContainer.contents().first()[0].textContent = fullReview.substring(0, 150) + '... ';
            link.text('See more');
        } else {
            reviewContainer.contents().first()[0].textContent = fullReview + ' ';
            link.text('See less');
        }
    });

    let reviewsPerPage = 5;
    let shown = reviewsPerPage;
    const totalReviews = $('.reviews-container .review-box').length;

    $('#load-more-reviews').on('click', function () {
        const nextReviews = $('.reviews-container .review-box').slice(shown, shown + reviewsPerPage);
        nextReviews.slideDown();
        shown += reviewsPerPage;
        if (shown >= totalReviews) {
            $(this).fadeOut();
        }
    });

    const authModal = new bootstrap.Modal(document.getElementById('authModal'), {
        backdrop: true,
        keyboard: true
    });

    $('#login-link').on('click', function (e) {
        e.preventDefault();
        $('#registerView').hide();
        $('#forgotPassView').hide();
        $('#loginView').show();
        authModal.show();
    });

    $('#register-link').on('click', function (e) {
        e.preventDefault();
        $('#loginView').hide();
        $('#forgotPassView').hide();
        $('#registerView').show();
        authModal.show();
    });

    $(document).on('click', '#to-register', function (e) {
        e.preventDefault();
        $('#loginView').fadeOut(200, function () {
            $('#registerView').fadeIn(200);
            $('#forgotPassView').addClass('d-none');
        });
    });

    $(document).on('click', '#to-login', function (e) {
        e.preventDefault();
        $('#registerView').fadeOut(200, function () {
            $('#forgotPassView').addClass('d-none');
            $('#loginView').fadeIn(200);

        });
    });

    $(document).on('click', '#to-forgot-password', function (e) {
        e.preventDefault();
        $('#loginView').fadeOut(200, function () {
            $('#forgotPassView').fadeIn(200);
            $('#forgotPassView').removeClass('d-none');
        });
    });

    $(document).on('click', '#to-login-from-forgot', function (e) {
        e.preventDefault();
        $('#registerView').fadeOut(200, function () {
            $('#forgotPassView').addClass('d-none');
            $('#loginView').fadeIn(200);

        });
    });


    //eye icon in register form
    $('.toggle-password').on('click', function () {
        const target = $($(this).data('target'));
        const icon = $(this);

        if (target.attr('type') === 'password') {
            target.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            target.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });

    //register ajax
    $('#btn_register').on('click', function (e) {
        e.preventDefault();
        var formData = $('#registerForm').serialize();
        var registerUrl = $(this).data('url');
        var $alertBox = $('#reg_msg_alert');

        $alertBox.addClass('d-none').removeClass('alert-success alert-danger').text('');

        $.ajax({
            url: registerUrl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                $('#btn_register').prop('disabled', true).text('Sign Up...');
            },
            success: function (response) {
                $('#btn_register').prop('disabled', false).text('Sign UP');

                if (response.status === 'success') {

                    $alertBox
                        .removeClass('d-none alert-danger')
                        .addClass('alert alert-success')
                        .text(response.message || 'Registration successful!')
                        .fadeIn();

                    $('#registerForm')[0].reset();

                    setTimeout(() => {
                        $alertBox.fadeOut(400, function () {
                            $('#registerView').fadeOut(200, function () {
                                $('#loginView').fadeIn(300);
                            });
                        });
                    }, 2000);
                }

                else {
                    $alertBox
                        .removeClass('d-none alert-success')
                        .addClass('alert alert-danger')
                        .text(response.message || 'Registration failed!')
                        .fadeIn();

                    setTimeout(() => {
                        $alertBox.fadeOut(500);
                    }, 3000);
                }
            },
            error: function () {
                $('#btn_register').prop('disabled', false).text('Register');
                $alertBox
                    .removeClass('d-none alert-success')
                    .addClass('alert alert-danger')
                    .text('Something went wrong! Please try again.')
                    .fadeIn();

                setTimeout(() => {
                    $alertBox.fadeOut(500);
                }, 3000);
            }

        });
    });



    $('#btn_login').on('click', function (e) {
        e.preventDefault();

        var $alertBox = $('#login_msg_alert');
        var email = $.trim($('input[name="login_email"]').val());
        var password = $.trim($('input[name="login_password"]').val());
        var captchaResponse = grecaptcha.getResponse();
        var loginUrl = $(this).data('url');

        // Hide old messages
        $alertBox.addClass('d-none').removeClass('alert-success alert-danger').text('');

        // Validate required fields
        if (!email || !password) {
            $alertBox
                .removeClass('d-none alert-success')
                .addClass('alert alert-danger')
                .text('All Required Fields Must Be Completed.')
                .fadeIn();

            setTimeout(() => {
                $alertBox.fadeOut(500);
            }, 3000);
            return;
        }

        // Validate reCAPTCHA
        if (!captchaResponse) {
            $alertBox
                .removeClass('d-none alert-success')
                .addClass('alert alert-danger')
                .text('Please verify you are not a robot.')
                .fadeIn();

            setTimeout(() => {
                $alertBox.fadeOut(500);
            }, 3000);
            return;
        }

        // Prepare form data
        var formDataLogin = $('#loginForm').serialize() + '&g-recaptcha-response=' + captchaResponse;

        // AJAX request
        $.ajax({
            url: loginUrl,
            type: 'POST',
            data: formDataLogin,
            dataType: 'json',
            beforeSend: function () {
                $('#btn_login').prop('disabled', true).text('Signing In...');
            },
            success: function (response) {
                $('#btn_login').prop('disabled', false).text('Sign In');
                grecaptcha.reset();

                if (response.status === 'success') {
                    $alertBox
                        .removeClass('d-none alert-danger')
                        .addClass('alert alert-success')
                        .text(response.message || 'Login successful!')
                        .fadeIn();

                    $('#loginForm')[0].reset();

                    setTimeout(() => {
                        $alertBox.fadeOut(400, function () {
                            authModal?.hide();
                        });
                        location.reload();
                    }, 2000);
                } else {
                    $alertBox
                        .removeClass('d-none alert-success')
                        .addClass('alert alert-danger')
                        .text(response.message || 'Login failed! Please try again.')
                        .fadeIn();

                    setTimeout(() => {
                        $alertBox.fadeOut(500);
                    }, 3000);
                }
            },
            error: function () {
                $('#btn_login').prop('disabled', false).text('Sign In');
                $alertBox
                    .removeClass('d-none alert-success')
                    .addClass('alert alert-danger')
                    .text('Something went wrong! Please try again.')
                    .fadeIn();

                setTimeout(() => {
                    $alertBox.fadeOut(500);
                }, 3000);
            }
        });
    });


    $('.login_close').on('click', function (e) {
        authModal.hide();
    });
    // show logout modal pop up
    $('#logoutBtn').on('click', function (e) {
        e.preventDefault();
        $('#logoutModal').modal('show');
    });
    $('#cancel_log_out').on('click', function () {
        $('#logoutModal').modal('hide');
    });
    $('#confirmLogout').on('click', function () {
        var logOut = $(this).data('url');

        $.ajax({
            url: logOut,
            type: 'POST',
            success: function (response) {
                $('#logoutModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error: function () {
                alert('Logout failed. Please try again.');
            }
        });
    });

    let showLoginPopup = "<?= $showLoginPopup ?? '' ?>";
    // if flag not set → do nothing
    if (!showLoginPopup) return;

    // wait for video end
    var introVideo = document.getElementById("introVideo");

    if (introVideo) {
        introVideo.addEventListener("ended", function () {
            $("#registerView").hide();
            $("#forgotPassView").addClass("d-none");
            $("#loginView").fadeIn(200);
            $("#authModal").modal("show");

        });
    }

    $("#search-toggle").click(function () {
        $("#search-bar").toggleClass("active");
        if ($("#search-bar").hasClass("active")) {
            $("#search-bar").focus();
        }
    });


    $('.header__right__auth').on('click', function (e) {

        if ($(e.target).closest('.dropdown-menu a').length) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        const $dropdown = $(this).find('#userDropDown');
        const $menu = $dropdown.find('.dropdown-menu');

        if ($dropdown.length) {
            if ($menu.is(':visible')) {
                $menu.hide();
            } else {
                $('.dropdown-menu').hide();
                $menu.show();
            }
        }
    });

    $(document).on('click', '.dropdown-menu a', function () {
        $('.dropdown-menu').hide();
    });

    // 🔍 Search redirect
    $("#search-bar").on("keypress", function (e) {
        if (e.which === 13) { // Enter key pressed
            const searchTerm = $(this).val().trim();
            if (searchTerm.length > 0) {
                $("#search-bar").removeClass("active");
                window.location.href = base_url + "/shop?search=" + encodeURIComponent(searchTerm);
            } else {
                alert("Please enter a search term.");
            }
        }
    });

    $('#btn_forgot_password').on('click', function (e) {
        e.preventDefault();

        var forgotUrl = $(this).data('url');
        var email = $('input[name="forgot_email"]').val();
        var alertBox = $('#forgotalert');

        alertBox.addClass('d-none').removeClass('alert-success alert-danger').text('');

        $.ajax({
            url: forgotUrl,
            type: 'POST',
            data: { forgot_email: email },
            dataType: 'json',
            success: function (res) {
                // Show alert with success or error message
                if (res.status === 'success') {
                    alertBox
                        .removeClass('d-none')
                        .addClass('alert-success')
                        .text(res.message)
                        .fadeIn(200);
                } else {
                    alertBox
                        .removeClass('d-none')
                        .addClass('alert-danger')
                        .text(res.message)
                        .fadeIn(200);
                }

                // ✅ Fade out after 3 seconds
                setTimeout(function () {
                    alertBox.fadeOut(500, function () {
                        alertBox.addClass('d-none').show(); // reset state
                    });
                }, 3000);
            },
            error: function (xhr, status, error) {
                console.error('Error:', xhr.responseText);
                alertBox
                    .removeClass('d-none')
                    .addClass('alert-danger')
                    .text('Something went wrong. Please try again.')
                    .fadeIn(200);

                // ✅ Fade out after 3 seconds
                setTimeout(function () {
                    alertBox.fadeOut(500, function () {
                        alertBox.addClass('d-none').show(); // reset state
                    });
                }, 3000);
            }
        });
    });

    function autoScrollPlayers() {
        const box = document.getElementById("playersScroll");
        if (!box) return;

        // Ensure modal rendered
        setTimeout(() => {
            box.scrollTop = 0; // start at first item

            const slowDuration = 1000;  // SLOW top → bottom
            const fastDuration = 2000;  // FAST bottom → top

            const startTop = 0;
            const endBottom = box.scrollHeight - box.clientHeight;

            // -------- SLOW scroll down ----------
            let slowStartTime = null;
            function slowScrollDown(ts) {
                if (!slowStartTime) slowStartTime = ts;
                const progress = ts - slowStartTime;
                const t = Math.min(progress / slowDuration, 1);
                box.scrollTop = startTop + t * (endBottom - startTop);
                if (t < 1) {
                    requestAnimationFrame(slowScrollDown);
                } else {
                    box.scrollTop = endBottom; // ensure exact bottom

                    // -------- FAST scroll up ----------
                    let fastStartTime = null;
                    function fastScrollUp(ts2) {
                        if (!fastStartTime) fastStartTime = ts2;
                        const progress2 = ts2 - fastStartTime;
                        const t2 = Math.min(progress2 / fastDuration, 1);
                        box.scrollTop = endBottom - t2 * (endBottom - startTop);
                        if (t2 < 1) {
                            requestAnimationFrame(fastScrollUp);
                        } else {
                            box.scrollTop = startTop; // back to first item
                        }
                    }
                    requestAnimationFrame(fastScrollUp);
                }
            }

            requestAnimationFrame(slowScrollDown);
        }, 200); // give modal time to render
    }

    $(function () {
        $("#dob").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:2025",
            dateFormat: "yy-mm-dd"
        });
    });



});



