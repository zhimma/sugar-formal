@extends('new.layouts.website')
<style>
    .chatWith > img {
        width: 40px;
        height: 40px;
        float: left;
        border-radius: 100px;
    }
</style>
@section('app-content')
    <div class="container matop70 chat">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @if(isset($to))
                    <div class="shouxq"><a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}"><img src="/new/images/xq_06.png" class="xlimg"></a><span>收件夾 - <a href="/dashboard/viewuser/{{$to->id}}" style="color: #fd5678;">{{$to->name}}</a></span>
                        <form class="" action="{{ route('chatpay_ec') }}" method=post id="ecpay">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                            <input type="hidden" name="userId" value="{{ $user->id }}">
                            <input type="hidden" name="to" value="@if(isset($to)) {{ $to->id }} @endif">
                            <button type="button" class="paypay" onclick="checkPay()">
    {{--                            <i class="m-nav__link-icon flaticon-profile"></i>--}}
    {{--                            <span class="m-nav__link-text">車馬費邀請(管道一)</span>--}}
                                <img src="/new/images/xq_03.png" class="xrgimg">
                            </button>
                        </form>
                    </div>
                @else
                    {{ logger('Chat with non-existing user: ' . url()->current()) }}
                @endif
                <div class="message">


                    <div class="message">
                        @php
                            $date_temp='';
                        @endphp
                        @if(!empty($messages))
                            @foreach ($messages as $message)
                                @php
                                    $msgUser = \App\Models\User::findById($message->from_id);
                                    \App\Models\Message::read($message, $user->id);
                                @endphp

                                @if($date_temp != substr($message['created_at'],0,10)) <div class="sebg matopj10">{{substr($message['created_at'],0,10)}}</div>@endif
                                <div class="@if($message['from_id'] == $user->id) show @else send @endif">
                                    <div class="msg @if($message['from_id'] == $user->id) msg1 @endif">
                                        @if($message['from_id'] == $user->id)
                                            <img src="{{$user->meta_()->pic}}">
                                        @else
                                            <a class="chatWith" href="{{ url('/dashboard/viewuser/' . $msgUser->id ) }}">
                                                <img src="{{$msgUser->meta_()->pic}}">
                                            </a>
                                        @endif
                                        <p>
                                            <i class="msg_input"></i>{!! nl2br($message['content']) !!}
                                            <a class="delete-btn" data-id="{{ $message['id'] }}" data-ct_time="{{ $message['created_at'] }}" data-content="{{ $message['content'] }}" href="javascript:void(0);"><img src="/new/images/del.png" @if($message['from_id'] == $user->id) class="shde2" @else class="shdel" @endif></a>
                                            <font class="sent_ri @if($message['from_id'] == $user->id)dr_l @if(!$isVip) novip @endif @else dr_r @endif">
                                                <span>{{ substr($message['created_at'],11,5) }}</span>
                                                @if(!$isVip && $message['from_id'] == $user->id)
                                                    <span>已讀/未讀</span>
                                                    <img src="/new/images/icon_35.png">
                                                @else
                                                <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀 @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀 @endif</span>
                                                @endif


                                            </font>
                                        </p>
                                    </div>
                                </div>
                                @php
                                    $date_temp = substr($message['created_at'],0,10);
                                @endphp
                            @endforeach
                        @endif
                    </div>
                    @if(!empty($messages) && count($messages)>10)
                        <div class="fenye" style="text-align: center;">
        {{--                    {!! $messages->appends(request()->input())->links() !!}--}}
                            <a id="prePage" href="{{ $messages->previousPageUrl() }}">上一頁</a>
                            <a id="nextPage" href="{{ $messages->nextPageUrl() }}">下一頁</a>
                        </div>
                    @endif
                    @if(isset($to))
                        <div class="se_text_bot">
                            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/chat2" id="chatForm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="userId" value="{{$user->id}}">
                                <input type="hidden" name="to" value="{{$to->id}}">
                                <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                                <textarea name="msg" cols="" rows="" class="se_text msg" id="msg" placeholder="請輸入" required></textarea>
        {{--                        <a href="javascript:document.getElementById('chatForm').submit();" id="msgsnd" class="se_tbut matop20 msgsnd">回復</a>--}}
                                <input type="submit" id="msgsnd" class="se_tbut matop20 msgsnd" value="回復">
                            </form>
                        </div>
                    @else
                        <div class="se_text_bot">
                            此會員資料已不存在。
                        </div>
                        {{ logger('Chat with non-existing user: ' . url()->current()) }}
                    @endif
                </div>
        </div>
    </div>
    </div>
    <div class="bl bl_tab" id="tab_payAlert">
        <div class="bltitle"><span>車馬費說明</span></div>
        <div class="n_blnr01 matop20">
            <div class="n_fengs"><span>這筆費用是用來向女方表達見面的誠意<br></span></div>
            <div class="n_fengs"><span><br>●若約見順利<br>站方在扣除 288 手續費，交付 1500 與女方。<br></span></div>
            <div class="n_fengs"><span><br>●若有爭議(例如放鴿子)<br>站方將依女方提供的證明資料，決定是否交付款項與女方。<br></span></div>
            <div class="n_fengs"><span><br>●爭議處理<br>若女方提出證明文件，則交付款項予女方。<br>若女方於於約見日五日內未提出相關證明文件。<br>將扣除手續費後匯回男方指定帳戶。<br></span></div>
            <div class="n_fengs"><span><br>注意：此費用一經匯出，即全權交由本站裁決處置。<br>本人絕無異議，若不同意請按取消鍵返回。</span></div>
            <div class="n_bbutton">
                <span><a class="n_left" href="javascript:">確認</a></span>
                <span><a onclick="$('.blbg').click();" class="n_right" href="javascript:">取消</a></span>
            </div>
        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>
