@extends('new.layouts.website')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
    />
    <style>
        .xin_nleft img {
            margin-top: unset;
            margin-left: unset;
            position: relative;
            left: -8px;
            top: -2px;
        }
        .xin_right img {
            /*position: relative;*/
            /*top: 5px;*/
        }
        .removeImg{
            border: unset;
            position: relative;
            float: right;
            left: 5px;
            background: unset;
        }
        .tempImg{
            display: inline-block;

        }
        .tempImg img{
            max-width: 100px;
        }
        .tao_time{ background: #eee; font-size: 12px; padding:5px 5px; margin: 0 auto; display: table; border-radius:100px; color: #999999; margin-bottom: 10px;}

        .msgPics{
            text-align: center;
            position: relative;
        }
        .nickname{
            display: block;
            position: absolute;
            top: -10px;
            color: white;
            border-radius: 10px;
            border: 1px #fe92a8;
            background-color: #fe92a8;
            padding-left: 5px;
            padding-right: 5px;
            left: 5px;
            font-size: 10px;
        }
        .msg>p{
            min-width: 60px;
        }
    </style>
@endsection

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="fbuttop"></div>
                <div class="shouxq te_ce">
                    <a class="fa_adbut left"><img src="/new/images/back_icon.png" class="fa_backicon">返回</a><!-- <img src="images/gg2.png" class="xlimg"> -->
                    <span class="se_rea">匿名聊天室</span>
                    <a class="fa_adbut right" style="background: none;"></a>
                </div>

                <div class="message xxi">

                    <div class="bangui matopj10">
                        <span><img src="/new/images/bgui.png"></span>
                        <font>{!! $anonymous_chat_announcement !!}</font>
                    </div>
                <div style="overflow: auto; position: relative; max-height: 580px; min-height: 580px;">
                    <livewire:anonymous-chat-show />
                </div>
                </div>


                <div class="se_text_bot">
                    <livewire:anonymous-chat-submit />
                </div>

            </div>
        </div>
    </div>

    <div class="bl_tab_aa" id="show_banned_ele" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;" id="anonymous_chat_name"></span></div>
            <div class="new_pot new_poptk_nn new_pot001">
                <div class="fpt_pic new_po000">
                    <form id="reportPostForm" action="{{ route('anonymous_chat_report') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="anonymous_chat_id" id="anonymous_chat_id" value="">
                        <textarea name="content" cols="" rows="" class="n_nutext" style="border-style: none;" maxlength="300" placeholder="請輸入檢舉理由" required></textarea>
                        <div class="n_bbutton" style="margin-top:10px;">
                            <div style="display: inline-flex;">
                                <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                                <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_banned_close()">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>

    <div class="bl_tab_aa" id="show_chat_message" style="display: none;">
        <div class="bl_tab_bb">
            <div class="bltitle"><span style="text-align: center; float: none;" id="anonymous_chat_message_name"></span></div>
            <div class="new_pot new_poptk_nn new_pot001">
                <div class="fpt_pic new_po000">
                    <form id="chatMessageForm" action="{{ route('anonymous_chat_message') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="anonymous_chat_message_id" id="anonymous_chat_message_id" value="">
                        <textarea name="content" cols="" rows="" class="n_nutext" style="border-style: none;" maxlength="300" placeholder="請輸入內容" required></textarea>
                        <div class="n_bbutton" style="margin-top:10px;">
                            <div style="display: inline-flex;">
                                <button type="submit" class="n_right" style="border-style: none; background: #8a9ff0; color:#ffffff; float: unset; margin-left: 0px; margin-right: 20px;">送出</button>
                                <button type="reset" class="n_left" style="border: 1px solid #8a9ff0; background: #ffffff; color:#8a9ff0; float: unset; margin-right: 0px;" onclick="show_chat_message_close()">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a onclick="show_banned_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
        </div>
    </div>

@stop

@section('javascript')

    <script>
    function padLeft(str, len) {
        str = '' + str;
        if (str.length >= len) {
            return str;
        } else {
            return padLeft("0" + str, len);
        }
    }

    function show_banned(id, name) {
        $('#anonymous_chat_id').val(id);
        $('#anonymous_chat_name').html('檢舉' + padLeft(name,4));
        $(".announce_bg").show();
        $("#show_banned_ele").show();
    }

    function show_banned_close(){
        $(".announce_bg").hide();
        $("#show_banned_ele").hide();
    }

    function chat_message(id, engroup, name){
        let self_engroup = '{{$user->engroup}}';
        if(self_engroup == engroup){
            c5('您好，本站僅限與異性互動！');
            return false;
        }

        let canNotMessage = '{{$canNotMessage}}';
        if(canNotMessage == 1){
            c5('您好，一週僅限發一則私訊！');
            return false;
        }

        $('#anonymous_chat_message_id').val(id);
        $('#anonymous_chat_message_name').html('發訊息給 ' + padLeft(name,4));
        $(".announce_bg").show();
        $("#show_chat_message").show();

    }

    function show_chat_message_close(){
        $(".announce_bg").hide();
        $("#show_chat_message").hide();
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endsection
