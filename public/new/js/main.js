
var bottomImg = ''
var bottomTxt = '\n' +
    '   >'

$(function () {
    $('#menuButton').click(function (){ // 菜单栏
        if ($('#menuList').hasClass('ulHeight')) {
            $(this).find('img').attr({'src': '/new/images/icon.png'})
            $('#menuList').removeClass('ulHeight')
            $('.menuBg').hide()
        } else {
            $(this).find('img').attr({'src': '/new/images/menu.png'})
            $('#menuList').addClass('ulHeight')
            $('.menuBg').fadeIn()
        }
    })
    $('.menuBg').click(function () {
        $('#menuList').removeClass('ulHeight')
        $(this).hide()
    })
    $('#bottomNav').html(bottomImg + bottomTxt)

    $('.productMain').find('dl').find('dd').click(function () { // 切换产品页
        $('.productMain').find('dl').find('dd').find('img').removeClass('imgThis')
        $(this).find('img').addClass('imgThis')
        var thisNum = $(this).index()
        $('.productList01').eq(thisNum).fadeIn().siblings('.productList01').hide()
    })
})