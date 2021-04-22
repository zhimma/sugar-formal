@extends('new.layouts.website')

@section('app-content')

<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="shou">
                <span class="zq_font1">可疑名單交流區</span>
                <font class="zq_font2">List of Accounts</font>
                <a href="" class="shou_but">聲明</a>
            </div>
            <div class="g_pnr" style="margin-bottom: 1%;">
                <div class="zhapian_zl">
                     <input placeholder="請輸入帳號" id="zhap_input" class="zhap_input" value="{{$query}}"><a class="zhap_search"><img src="/new/images/zhapian_icon1.png">搜尋</a> 
                </div>
                @if(count($suspicious)>0)
                <div class="zhp_list">
                     <div class="zhp_ptitle"><img src="/new/images/zhapian_icon2.png">搜尋結果</div>
                     <ul class="zhp_ullist">
                        @foreach ($suspicious as $suspiciou)
                        <li>
                            <h2>{{$suspiciou->name}}<span>{{$suspiciou->created_at}}</span></h2>
                            <h3>帳號：{{$suspiciou->account_text}}</h3>
                        </li>
                        @endforeach
                     </ul>
                </div>
                <!-- <div class="fenye">
                    <a href="">上一頁</a><span class="new_page">1/5</span>
                    <a href="">下一頁</a>
                </div> -->
                <div style="text-align: center;">
                    {!! $suspicious->appends(request()->input())->links('pagination::sg-pages2') !!}
                </div>
                @else
                <div class="zhp_list">
                     <div class="zhp_ptitle"><img src="/new/images/zhapian_icon2.png">搜尋結果</div>
                     <ul class="zhp_ullist">
                        <img src="/new/images/wuziliao.png" class="ziliao_wimg">
                        <div class="zlfoxinx">查無資料</div>
                     </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="zhp_xz" ><img src="/new/images/zhapian_icon3.png"></div>
