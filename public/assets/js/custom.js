window.onload = function () {
    AOS.init({
        duration: 1000,
        once: false,
        mirror: true
    });
};
$(document).ready(function () {
    var currentUrl = window.location.href.toLowerCase();

    if (currentUrl.includes("women")) {
        $(".header__menu ul li").removeClass("active");
        $("#women").parent().addClass("active");
    } else if (currentUrl.includes("men")) {
        $(".header__menu ul li").removeClass("active");
        $("#men").parent().addClass("active");
    } else if (currentUrl.includes("game_arena")) {
        $(".header__menu ul li").removeClass("active");
        $("#game_arena").parent().addClass("active");
    } else if (currentUrl.includes("contact")) {
        $(".header__menu ul li").removeClass("active");
        $("#contact").parent().addClass("active");
    } else {
        $(".header__menu ul li").removeClass("active");
        $("#home").parent().addClass("active");
    }

    $(".header__menu ul li a").on("click", function () {
        $(".header__menu ul li").removeClass("active");
        $(this).parent().addClass("active");
    });
    $("#leader_board").on("click", function (e) {
        e.preventDefault();
        $("#categoriesModal").fadeIn(600);
    });

    $(".close-btn").on("click", function () {
        $("#categoriesModal").hide();
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
        $('#loginView').show();
        authModal.show();
    });

    $('#register-link').on('click', function (e) {
        e.preventDefault();
        $('#loginView').hide();
        $('#registerView').show();
        authModal.show();
    });

    $(document).on('click', '#to-register', function (e) {
        e.preventDefault();
        $('#loginView').fadeOut(200, function () {
            $('#registerView').fadeIn(200);
        });
    });

    $(document).on('click', '#to-login', function (e) {
        e.preventDefault();
        $('#registerView').fadeOut(200, function () {
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

    //login ajax
    // $('#btn_login').on('click', function (e) {
    //     e.preventDefault();
    //     var formDataLogin = $('#loginForm').serialize();
    //     var loginUrl = $(this).data('url');
    //     var $alertBox = $('#login_msg_alert');


    //     $alertBox.addClass('d-none').removeClass('alert-success alert-danger').text('');

    //     var captchaResponse = grecaptcha.getResponse();
    //     if (!captchaResponse) {
    //         $alertBox
    //             .removeClass('d-none alert-success')
    //             .addClass('alert alert-danger')
    //             .text('Please verify you are not a robot.')
    //             .fadeIn();
    //         setTimeout(() => {
    //             $alertBox.fadeOut(500);
    //         }, 3000);
    //         return;

    //     }

    //     formDataLogin += '&g-recaptcha-response=' + captchaResponse;

    //     $.ajax({
    //         url: loginUrl,
    //         type: 'POST',
    //         data: formDataLogin,
    //         dataType: 'json',
    //         beforeSend: function () {
    //             $('#btn_login').prop('disabled', true).text('Sign In...');
    //         },
    //         success: function (response) {

    //             $('#btn_login').prop('disabled', false).text('Sign In');
    //             grecaptcha.reset();

    //             if (response.status === 'success') {
    //                 $alertBox
    //                     .removeClass('d-none alert-danger')
    //                     .addClass('alert alert-success')
    //                     .text(response.message || 'Login successful!')
    //                     .fadeIn();

    //                 $('#loginForm')[0].reset();

    //                 setTimeout(() => {
    //                     $alertBox.fadeOut(400, function () {
    //                         authModal?.hide();
    //                     });
    //                     location.reload();
    //                 }, 2000);
    //             } else {
    //                 $alertBox
    //                     .removeClass('d-none alert-success')
    //                     .addClass('alert alert-danger')
    //                     .text(response.message || 'Login failed!')
    //                     .fadeIn();

    //                 setTimeout(() => {
    //                     $alertBox.fadeOut(500);
    //                 }, 3000);
    //             }
    //         },
    //         error: function () {
    //             $('#btn_login').prop('disabled', false).text('Login');
    //             $alertBox
    //                 .removeClass('d-none alert-success')
    //                 .addClass('alert alert-danger')
    //                 .text('Something went wrong! Please try again.')
    //                 .fadeIn();

    //             setTimeout(() => {
    //                 $alertBox.fadeOut(500);
    //             }, 3000);
    //         }

    //     });
    // });

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
                .text('All mandatory fields are required.')
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
    $('#userDropDown').on('click', function (e) {
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


});

$(document).ready(function() {
    $("#search-toggle").click(function() {
        $("#search-bar").toggleClass("active");
        if ($("#search-bar").hasClass("active")) {
            $("#search-bar").focus();
        }
    });
});

