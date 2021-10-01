jQuery(window).scroll(function() {
    let scroll = jQuery(window).scrollTop();
    let mainHeader = jQuery(".main-header");
    if (scroll >= 40) {
        mainHeader.addClass("fixed");
    }else{
        mainHeader.removeClass("fixed");
    }
});