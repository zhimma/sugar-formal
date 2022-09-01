@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <h1>站長審核 - 匿名評價訊息</h1>
    <table class="table-bordered table-hover center-block table" id="table">
        <thead>
            <tr>
                <th width="15%" scope="col">email</th>
                <th width="10%" scope="col">暱稱</th>
                <th width="5%" scope="col">VIP</th>
                <th width="5%" scope="col">性別</th>
                <th width="10%" scope="col">被檢舉分數</th>
                <th width="15%" scope="col">評價對象</th>
                <th width="20%" scope="col">評價內容</th>
                <th width="5%" scope="col">評價圖片</th>
                <th width="5%" scope="col">審核狀態</th>
                <th width="10%" scope="col">評價時間</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            @php
                $Vip = \App\Models\Vip::vip_diamond($row->id);
            @endphp
            <tr>
                <td scope="row"><a href="users/advInfo/{{$row->id}}" target="_blank">{{$row->email}}</a></td>
                <td>{{$row->name}}</td>
                <td>
                    @if($row->isVip()==1)
                        @if($Vip=='diamond_black')
                            <img src="/img/diamond_black.png" style="height: 1.5rem;">
                        @else
                            @for($z = 0; $z < $Vip; $z++)
                                <img src="/img/diamond.png" style="height: 1.5rem;">
                            @endfor
                        @endif
                    @endif
                </td>
                <td>@if($row->engroup==1)男@else女@endif</td>
                <td>{{$row->WarnedScore()}}</td>
                <td scope="row"><a href="users/advInfo/{{$row->to_id}}" target="_blank">{{$row->to_email}}</a></td>
                <td style="word-break: break-all">{{$row->content}}</td>
                <td class="evaluation_zoomIn">
                    @foreach($row['pic'] as $evaluationPic)
                        <li>
                            <img src="{{ $evaluationPic->pic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                        </li>
                    @endforeach
                </td>
                <td>@switch($row->anonymous_content_status)
                        @case(0)
                            <button type="button" class="btn btn-primary" onclick="checkAction({{$row->evaluation_id}},1,{{ $row->id }})" >通過</button>
                            <button type="button" class="btn btn-danger reject_button" onclick="checkAction({{$row->evaluation_id}},2,{{ $row->id }})" >不通過</button>
                            @if ($row->content_violation_processing != 'return')
                            <form method="POST" action="{{ route('evaluationModifyContent', $row->evaluation_id) }}" style="margin:0px;display:inline;">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$row->evaluation_id}}">
                                <textarea class="form-control m-input content_{{$row->evaluation_id}}" type="textarea" name="evaluation_content" rows="3" maxlength="300" style="display: none;">{{$row->content}}</textarea>
                                <div class="btn btn_edit btn-success modify_content_btn_{{$row->evaluation_id}}" onclick="showTextArea({{$row->evaluation_id}})">修改評價內容</div>
                                <button type="submit" class="text-white btn btn-primary modify_content_submit evaluation_content_btn_{{ $row->evaluation_id }}" style="display: none;">確認修改</button>
                            </form>
                            @endif
                        @break
                        @case(1)
                            通過<br>
                            @if ($row->content_violation_processing != 'return')
                            <form method="POST" action="{{ route('evaluationModifyContent', $row->evaluation_id) }}" style="margin:0px;display:inline;">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$row->evaluation_id}}">
                                <textarea class="form-control m-input content_{{$row->evaluation_id}}" type="textarea" name="evaluation_content" rows="3" maxlength="300" style="display: none;">{{$row->content}}</textarea>
                                <div class="btn btn_edit btn-success modify_content_btn_{{$row->evaluation_id}}" onclick="showTextArea({{$row->evaluation_id}})">修改評價內容</div>
                                <button type="submit" class="text-white btn btn-primary modify_content_submit evaluation_content_btn_{{ $row->evaluation_id }}" style="display: none;">確認修改</button>
                            </form>
                            @endif
                        @break
                        @case(2)
                            不通過<br>
                            @if ($row->content_violation_processing != 'return')
                            <form method="POST" action="{{ route('evaluationModifyContent', $row->evaluation_id) }}" style="margin:0px;display:inline;">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$row->evaluation_id}}">
                                <textarea class="form-control m-input content_{{$row->evaluation_id}}" type="textarea" name="evaluation_content" rows="3" maxlength="300" style="display: none;">{{$row->content}}</textarea>
                                <div class="btn btn_edit btn-success modify_content_btn_{{$row->evaluation_id}}" onclick="showTextArea({{$row->evaluation_id}})">修改評價內容</div>
                                <button type="submit" class="text-white btn btn-primary modify_content_submit evaluation_content_btn_{{ $row->evaluation_id }}" style="display: none;">確認修改</button>
                            </form>
                            @endif
                        @break
                    @endswitch
                </td>
                <td>{{$row->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Modal -->
{{--    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLabel"></h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <form id="reject_form">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="recipient-name" class="col-form-label">請輸入原因:</label>--}}
{{--                            <textarea class="form-control" name="reject_content"></textarea>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    --}}{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">X</button>--}}
{{--                    <button type="button" class="btn btn-danger reject_submit" data-id="" id="reject_submit">送出</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

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

</body>
<!--照片查看-->
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script>
    function checkAction(evaluation_id, status, user_id){
        $.ajax({
            type: 'POST',
            url: "/admin/checkAnonymousContent?{{csrf_token()}}={{now()->timestamp}}",
            data:{
                _token: '{{csrf_token()}}',
                evaluation_id: evaluation_id,
                status: status,
            },
            dataType:"json",
            success: function(res){
                //location.reload();
                window.open(`/admin/users/message/anonymous-checked/to/${user_id}/${evaluation_id}`, '_blank');
            }});
    }

    function showTextArea(evaluation_id){
        $('.modify_content_btn_'+ evaluation_id).hide();
        $('.content_'+ evaluation_id).show();
        $('.evaluation_content_btn_'+ evaluation_id).show();
    }
    $('.modify_content_submit').on('click',function(e){
        if(!confirm('確定要修改該筆評價內容?')){
            e.preventDefault();
        }
    });

    // $('.input_reject').on('click', function(){
    //     var id = $(this).data('id');
    //     $('.reject_submit').data('id',id); //setter
    // });
    //
    // $('#reject_submit').on('click', function(){
    //     $('.reject_content_' + $('#reject_submit').data('id')).text($('textarea[name=reject_content]').val());
    //     $('#exampleModal').modal('hide');
    // });

    $('.reject_button').on('click', function(){

        var reject_content;
        // if( $('.reject_content_'+ $(this).data('id')).text() != '請輸入原因'){
        //     reject_content = $('.reject_content_'+ $(this).data('id')).text();
        // }
        reject_content = $('#reject_content_'+ $(this).data('id')).val();
        $.ajax({
            type: 'POST',
            url: "/admin/checkExchangePeriod?{{csrf_token()}}={{now()->timestamp}}",
            data:{
                _token: '{{csrf_token()}}',
                id: $(this).data('id'),
                status: 2,
                reject_content: reject_content,
            },
            dataType:"json",
            success: function(res){
                location.reload();
            }
        });
    });

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

    $(".evaluation_zoomIn li").on("click", function () {
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
</script>
@stop
