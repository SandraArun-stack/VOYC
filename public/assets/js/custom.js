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

});
