@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
</style>
<style>
.message_block
{
    display:inline;
}
</style>
<body style="padding: 15px;">
    @include('partials.errors')
    @include('partials.message')
    <table class="table table-bordered table-hover" style="width: 50%">
        <h1 class="message_block">{{ $user->name }}  評價 {{ $to_user->name }} 的照片列表</h1>
        <br>
        <tr>
            <td width="10%">序號</td>
            <td>評價照片</td>
            <td width="10%">動作</td>
        </tr>
        @foreach($picList as $key => $detail)
            <tr>
                <td>{{ $key+1 }}</td>
                <td class="evaluation_zoomIn"><li><img src="{{ $detail->pic }}" style="max-width:130px;max-height:130px;margin-right: 5px;"></li></td>
                <td>
                    <form method="POST" action="{{ route('evaluationPicDelete', [$detail->id]) }}">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-danger ">刪除</button>&nbsp;&nbsp;
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <form method="POST" action="{{ route('evaluationPicAdd') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="evaluation_id" value="{{ $evaluation_id }}">
        <input type="hidden" name="uid" value="{{ $user->id }}">
        <table class="table table-hover table-bordered" width="60%">
            <tr>
                <td><label class="col-form-label twzip" for="images">新增評價照</label></td>
                <td class="input_field_weap">
                    <label class="custom-file">
                        <input type="file" id="images" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                        <span class="custom-file-control"></span>
                    </label>
                    <button type="button" id="add_image" class="" name="button">+</button>
                </td>
                <td>
                    <button type="submit" class="btn btn-success upload-submit">上傳</button>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </form>
</body>
<!--照片查看-->
<div class="big_img">
    <!-- 自定义分页器 -->
    <div class="swiper-num">
        <span class="active"></span>/
        <span class="total"></span>
    </div>
    <div class="swiper-container2">
        <div class="swiper-wrapper">
        </div>
    </div>
    <div class="swiper-pagination2"></div>

</div>

<script>
    let wrapper         = $(".input_field_weap");
    let add_button      = $("#add_image"); //Add button ID
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        $(wrapper).append('<div><label class="custom-file"><input type="file" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val())"><span class="custom-file-control"></span></label><a href="#" class="remove_field">&nbsp;Remove</a></div>'); //add input box
    });
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
    });
</script>
<script>
    $(".str_type").on('click', function(){
        var text = $(this).text();
        var str_type = $(this).parent().find('.msg').append(text);
    });
    $(".btn_del").on('click', function(){
        var id = $(this).attr('id');
        var r=confirm("刪除訊息？")
        if (r==true)
        {
            $.ajax({
            type: 'POST',
            url: "/admin/users/delmsglib",
            data:{
                _token: '{{csrf_token()}}',
                id    : $(this).attr('id'),
            },
            dataType:"json",
            success: function(res){
                alert('刪除成功');
                location.reload();

        }});
        }else
        {
            alert('刪除失敗');
        }

    });
</script>
<!--照片查看-->
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script>
    $(document).ready(function () {
        /*调起大图 S*/
        var mySwiper = new Swiper('.swiper-container2',{
            pagination : '.swiper-pagination2',
            paginationClickable:true,
            onInit: function(swiper){//Swiper初始化了
                // var total = swiper.bullets.length;
                var active =swiper.activeIndex;
                $(".swiper-num .active").text(active);
                // $(".swiper-num .total").text(total);
            },
            onSlideChangeEnd: function(swiper){
                var active =swiper.realIndex +1;
                $(".swiper-num .active").text(active);
            }
        });

        $(".evaluation_zoomIn li").on("click",
            function () {
                var imgBox = $(this).parent(".evaluation_zoomIn").find("li");
                var i = $(imgBox).index(this);
                $(".big_img .swiper-wrapper").html("")

                for (var j = 0, c = imgBox.length; j < c ; j++) {
                    $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
                }
                mySwiper.updateSlidesSize();
                mySwiper.updatePagination();
                $(".big_img").css({
                    "z-index": 1001,
                    "opacity": "1"
                });
                //分页器
                var num = $(".swiper-pagination2 span").length;
                $(".swiper-num .total").text(num);
                // var active =$(".swiper-pagination2").index(".swiper-pagination-bullet-active");
                $(".swiper-num .active").text(i + 1);
                // console.log(active)

                mySwiper.slideTo(i, 0, false);
                return false;
            });
        $(".swiper-container2").click(function(){
            $(this).parent(".big_img").css({
                "z-index": "-1",
                "opacity": "0"
            });
        });

    });
    /*调起大图 E*/
</script>
<!--照片查看end-->

