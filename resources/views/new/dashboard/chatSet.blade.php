@extends('new.layouts.website')

@section('app-content')
<div class="container matop70 chat">
    <div class="row">
      <div class="col-sm-2 col-xs-2 col-md-2 dinone">
          @include('new.dashboard.panel')
      </div>
      <div class="col-sm-12 col-xs-12 col-md-10 g_pnr_gq">
          <div class="shou"><span style="margin-left: 58px;">設定</span>
              <font>Setting</font>
              <a href="{!! url('dashboard/chat2/'.csrf_token().\Carbon\Carbon::now()->timestamp) !!}" class="shou_but">返回</a>
          </div>
          <form id="chatSetForm" action="{{ route('chatNoticeSet') }}" method="post">
              {!! csrf_field() !!}
          <div class="sidebar_box">
              <div class="sys_log">
                  <dt class=""><span><img src="/new/images/shed_icon.png">Line通知設定</span></dt>
                  <dd style="">
                      <div class="tuba">當您開啟LINE通知後，將優先接收您所收藏的會員來訊通知，並可設定不同會員等級來訊通知與否。</div>
                      <div class="tu_bd">狀態：@if($user->line_notify_token==null)尚未綁定<a href="javascript:void(0);" class="tuk_bdbutton right line_notify">立即绑定</a>@else 已綁定<a href="javascript:void(0);" class="qux_bdbutton right line_notify_cancel">取消綁定</a>@endif</div>
                      <div class="ti_ktx"><span>來訊通知</span></div>
                      <div class="ti_xcheck">

                          @foreach($line_notify_chat as $row)
                              <span><input type="checkbox" name="group_name[]" id="q4" class="ti_ceckys" value="{{$row->id}}" @if(in_array($row->id, $user_line_notify_chat_set)) checked @endif>{{$row->name}}</span>
                          @endforeach
                      </div>
                  </dd>
              </div>
{{--              <div class="sys_log">--}}
{{--                  <dt class=""><span><img src="/new/images/shed_icon.png">拒絕收信設定</span></dt>--}}
{{--                  <dd style="">--}}
{{--                      @if($user->engroup==2)--}}
{{--                          <div class="tuba">拒絕接收不同天數內註冊的男會員來信。</div>--}}
{{--                          <div class="nesdxbot">一般來說優質的daddy通常不會是新帳號，可以過濾不斷開新帳號的無聊男生。但是天數請自行拿捏，畢竟每天都會有新的正常daddy來註冊。</div>--}}
{{--                      @else--}}
{{--                          <div class="tuba">拒絕接收不同天數內註冊的女會員來信。</div>--}}
{{--                          <div class="nesdxbot">--}}
{{--                              <h2 style="padding-bottom:6px;"><b>優點:</b>可以過濾不分八大行業，他們通常註冊後大量發送訊息。</h2>--}}
{{--                              <h2 style="padding-top: 6px;"><b>缺點:</b>收不到新註冊的女會員來訊。請自行拿捏。</h2>--}}
{{--                          </div>--}}
{{--                      @endif--}}

{{--                      <div class="ne_dxz">--}}
{{--                          <span><input type="radio" name="1" id="q4" class="ti_ceckys">1天</span>--}}
{{--                          <span><input type="radio" name="1" id="q4" class="ti_ceckys">3天</span>--}}
{{--                          <span><input type="radio" name="1" id="q4" class="ti_ceckys">7天</span>--}}
{{--                          <span><input type="radio" name="1" id="q4" class="ti_ceckys">10天</span>--}}
{{--                          <span><input type="radio" name="1" id="q4" class="ti_ceckys">30天</span>--}}
{{--                          <font><input type="radio" name="1" id="q4" class="ti_ceckys"><i class="left">自訂天數</i><input placeholder="" class="ne_input"><i class="left">天</i></font>--}}
{{--                      </div>--}}
{{--                  </dd>--}}
{{--              </div>--}}
          </div>
          <a class="dlbut matop10 mabot_30 form_submit">更新資料</a>
          </form>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>

    $(".sidebar_box dd").show();
    $('.sys_log dt').toggleClass('on');

    @if(Session::has('message'))
    c5("{{Session::get('message')}}");
    <?php session()->forget('message');?>
    @endif

    $('.sys_log dt').on('click', function() {
        
        if($(this).hasClass('on')){
            $(this).removeClass('on');
            $(this).toggleClass('off');
        }else{
            $(this).removeClass('off');
            $(this).toggleClass('on');
        }

        $(this).next('dd').slideToggle();
    });

    $(".line_notify").on('click', function() {
        var lineClientId = '{{config('line.line_notify.client_id')}}';
        var callbackUrl = '{{config('line.line_notify.callback_url')}}';
        var URL = '{{config('line.line_notify.authorize_url')}}?';
        URL += 'response_type=code';
        URL += '&client_id='+lineClientId;
        URL += '&redirect_uri='+callbackUrl;
        URL += '&scope=notify';
        URL += '&state={{csrf_token()}}';
        window.location.href = URL;
    });

    $(".line_notify_cancel").on('click', function() {
        c4('確定要解除LINE綁定通知嗎?');
        var URL = '{{route('lineNotifyCancel')}}';
        $(".n_left").on('click', function() {
            $("#tab04").hide();
            $(".blbg").hide();
            window.location.href = URL;
        });
    });

    $('.form_submit').on('click', function() {
        var user_line_token = '{{$user->line_notify_token}}';
        if(user_line_token.length==0){
            $('input:checkbox').prop('checked', false);
            c5('請先綁定Line');
        }else {
            $('#chatSetForm').submit();
        }
    });

</script>
@stop