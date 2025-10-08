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
        nextReviews.slideDown(); // show next batch
        shown += reviewsPerPage;
        if (shown >= totalReviews) {
            $(this).fadeOut();
        }
    });

});
