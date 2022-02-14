@extends('new.layouts.website')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" href="/new/css/iconfont.css">
<style>
    .btcic{font-size:12px; font-style: normal;background:linear-gradient(to right,#f9a9bf,#febfcf); position: absolute; right:-30px; height: 20px; border-radius: 100px;line-height:20px;
        padding: 0 3px; top:4px; font-weight: normal; color: #fff;}

    @media (max-width:320px) {
        .btcic{ right:-20px;}
    }

    .tongzhi_ss{width: 94%; margin: 0 auto; box-shadow: 0 0 10px #ffdae5; border-radius: 20px 0px 0px 20px; padding: 15px 0; display: table;}
    .tong_x{width: 98%;display: table; float: right;}
    .tong_x li{ padding: 5px 0 10px 0; border-bottom: #ffd2df 1px solid; display: table; margin-bottom: 10px;width: 95%; float: right; margin-right:3%; }
    .tong_list{width:45px; height: 45px; float: left;border-radius:100px;box-shadow:0 6px 14px #ffbed0; background: #fff}
    .tong_list img{ margin: 0 auto; height:25px; display: table; margin-top: 10px;}

    .tong_font{width:calc(100% - 55px); float: right; cursor: pointer;}
    .tong_font h2{ font-size: 16px; width: 100%; display: table; position: relative; font-weight:600;}
    .tong_font h3{ font-size: 13px; width: 100%; display: table; color: #999999; padding-top:5px;}

    .ronr_icon{ float: right; height: 20px;}

    .widu{background: #f7f7f7; border-radius: 15px 0 0 15px; padding: 10px 0; margin-bottom: 10px;}
    .wid_hs{ background: #f7f7f7 !important;}



    .tong_xh{box-shadow:0 6px 14px #ccc }

    @media (max-width:450px) {
        .tongzhi_ss{width: 94%;box-shadow: 0 0 10px #ffdae5; border-radius: 20px 0px 0px 20px; padding: 15px 0;}
        .tong_x{width: 96%;display: table; float: right;}
        .tong_x li{ padding: 5px 0 10px 0; border-bottom: #ffd2df 1px solid;display: table; margin-bottom: 10px;width: 90%; float: right; margin-right:6%; }
    }

</style>
<style>
    @media (max-width:768px){
        .zap_bb>.text span{color: #666; line-height:20px; overflow: hidden;}
        .zap_bb>.text .showText{ max-height:20px;}
    }
    .on {min-height: 20px!important;}
</style>
<style>
    .tong_font>.text span{color: #666;line-height:20px; overflow: hidden;}
    .tong_font>.text .showText{ max-height:20px;}

    .tong_font>.text>a{ background: #fff; padding-right: 4px; cursor: pointer;}
    .tong_font>.text>.on{display: block}
    .tong_font>.text{position: relative; margin-top: 6px; }
    .tong_font>.text>.on~a{position: absolute; bottom: 0; right: 0;}
    .tong_font>.text>a:active,.tong_font>.text>a:visited{color: #666;}
    .tong_font>.text>a:active>em,.tong_font>.text>a:visited>em{color:#fe92a8;}
    .tong_font>.text>a>em{color:#fe92a8; padding-left: 6px;}
    .pda_zx{ padding: 0;}
    .padc{ background: #f7f7f7  !important;}

    .btn_img{width:40px; height:31px;margin:8px -5px 0 0;background:none;padding:0;}
    .btn_img>.btn_back{width:100%; height:100%; background: url("../new/images/fanhui.png") no-repeat 0 0; background-size:100%;}
    .btn_img:hover>.btn_back{background-position:0 -31px;}
    .btn_img:hover{box-shadow:unset;}


    .toug_back{
        background:#fe92a8;
        border-radius:100em;
        height:21px;
        width:21px;
        line-height:19px;
        color:#ffffff;
        text-align:center;
        float:right;
        font-size:13px;
        margin-top:10px;
        margin-right:16px;
    }

    /*厚康追加項目OP*/
    .toug_back embed{
        font-size:13px;
        font-weight:100;
        text-shadow: 0px 0px 5px rgba(231,92,124,1);
        height:21px;
        width:21px;
        margin-left:3px;
    }



    /* 區塊陰影及高光效果 */
    .toug_back .black-shadow{
        box-shadow: 2px 2px 6px 5px rgba(0,0,0,0.4);
        border-radius:100em;
    }/*外陰影*/

    .toug_back .black-shadow02{
        box-shadow: 0 0 3px 0.3px #FF8080;
        border-radius:100em;
    }/*中粉影*/

    .toug_back .inset-shadow{
        box-shadow:inset 1.5px 1.5px 4px 0px rgba(255,255,255,1);
        border-radius:100em;
    }/*內白影*/

    .toug_back .white-emboss{
        box-shadow: -7px -5px 8px 15px rgba(255,255,255,1);
        border-radius:100em;
    }/*外白影*/

    /* 區塊陰影及高光效果ED */
    /*厚康追加項目ED */

    .toug_back:hover{
        color:#ffffff;
        box-shadow:inset 0px 13px 10px -10px #f83964,inset 0px -10px 10px -20px #f83964;
    }

    .toug_back .inset-shadow:hover{
        box-shadow:inset -2px -2px 8px 1px rgba(255,255,255,0.3);
        border-radius:100em;
    }


    .toug_back img{
        height: 14px;
        vertical-align: middle;
        margin-top: -3px;
        margin-right: 2px;
    }


    .btn_img01{width:99px; height:auto;margin:7px -8px 0 0;background:none;padding:0; line-height:32px;}
    .btn_img01>.btn_back{width:100%;background: url("../new/images/fanhui01.png") no-repeat 0 0; background-size: cover}
    .btn_img01:hover>.btn_back{background-position:0 -33px;}
    .btn_img01:hover{box-shadow:unset;}
</style>

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou" style="text-align: center; position: relative;">
                    <a href="/dashboard/personalPage" class="toug_back btn_img" style=" position: absolute; left: 0;">
                        <div class="btn_back"></div>
                    </a>
                    <span style="margin: 0 auto; position: relative;line-height: 44px;padding-bottom: 3px;">{{$msg_spoken}}<i class="btcic">{{ $unreadCount }}</i></span>
                    <a onclick="deleteAll();" class="toug_back btn_img01" style=" position: absolute; right: 0;">
                        <div class="btn_back"><img src="{{ asset("new/images/zs_jsdel.png") }}" style="margin-left: -5px;">全部刪除</div>
                    </a>
                </div>
                <div class="tongzhi_ss">
                    <ul class="tong_x">
                        @foreach($admin_msgData as $msg)
                            @if($msg->read!=='Y')
                            <li class="unread" data-MsgId="{{ $msg->id }}">
                                <div class="tong_list"><img src="{{ asset("new/images/lingdang.png") }}" ></div>
                                <div class="tong_font">
                                    <h2>{{$msg_spoken}}<a><img src="{{ asset("new/images/cold.png") }}"  class="ronr_icon" onclick="deleteAdminMsg('{{ $msg->id }}')"></a></h2>
                                    <div class="text pda_zx">
                                        <span class="showText">{!! $msg->content !!}</span>
                                        <a>…<em>更多</em></a>
                                    </div>
                                    <h3>{{ $msg->created_at }}</h3>
                                </div>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                    @if($readCount>0)
                        <ul class="tong_x widu">
                            @foreach($admin_msgData as $msg)
                                @if($msg->read=='Y')
                                <li data-MsgId="{{ $msg->id }}">
                                    <div class="tong_list tong_xh"><img src="{{ asset("new/images/lingdang01.png") }}" ></div>
                                    <div class="tong_font">
                                        <h2>{{$msg_spoken}}<a><img src="{{ asset("new/images/cold.png") }}"  class="ronr_icon" onclick="deleteAdminMsg('{{ $msg->id }}')"></a></h2>
                                        <div class="text pda_zx">
                                            <span class="showText">{!! $msg->content !!}</span>
                                            <a class="padc">…<em>更多</em></a>
                                        </div>
                                        <h3>{{ $msg->created_at }}</h3>
                                    </div>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="fenye" style="padding-top:20px; padding-bottom: 20px;">
                    {{ $admin_msgData->links('pagination::sg-pages2') }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
<script>

    $('.pda_zx').click(function (){
        $(this).children('span').toggleClass('showText')
        $(this).children('a').toggle(0)
    })

    $(".text span").each(function(){
        var  h = $(this).innerHeight();
        if( h > 39){
            $(this).addClass('on');

        }else{
            $(this).next('a').hide();
        }
    })
    $(function (){
        $(".zhap_new a:first").addClass("zhap_new_hover");
        $(".zap_ullist").hide();
        $(".zap_ullist:first").show();
        $(".zhap_new a").click(function () {
            $('.zhap_new a:not(this)').removeClass("zhap_new_hover");
            $(this).addClass("zhap_new_hover");
            $('.zap_ullist').hide();
            var i=$(this).index();
            $('.zap_ullist').eq(i).show();
            $('.zap_ullist').eq(i).find(".text span").each(function(){
                var  h = $(this).innerHeight();
                if( h > 39){
                    $(this).addClass('on');
                    $(this).next('a').show();
                }else{
                    $(this).next('a').remove();
                }
            })
        });
    });

    function deleteAll() {

        c6('確認刪除全部{{$msg_spoken}}?');
        var items = [];
        $('.tong_x').find('li').each(function(){
            items.push($(this).attr('data-MsgId'));
        });
        $(".n_left").on('click', function() {
            $.post('{{ route('personalPageHideRecordLog') }}', {
                type: 'admin_msgs',
                deleteItems: items,
                user_id: '{{ $user->id }}',
                _token: '{{ csrf_token() }}'
            },function(data) {
                $(".blbg").hide();
                $("#tab06").hide();
                c5('刪除成功');
                window.location.reload();
            });
        });
    }

    function deleteAdminMsg(msgId){
        c6('確認刪除該{{$msg_spoken}}?');
        var items = [];
        $(".n_left").on('click', function() {
            items.push(msgId);
            $.post('{{ route('personalPageHideRecordLog') }}', {
                type: 'admin_msgs',
                deleteItems: items,
                user_id: '{{ $user->id }}',
                _token: '{{ csrf_token() }}'
            },function(data) {
                $(".blbg").hide();
                $("#tab06").hide();
                c5('刪除成功');
                window.location.reload();
            });
        });
    }

    $(".unread").click(function () {

        var msgID = $(this).attr('data-MsgId');
        $.ajax({
            type: 'POST',
            url: "/dashboard/adminMsgRead/"+msgID,
            data:{
                _token: '{{ csrf_token() }}'
            },
            dataType:"json",
            success: function(){
                //location.reload();
            }
        });
    }).find('.ronr_icon').click(function() {
        return false;
    });

</script>
<script>
    @if (isset($errors) && $errors->count() > 0)
        @foreach ($errors->all() as $error)
            c5('{{ $error }}');
        @endforeach
    @endif

    @if (Session::has('message') && Session::get('message')=="此用戶已關閉資料。")
        ccc('{{Session::get('message')}}');
    @elseif(Session::has('message'))
        c5('{{Session::get('message')}}');
    @endif
</script>
<script type="text/javascript">
    $(function() {
		@if(isset($admin_msgs) && count($admin_msgs))
		    $('.btn_admin_msgs').show();
		@endif
	});
</script>

@stop
