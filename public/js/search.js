$(function() {
    resizeUserPanel();
    
    $(window).resize(function() {
        resizeUserPanel();
    });


    function resizeUserPanel() {
        if($(window).width() < 1150) {
            $('.user-panel').css('display', 'none');
            $('.content').addClass('col-md-12');
            $('.content').removeClass('col-lg-9');
        }
        else {
            $('.user-panel').css('display', 'inline-block');
            $('.content').removeClass('col-md-12');
            $('.content').addClass('col-lg-9');
        }
    }

});
