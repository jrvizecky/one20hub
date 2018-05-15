jQuery(function() {
    var header = jQuery(".site-branding,.secondary-nav,.category-nav");
    jQuery(window).scroll(function() {
        var scroll = jQuery(window).scrollTop();

        if (scroll >= 150) {
            header.addClass("scroll");
        } else {
            header.removeClass("scroll");
        }
    });
});

jQuery(document).ready(function () {
    jQuery('div.sfm-va-middle span').each(function() {
        jQuery(this).replaceWith(jQuery(this).text());
    });
});
