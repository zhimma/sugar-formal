$(function() {
    resizeImage();

    $(window).resize(function() {
        resizeImage();
    });


    function isResize(width) {
        if(width == 350 || width == 330 || width == 300 || width == 280 || width == 250) {
            return true;
        }
        return false;
    }

    function resizeImage() {
        $('.upload-image > img , .personal-image > img').each(function() {

            if($(window).width() < 460 && $(window).width() > 400 && $(this).width() >= 400) {
                $(this).css('width', 350);
            }
            if($(window).width() < 400 && $(window).width() > 380 && $(this).width() >= 380) {
                $(this).css('width', 330);
            }
            if($(window).width() < 380 && $(window).width() > 350 && $(this).width() >= 350) {
                $(this).css('width', 300);
            }
            if($(window).width() < 350 && $(window).width() > 300 && $(this).width() >= 300) {
                $(this).css('width', 280);
            }
            if($(window).width() < 300 && $(window).width() > 250 && $(this).width() >= 250) {
                $(this).css('width', 250);
            }
            else if($(window).width() >= 460 && isResize($(this).width())) {
                $(this).css('width', 400);
            }
        });
    }

});
