@extends('new.layouts.website')
@section('app-content')
    <div class="container matop70 chat">
        <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10 g_pnr_gq">
    {{-- 
            <div class="shou" style="text-align: center;"><span style="margin-left: 58px; border-bottom: unset;">設定</span>
                <font>Setting</font>
                <a href="{!! url('dashboard/chat2/') !!}" class="shou_but">返回</a>
            </div>
    --}}
            <div class="g_password g_pnr">
                <div class="g_pwicon">
                    <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                    <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                    <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                    <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>帳號設定</span></a></li>
    {{--                  <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                </div>
                
                <!-- <div class="shou n_dw90" style="margin-top: 20px;"><span>設定</span>
                    <font>Setting</font>
                    <a href="" class="shou_but">返回</a>
                </div> -->
                
                
                <form id="chatSetForm" action="{{ route('chatNoticeSet') }}" method="post">
                    {{ csrf_field() }}
                    <div class="gg_zh">
                        <dt id="line_notify_expand" class="n_gg_mm"><span><i></i>Line通知設定</span><!--<img src="/new/images/shed_icon.png">--></dt>
                        <dd style="display: block;">
                            <div class="tuba">當您開啟LINE通知後，可設定不同會員等級或收藏的會員 來訊通知與否。</div>
                            <div class="tu_bd">
                                <div class="ngg_a_tab">
                                    <div class="ti_xcheck naa_dd ga_tit">
                                        狀態：
                                        @if($user->line_notify_token==null)
                                            尚未綁定
                                            <a @if($user->isVip()) href="javascript:void(0);" @else onclick="show_onlyForVipPleaseUpgrade()"  @endif class="tuk_bdbutton right line_notify">
                                                立即绑定
                                            </a>
                                        @else
                                            已綁定
                                            <a href="javascript:void(0);" class="qux_bdbutton right line_notify_cancel" style="margin-bottom: 10px;">
                                                取消綁定
                                            </a>
                                        @endif
                                    </div>
                                    @if($user->line_notify_token!=null)
                                        <div class="ti_ktx na_top10 ga_ti_ft"><font class="na_nb">a.<i class="ga_i">身份選擇。勾選的會員來訊時，會用 line notify 通知您</i></font></div>
                                        <div class="ti_xcheck naa_dd">
                                            @if($user->engroup==1)
                                                <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="1" @if(in_array(1, $user_line_notify_chat_set)) checked @endif>長期為主</span>
                                                <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="2" @if(in_array(2, $user_line_notify_chat_set)) checked @endif>長短皆可</span>
                                                <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="3" @if(in_array(3, $user_line_notify_chat_set)) checked @endif>短期為主</span>
                                            @else
                                                <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="5" @if(in_array(5, $user_line_notify_chat_set)) checked @endif>VIP</span>
                                                <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="6" @if(in_array(6, $user_line_notify_chat_set)) checked @endif>普通會員</span>
                                            @endif
                                            <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="8" @if(in_array(8, $user_line_notify_chat_set)) checked @endif>已收藏會員</span>
                                        </div>
                                        <div class="ti_ktx na_top25 ga_ti_ft"><font class="na_nb">b.<i class="ga_i">站方警示封鎖會員通知。勾選後，當與您聊天的{{$user->engroup==1? '女':'男'}}會員被站方封鎖/警示時，會用 line notify 通知您</i></font></div>
                                        <div class="ti_xcheck naa_dd">
                                            <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="7" @if(in_array(7, $user_line_notify_chat_set)) checked @endif>警示會員</span>
                                            <span><input type="checkbox" name="group_name[]" class="ti_ceckys" value="11" @if(in_array(11, $user_line_notify_chat_set)) checked @endif>封鎖會員</span>
                                        </div>
                                        <div class="ti_ktx na_top25"><span class="na_nb">c.其他</span></div>
                                        <div class="ti_xcheck naa_dd">
                                            <span class="ga_w100"><input type="checkbox" name="group_name[]" class="ti_ceckys" value="9" @if(in_array(9, $user_line_notify_chat_set)) checked @endif><i class="gabb_i">來訪通知：當有{{$user->engroup==1? '女':'男'}}會員來查閱您時，會用 line notify 通知您</i></span>
                                            <span class="ga_w100"><input type="checkbox" name="group_name[]" class="ti_ceckys" value="10" @if(in_array(10, $user_line_notify_chat_set)) checked @endif><i class="gabb_i">收藏通知：當有{{$user->engroup==1? '女':'男'}}會員收藏您時，會用 line notify 通知您</i></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
{{--                            @if($user->line_notify_token!=null)--}}
{{--                                <div class="ti_ktx"><span>來訊通知</span></div>--}}
{{--                                <div class="ti_xcheck">--}}
{{--                                    @foreach($line_notify_chat as $row)--}}
{{--                                        @if($row->name == '收藏會員' || $row->name == '誰來看我' || $row->name == '收藏我的會員')--}}
{{--                                            @if($user->isVip())--}}
{{--                                                <span><input type="checkbox" name="group_name[]" id="q4" class="ti_ceckys" value="{{$row->id}}" @if(in_array($row->id, $user_line_notify_chat_set)) checked @endif>{{$row->name}}</span>--}}
{{--                                            @else--}}
{{--                                                @continue--}}
{{--                                            @endif--}}
{{--                                        @else--}}
{{--                                            <span><input type="checkbox" name="group_name[]" id="q4" class="ti_ceckys" value="{{$row->id}}" @if(in_array($row->id, $user_line_notify_chat_set)) checked @endif>{{$row->name}}</span>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            @endif--}}
                        </dd>

                        @if($user->engroup==2)
                            <dt id="refuse_inbox_expand" class="n_gg_mm">
                                <span><i></i>拒收站內來信設定</span>
                            </dt>
                            <dd>  
                                <div class="ngg_a_tab">
                                    <div class="ti_ktx">
                                        <span class="na_nb">
                                            a.身分選擇(勾選的不收)
                                        </span>
                                    </div>
                                    <div class="ti_xcheck naa_dd">
                                        <input type="hidden" name="isHideWeight" value="0">
                                        <span><input type="checkbox" name="isRefused_vip_user" id="q4" class="ti_ceckys" value="1" @if($inbox_refuse_set->isrefused_vip_user == true) checked @endif>vip</span>
                                        <span><input type="checkbox" name="isRefused_common_user" id="q4" class="ti_ceckys" value="1" @if($inbox_refuse_set->isrefused_common_user == true) checked @endif>普通會員</span>
                                        <span><input type="checkbox" name="isRefused_warned_user" id="q4" class="ti_ceckys" value="1" @if($inbox_refuse_set->isrefused_warned_user == true) checked @endif>警示會員</span>
                                    </div>
                                    <div class="ti_ktx na_top25"><span class="na_nb">b.PR分數低於多少不收</span></div>
                                    <div class="ti_xcheck naa_dd">
                                        <select name="refuse_PR" class="na_selct na_top">
                                            <option value=-1 @if($inbox_refuse_set->refuse_pr == -1) selected @endif>請選擇</option>
                                            <option value=0 @if($inbox_refuse_set->refuse_pr == 0) selected @endif>0(無)</option>
                                            <option value=25 @if($inbox_refuse_set->refuse_pr == 25) selected @endif>25</option>
                                            <option value=50 @if($inbox_refuse_set->refuse_pr == 50) selected @endif>50</option>
                                            <option value=75 @if($inbox_refuse_set->refuse_pr == 75) selected @endif>75</option>
                                            <option value=100 @if($inbox_refuse_set->refuse_pr == 100) selected @endif>100</option>
                                        </select>
                                    </div>
                                    <div class="ti_ktx na_top25"><span class="na_nb">c.七天內罐頭訊息數量超過多少不收</span></div>
                                    <div class="ti_xcheck naa_dd">
                                        <select name="refuse_canned_message_PR" class="na_selct na_top">
                                            <option value=-1 @if($inbox_refuse_set->refuse_canned_message_pr == -1) selected @endif>請選擇</option>
                                            <option value=25 @if($inbox_refuse_set->refuse_canned_message_pr == 25) selected @endif>25%</option>
                                            <option value=50 @if(!$inbox_refuse_set->refuse_canned_message_pr) selected @endif @if($inbox_refuse_set->refuse_canned_message_pr == 50) selected @endif>50%</option>
                                            <option value=75 @if($inbox_refuse_set->refuse_canned_message_pr == 75) selected @endif>75%</option>
                                        </select>
                                    </div>
                                    <div class="ti_ktx na_top25"><span class="na_nb">d.拒收幾天內註冊的新會員</span></div>
                                    <div class="ti_xcheck naa_dd">
                                        <select name="refuse_register_days" class="na_selct na_top">
                                            <option value=0 @if($inbox_refuse_set->refuse_register_days == 0) selected @endif>請選擇</option>
                                            <option value=3 @if($inbox_refuse_set->refuse_register_days == 3) selected @endif>三天</option>
                                            <option value=5 @if($inbox_refuse_set->refuse_register_days == 5) selected @endif>五天</option>
                                            <option value=7 @if($inbox_refuse_set->refuse_register_days == 7) selected @endif>七天</option>
                                        </select>
                                    </div>
                                </div>	
                            </dd>
                        @endif
                    </div>
                    <a class="dengl_but matop10 mabot_30 form_submit">更新資料</a>
                </form>
                

    {{--          <form id="chatSetForm" action="{{ route('chatNoticeSet') }}" method="post">--}}
    {{--              {!! csrf_field() !!}--}}
    {{--          <div class="sidebar_box">--}}
    {{--              <div class="sys_log">--}}
    {{--                  <dt class=""><span><img src="/new/images/shed_icon.png">Line通知設定</span></dt>--}}
    {{--                  <dd style="">--}}
    {{--                      <div class="tuba">當您開啟LINE通知後，將優先接收您所收藏的會員來訊通知，並可設定不同會員等級來訊通知與否。</div>--}}
    {{--                      <div class="tu_bd">狀態：@if($user->line_notify_token==null)尚未綁定<a href="javascript:void(0);" class="tuk_bdbutton right line_notify">立即绑定</a>@else 已綁定<a href="javascript:void(0);" class="qux_bdbutton right line_notify_cancel">取消綁定</a>@endif</div>--}}
    {{--                      <div class="ti_ktx"><span>來訊通知</span></div>--}}
    {{--                      <div class="ti_xcheck">--}}

    {{--                          @foreach($line_notify_chat as $row)--}}
    {{--                              @if($row->name == '收藏會員')--}}
    {{--                                 @if($user->isVip())--}}
    {{--                                     <span><input type="checkbox" name="group_name[]" id="q4" class="ti_ceckys" value="{{$row->id}}" @if(in_array($row->id, $user_line_notify_chat_set)) checked @endif>{{$row->name}}</span>--}}
    {{--                                  @else--}}
    {{--                                      @continue--}}
    {{--                                  @endif--}}
    {{--                              @else--}}
    {{--                                  <span><input type="checkbox" name="group_name[]" id="q4" class="ti_ceckys" value="{{$row->id}}" @if(in_array($row->id, $user_line_notify_chat_set)) checked @endif>{{$row->name}}</span>--}}
    {{--                              @endif--}}
    {{--                          @endforeach--}}
    {{--                      </div>--}}
    {{--                  </dd>--}}
    {{--              </div>--}}
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
    {{--          </div>--}}
    {{--          <a class="dlbut matop10 mabot_30 form_submit">更新資料</a>--}}
    {{--          </form>--}}
        </div>
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
            @if($user->isVip())
            show_line_notify_set_alert();
            @else
            show_onlyForVipPleaseUpgrade();
            @endif

            $(".n_bllbut").on('click', function() {
                var lineClientId = '{{config('line.line_notify.client_id')}}';
                var callbackUrl = '{{config('line.line_notify.callback_url')}}';
                var URL = '{{config('line.line_notify.authorize_url')}}?';
                URL += 'response_type=code';
                URL += '&client_id='+lineClientId;
                URL += '&redirect_uri='+callbackUrl;
                URL += '&scope=notify';
                URL += '&state={{csrf_token()}}';
                URL += '&response_mode=form_post';
                window.open(URL, '_blank');
            });
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
            /*var user_line_token = '{{$user->line_notify_token}}';
            if(user_line_token.length==0){
                $('input:checkbox').prop('checked', false);
                c5('請先綁定Line');
            }else {
                $('#chatSetForm').submit();
            }*/
            window.sessionStorage.setItem('isUpdated',true);
            $('#chatSetForm').submit();
        });

        $(function() {
            $(".gg_zh dd").hide();
            if(window.sessionStorage.getItem('isUpdated')){
                if(window.sessionStorage.getItem('line_notify_expand'))
                {
                    $('#line_notify_expand').toggleClass('on');
                    $('#line_notify_expand').next('dd').slideToggle();
                }
                if(window.sessionStorage.getItem('refuse_inbox_expand'))
                {
                    $('#refuse_inbox_expand').toggleClass('on');
                    $('#refuse_inbox_expand').next('dd').slideToggle();
                }
            }
            else{
                
                window.sessionStorage.removeItem('line_notify_expand');
                window.sessionStorage.removeItem('refuse_inbox_expand');
            }
            window.sessionStorage.removeItem('isUpdated');
            
        })
            
        $('#line_notify_expand').click(function(e) {
            if(window.sessionStorage.getItem('line_notify_expand'))
            {
                window.sessionStorage.removeItem('line_notify_expand');
            }
            else
            {
                window.sessionStorage.setItem('line_notify_expand',true);
            }
            $(this).toggleClass('on');
            $(this).next('dd').slideToggle();
        });

        $('#refuse_inbox_expand').click(function(e) {
            if(window.sessionStorage.getItem('refuse_inbox_expand'))
            {
                window.sessionStorage.removeItem('refuse_inbox_expand');
            }
            else
            {
                window.sessionStorage.setItem('refuse_inbox_expand',true);
            }
            $(this).toggleClass('on');
            $(this).next('dd').slideToggle();
        });
    </script>
@stop