@stop
@section('javascript')
<script>
    // $(document).ready(function(){
        $.ajaxSetup({ cache: false });
        $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
            // you can use originalOptions.type || options.type to restrict specific type of requests
            options.data = jQuery.param($.extend(originalOptions.data||{}, {
                timeStamp: new Date().getTime()
            }));
        });
        d = new Date('{{ \App\Models\Message::$date }}');
                @if(isset($m_time))
        let m_time = '{{ $m_time }}';
                @else
        let m_time = '';
        @endif
        if(m_time){
            let intervalID = setInterval(function() {
                let intervalSecs = 60;
                        @if(isset($m_time))
                let m_time = '{{ $m_time }}';
                        @else
                let m_time = '';
                @endif
                // Split timestamp into [ Y, M, D, h, m, s ]
                let t = m_time.split(/[- :]/);
                // Apply each element to the Date function
                m_time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
                m_time.setHours(m_time.getHours() - 8);
                let now = new Date();
                let diff = now.getTime() - m_time.getTime();
                let diffInSec = Math.floor(diff / 1000);
                let still = intervalSecs - diffInSec;
                let text = document.getElementById('msgsnd').firstChild;
                if(diff < 0 && diffInSec >= intervalSecs){
                    $(".tips").remove();
                    text.data = '回覆';
                    $('#msgsnd').enable(true);
                    clearInterval(intervalID);
                }
                else{
                    $('#msgsnd').enable(false);
                    text.data = '還有' + still + '秒才能回覆';
                }
            },100);
            $("<a href='{!! url('dashboard/vip') !!}' style='color: red;' class='tips'>成為VIP即可知道對方是否讀取信件哦！<br></a>").insertBefore('#msgsnd');
        }

        $('#msg').keyup(function() {
            let msgsnd = $('.msgsnd');
            if(!$.trim($("#msg").val())){
                $('.alert').remove();
                $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter(this);
                msgsnd.prop('disabled', true);
            }
            else {
               $('.alert').remove();
                msgsnd.prop('disabled', !checkForm());
            }
        });
    {{--    $("#showhide").click(function(){--}}
    {{--        if ($("user-list").isHidden()) {--}}
    {{--            $("user-list").show();--}}
    {{--        }--}}
    {{--        else {--}}
    {{--            $("user-list").hide();--}}
    {{--        }--}}
    {{--    });--}}
        setTimeout(function() {
            window.location.reload();
        }, 300000);
    {{--    $('#admin').each(--}}
    {{--        function (){--}}
    {{--            $(this).insertBefore($('#normal'));--}}
    {{--        }--}}
    {{--    );--}}
    //     $('#delete-btn').on('click',function(e){
    //         if(!confirm('確定要刪除?')){
    //             e.preventDefault();
    //         }else{
    //             //$('.deleteMsg').submit();
    //         }
    //     });
    {{--    // $('.report-btn').on('click',function(e){--}}
    {{--    //     if(!confirm('確定要檢舉?')){--}}
    {{--    //         e.preventDefault();--}}
    {{--    //     }--}}
    {{--    // });--}}
    {{--    if($('.user-list').length <= 3){--}}
    {{--        $('<p style="color:red;" id="tips">如果發現訊息不完整，請按下全部顯示</p>').insertAfter($('.options'));--}}
    {{--    }--}}
    {{--    else{--}}
    {{--        $('.showAll').hide();--}}
    {{--    }--}}
    {{--});--}}
    $('#chatForm').submit(function () {
        let content = $('#msg').val(), msgsnd = $('.msgsnd');
        if($.trim(content) == "" ){
            $('.alert').remove();
            $("<a style='color: red; font-weight: bold;' class='alert'>請勿僅輸入空白！</a>").insertAfter($('.msg'));
            msgsnd.prop('disabled', true);
            return checkForm;
        }
        else {
            $('.alert').remove();
            return checkForm;
        }
    });
    function checkForm(){
                @if(isset($m_time))
        let m_time = '{{ $m_time }}';
                @else
        let m_time = '';
        @endif
        if(m_time) {
            let intervalSecs = 60;
                    @if(isset($m_time))
            let m_time = '{{ $m_time }}';
                    @else
            let m_time = '';
            @endif
            // Split timestamp into [ Y, M, D, h, m, s ]
            let t = m_time.split(/[- :]/);
            // Apply each element to the Date function
            m_time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
            m_time.setHours(m_time.getHours() - 8);
            let now = new Date();
            let diff = now.getTime() - m_time.getTime();
            let diffInSec = Math.floor(diff / 1000);
            return diffInSec >= intervalSecs;
        }
        else{
            return true;
        }
    }

    $(".blbg").hide();
    @if(isset($to))
        $('.delete-btn').on('click',function(){
            c4('確定要刪除嗎?');
            var ct_time = $(this).data('ct_time');
            var content = $(this).data('content');
            var id = $(this).data('id');
            $(".n_left").on('click', function() {
                $.post('{{ route('delete2Single') }}', {
                    uid: '{{ $user->id }}',
                    sid: '{{ $to->id }}',
                    ct_time: ct_time,
                    content: content,
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    //window.location.reload();
                    $("#tab04").hide();
                    c2('刪除成功');
                });
            });
        });
    @endif

    function  checkPay(){
        $(".blbg").show();
        $('#tab_payAlert').show();
        $(".n_left").on('click', function() {
           //alert(1);
            $(".blbg").hide();
            $('#tab_payAlert').hide();
            $( "#ecpay" ).submit();
        });
    }
</script>
@stop