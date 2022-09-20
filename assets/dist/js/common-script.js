$(".nav .nav-link").on("click", function () {
    $(".nav").find(".active").removeClass("active");
    $(this).addClass("active");

    var url1 = window.location.href;
    //  if(url1==http://18.206.115.28/cityxone_beta/web/setting)
    var res = url1.split("/");
    var spl_for_loc = url1.split("?");
    var res = spl_for_loc[0].split("/");
    // console.log(spl_for_loc)
    // console.log(res)

    //     if(spl=="dashboard")
    if (jQuery.inArray("register-user-details", res) !== -1) {
        $(".register_uesrs").addClass("active");
    }

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

