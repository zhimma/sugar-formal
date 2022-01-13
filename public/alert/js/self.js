

$('.dc_anniudh').click(function(){
  $(this).addClass('hover');
  var that=$(this);
  setTimeout(function(){that.removeClass('hover');}, 200);
})



