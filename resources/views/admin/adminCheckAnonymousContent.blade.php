@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <style>
        .gender_1, a.gender_1.email_link, a.gender_1.email_link:visited, a.gender_1.email_link:active, a.gender_1.email_link:hover {color:blue;}
        .gender_2, a.gender_2.email_link, a.gender_2.email_link:visited, a.gender_2.email_link:active, a.gender_2.email_link:hover {color:red;}
        .reject_detail_edit_block {display:none;}
    </style>
    <h1>
        站長審核 - 匿名評價訊息
        <button type="button" class="btn btn-primary" onclick="toggle_row(1,this);" ><span>開啟</span>已通過</button>
        <button type="button" class="btn btn-primary" onclick="toggle_row(2,this);" ><span>開啟</span>不通過</button>        
    </h1>
    <table class="table-bordered table-hover center-block table" id="table">
        <thead>
            <tr style="border-bottom:solid;">
                <th scope="row" width="3em"></th>
                <th width="15%" scope="col">email</th>
                <th width="8em" scope="col">暱稱</th>
                <th width="3em" scope="col">VIP</th>
                <th scope="col">評價內容</th>
                <th  scope="col" width="20%" style="min-width:150px;">評價圖片</th>
                <th width="5%" scope="col">審核狀態</th>
                <th width="10%" scope="col">評價時間</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            @php
                $Vip = \App\Models\Vip::vip_diamond($row->id);
                $cur_to_user = \App\Models\User::with('aw_relation', 'banned', 'implicitlyBanned')->find($row->to_id)??new \App\Models\User;            
                $cur_to_user_Vip = \App\Models\Vip::vip_diamond($cur_to_user->id);
            @endphp
            <tr class="status_{{$row->anonymous_content_status}}">
                <td scope="row">評價者</td>
                <td scope="row"
                        @if($row->is_banned())
                            bgcolor="#FDFF8C"
                        @elseif($row->is_warned())
                            bgcolor="#B0FFB1"
                        @elseif(!$row->accountStatus)
                            bgcolor="#C9C9C9"
                        @elseif(!$row ->account_status_admin)
                            bgcolor="#969696"
                        @elseif($row->is_waiting_for_more_data())
                            bgcolor="#DBA5F2"
                        @elseif($row->is_waiting_for_more_data_with_login_time())
                            bgcolor="#A9D4F5"
                        @endif                  
                ><a href="users/advInfo/{{$row->id}}" target="_blank" class="gender_{{$row->engroup}} email_link ">{{$row->email}}</a></td>
                <td nowrap>{{$row->name}}</td>
                <td nowrap>
                    @if($row->isVip()==1)
                        @if($Vip=='diamond_black')
                            <img src="/img/diamond_black.png" style="height: 1.5rem;">
                        @else
                            @for($z = 0; $z < $Vip; $z++)
                                <img src="/img/diamond.png" style="height: 1.5rem;">
                            @endfor
                        @endif
                    @endif
                    <button type="button" class="btn btn-warning" onclick="window.open('{{route('admin/showAdminCheckAnonymousBetweenMessages',['evaluate_from'=>$row->id,'evaluate_to'=>$cur_to_user->id])}}');" style="vertical-align:bottom;display:block;" >站內對話</button>
                </td>                
                <td rowspan="2" style="word-break: break-all">
                    <div class="show_content_{{$row->evaluation_id}}">
                    {{$row->content}}
                    </div>
                    @if ($row->content_violation_processing != 'return')
                    <form method="POST" action="{{ route('evaluationModifyContent', $row->evaluation_id) }}" style="margin:0px;display:inline;">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$row->evaluation_id}}">
                        <textarea class="form-control m-input content_{{$row->evaluation_id}}" type="textarea" name="evaluation_content" rows="3" maxlength="300" style="display: none;">{{$row->content}}</textarea>
                        <div class="btn btn_edit btn-success modify_content_btn_{{$row->evaluation_id}}" onclick="showTextArea({{$row->evaluation_id}})">修改評價內容</div>
                        <button type="submit" class="text-white btn btn-info modify_content_submit evaluation_content_btn_{{ $row->evaluation_id }}" style="display: none;">確認修改</button>
                        <button type="submit" class="text-white btn btn-danger modify_content_cancel evaluation_content_cancel_btn_{{ $row->evaluation_id }}" style="display: none;" onclick="cancelTextArea({{$row->evaluation_id}});">取消</button>
                    </form>
                    @endif                    
                </td>
                <td rowspan="2" class="evaluation_zoomIn">
                    @foreach($row['pic'] as $evaluationPic)
                        <li>
                            <img src="{{ $evaluationPic->pic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                        </li>
                    @endforeach
                </td>
                <td rowspan="2" nowrap>
                    <div>
                        @switch($row->content_violation_processing)
                            @case('modify_directly')
                            接受站方修改
                            @break
                            @case('return')
                            不接受站方修改
                            @break
                        @endswitch
                    </div>
                    @switch($row->anonymous_content_status)
                        @case(0)
                            @if($row->deleted_at)
                                (刪)
                            @else
                            <button type="button" class="btn btn-primary" onclick="checkAction({{$row->evaluation_id}},1,{{ $row->id }})" >通過</button>
                            <button type="button" class="btn btn-primary" onclick="checkAction({{$row->evaluation_id}},1,{{ $row->id }},null,1)" >通過<br>(不附照片)</button>
                            <br>
                            <button type="button" data-evaluation_id="{{$row->evaluation_id}}" class="btn btn-danger reject_button" onclick="checkAction({{$row->evaluation_id}},2,{{ $row->id }},this);window.open('/admin/users/message/anonymous-checked/to/{{$row->id}}/{{$row->evaluation_id}}');" >不通過</button>
                            @endif
                        @break
                        @case(1)
                            通過
                            @if($row->only_show_text)
                                (不附照片)
                            @endif
                            
                            @if($row->deleted_at)
                            (刪)
                            @else
                            <br>
                            <button type="button" class="btn btn-danger" onclick="checkAction({{$row->evaluation_id}},0,{{ $row->id }});" >取消通過</button>
                            @endif
                        @break
                        @case(2)
                            不通過
                            @if($row->deleted_at)
                            (刪)
                            @else
                            <br>
                            <button type="button" class="btn btn-danger" onclick="checkAction({{$row->evaluation_id}},0,{{ $row->id }});" >取消不通過</button>
                            @endif
                        @break
                    @endswitch
                    <br>
                    <a class="btn btn-dark" href="{{ route('admin/showAnonymousChatMessage', $row->evaluation_id) }}" target="_blank">對話紀錄</a>                           
                    <br>
                    <a href="/admin/users/message/anonymous-checked/to/{{$row->id}}/{{$row->evaluation_id}}" target="_blank" class="btn btn-dark">發送站長訊息</a>
                </td>
                <td rowspan="2">{{$row->created_at}}</td>
            </tr>
            <tr style="border-bottom:solid;"  class="status_{{$row->anonymous_content_status}}">
                <td scope="row" nowrap>被評價者</td>
                <td scope="row"
                        @if($cur_to_user->is_banned())
                            bgcolor="#FDFF8C"
                        @elseif($cur_to_user->is_warned())
                            bgcolor="#B0FFB1"
                        @elseif(!$cur_to_user->accountStatus)
                            bgcolor="#C9C9C9"
                        @elseif(!$cur_to_user->account_status_admin)
                            bgcolor="#969696"
                        @elseif($cur_to_user->is_waiting_for_more_data())
                            bgcolor="#DBA5F2"
                        @elseif($cur_to_user->is_waiting_for_more_data_with_login_time())
                            bgcolor="#A9D4F5"
                        @endif                
                ><a href="users/advInfo/{{$cur_to_user->id}}" target="_blank" class="gender_{{$cur_to_user->engroup}} email_link ">{{$cur_to_user->email}}</a></td>                
                <td scope="row"  nowrap>{{$cur_to_user->name}}</td>
                <td  nowrap>
                    @if($cur_to_user->isVip()==1)
                        @if($cur_to_user_Vip=='diamond_black')
                            <img src="/img/diamond_black.png" style="height: 1.5rem;">
                        @else
                            @for($z = 0; $z < $cur_to_user_Vip; $z++)
                                <img src="/img/diamond.png" style="height: 1.5rem;">
                            @endfor
                        @endif
                    @endif
                </td>         
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
    $('tr.status_1,tr.status_2').hide();
    function checkAction(evaluation_id, status, user_id,dom=null,only_show_text=0){
        let send_data = {
                _token: '{{csrf_token()}}',
                evaluation_id: evaluation_id,
                status: status,
                only_show_text:only_show_text,
            };
            
        if(status==2 && dom!=null) {
            send_data['status_reason'] = $(dom).closest('td').find('.status_reason').val();
        }
        
        $.ajax({
            type: 'POST',
            url: "/admin/checkAnonymousContent?{{csrf_token()}}={{now()->timestamp}}",
            data:send_data,
            dataType:"json",
            success: function(res){
                location.reload();
            }});
    }

    function showTextArea(evaluation_id){
        $('.modify_content_btn_'+ evaluation_id).hide();
        $('.show_content_'+ evaluation_id).hide();
        $('.content_'+ evaluation_id).show();
        $('.evaluation_content_btn_'+ evaluation_id).show();
        $('.evaluation_content_cancel_btn_'+ evaluation_id).show();
    }
    
    function cancelTextArea(evaluation_id){
        $('.modify_content_btn_'+ evaluation_id).show();
        $('.show_content_'+ evaluation_id).show();
        $('.content_'+ evaluation_id).hide();
        $('.evaluation_content_btn_'+ evaluation_id).hide();
        $('.evaluation_content_cancel_btn_'+ evaluation_id).hide();
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
    

    function toggle_row(status,dom)
    {
       let cur_elt = $(dom);
       let switch_elt = cur_elt.children('span').first();

       switch(switch_elt.html()) {
           case '開啟':
               $('#table').find('tr.status_'+status).css('display','');
               switch_elt.html('隱藏');           
           break;
           case '隱藏':
               $('#table').find('tr.status_'+status).hide();
               switch_elt.html('開啟');                      
           break;           
       }
    }
</script>
@stop