@stop
<style>
    .blur_img {
        filter: blur(1px);
        -webkit-filter: blur(1px);
    }
    .g_pnr {
        min-height: 780px;
    }
    @media (max-width: 450px) {
        .g_pnr {
            min-height: 600px;
        }
    }
    @media (max-width: 768px) {
        .g_pnr {
            min-height: 780px;
        }
    }
    
    @media (max-width: 1024px) {
        .g_pnr {
            min-height: 1120px;
        }
    }
    .zhapian_zl {
        width: 700px;
        height: 40px;
        line-height: 40px;
        background: #f8f8f8;
        border-radius: 5px;
        margin: 0 auto;
        display: table;
    }
    @media (max-width: 824px) {
        .zhapian_zl {
            width: 94%;
            margin: 0 auto;
        }
    }
    .zhp_list {
        width: 700px;
        margin: 0 auto;
        display: table;
        border: #ffbfcd 1px solid;
        border-radius: 10px;
        margin-top: 20px;
        padding: 10px;
    }
    @media (max-width: 824px) {
        .zhp_list {
            width: 94%;
        }
    }
    @media (max-width: 320px) {
        .zq_font1 {
            font-size: 18px !important;
        }
    }
    @media (max-width: 320px) {
        .zq_font2 {
            font-size: 13px !important;
        }
    }
    .zhap_input {
        width: 610px;
        float: left;
        border: none;
        background: none;
        padding-left: 15px;
        outline: none;
    }
    @media (max-width: 824px) {
        .zhap_input {
            width: calc(100% - 80px);
        }
    }
    .zhap_search {
        width: 80px;
        background: #fe92a8;
        float: right;
        color: #fff;
        border-radius: 5px;
        font-size: 15px;
        text-align: center;
        cursor: pointer;
        height: 41px;
    }
    @media (max-width: 824px) {
        .zhap_search {
            width: 80px;
        }
    }
    .zhap_search img {
        height: 24px;
    }
    .zhp_ptitle {
        width: 100%;
        background: #fcedf1;
        height: 40px;
        line-height: 40px;
        font-size: 16px;
        font-weight: bold;
        color: #e55073;
        border-radius: 5px;
    }
    .zhp_ptitle img {
        height: 24px;
        vertical-align: middle;
        margin-left: 10px;
        margin-right: 6px;
    }
    .zhp_ullist {
        width: 100%;
        margin: 0 auto;
        display: table;
        margin-top: 15px;
    }
    .zhp_ullist li {
        background: #ffffff;
        border-radius: 5px;
        box-shadow: #fcedf1 0 0 6px;
        padding: 10px 0;
        margin-bottom: 10px;
    }
    .zhp_ullist li h2 {
        width: 95%;
        margin: 0 auto;
        display: table;
        color: #666666;
        border-bottom: #eee 1px dashed;
        padding-bottom: 8px;
    }
    .zhp_ullist li h2 span {
        float: right;
    }
    .zhp_ullist li h3 {
        width: 95%;
        margin: 0 auto;
        display: table;
        color: #e55073;
        padding-top: 8px;
    }
    .ziliao_wimg {
        width: 160px;
        margin: 0 auto;
        display: table;
        padding-top: 20px;
    }
    .zlfoxinx {
        text-align: center;
        color: #999;
        padding-bottom: 30px;
        padding-top: 10px;
    }

    .zhp_xz {
        width: 60px;
        height: 60px;
        background: #000;
        position: fixed;
        bottom: 30px;
        right: 20px;
        display: table;
        border-radius: 100px;
        box-shadow: 0 0 15px #fa879f;
        background-image: linear-gradient(to right, #e8597a 0%, #fa879f 100%);
        cursor: pointer;
    }
    .zhp_xz:hover {
        background-image: linear-gradient(to right, #fa879f 0%, #e8597a 100%);
    }
    .zhp_xz img {
        width: 30px;
        margin: 0 auto;
        display: table;
        vertical-align: middle;
        margin-top: 15px;
    }
</style>
@section('javascript')
<script>
    $('.unblock').on('click', function() {
       c4('確定要解除封鎖嗎?')
        var uid=$(this).data('uid');
        var to=$(this).data('to');
        $(".n_left").on('click', function() {
            $.post('{{ route('unblockAJAX') }}', {
                uid: uid,
                to: to,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#tab04").hide();
                show_pop_message('已解除封鎖');
            });
        });
    });

    $('.shou_but').on('click', function() {
        popSus('');
        $(".n_left").on('click', function() {
            // $.post('{{ route('unblockAll') }}', {
            //     uid: '{{ $user->id }}',
            //     _token: '{{ csrf_token() }}'
            // }, function (data) {
            //     $("#tab04").hide();
            //     show_pop_message('已解除封鎖');
            // });
        });
        return false;
    });

    $('.zhp_xz').on('click', function() {
        popSusNew();
        $(".n_left").on('click', function() {
            if($(".blinput").val() == "") {
                $("#popSusNew").hide();
                $(".blbg").hide();
                c5('帳號不能為空白')
                return
            }
            if($.isNumeric($(".blinput").val()) == false){
                $("#popSusNew").hide();
                $(".blbg").hide();
                c5('帳號只能為數字');
                return
            }
            $.post('{{ route('suspicious_u_account') }}', {
                uid: '{{ $user->id }}',
                account_txt: $(".blinput").val(),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                $("#popSusNew").hide();
                $(".blbg").hide();
                c8('新增成功');
                $(".n_left").on('click', function() {
                    $(".blbg").hide();
                    $("#tab08").hide();
                    window.location.href = '?s=false'
                });
            });
        });
        return false;
    });
    
    $('.zhap_search').on('click', function() {
        window.location.href = '?q=' + $('.zhap_input').val() + '&s=false'
    })

    var input1 = document.getElementById("zhap_input");
    input1.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            window.location.href = '?q=' + $('.zhap_input').val() + '&s=false'
        }
    });

    $(document).ready(function(){
        let urlParams = new URLSearchParams(window.location.search);
        if(!urlParams.has('s')) {
            popSus('');
        }
    })
</script>
@stop