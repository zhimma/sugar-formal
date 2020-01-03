@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70 chat">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shouxq"><img src="/new/images/xq_06.png" class="xlimg"><span>收件夾 - {{$to->name}}</span><a href=""><img src="/new/images/xq_03.png" class="xrgimg"></a></div>

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
                                    <img src="@if($message['from_id'] == $user->id) {{$user->meta_()->pic}} @else {{$msgUser->meta_()->pic}} @endif">
                                    <p>
                                        <i class="msg_input"></i>{{$message['content']}}
                                        <a class="delete-btn" data-id="{{ $message['id'] }}" data-ct_time="{{ $message['created_at'] }}" data-content="{{ $message['content'] }}" href="javascript:void(0);"><img src="/new/images/del.png" @if($message['from_id'] == $user->id) class="shde2" @else class="shdel" @endif></a>
                                        <font class="sent_ri @if($message['from_id'] == $user->id)dr_l @else dr_r @endif">
                                            @if(!$isVip)
                                                <img src="/new/images/icon_35.png">
                                            @else
                                            <span>@if($message['read'] == "Y" && $message['from_id'] == $user->id) 已讀 @elseif($message['read'] == "N" && $message['from_id'] == $user->id) 未讀 @endif</span>
                                            <span>{{ substr($message['created_at'],11,5) }}</span>
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
                <div class="m-form__actions" style="text-align: center;">
                    {!! $messages->appends(request()->input())->links() !!}
                </div>
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
        </div>
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
                        $("<a href='{!! url('dashboard/upgrade') !!}' style='color: red;' class='tips'>成為VIP即可解除此限制<br></a>").insertBefore('#msgsnd');
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
                            window.location.reload();
                        });
                    });
                });
            </script>


@stop