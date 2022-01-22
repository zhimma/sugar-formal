$(document).ready(function($) {
  //----------------------------
  asmonload();//初始化执行函数
  $(window).resize(function() {asmresize();}); //浏览器动态放大缩小
  // $(window).bind("scroll", function(event){asmscroll();});//浏览器滚动监听函数
   //------------------------------
    function asmonload(){
      //pdappear();//是否出现在可视区域内
      setTimeout( function () { $("body").css("visibility","visible"); }, 100);
    }//初始化监听
    function asmresize(){
      //pdappear();//是否出现在可视区域内 
    }//缩放监听
    // function asmscroll(){
    //   //pdappear();//是否出现在可视区域内
    //   setTimeout(function(){scrollfx();}, 10);
    //   setTimeout(function(){asmtoped();}, 10);
    // }//滚动监听
  //-----------------------------
function widthresize(){
  var jqwd;
    jqwd=$(window).width();
    if(jqwd>767){
    }
}//widthresize end



//弹窗
$(".dc-cet1-open1").click(function(){
  var getbs=$(this).attr('data-tcbs');
  //alert(getbs);
  $('.dc-pgm2').removeClass('shizu');
  $('.dc-pgm2').removeClass('rizu');
  $('.dc-pgm2').removeClass('hid');
  $('.dc-pgm2').addClass(getbs);
    $(".dc-cet1").removeClass('msg-hid');

});
$(".dc-cet1-close1").click(function(){
    $(".dc-cet1").addClass('msg-hid');
});
//自动关闭函数
function adddn(cname){
    $(cname).addClass("msg-hid");
}
//关闭事件
$(".dc-cet1").click(function(e){
  //var _con = $('.box-wd1');   // 除了这块的目标区域
 // if(!_con.is(e.target) && _con.has(e.target).length === 0){ // Mark 1
 //   $(".dc-cet1").addClass('msg-hid');
//  }
});

$('.mengceng').click(function(){
  $(this).closest('.dc-cet1').addClass('msg-hid');
})


$('.dc-anniudh').click(function(){
  $(this).addClass('hover');
  var that=$(this);
  setTimeout(function(){that.removeClass('hover');}, 200);
})



//新弹窗
$('.mengceng1').click(function(){
  $('.dc-tcbox1').removeClass('open');
  $(this).removeClass('open');
  $('body').removeClass('noscroll');
})
$(".dc-close1").click(function(){
    $(this).closest('.dc-tcbox1').removeClass('open');
    $('.mengceng1').removeClass('open');
    $('body').removeClass('noscroll');
});

$(".dc-tcbox1-open1").click(function(){
  $('body').addClass('noscroll');
  $('.mengceng1').addClass('open');
  $('.dc-tcbox1').addClass('open');
  var getactbs=$(this).attr('data-actbs');//dengru zhuce
  $('.dc-zcbox1').addClass('hid');
  $('.dc-dlbox1').addClass('hid');
  if(getactbs=='dengru'){
    $('.dc-dlbox1').removeClass('hid');
  }//if
  if(getactbs=='zhuce'){
    $('.dc-zcbox1').removeClass('hid');
  }//if


});



});