$(".nav .nav-link").on("click", function () {
    $(".nav").find(".active").removeClass("active");
    $(this).addClass("active");
});

jQuery(function ($) {


    $("#show-sidebar").click(function () {
        $(".page-wrapper").toggleClass("toggled");
    });
});

// jQuery(function ($) {
//     var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
//     $('ul a').each(function () {
//         if (this.href === path) {
//             $(this).addClass('active');
//         }
//     });
// });

