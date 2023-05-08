<?php
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')
@section('style')
<style>
.real_auth_bg{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
.real_auth_n_left
{   
    float: right;
    width: 120px;
    height: 40px;
    background: #8a9ff0;
    border-radius: 200px;
    color: #ffffff;
    text-align: center;
    line-height: 40px;
    font-size: 16px;
    margin-right: 11px;
}

a.real_auth_n_left:hover {
    color: #ffffff;
    box-shadow: inset 0px 15px 10px -10px #4c6ded, inset 0px -10px 10px -20px #4c6ded;
}

#real_auth_hint_tab .bltext ol {
    margin:1em 0 1em 0;
    list-style:none;
    background:yellow;
}

#new_height_input_error_msg,#new_weight_input_error_msg {color:red;font-weight:bold;}

</style>
<script>
real_auth_bad_count = 0;
</script>
@if($rap_service->isInRealAuthProcess())
<script>
    function real_auth_process_check()
    {
        $('body').hide();
        $.get( "{{route('check_is_in_real_auth_process')}}"+location.search+"&{{csrf_token()}}="+(new Date().getTime()),function(data){
            if(data!='1') {
                window.history.replaceState( {} , $('title').html(), '{{route("real_auth")}}' );
                location.href='{{route("real_auth")}}';
            }
            else {
                $('body').show();
            }
        });
    } 
    
    real_auth_process_check();
</script>   
@endif
@stop
@section('app-content')

  <?php
    //拒絕接受搜索縣市最大數量
    $blockcity_limit_count = 10;
    $blockarea_selected = [];
    $blockcity_selected = [];
    if (!isset($user)) {
        $umeta = null;
    } else {
        $umeta = $user->meta;
        $umeta_block = [];
        // dd($umeta);
        if(isset($umeta->city)){
            $umeta->city = explode(",",$umeta->city);
            $umeta->area = explode(",",$umeta->area);
        }

        if(isset($umeta->blockcity)){
            $city = explode(",",$umeta->blockcity);
            $area = explode(",",$umeta->blockarea);
            $arr = [];
            for($i = 0; $i < count($area); $i++)
            {
                for($j = $i+1; $j < count($area); $j++)
                {
                    if($area[$i] == $area[$j])
                    {
                        $arr[] = $j;
                    }
                }
            }
            foreach($arr as $a)
            {
                unset($city[$a]);
                unset($area[$a]);
            }
            $umeta->blockcity = $city;
            $umeta->blockarea = $area;
      }
    }
  ?>
  <style type="text/css">
    .abtn{cursor: pointer;}
    .twzip {display: inline-block !important;width: auto !important;min-width: 49%;/*margin-right: 10PX;*/}
    .select_xx2{width: 100%;border: #d2d2d2 1px solid;border-radius: 4px;height: 40px;padding: 0 6px;color:#555;background:#ffffff;font-size: 15px;margin-bottom: 10px;}
    .select_xx01 {
        color:unset;
    }
    .blnr{
        padding: 20 20 20 20;
    }
  </style>
  <style>
.switch_left{ float:right;width:120px;height: 40px;background: #8a9ff0;border-radius: 200px;color: #ffffff;text-align: center;line-height: 40px;font-size: 16px; margin-right:11px;}
.switch_left:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #4c6ded,inset 0px -10px 10px -20px #4c6ded;}
.switch_right{ float:left;width:120px;height: 40px;background: #ffffff; border: #8a9ff0 1px solid;border-radius: 200px;color: #8a9ff0;text-align: center;line-height: 40px;font-size: 16px; margin-left:11px;}
.switch_right:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #516cd4,inset 0px -10px 10px -20px #516cd4; background:#8a9ff0}
body div .g_inputt a.zw_dw_left {float:left;}
.zw_dw {margin-bottom:5px;}
dt span.engroup_type_title {display:inline-block;width:10%;white-space:nowrap;}
  </style>
  <style>
    div.se_zlman div.birth_lock {display:inline-block;}
  </style>

    <div class="container matop70 chat">
    <div class="row">
      <div class="col-sm-2 col-xs-2 col-md-2 dinone">
          @include('new.dashboard.panel')
      </div>
      <div class="col-sm-12 col-xs-12 col-md-10">
        <div class="g_password">
          <div class="g_pwicon">
              <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
              <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t g_hicon1"><span>基本資料</span></a></li>
              <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
              <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3"><span>帳號設定</span></a></li>
{{--              <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
          </div>
          <div class="addpic g_inputt">
                    @if($user->isVipOrIsVvip() && !Session::has('original_user'))
{{--                    <a onClick="popSwitchOtherEngroup()" class="zw_dw zw_dw_left">模擬{{$user->engroup == 1?'女':'男'}}會員</a>--}}
                    @endif  
                    @if(Session::has('original_user'))  
                    <a class="zw_dw zw_dw_left" href="{{ route('escape') }}">回到原使用者</a>     
                    @endif
{{--              <div class="n_adbut"><a href="/dashboard/viewuser/{{$user->id}}"><img src="/new/images/1_06.png">預覽</a></div>--}}
{{--              <div class="n_adbut"><a href="/member_auth/" style="padding-left: 10px;">手機驗證</a></div>--}}
              @if($user->engroup==1)
              <div style="float:right; padding-right: 5px;">
                  <div class="vvipjdt_aa" onclick="pr()" style="cursor: pointer;">
{{--                      @if($pr != false && $pr >= 1)--}}
{{--                          @php--}}
{{--                          if($pr==1){$pr = 0;}--}}
{{--                          @endphp--}}
                          <div class="progress progress-striped vvipjdt_pre" title="大方指數">
                              <div class="progress-bar progress_info" role="progressbar" aria-valuenow="{{$pr}}" aria-valuemin="0" aria-valuemax="100"
                                   style="width:{{$pr}}%;">
                                  <span class="prfont pr_text">PR: {{$pr}}</span>
                              </div>
                          </div>
{{--                      @elseif($pr == false)--}}
{{--                          <div class="progress progress-striped vvipjdt_pre" title="大方指數">--}}
{{--                              <div class="progress-bar progress_info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"--}}
{{--                                   style="width:0%;">--}}
{{--                                  <span class="prfont pr_text">PR: 無</span>--}}
{{--                              </div>--}}
{{--                          </div>--}}
{{--                      @endif--}}
                  </div>
                  <img src="/new/images/tx_new.png" style="position: absolute; width:40px; margin-top:-44px; margin-left:-25px;">
              </div>
              @endif
            <div class="xiliao_input">
               <form class="m-form m-form--fit m-form--label-align-right" method="POST" name="user_data" action="" id="information" data-parsley-validate novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="userId" value="{{$user->id}}">
                <div class="n_input">
                    @if($user->engroup==2 && !$rap_service->isInRealAuthProcess() )
                    <dt>
                        <span>本人驗證</span>
                        <span>
                            <div class="select_xx03">
                                @if($rap_service->isSelfAuthWaitingCheck())
                                等待審核中
                                @elseif($rap_service->isSelfAuthApplyNotVideoYet())
                                尚未與站方視訊
                                <a class="btn btn-success" href="{{url('user_video_chat_verify')}}">
                                    前往視訊頁面
                                </a>
                                @elseif($rap_service->isPassedByAuthTypeId(1))
                                <a class="btn btn-success" href="{{route('real_auth')}}">
                                    請點此檢視認證
                                </a>
                                @else
                                尚未認證
                                <a class="btn btn-success" href="{{route('real_auth')}}">
                                    立即申請
                                </a>                            
                                @endif
                            </div>
                        </span>
                    </dt>                
                    @endif
                    @if($user->engroup==2)
                        <dt>
                            <span>視訊錄影驗證</span>
                            <span>
                                <div class="select_xx03">
                                    @if($user->backend_user_details->first()->is_need_video_verify ?? false)
                                        @if($user->backend_user_details->first()->has_upload_video_verify ?? false)
                                            您好，您於 {{Carbon\Carbon::parse($user->backend_user_details->first()->need_video_verify_date)->format('Y-m-d')}} 時於本站申請 視訊錄影認證，目前已完成視訊錄影，待站方審核通知。
                                        @else
                                            @if($user->backend_user_details->first()->video_verify_fail_count>=3)
                                                您連續三次視訊驗證失敗，暫時停止視訊驗證，若有問題請與站長聯絡 <a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0" style="all: initial;all: unset;height: 26px; float: unset;vertical-align:middle !important;"></a>
                                            @elseif($user->warned_users->video_auth ?? false)
                                                你好，您目前被站方警示，站方會再跟您約視訊驗證時間，再請注意來訊。
                                            @else
                                                已申請，站方會再跟您約驗證時間，再請注意來訊。
                                            @endif
                                        @endif
                                    @elseif($user->video_verify_auth_status == 0)
                                        @if($user->isAdvanceAuth())
                                            尚未申請<a id="apply_video_record_verify" class="btn btn-success">申請驗證</a>
                                        @else
                                            尚未通過進階驗證
                                        @endif
                                    @elseif($user->video_verify_auth_status == 1)
                                        已通過
                                    @endif
                                </div>
                            </span>
                        </dt>
                    @endif
                    <dt>
                        <span>LINE 通知</span>
                        <span>
                            <div class="select_xx03">@if($user->line_notify_token == null) 尚未綁定<a class="btn btn-success line_notify">立即綁定</a> @else 已綁定 <a class="btn btn-secondary line_notify_cancel">取消綁定</a>&nbsp;<a href="{{route('viewChatNotice')}}">請點我設定</a>@endif</div>
                        </span>
                    </dt>
                  <dt>
                    <span>暱稱<i>(必填)</i></span>
                    <span>
{{--                        <input name="name" id="name" type="text" class="select_xx01"  placeholder="至多八個字" value="{{$user->name}}" data-parsley-errors-messages-disabled maxlength="8" disabled style="background-color: #d2d2d2;">--}}
                        <div class="select_xx01 senhs hy_new" style="background: #d2d2d2;">{{$user->name}}</div>
                    </span>
                      <input name="name" id="name" type="hidden" class="select_xx01"  placeholder="至多八個字" value="{{$user->name}}">
                  </dt>
                  <dt>
                    <span>一句話形容自己<i>(必填)</i></span>
                    <span><input name="title" type="text" class="select_xx01"  placeholder="請輸入" value="{{$user->title}}" data-parsley-errors-messages-disabled></span>
                  </dt>

                  <dt>
                        <div>
                            <span class="engroup_type_title">帳號類型</span>
                            @if($user->engroup==2)
                                <input type="hidden" name="is_pure_dating" value="1">
                                <div style="float: right;margin-top:8px;"><input name="is_pure_dating" type="checkbox" @if(isset($umeta->is_pure_dating) && $umeta->is_pure_dating != 1) checked @endif value="0"> 拒絕進一步發展</div>
                            @endif
                        </div>

                        @if($user->isVipOrIsVvip() && !Session::has('original_user'))
{{--                        <a onClick="popSwitchOtherEngroup()"class="zw_dw">模擬{{$user->engroup == 1?'女':'男'}}會員</a>--}}
                        @endif 
                        @if(Session::has('original_user'))  
                        <a class="zw_dw" href="{{ route('escape') }}">回到原使用者</a>     
                        @endif                        
                      <span>
{{--                          <input name="" id="" type="text" class="select_xx01" value="@if($user->engroup==1)甜心大哥@else甜心寶貝@endif" data-parsley-errors-messages-disabled disabled style="background-color: #d2d2d2;">--}}
                          <div class="select_xx01 senhs hy_new" style="background: #d2d2d2;">@if($user->engroup==1)甜心大哥@else甜心寶貝@endif</div>

                      </span>

                      <input name="engroup" id="" type="hidden" class="select_xx01" value="{{$user->engroup}}" data-parsley-errors-messages-disabled disabled style="background-color: #d2d2d2;">
                  </dt>
{{--                  <dt>--}}
{{--                      <span>帳號類型</span>--}}
{{--                      <div class="n_heg" style="margin-top:-2px">--}}
{{--                        <form name="form1" method="post" action="">--}}
{{--                            <label class="n_lod"><input required data-parsley-errors-messages-disabled type="radio" name="engroup" value="1" id="RadioGroup1_0" {{ ($user->engroup == 1)?"checked":"" }} ><font class="n_loleft">甜心大哥</font></label>--}}
{{--                            <label class="n_lod"><input required data-parsley-errors-messages-disabled type="radio" name="engroup" value="2" id="RadioGroup1_1" {{ ($user->engroup == 2)?"checked":"" }}><font class="n_loleft">甜心寶貝</font></label>--}}
{{--                        </form>--}}
{{--                        <div class="n_red">註：每個帳號只能變更一次</div>--}}
{{--                      </div>--}}
{{--                  </dt>--}}
                    @if($user->engroup==2)
                    <dt>
                        <span style="@if($rap_service->getLatestActualUnchekedExchangePeriodModifyEntry()) display:inline-block;width:51%; @endif" >包養關係</span>
                        @if($rap_service->modify_entry())
                        <span style="display:inline-block;width:47%;">正在審核的包養關係異動</span>
                        @endif                        
                        @php
                            $exchange_period_name = DB::table('exchange_period_name')->where('id',$user->exchange_period)->first();
                        @endphp
                        <span>
{{--                            <input name="" id="" type="text" class="select_xx01" value="{{$exchange_period_name->name}}" data-parsley-errors-messages-disabled disabled style="background-color: #d2d2d2;">--}}
                            <div class="select_xx01 senhs hy_new" tabindex="-1" id="exchange_period_readonly_block" style="background: #d2d2d2;@if($rap_service->modify_entry()) display:inline-block;width:50%;  @endif">{{$exchange_period_name?->name}}{{$exchange_period_name?->name_explain}}</div>
                            @if($rap_service->isPassedByAuthTypeId(1) && $rap_service->modify_entry())
                            <div class="select_xx01 senhs hy_new" tabindex="-1" id="new_exchange_period_readonly_block" style="background: #d2d2d2;display:inline-block;width:47%;" >{{$rap_service->modify_entry()->new_exchange_period_name?->name}}{{$rap_service->modify_entry()->new_exchange_period_name?->name_explain}}</div>        
                            @endif                            
                        </span>
                        <input name="exchange_period" id="" type="hidden" class="select_xx01" value="{{$user->exchange_period}}" data-parsley-errors-messages-disabled disabled style="background-color: #d2d2d2;">                                     
                    </dt>
                    @endif
                    <dt>
                        <div>
                            <span class="engroup_type_title">地區<i>(必填)</i></span>
                            @if($user->engroup==2)
                                <input type="hidden" name="is_dating_other_county" value="0">
                                <div style="float: right;margin-top:8px;"><input name="is_dating_other_county" type="checkbox" @if(isset($umeta->is_dating_other_county) && $umeta->is_dating_other_county == true) checked @endif value="1"> 願意接受約外縣市</div>
                            @endif
                        </div>
                        
                        <div id="county">
                        
                        @if(isset($umeta->city))
                            @if(is_array($umeta->city))
                                @foreach($umeta->city as $key => $cityval)
                                <div class="twzipcode">
                                    <div class="twzip" data-role="county" data-name="@if($key != 0 )city{{$key}}@else{{'city'}}@endif" data-value="{{$umeta->city[$key]}}">
                                    </div>
                                    <div class="twzip right" data-role="district" data-name="@if($key != 0 )area{{$key}}@else{{'area'}}@endif" data-value="{{$umeta->area[$key]}}">
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="twzipcode">
                                <div class="twzip" data-role="county" data-name="city" data-value="{{$umeta->city}}">
                                </div>
                                <div class="twzip right" data-role="district" data-name="area" data-value="{{$umeta->area}}">
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="twzipcode">
                                <div class="twzip" data-role="county" data-name="city" data-value="">
                                </div>
                                <div class="twzip right" data-role="district" data-name="area" data-value="">
                                </div>
                            </div>
                        @endif
                        </div>
                        <div class="n_xqline">
                        <div class="right" style="margin-bottom: 10px;">
                            <a href="javascript:" id="add_county"><img src="/new/images/jh.png">新增縣市</a>
                            <input type="hidden" name="isHideArea" value="0">
                            <input name="isHideArea" type="checkbox" @if($umeta->isHideArea == true) checked @endif value="1"> 隱藏鄉鎮市區
                        </div>
                        </div>
                        <!-- <span class="matop10">
                        <select name=""  class="select_xx"><option>連江縣</option><option>B</option></select>
                        <select name=""  class="select_xx right"><option>南竿鄉</option><option>B</option></select>
                        </span>-->
                    </dt>
                    @if($user->engroup==2)
                    
                    <dt class="matopj15">
                        <span>拒絕接受搜索縣市</span>
                        <div id="block_county">
                        @if(isset($umeta->blockcity))
                            @if(is_array($umeta->blockcity))
                            @foreach($umeta->blockcity as $key => $cityval)
                                <div class="twzipcode">
                                    <div class="twzip " data-role="county"
                                        data-name="blockcity{{$key == 0 ? '' : $key}}"
                                        data-value="{{$umeta->blockcity[$key]}}">
                                    </div>
                                    <div class="twzip right" data-role="district"
                                        data-name="blockarea{{$key == 0 ? '' : $key}}"
                                        data-value="{{$umeta->blockarea[$key]}}">
                                        <?php
                                            /*if ($key ==0)
                                                $blockarea_selected = $umeta->blockarea[0];
                                            else if ($key ==1){
                                                $blockarea1_selected = $umeta->blockarea[1];
                                            }else if ($key ==2){
                                                $blockarea2_selected = $umeta->blockarea[2];
                                            }*/

                                        ?>
                                    </div>
                                </div>
                            @endforeach
                            @for ($i = 0; $i < $blockcity_limit_count; $i++)
                                <?php
                                    $blockcity_selected[] = isset($umeta->blockcity[$i]) ? $umeta->blockcity[$i] : '';
                                    $blockarea_selected[] = isset($umeta->blockarea[$i]) ? str_replace($umeta->blockcity[$i],'',$umeta->blockarea[$i]) :'';
                                    if($blockcity_selected[$i] == '海外')
                                    {
                                        if($blockarea_selected[$i] == '全區')
                                        {
                                            $blockarea_selected[$i] = '全部';
                                        }
                                    }
                                ?>
                            @endfor
                            @else
                            <div class="twzipcode">
                                <div class="twzip" data-role="county" data-name="blockcity" data-value="{{$umeta->blockcity}}">
                                </div>
                                <div class="twzip right" data-role="district" data-name="blockarea" data-value="{{$umeta->blockarea}}">
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="twzipcode">
                                <div class="twzip" data-role="county" data-name="blockcity" data-value="">
                                </div>
                                <div class="twzip right" data-role="district" data-name="blockarea" data-value="">
                                </div>
                            </div>
                        @endif
                        </div>
                        <div class="n_xqline">
                            <div class="right" style="margin-bottom: 10px;">
                                <a href="javascript:" id="add_block_county"><img src="/new/images/jh.png">新增縣市</a>
                            </div>
                        </div>
                    </dt>
                    @endif
                    @if($user->engroup==2)
                            {{--<dt class="">
                                <span>預算<i>(必填)</i></span>
                                <span>
                                <select data-parsley-errors-messages-disabled name="budget"  class="select_xx01">
                                    <option value="">請選擇</option>
                                    <option value="基礎" {{($umeta->budget == '基礎')?"selected":""  }}>基礎</option>
                                    <option value="進階"
                                            @if($umeta->budget == '進階') selected @endif>進階
                                    </option>
                                    <option value="高級"
                                            @if($umeta->budget == '高級') selected @endif>高級
                                    </option>
                                    <option value="最高"
                                            @if($umeta->budget == '最高') selected @endif>最高
                                    </option>
                                    <option value="可商議" {{($umeta->budget == '可商議')?"selected":""  }}>可商議</option>
                                </select>
                                </span>
                            </dt>--}}
                    @endif
                    <dt>
                        <span>出生年月<i>(必填)</i></span>
                        <span>
                          <input type="hidden" name="day" value="01">
                          <div class="se_zlman left">
                          @if(!$user->advance_auth_status??null)
                            <select data-parsley-errors-messages-disabled name="year"  class="select_xx04 sel_year" data-value="{{ $year }}">
                            </select>
                          @else
                              <div class="select_xx01 senhs hy_new select_xx04 birth_lock" style="background: #d2d2d2;">{{ $year }}</div>                            
                          @endif
                            <i class="right">年</i>
                          </div>
                          <div class="se_zlman right">
                          @if(!$user->advance_auth_status??null)
                            <select data-parsley-errors-messages-disabled name="month"  class="select_xx04 sel_month" data-value="{{ $month }}">
                            </select>
                          @else
                              <div class="select_xx01 senhs hy_new select_xx04 birth_lock" style="background: #d2d2d2;">{{ $month }}</div>                            
                          @endif
                            <i class="right">月</i>
                          </div>
                        </span>
                    </dt>
                    <dt id="height_container">
                        <span  style="@if($rap_service->getLatestActualUnchekedHeightModifyEntry()) display:inline-block;width:51%; @endif" >身高（cm）<i>(必填)</i></span>
                        @if($rap_service->modify_entry())
                        <span style="display:inline-block;width:47%;">正在審核的身高異動</span>
                        @endif
                        @if($rap_service->isPassedByAuthTypeId(1))                      
                            <div onclick="show_real_auth_new_height_tab();"  class="select_xx01 senhs hy_new" tabindex="-1" id="height_readonly_block" style="background: #d2d2d2;@if($rap_service->modify_entry()) display:inline-block;width:50%;  @endif" onclick="show_real_auth_new_height_tab();">{{$umeta->height}}</div>    
                            @if($rap_service->modify_entry())
                            <div class="select_xx01 senhs hy_new" tabindex="-1" id="new_height_readonly_block" style="background: #d2d2d2;display:inline-block;width:47%;" onclick="show_real_auth_new_height_tab();">{{$rap_service->modify_entry()->new_height}}</div>        
                            @endif
                        @else  
                            <span><input name="height" id="height" type="number" class="select_xx01"  placeholder="請輸入數字範圍140～210" value="{{$umeta->height}}" title="請輸入140~210範圍" ></span>
                        @endif
                    </dt>
                                
                    <!--新增體重欄位 By Simon-->
                    <dt>
                        <span style="@if($rap_service->getLatestActualUnchekedWeightModifyEntry()) display:inline-block;width:51%; @endif">體重（kg）</span>                     
                        @if($rap_service->modify_entry())
                        <span style="display:inline-block;width:47%;">正在審核的體重異動</span>
                        @endif                    
                    @if($rap_service->isPassedByAuthTypeId(1))
                    <div  onclick="show_real_auth_new_weight_tab();"  class="select_xx01 senhs hy_new" tabindex="-1" id="weight_readonly_block" style="background: #d2d2d2;@if($rap_service->modify_entry()) display:inline-block;width:50%;  @endif">{{$umeta->weight?($umeta->weight-4).' ~ '.$umeta->weight:str_replace('0','不填寫',$umeta->weight)}}</div>         
                    @if($rap_service->modify_entry())
                    <div class="select_xx01 senhs hy_new" tabindex="-1" id="new_weight_readonly_block" style="background: #d2d2d2;display:inline-block;width:47%;" onclick="show_real_auth_new_weight_tab();">{{$rap_service->getProfileWeightWord( $rap_service->modify_entry()->new_weight)}}</div>        
                    @endif                    
                    @else
                        <span>                        
                        <select name="weight"  class="select_xx01">
                            <option value=null>請選擇</option>
                            @for ($i = 1; $i < 21; $i++)
                            <option value="{{$i*5}}"
                                    @if($umeta->weight == $i*5) selected @endif>{{$i*5-4}} ~ {{$i*5}}
                            </option>
                            @endfor
                        </select>
                        </span>                         
                    @endif                      
                        <div class="n_xqline">
                            <div class="right" style="margin-bottom: 10px;">
                                <input type="hidden" name="isHideWeight" value="0">
                                <input name="isHideWeight" type="checkbox" @if($umeta->isHideWeight == true) checked @endif value="1"> 隱藏體重
                            </div>
                        </div>
                    </dt>
                    <dt>
                        <span>體型</span>
                        <span>
                            <select name="body"  class="select_xx01">
                            <option value=null>請選擇</option>
                            <option value="瘦"
                                    @if($umeta->body == '瘦') selected @endif>瘦
                            </option>
                            <option value="標準"
                                    @if($umeta->body == '標準') selected @endif>標準
                            </option>
                            <option value="微胖"
                                    @if($umeta->body == '微胖') selected @endif>微胖
                            </option>
                            <option value="胖"
                                    @if($umeta->body == '胖') selected @endif>胖
                            </option>
                            </select>
                        </span>
                    </dt>
                    @if($user->engroup==2)
                        {{--<dt>--}}
                            {{--<span>體重（kg）</span>--}}
                            {{--<span><input minlength="2"  data-parsley-minlength="2" name="weight" type="text" class="select_xx01"  placeholder="請填入體重" value="{{$umeta->weight}}"></span>--}}
                            {{--<div class="right" style="margin: 10px 0 -5px 10px;">--}}
                                {{--<input type="hidden" name="isHideWeight" value="0">--}}
                                {{--<input name="isHideArea" type="checkbox"  @if($umeta->isHideWeight == true) checked--}}
                                {{--@endif value="1"> 隱藏體重--}}
                            {{--</div>--}}
                        {{--</dt>--}}
                        <dt style="margin-bottom:7px;">
                            <span>CUP</span>
                            <span>
                                <select name="cup"  class="select_xx01" >
                                <option value=null>請選擇</option>
                                <option value="A"
                                        @if($umeta->cup == 'A') selected @endif>A
                                </option>
                                <option value="B"
                                        @if($umeta->cup == 'B') selected @endif>B
                                </option>
                                <option value="C"
                                        @if($umeta->cup == 'C') selected @endif>C
                                </option>
                                <option value="D"
                                        @if($umeta->cup == 'D') selected @endif>D
                                </option>
                                <option value="E"
                                        @if($umeta->cup == 'E') selected @endif>E
                                </option>
                                <option value="F"
                                        @if($umeta->cup == 'F') selected @endif>F
                                </option>
                                </select>
                            </span>
                            <div class="n_xqline">
                                <div class="right" style="margin-bottom: 10px;">
                                    <input type="hidden" name="isHideCup" value="0">
                                    <input name="isHideCup" type="checkbox" @if($umeta->isHideCup == true) checked @endif value="1"> 隱藏CUP
                                </div>
                            </div>
                        </dt>
                        <dt>
                            <span>家庭狀況</span>
                            <select name="family_situation"  class="select_xx01">
                                <option value=null>請選擇</option>
                                <option value="有小孩(撫養中)"
                                        @if($umeta->family_situation == '有小孩(撫養中)') selected @endif>有小孩(撫養中)
                                </option>
                                <option value="有小孩(無撫養)"
                                        @if($umeta->family_situation == '有小孩(無撫養)') selected @endif>有小孩(無撫養)
                                </option>
                                <option value="有小孩(輪流養)"
                                        @if($umeta->family_situation == '有小孩(輪流養)') selected @endif>有小孩(輪流養)
                                </option>
                                <option value="單親"
                                        @if($umeta->family_situation == '單親') selected @endif>單親
                                </option>
                            </select>
                        </dt>

                        @if($user->engroup==2)
                        <dt>
                            <span id="about_column" style="border-bottom: #eee 1px solid;">關於我<i>(必填)</i></span>

                            <div class="ka_zli"><i></i>尋找關係</div>
                            <div id="itemssxN">
                                <nav class="custom_nav_n">
                                    @foreach($looking_for_relationships as $option)
                                        <div class="custom_s a1 option_looking_for_relationships @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}<b class="cr_b" @if($option->xref_id ?? false) style="display: block" @endif>{{$option->option_content}}</b></div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="looking_for_relationships" type="hidden" name="looking_for_relationships" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                                $(function(){
                                    $('#itemssxN .a1').click(function(){
                                        if($(this).children().is(':hidden'))
                                        {
                                            $(this).children().show();
                                        }
                                        else
                                        {
                                            $(this).children().hide();
                                        }
                                    })
                                })
                            </script>

                            <div class="ka_zli cutop"><i></i>對糖爹的期待</div>
                            <div id="itemssxN1">
                                <nav class="custom_nav_n">
                                    @foreach($expect as $option)
                                        <div class="custom_s a1 option_expect @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="expect" type="hidden" name="expect" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN1 .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                            </script>

                            <div class="ka_zli cutop"><i></i>或是其他你想說的</div>
                            <textarea data-parsley-errors-messages-disabled id="about_other" name="about" cols="" rows="3" class="select_xx05">{{$umeta->about}}</textarea>
                        </dt>
                        <dt>
                            <span  id="style_column" style="border-bottom: #eee 1px solid;">期待的約會模式<i>(必填)</i></span>

                            <div class="ka_zli"><i></i>喜歡的食物</div>
                            <div id="itemssxN2">
                                <nav class="custom_nav_n">
                                    @foreach($favorite_food as $option)
                                        <div class="custom_s a1 option_favorite_food @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="favorite_food" type="hidden" name="favorite_food" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN2 .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                            </script>

                            <div class="ka_zli"><i></i>偏好約會地點</div>
                            <div id="itemssxN3">
                                <nav class="custom_nav_n"> 
                                    @foreach($preferred_date_location as $option)
                                        <div class="custom_s a1 option_preferred_date_location @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="preferred_date_location" type="hidden" name="preferred_date_location" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN3 .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                            </script>

                            <div class="ka_zli"><i></i>期望模式</div>
                            <div id="itemssxb4">
                                <nav class="custom_nav_n">
                                    @foreach($expected_type as $option)
                                        <div class="custom_s b1 option_expected_type @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}<b class="cr_b" @if($option->xref_id ?? false) style="display: block" @endif>{{$option->option_content}}</b></div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="expected_type" type="hidden" name="expected_type" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxb4 .b1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                                $(function(){
                                    $('#itemssxb4 .b1').click(function(){
                                        if($(this).children().is(':hidden'))
                                        {
                                            $(this).children().show();
                                        }
                                        else
                                        {
                                            $(this).children().hide();
                                        }
                                    })
                                })
                            </script>

                            <div class="ka_zli cutop"><i></i>相處的頻率與模式</div>
                            <div id="itemssxN5">
                                <nav class="custom_nav_n">
                                    @foreach($frequency_of_getting_along as $option)
                                        <div class="custom_s a1 option_frequency_of_getting_along @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="frequency_of_getting_along" type="hidden" name="frequency_of_getting_along" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN5 .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                            </script>

                            <div class="ka_zli cutop"><i></i>或是其他你想說的</div>
                            <textarea data-parsley-errors-messages-disabled id="style_other" name="style" cols="" rows="3" class="select_xx05">{{$umeta->style}}</textarea>
                        </dt>
                        @endif

                        <dt>
                            <span>有空時段</span>
                            <select name="available_time"  class="select_xx01">
                                <option value=null>請選擇</option>
                                <option value="不固定"
                                        @if($umeta->available_time == '不固定') selected @endif>不固定
                                </option>
                                <option value="平日白天"
                                        @if($umeta->available_time == '平日白天') selected @endif>平日白天
                                </option>
                                <option value="平日晚上"
                                        @if($umeta->available_time == '平日晚上') selected @endif>平日晚上
                                </option>
                                <option value="週末白天"
                                        @if($umeta->available_time == '週末白天') selected @endif>週末白天
                                </option>
                                <option value="週末晚上"
                                        @if($umeta->available_time == '週末晚上') selected @endif>週末晚上
                                </option>
                            </select>
                        </dt>
                        <dt>
                            <span>有刺青</span>
                            <font>
                                <select name="tattoo_part" id="tattoo_part" class="select_xx01 new_iptnone select_xx2" style="width: 48%; float: left;color:#666666;">
                                    <option value="">請選擇位置</option>
                                    <option value="四肢" {{$user->isTattooPart('四肢')?'selected':''}}>四肢</option>
                                    <option value="身體" {{$user->isTattooPart('身體')?'selected':''}}>身體</option>
                                </select>
                                <select name="tattoo_range" id="tattoo_range" class="select_xx01 new_iptnone select_xx2" style="width: 48%; float: right;color:#666666;">
                                    <option value="">請選擇面積</option>
                                    <option value="大" {{$user->isTattooRange('大')?'selected':''}}>大</option>
                                    <option value="小" {{$user->isTattooRange('小')?'selected':''}}>小</option>
                                </select>
                                
                            </font>
                        </dt>                  
                    @endif
                    @if($user->engroup==1)
                        <dt>
                            <span>關於我<i>(必填)</i></span>
                            <span><textarea data-parsley-errors-messages-disabled name="about" cols="" rows="3" class="select_xx05">{{$umeta->about}}</textarea></span>
                        </dt>
                        <dt class="matopj15">
                            <span>期待的約會模式<i>(必填)</i></span>
                            <span><textarea data-parsley-errors-messages-disabled name="style" cols="" rows="3" class="select_xx05">{{$umeta->style}}</textarea></span>
                        </dt>
                    @endif

                    @if($user->engroup==2)
                        <dt>
                            <span>感情狀況</span>
                            <div id="itemssxN_RS">
                                <nav class="custom_nav_n">
                                    @foreach($relationship_status as $option)
                                        <div class="custom_s a1 option_relationship_status @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                </nav>
                            </div>
                            <input id="relationship_status" type="hidden" name="relationship_status" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN_RS .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                });
                            </script>
                        </dt>
                        <dt>
                            <span>人格特質</span>
                            <div id="itemssxN_PT">
                                <nav class="custom_nav_n">
                                    @foreach($personality_traits as $option)
                                        <div class="custom_s a1 option_personality_traits @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                    @foreach($personality_traits_other as $option)
                                        <div class="custom_s a1 personality_traits_user_defined_tag cractive" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                    <div class="user_defined_area"></div>
                                </nav>
                                <div style="margin-top:-10px;margin-bottom: 12px;">
                                    <div class="ka_zli cutop"><i></i>或自行輸入</div>
                                    <span class="huinput" style="width: 180px;margin-left:16px;">
                                        <input id="personality_traits_tag_input" class="hf_i xin_input_qq" placeholder="請輸入">
                                    </span>
                                    <div class="re_area">
                                        <a class="hf_but tag_submit" >送出</a>
                                    </div>
                                </div>
                            </div>
                            <input id="personality_traits" type="hidden" name="personality_traits" value="">
                            <input id="personality_traits_user_defined_tag" type="hidden" name="personality_traits_user_defined_tag" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN_PT .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                    $(".personality_traits_user_defined_tag").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                    $("#itemssxN_PT .tag_submit").on("click", function() {
                                        if($('#personality_traits_tag_input').val()==''){
                                            c5('請輸入選項');
                                            return false;
                                        }
                                        $('#itemssxN_PT .user_defined_area').append('<div class="custom_s a1 personality_traits_user_defined_tag cractive">'+$('#personality_traits_tag_input').val()+'</div>');
                                        $('#personality_traits_tag_input').val('');
                                    });
                                });
                                $(document).on("click", ".personality_traits_user_defined_tag", function(e) {
                                    e.preventDefault();
                                    $(this).toggleClass('cractive');
                                });
                            </script>
                        </dt>
                        <dt>
                            <span>生活型態</span>
                            <div id="itemssxN_LS">
                                <nav class="custom_nav_n">
                                    @foreach($life_style as $option)
                                        <div class="custom_s a1 option_life_style @if($option->xref_id ?? false) cractive @endif" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                    @foreach($life_style_other as $option)
                                        <div class="custom_s a1 life_style_user_defined_tag cractive" value={{$option->id}}>{{$option->option_name}}</div>
                                    @endforeach
                                    <div class="user_defined_area"></div>
                                </nav>
                                <div style="margin-top:-10px;margin-bottom: 12px;">
                                    <div class="ka_zli cutop"><i></i>或自行輸入</div>
                                    <span class="huinput" style="width: 180px;margin-left:16px;">
                                        <input id="life_style_tag_input" class="hf_i xin_input_qq" placeholder="請輸入">
                                    </span>
                                    <div class="re_area">
                                        <a class="hf_but tag_submit" >送出</a>
                                    </div>
                                </div>
                            </div>
                            <input id="life_style" type="hidden" name="life_style" value="">
                            <input id="life_style_user_defined_tag" type="hidden" name="life_style_user_defined_tag" value="">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#itemssxN_LS .a1").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                    $(".life_style_user_defined_tag").on("click", function() {
                                        $(this).toggleClass('cractive');
                                    });
                                    $("#itemssxN_LS .tag_submit").on("click", function() {
                                        if($('#life_style_tag_input').val()==''){
                                            c5('請輸入選項');
                                            return false;
                                        }
                                        $('#itemssxN_LS .user_defined_area').append('<div class="custom_s a1 life_style_user_defined_tag cractive">'+$('#life_style_tag_input').val()+'</div>');
                                        $('#life_style_tag_input').val('');
                                    });
                                });
                                $(document).on("click", ".life_style_user_defined_tag", function(e) {
                                    e.preventDefault();
                                    $(this).toggleClass('cractive');
                                });
                            </script>
                        </dt>
                        {{--
                        <dt class="matopj15">
                            <span>現況<i>(必填)</i></span>
                            <span>
                                <select data-parsley-errors-messages-disabled name="situation"  class="select_xx01">
                                <option value="">請選擇</option>
                                <option value="學生"
                                        @if($umeta->situation == '學生') selected @endif>
                                    學生
                                </option>
                                <option value="待業"
                                        @if($umeta->situation == '待業') selected @endif>
                                    待業
                                </option>
                                <option value="休學"
                                        @if($umeta->situation == '休學') selected @endif>
                                    休學
                                </option>
                                <option value="打工"
                                        @if($umeta->situation == '打工') selected @endif>
                                    打工
                                </option>
                                <option value="上班族"
                                        @if($umeta->situation == '上班族') selected @endif>
                                    上班族
                                </option>
                                <option value="在家工作"
                                  @if($umeta->situation == '在家工作') selected @endif>
                                    在家工作
                                </option>
                                <option value="自行開業"
                                        @if($umeta->situation == '自行開業') selected @endif>
                                    自行開業
                                </option>
                                </select>
                            </span>
                        </dt>
                        --}}
                        <dt>
                            <span>工作/學業模式</span>
                            <span>
                                <select id="new_occupation" name="new_occupation"  class="select_xx01">
                                    <option value="">請選擇</option>
                                    @foreach(\App\Models\OptionOccupation::where('is_custom',false)->get() as $option)
                                        <option value={{$option->id}}  @if(($user_option->occupation->option_id ?? 0) == $option->id) selected @endif>
                                            {{$option->option_name}}
                                        </option>
                                    @endforeach
                                    <option value='other' @if(($user_option->occupation->occupation->is_custom) ?? 0) selected @endif>
                                        其他(自填)
                                    </option>
                                </select>
                                <input id="new_occupation_other" name="new_occupation_other" value={{$user_option->occupation->occupation->option_name ?? ''}} class="select_xx01" @if(!($user_option->occupation->occupation->is_custom ?? 0)) style="display:none" @endif>
                            </span>
                            <div class="right" style="margin: 10px 0 -5px 10px;">
                                <input type="hidden" name="isHideOccupation" value="0">
                                <input type="checkbox" name="isHideOccupation"  @if($umeta->isHideOccupation == true) checked @endif value="1"> 隱藏工作/學業模式
                            </div>
                        </dt>
                        {{--
                        <dt>
                            <span>職業</span>
                            <span>
                                <select name="occupation"  class="select_xx01">
                                    <option value=null>請選擇</option>
                                    <option value="學生"
                                            @if($umeta->occupation == '學生') selected @endif>
                                        學生
                                    </option>
                                    <option value="無業"
                                            @if($umeta->occupation == '無業') selected @endif>
                                        無業
                                    </option>
                                    <option value="人資"
                                            @if($umeta->occupation == '人資') selected @endif>
                                        人資
                                    </option>
                                    <option value="業務銷售"
                                            @if($umeta->occupation == '業務銷售') selected @endif>
                                        業務銷售
                                    </option>
                                    <option value="行銷企劃"
                                            @if($umeta->occupation == '行銷企劃') selected @endif>
                                        行銷企劃
                                    </option>
                                    <option value="行政助理"
                                            @if($umeta->occupation == '行政助理') selected @endif>
                                        行政助理
                                    </option>
                                    <option value="專案管理"
                                            @if($umeta->occupation == '專案管理') selected @endif>
                                        專案管理
                                    </option>
                                    <option value="餐飲類服務業"
                                            @if($umeta->occupation == '餐飲類服務業') selected @endif>
                                        餐飲類服務業
                                    </option>
                                    <option value="旅遊類服務業"
                                            @if($umeta->occupation == '旅遊類服務業') selected @endif>
                                        旅遊類服務業
                                    </option>
                                    <option value="美容美髮美甲芳療"
                                            @if($umeta->occupation == '美容美髮美甲芳療') selected @endif>
                                        美容美髮美甲芳療
                                    </option>
                                    <option value="操作員"
                                            @if($umeta->occupation == '操作員') selected @endif>
                                        操作員
                                    </option>
                                    <option value="文字工作者"
                                            @if($umeta->occupation == '文字工作者') selected @endif>
                                        文字工作者
                                    </option>
                                    <option value="學術研究"
                                            @if($umeta->occupation == '學術研究') selected @endif>
                                        學術研究
                                    </option>
                                    <option value="教育輔導"
                                            @if($umeta->occupation == '教育輔導') selected @endif>
                                        教育輔導
                                    </option>
                                    <option value="金融營業交易"
                                            @if($umeta->occupation == '金融營業交易') selected @endif>
                                        金融營業交易
                                    </option>
                                    <option value="財務會計"
                                            @if($umeta->occupation == '財務會計') selected @endif>
                                        財務會計
                                    </option>
                                    <option value="總機秘書櫃檯"
                                            @if($umeta->occupation == '總機秘書櫃檯') selected @endif>
                                        總機秘書櫃檯
                                    </option>
                                    <option value="法務記帳代書"
                                            @if($umeta->occupation == '法務記帳代書') selected @endif>
                                        法務記帳代書
                                    </option>
                                    <option value="資訊軟體"
                                            @if($umeta->occupation == '資訊軟體') selected @endif>
                                        資訊軟體
                                    </option>
                                    <option value="客服"
                                            @if($umeta->occupation == '客服') selected @endif>
                                        客服
                                    </option>
                                    <option value="貿易船務"
                                            @if($umeta->occupation == '貿易船務') selected @endif>
                                        貿易船務
                                    </option>
                                    <option value="交通運輸物流"
                                            @if($umeta->occupation == '交通運輸物流') selected @endif>
                                        交通運輸物流
                                    </option>
                                    <option value="倉管採購"
                                            @if($umeta->occupation == '倉管採購') selected @endif>
                                        倉管採購
                                    </option>
                                    <option value="設計美術"
                                            @if($umeta->occupation == '設計美術') selected @endif>
                                        設計美術
                                    </option>
                                    <option value="模特演員"
                                            @if($umeta->occupation == '模特演員') selected @endif>
                                        模特演員
                                    </option>
                                    <option value="傳播藝術"
                                            @if($umeta->occupation == '傳播藝術') selected @endif>
                                        傳播藝術
                                    </option>
                                    <option value="soho"
                                            @if($umeta->occupation == 'soho') selected @endif>
                                        soho
                                    </option>
                                    <option value="自行創業"
                                            @if($umeta->occupation == '自行創業') selected @endif>
                                        自行創業
                                    </option>
                                </select>
                            </span>
                            <div class="right" style="margin: 10px 0 -5px 10px;">
                                <input type="hidden" name="isHideOccupation" value="0">
                                <input type="checkbox" name="isHideOccupation"  @if($umeta->isHideOccupation == true) checked @endif value="1"> 隱藏職業
                            </div>
                        </dt>
                        --}}
                    @else
                        <dt class="matopj15">
                            <span>產業</span>
                            <span>
                                <input type="hidden" name="day" value="01">
                                <div class="se_zlman left">
                                <select  class="select_xx2 left" name="domainType" id="domainType" onchange="setDomain(0);">
                                    <option value=null>請選擇</option>
                                    <option value="資訊科技"
                                            @if($umeta->domainType == '資訊科技') selected @endif>
                                        資訊科技
                                    </option>
                                    <option value="傳產製造"
                                            @if($umeta->domainType == '傳產製造') selected @endif>
                                        傳產製造
                                    </option>
                                    <option value="工商服務"
                                            @if($umeta->domainType == '工商服務') selected @endif>
                                        工商服務
                                    </option>
                                    <option value="民生服務"
                                            @if($umeta->domainType == '民生服務') selected @endif>
                                        民生服務
                                    </option>
                                    <option value="文教傳播"
                                            @if($umeta->domainType == '文教傳播') selected @endif>
                                        文教傳播
                                    </option>
                                </select>
                                </div>
                                <div class="se_zlman right">
                                <select class="select_xx2 right" name="domain" id="domain">
                                </select>
                                </div>
                            </span>
                        </dt>
                        <dt class="matopj15">
                            <span>職業<i></i></span>
                            <span>
                                <input name="occupation" type="text" class="select_xx01"  placeholder="請填入職業" @if(!empty($umeta->occupation) && $umeta->occupation != 'null')value="{{$umeta->occupation}}" @endif>
                                <input name="occupation" id="occupation" type="hidden" value=null>
                            </span>
                        </dt>
                    @endif
                    <dt>
                        <span>教育<i>(必填)</i></span>
                        <span>
                            <select data-parsley-errors-messages-disabled name="education"  class="select_xx01">
                            <option value="">請選擇</option>
                            <option value="國中"
                                    @if($umeta->education == '國中') selected @endif>
                                國中
                            </option>
                            <option value="高中"
                                    @if($umeta->education == '高中') selected @endif>
                                高中
                            </option>
                            <option value="大學"
                                    @if($umeta->education == '大學') selected @endif>
                                大學
                            </option>
                            <option value="研究所"
                                    @if($umeta->education == '研究所') selected @endif>
                                研究所
                            </option>
                            </select>
                        </span>
                    </dt>
                    <dt>
                        <span>婚姻<i>(必填)</i></span>
                        <span>
                            <select data-parsley-errors-messages-disabled name="marriage"  class="select_xx01">
                            <option value="">請選擇</option>
                            <option value="已婚"
                                    @if($umeta->marriage == '已婚') selected @endif>已婚
                            </option>
                            <option value="分居"
                                    @if($umeta->marriage == '分居') selected @endif>分居
                            </option>
                            <option value="單身"
                                    @if($umeta->marriage == '單身') selected @endif>單身
                            </option>
                            @if($user->engroup==1)
                            <option value="有女友"
                                        @if($umeta->marriage == '有女友') selected @endif>
                                    有女友
                            </option>
                            @else
                            <option value="有男友"
                                        @if($umeta->marriage == '有男友') selected @endif>
                                    有男友
                            </option>
                            @endif
                            </select>
                        </span>
                    </dt>
                    @if($user->engroup==2)
                        {{--
                        <dt>
                            <span>希望進一步發展嗎?</span>
                            <span>
                                <select data-parsley-errors-messages-disabled name="is_pure_dating"  class="select_xx01">
                                    <option value="-1"
                                            @if($umeta->is_pure_dating == "-1") selected @endif>請選擇
                                    </option>
                                    <option value="1"
                                            @if($umeta->is_pure_dating == "1") selected @endif>是
                                    </option>
                                    <option value="0"
                                            @if($umeta->is_pure_dating == "0") selected @endif>否
                                    </option>
                                </select>
                            </span>
                        </dt>
                        --}}
                    @endif
                  <dt>
                      <span>喝酒<i>(必填)</i></span>
                      <span>
                        <select data-parsley-errors-messages-disabled name="drinking"  class="select_xx01">
                          <option value="">請選擇</option>
                          <option value="不喝"
                                  @if($umeta->drinking == '不喝') selected @endif>不喝
                          </option>
                          <option value="偶爾喝"
                                  @if($umeta->drinking == '偶爾喝') selected @endif>
                              偶爾喝
                          </option>
                          <option value="常喝"
                                  @if($umeta->drinking == '常喝') selected @endif>常喝
                          </option>
                        </select>
                      </span>
                  </dt>
                  <dt>
                      <span>抽煙<i>(必填)</i></span>
                      <span>
                        <select data-parsley-errors-messages-disabled name="smoking"  class="select_xx01">
                          <option value="">請選擇</option>
                          <option value="不抽"
                                  @if($umeta->smoking == '不抽') selected @endif>不抽
                          </option>
                          <option value="偶爾抽"
                                  @if($umeta->smoking == '偶爾抽') selected @endif>
                              偶爾抽
                          </option>
                          <option value="常抽"
                                  @if($umeta->smoking == '常抽') selected @endif>常抽
                          </option>
                        </select>
                      </span>
                  </dt>
                    @if($user->engroup==1)
                        {{--
                        <dt>
                            <span>年收<i>(必填)</i></span>
                            <span>
                                <select data-parsley-errors-messages-disabled name="income"  class="select_xx01">
                                <option value="">請選擇</option>
                                <option value="50萬以下"
                                        @if($umeta->income == '50萬以下') selected @endif>
                                    50萬以下
                                </option>
                                <option value="50~100萬"
                                        @if($umeta->income == '50~100萬') selected @endif>
                                    50~100萬
                                </option>
                                <option value="100-200萬"
                                        @if($umeta->income == '100-200萬') selected @endif>
                                    100-200萬
                                </option>
                                <option value="200-300萬"
                                        @if($umeta->income == '200-300萬') selected @endif>
                                    200-300萬
                                </option>
                                <option value="300萬以上"
                                        @if($umeta->income == '300萬以上') selected @endif>
                                    300萬以上
                                </option>
                                </select>
                            </span>
                        </dt>
                        --}}
                        {{--
                        <dt>
                            <span>資產<i>(必填)</i></span>
                            <span><input data-parsley-errors-messages-disabled name="assets" id="assets" value="{{$umeta->assets}}" type="number" class="select_xx01"  placeholder="請輸入數字範圍0～10000000000"></span>
                        </dt>
                        --}}
                        <dt>
                            <span>每月預算</span>
                            <span style="text-align:center;">
                                <input id="budget_per_month_min" name="budget_per_month_min" type="number" style="width:48%;" class="select_xx01 se_zlman left"  placeholder="請輸入最低金額" @if(!empty($umeta->budget_per_month_min) && $umeta->budget_per_month_min != -1) value="{{$umeta->budget_per_month_min}}" @endif data-parsley-errors-messages-disabled onchange="budget_per_month_change()">
                                ~
                                <input id="budget_per_month_max" name="budget_per_month_max" type="number" style="width:48%;" class="select_xx01 se_zlman right"  placeholder="請輸入最高金額" @if(!empty($umeta->budget_per_month_max) && $umeta->budget_per_month_max != -1) value="{{$umeta->budget_per_month_max}}" @endif data-parsley-errors-messages-disabled onchange="budget_per_month_change()">
                            </span>
                        </dt>
                        <dt>
                            <span>車馬費預算</span>
                            <span style="text-align:center;">
                                <input id="transport_fare_min" name="transport_fare_min" type="number" style="width:48%;" class="select_xx01 se_zlman left"  placeholder="請輸入最低金額" @if(!empty($umeta->transport_fare_min) && $umeta->transport_fare_min != -1) value="{{$umeta->transport_fare_min}}" @endif data-parsley-errors-messages-disabled onchange="transport_fare_change()">
                                ~
                                <input id="transport_fare_max" name="transport_fare_max" type="number" style="width:48%;" class="select_xx01 se_zlman right"  placeholder="請輸入最高金額" @if(!empty($umeta->transport_fare_max) && $umeta->transport_fare_max != -1) value="{{$umeta->transport_fare_max}}" @endif data-parsley-errors-messages-disabled onchange="transport_fare_change()">
                            </span>
                        </dt>
                    @endif
                </div>
                <a class="dlbut g_inputt20 abtn" onclick="$('body').attr('onbeforeunload','');$('form[name=user_data]').submit();">
                    @if($rap_service->isInRealAuthProcess())
                    完成
                    @else
                    確定更新
                    @endif
                </a>
                <a href="{{$rap_service->isInRealAuthProcess()?route('real_auth'):null}}" class="zcbut matop20">取消</a>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="bl bl_tab" id="isWarned" style="display: none;">
      <div class="bltitle">提示</div>
      <div class="blnr bltext">
          @if($isAdminWarned)
              @php
                  $warned_users = \App\Models\SimpleTables\warned_users::where('member_id', $user->id)->where(
                      function ($query) {
                          $query->whereNull('expire_date')->orWhere('expire_date', '>=', \Carbon\Carbon::now());
                      })
                  ->first();
            $diff_in_days  = '';
            if(isset($warned_users->expire_date)){
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $warned_users->expire_date);
                $now = \Carbon\Carbon::now();

                $diff_in_days = ' ' . $to->diffInDays($now) . ' 天';
            }

              $reason = $warned_users->reason == '' ? '系統原因' : $warned_users->reason;
              @endphp
              您因為 {{$reason}} 被站長警示{{$diff_in_days}}，如有問題請點右下聯絡我們加站長 line 反應。
          @else
{{--          由於{!! $isWarnedReason !!}原因，您目前是警示會員。--}}
          {{$user->name}} 您好，為防止八大入侵，系統有設置警示會員制度。<br>
          您目前被系統暫時列為警示會員，通過手機簡訊認證即可解除此狀態。<br>
          此機制主要針對色情行業，但偶爾會誤判，還請見諒。<br>
          手機號碼不會公布敬請放心，有問題可加站長line：@giv4956r 反應。<br>
          前往<a href='/member_auth'>會員驗證</a>
          @endif
      </div>

      <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
  </div>

  <div class="bl bl_tab" id="isExchangePeriod" style="display: none;">
      <div class="bltitle">提示</div>
      <div class="blnr bltext">

              {{$user->name}} 您好，您尚未修改過基本資料-包養關係
              提醒您前往<a href='/dashboard/account_exchange_period'>變更包養關係</a>
      </div>

      <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
  </div>

  <div class="bl bl_tab" id="isGetBarCodeNotVIP" style="display: none;">
      <div class="bltitle">提示</div>
      <div class="blnr bltext">超商條碼或超商代碼付款，會在七天內待綠界回傳資料就<span id="vip_pass">直接給 VIP</span>
      </div>

      <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
  </div>

  <div class="bl bl_tab_cc prz" id="prz" style="display: none;">
      <div class=" bl_tab_bb">
          <div class="bltitle"><span style="text-align: center; float: none;">PR值說明</span></div>
          <div class="new_poptk_aa new_poptk_nn">
              <div class="fpt_z_cc">
                  <div class="pj_add">
                      <p class="yidy_font">此數字為daddy的大方指數，您目前指數為 {{$pr}}</p>
                      <img src="/new/images/ziliao.png" class="yiimg">
                      <div class="ytext_img">
                          <div class="ye_title"><img src="/new/images/zhe_dd.png">提升方式</div>
                          <h2 class="ye_h2">[連續型 vip 會員]</h2>
                          <h3 class="ye_h3">◎保持您的 vip 不中斷</h3>
                          <h3 class="ye_h3">◎多使用車馬費邀請</h3>
                          <h2 class="ye_h2">[單月繳會員]</h2>
                          <h3 class="ye_h3">◎改為連續繳款/季繳</h3>
                          <h3 class="ye_h3">◎保持您的 vip 不中斷</h3>
                          <h3 class="ye_h3">◎多使用車馬費邀請</h3>
                          <h2 class="ye_h2">[非 vip]</h2>
                          <h3 class="ye_h3">◎升級 vip </h3>

                      </div>
                  </div>
                  <div class="n_bbutton">
                      <span><a class="n_left" onclick="$('.blbg').click();">確定</a></span>
                      <span><a class="n_right" onclick="$('.blbg').click();">取消</a></span>
                  </div>

              </div>

          </div>
          <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
      </div>
  </div>

  {{--<div class="bl bl_tab" id="messageBoard_enter_limit">
      <div class="bltitle">提示</div>
      <div class="n_blnr01 matop10">
          @if($user->engroup==2)
              <div class="blnr bltext">目前僅開放給有通過手機驗證<a href="/member_auth" style="color: red;">(點此進行驗證)</a>的：VIP會員<a href="/dashboard_img" style="color: red;">(點此上傳照片取得VIP)</a>使用。</div>
          @else
              <div class="blnr bltext">目前僅開放給VIP會員使用，<a href="/dashboard/new_vip" style="color: red;">點此升級VIP</a>。</div>
          @endif
          <a class="n_bllbut matop30" onclick="gmBtnNoReload()">確定</a>
      </div>
  </div>--}}
  <div class="bl bl_tab" id="messageBoard_enter_limit" style="display: none;">
      <div class="bltitle"><span>提示</span></div>
      <div class="n_blnr01 ">
          <div class="new_tkfont">您目前未達標準<br>不可使用留言板功能</div>
          <div class="new_tablema">
              <table>
                  @if($user->engroup==2)
                      <tr>
                          <td class="new_baa new_baa1">須通過手機驗證</td>
                          <td class="new_baa1">@if($user->isPhoneAuth())<img src="/new/images/ticon_01.png">@else<img src="/new/images/ticon_02.png">@endif</td>
                      </tr>
                  @endif
                  <tr>
                      <td class="new_baa">需為VIP會員</td>
                      <td class="">@if(!$user->isVipOrIsVvip())<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
                  </tr>
              </table>
          </div>
      </div>
      <a id="" onClick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
  </div>
  <div class="bl bl_tab" id="messageBoard_msg">
      <div class="bltitle">提示</div>
      <div class="n_blnr01 matop10">
          <div class="blnr bltext">{{  Session::get('messageBoard_msg')  }}</div>
          <a class="n_bllbut matop30" onclick="gmBtnNoReload()">確定</a>
      </div>
      <a onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
  </div>

    <div class="real_auth_bg" onclick="$(this).hide();gmBtnNoReload()" style="display:none;"></div>
    <div class="bl bl_tab" id="real_auth_hint_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext">
                請確認以下重要欄位，驗證後若要修改則須向站方申請
                <ol>
                    <li>包養關係、身高、體重</li>
                </ol>
            </div>
            <a class="n_bllbut matop30" onclick="real_auth_tab_close(this)">確定</a> 
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>
    
    <div class="bl bl_tab" id="new_height_modify_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext">
                
                <div id="new_height_confirm_block">
                    要修改通過本人認證的身高資料，需經過審核。
                    <br><br>
                    您確定要申請身高資料異動?
                </div>
                <div id="new_height_elt_container" style="display:none;">
                    <div style="margin-bottom:1%;">請輸入身高（cm）</div>
                    <span><input name="new_height" id="new_height_elt" type="number" class="select_xx01" max="210" min="140"  placeholder="請輸入數字範圍140～210" value="" title="請輸入140~210範圍" required></span>
                    <div id="new_height_input_error_msg"></div>
                </div>
            </div>
            <div class="n_bbutton">
                <span><a class="real_auth_n_left" href="javascript:void(0)" onclick="real_auth_input_new_height_handle();" >確認</a></span>
                <span><a onclick="real_auth_tab_close(this)" class="n_right" href="javascript:">返回</a></span>
            </div>            
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>
    
    <div class="bl bl_tab" id="new_weight_modify_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext">
                
                <div id="new_weight_confirm_block">
                    要修改通過本人認證的體重資料，需經過審核。
                    <br><br>
                    您確定要申請體重資料異動嗎?
                </div>
                <div id="new_weight_elt_container" style="display:none;">
                    <div style="margin-bottom:1%;">請選擇體重選項（kg）</div>
                    <select name="new_weight" id="new_weight_elt"  class="select_xx01">
                      <option value="0">不填寫</option>
                      @for ($i = 1; $i < 21; $i++)
                      <option value="{{$i*5}}">{{$i*5-4}} ~ {{$i*5}}
                      </option>
                      @endfor
                    </select>                
                
                </div>
                <div id="new_weight_input_error_msg"></div>
            </div>
            <div class="n_bbutton">
                <span><a class="real_auth_n_left" href="javascript:void(0)" onclick="real_auth_input_new_weight_handle();" >確認</a></span>
                <span><a onclick="real_auth_tab_close(this)" class="n_right" href="javascript:">返回</a></span>
            </div>            
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>    

    <div class="bl bl_tab" id="real_auth_result_msg_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext">            
            </div>
            <a class="n_bllbut matop30" onclick="real_auth_tab_close(this)">確定</a> 
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>
        
    <div class="bl bl_tab" id="real_auth_backward_tab" style="display: none;">
        <div class="bltitle">提示</div>
        <div class="n_blnr01 matop10">
            <div class="blnr bltext">
                您尚未設定頭像，{{$add_avatar}}，並確認照片符合您的現況。         
            </div>
            <a class="n_bllbut matop30" onclick="location.href='{{route('dashboard_img',['real_auth'=>request()->real_auth])}}';">確定</a> 
        </div>
        <a onclick="real_auth_tab_close(this);" class="bl_gb"><img src="{{asset('/new/images/gb_icon.png')}}"></a>
    </div>


  <script>
      function pr() {
          $(".blbg").show();
          $(".prz").show();
      }

      $(document).ready(function () {
          @if(Session::has('message'))
          @if (is_array(Session::get('message')))
          c5('{{ implode("", Session::get('message')) }}');
          @php
              \Sentry\captureMessage(implode("", \Session::get('message')));
          @endphp
          @else
          c5('{{Session::get('message')}}');
          @endif
              <?php session()->forget('message'); ?>
          @endif
      });

  </script>
  <script src="/new/js/birthday.js" type="text/javascript"></script>
  <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
  <script type="text/javascript">
      $.ms_DatePicker({
          YearSelector: ".sel_year",
          MonthSelector: ".sel_month",
    });
    $('.twzipcode').twzipcode({
      'detect': true, 'css':['select_xx2', 'select_xx2', 'd-none'], onCountySelect: function() {
       //   $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
      }
    });
    var domainJson = ({
        '請選擇': ['請選擇'],
        '資訊科技': ['軟體網路', '電信通訊', '光電光學', '半導體業', '電腦週邊', '電子相關'],
        '傳產製造': ['食品飲料', '紡織相關', '鞋類紡織', '家具家飾', '紙製製造', '印刷相關', '化學製造', '石油製造', '橡膠塑膠', '非金屬製造', '金屬製造', '機械設備', '電力機械', '運輸工具', '儀器醫材', '育樂用品', '其他製造', '物流倉儲', '營建土木', '農林漁牧', '礦業土石'],
        '工商服務': ['法律服務', '會計服務', '顧問研發', '人力仲介', '租賃業', '汽車維修', '徵信保全'],
        '民生服務': ['批發零售', '金融機構', '投資理財', '保險業', '電影業', '旅遊休閒', '美容美髮', '醫療服務', '環境衛生', '住宿服務', '餐飲服務'],
        '文教傳播': ['教育服務', '印刷出版', '藝文相關', '廣播電視', '廣告行銷', '政治社福']
    });
    function setDomain(initial) {
        var domain = eval(domainJson);
        $("#domain").html('<option value=null>請選擇</option>');
        //删除所有的option
        $('#domain option:not(:first)').remove();

        var type = $("#domainType").val();
        //console.log('type is ' + type);
        if (!initial) {
            $("#domain option").remove();
            $("#domain").append('<option value=null>請選擇</option>');
        }
        for (var i in domain[type]) {
            //console.log(domain[type][i]);
            if (domain[type][i] != $("#domain option:selected").val()) {
                $("#domain").append('<option value="' + domain[type][i] + '">' + domain[type][i] + '</option>');
            }
        }
        if(type=='')$('#domain option:not(:first)').remove();
    }
    $(document).ready(function() {
        let blockarea_selected_arr = @json($blockarea_selected);
        let blockcity_selected_arr = @json($blockcity_selected);

        //var blockarea_selected = '{{ isset($umeta->blockarea[0]) ? ($umeta->blockarea[0] == "" ? "全區" : str_replace($umeta->blockcity[0],'',$umeta->blockarea[0])) : '全區' }}';
        //var blockarea1_selected = '{{ isset($umeta->blockarea[1]) ? str_replace($umeta->blockcity[1],'',$umeta->blockarea[1]) :'全區'  }}';
        //var blockarea2_selected = '{{ isset($umeta->blockarea[2]) ? str_replace($umeta->blockcity[2],'',$umeta->blockarea[2]) : '全區'  }}';

        if(blockcity_selected_arr[0] == '海外')
        {
            if(blockarea_selected_arr[0] == '全部')
            {
                $("select[name='blockarea']").prepend('<option selected value="">全部</option>');
            }
            else
            {
                $("select[name='blockarea'] option[value=" + blockarea_selected_arr[0] + "]").attr('selected', true);
                $("select[name='blockarea']").prepend('<option value="">全部</option>');
            }
            
        }
        else if(blockcity_selected_arr[0] == '')
        {

        }
        else
        {
            if (blockarea_selected_arr[0] == '全區') 
            {
                $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
            } 
            else 
            {
                $("select[name='blockarea'] option[value=" + blockarea_selected_arr[0] + "]").attr('selected', true);
                $("select[name='blockarea']").prepend('<option value="">全區</option>');
            }
        }

        /*if ($("select[name='blockarea'] option:eq(0)").text() !== '全區') 
        {
            //$("select[name='blockarea']").prepend('<option value="">全區</option>');
            if (blockarea_selected_arr[0] == '全區') 
            {
                if ($("select[name='blockcity']").val() !== '') 
                {
                    $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
                }
            } 
            else if(blockarea_selected_arr[0] == '全部')
            {
                $("select[name='blockarea']").prepend('<option selected value="">全部</option>');
            }
            else 
            {
                $("select[name='blockarea'] option[value=" + blockarea_selected_arr[0] + "]").attr('selected', true);
                $("select[name='blockarea']").prepend('<option value="">全區</option>');
            }
        }*/

        for(let i = 1;i < {{$blockcity_limit_count}}; i++)
        {
            let blockarea_name = 'blockarea' + i;

            if(blockcity_selected_arr[i] == '海外')
            {
                if(blockarea_selected_arr[i] == '全部')
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全部</option>');
                }
                else
                {
                    $("select[name=" +blockarea_name+ "] option[value=" + blockarea_selected_arr[i] + "]").attr('selected', true);
                    $("select[name=" +blockarea_name+ "]").prepend('<option value="">全部</option>');
                }
                
            }
            else if(blockcity_selected_arr[i] == '')
            {

            }
            else
            {
                if (blockarea_selected_arr[i] == '全區') 
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全區</option>');
                } 
                else 
                {
                    $("select[name=" +blockarea_name+ "] option[value=" + blockarea_selected_arr[i] + "]").attr('selected', true);
                    $("select[name=" +blockarea_name+ "]").prepend('<option value="">全區</option>');
                }
            }
            


            /*if ($("select[name=" +blockarea_name+ "] option:eq(0)").text() !== '全區') 
            {
                //$("select[name='blockarea1']").prepend('<option value="">全區</option>');
                if (blockarea_selected_arr[i] == '全區') 
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全區</option>');
                } 
                else if(blockarea_selected_arr[i] == '全部')
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全部</option>');
                }
                else 
                {
                    $("select[name=" +blockarea_name+ "] option[value=" + blockarea_selected_arr[i] + "]").attr('selected', true);
                    $("select[name=" +blockarea_name+ "]").prepend('<option value="">全區</option>');

                }
            }*/
        }



        $("select[name='blockcity']").on('change', function () {
            if ($("select[name='blockcity'] option:selected").text() == '縣市') 
            {
                $("select[name='blockarea']").prepend('<option selected value="">鄉鎮市區</option>');
            } 
            else if($("select[name='blockcity'] option:selected").text() == '海外')
            {
                $("select[name='blockarea']").prepend('<option selected value="">全部</option>');
            }
            else 
            {
                $("select[name='blockarea']").prepend('<option selected value="">全區</option>');  
            }
        });

        for(let i = 1;i < {{$blockcity_limit_count}}; i++)
        {
            let blockcity_name = 'blockcity' + i;
            let blockarea_name = 'blockarea' + i;
            $("select[name=" +blockcity_name+ "]").on('change', function () 
            {
                if ($("select[name=" +blockcity_name+ "] option:selected").text() == '縣市') 
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">鄉鎮市區</option>');
                } 
                else if($("select[name=" +blockcity_name+ "] option:selected").text() == '海外')
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全部</option>');
                }
                else 
                {
                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全區</option>');
                }
            });
        }
        

        function getAge(birth) {
            birth = Date.parse(birth.replace('/-/g', "/"));
            var year = 1000 * 60 * 60 * 24 * 365;
            var now = new Date();
            var birthday = new Date(birth);
            var age = parseInt((now - birthday) / year);
            return age;
        }

        @php
            $ckBarCodeLog = \App\Models\PaymentGetQrcodeLog::where('user_id',$user->id)->where('ExpireDate','>=',now())->where('isRead',0)->count();
        @endphp

        @if(!$user->isAdmin())
            let banned_vip_pass=false, warned_vip_pass=false;
            if (window.location.hash) {
                // alert(window.location.hash)
                if (window.location.hash.substring(1) == 'banned_vip_pass') {
                    banned_vip_pass = true;
                    $('#vip_pass').html('會直接升級VIP並解除封鎖');
                } else if (window.location.hash.substring(1) == 'warned_vip_pass') {
                    warned_vip_pass = true;
                    $('#vip_pass').html('會直接升級VIP並解除警示');
                }

                history.replaceState(null, null, ' ');
            }

            @if($ckBarCodeLog==0)
                if(banned_vip_pass){
                    c5('您已成功解除封鎖');
                }
                if(warned_vip_pass){
                    c5('您已成功解除警示');
                }
            @elseif($ckBarCodeLog>0 && !$user->isVipOrIsVvip())
                $('#isGetBarCodeNotVIP').show();
                $('#announce_bg').show();
                @php
                    \App\Models\PaymentGetQrcodeLog::where('user_id',$user->id)->where('ExpireDate','>=',now())->where('isRead',0)->update(['isRead' => 1]);
                @endphp
            @endif
            @if (!$umeta->isAllSet( $user->engroup ))
                c5('請寫上基本資料。');
            @elseif (empty($umeta->pic))
                @if($rap_service->isInRealAuthProcess())
                real_auth_backward_popup();
                @else
                c5("{{$add_avatar}}");
                @endif
            @elseif ($umeta->age()<18)
                c5('您好，您的年齡低於法定18歲，請至個人基本資料設定修改，否則您的資料將會被限制搜尋。');
            @endif
        @endif

        @php
            $exchange_period_read = DB::table('exchange_period_temp')->where('user_id',$user->id)->count();
        @endphp
        @if($user->engroup==2 && ($exchange_period_read == 0))
            $('#isExchangePeriod').show();
            $('#announce_bg').show();
        @endif



      //ajax_表單送出
      $('form[name=user_data]').submit(function(e){

        //計算註冊時間
        @if(!$umeta->isAllSet( $user->engroup ))
            regist_end_time = new Date();
            cost_time = Math.round((regist_end_time.getTime() - regist_start_time.getTime()) / 1000);
            $.ajax({
                type:'post',
                url:'{{route("regist_time")}}',
                data:{
                    _token: "{{ csrf_token() }}",
                    cost_time_of_first_dataprofile:cost_time
                }
            });
        @endif
        //計算註冊時間
        
        @if($user->engroup==2)
            //複選選項處理為陣列
            let option_array = [];

            option_array = [];
            $('.option_relationship_status.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            option_array = JSON.stringify(option_array);
            $('#relationship_status').val(option_array);
            



            let fill_about = false;
            option_array = [];
            $('.option_looking_for_relationships.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_about = true;
            }
            option_array = JSON.stringify(option_array);
            $('#looking_for_relationships').val(option_array);
            

            option_array = [];
            $('.option_expect.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_about = true;
            }
            option_array = JSON.stringify(option_array);
            $('#expect').val(option_array);

            if(!fill_about)
            {
                if($('#about_other').val() === "")
                {
                    $('#about_other').focus();
                    c5('請選擇關於我的標籤或輸入文字');
                    return false;
                }
            }

            if($('#about_other').val() !== "")
            {
                if($('#about_other').val().length < 4 || $('#about_other').val().length > 300)
                {
                    $('#about_other').focus();
                    c5('關於我：請輸入4～300個字');
                    return false;
                }
            }




            let fill_style = false;
            option_array = [];
            $('.option_favorite_food.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#favorite_food').val(option_array);
            

            option_array = [];
            $('.option_preferred_date_location.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#preferred_date_location').val(option_array);
            

            option_array = [];
            $('.option_expected_type.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#expected_type').val(option_array);
            

            option_array = [];
            $('.option_frequency_of_getting_along.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#frequency_of_getting_along').val(option_array);


            option_array = [];
            $('.option_personality_traits.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#personality_traits').val(option_array);
            //自行輸入tag
            option_array = [];
            $('.personality_traits_user_defined_tag.cractive').each(function(){
                option_array.push($(this).text());
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#personality_traits_user_defined_tag').val(option_array);

            option_array = [];
            $('.option_life_style.cractive').each(function(){
                option_array.push($(this).attr('value'));
            });
            if(option_array.length !== 0) {
                fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#life_style').val(option_array);
            //自行輸入tag
            option_array = [];
            $('.life_style_user_defined_tag.cractive').each(function(){
            option_array.push($(this).text());
            });
            if(option_array.length !== 0) {
            fill_style = true;
            }
            option_array = JSON.stringify(option_array);
            $('#life_style_user_defined_tag').val(option_array);

            if(!fill_style)
            {
                if($('#style_other').val() === "")
                {
                    $('#style_other').focus();
                    c5('請選擇期待的約會模式的標籤或輸入文字');
                    return false;
                }
            }

            if($('#style_other').val() !== "")
            {
                if($('#style_other').val().length < 4 || $('#style_other').val().length > 300)
                {
                    $('#style_other').focus();
                    c5('期待約會模式：請輸入4～300個字');
                    return false;
                }
            }
            
            //複選選項處理為陣列
        @endif

        e.preventDefault();
        if($(this).parsley().isValid()){
            let birth = $('select[name=year]').val()+'/'+$('select[name=month]').val()+'/'+$('input[name=day]').val();
            let age = getAge(birth);
            let title = $('input[name=title]');
            let about = $('textarea[name=about]');
            let style = $('textarea[name=style]');
            let budget = $('select[name=budget]');
            let assets = $('#assets');
            let height = $('#height');
            let income = $('select[name=income]');
            let marriage = $('select[name=marriage]');
            let education = $('select[name=education]');
            let drinking = $('select[name=drinking]');
            let smoking = $('select[name=smoking]');
            let county = $("#county");
            let situation = $('select[name=situation]');
            let tattoo_part = $('#tattoo_part');
            let tattoo_range = $('#tattoo_range');
            let budget_per_month_min = $('#budget_per_month_min');
            let budget_per_month_max = $('#budget_per_month_max');
            let transport_fare_min = $('#transport_fare_min');
            let transport_fare_max = $('#transport_fare_max');
            let new_occupation = $('#new_occupation');
            let new_occupation_other = $('#new_occupation_other');
            let personality_traits = $('#personality_traits');
            let personality_traits_other = $('#personality_traits_other');
            let life_style = $('#life_style');
            let life_style_other = $('#life_style_other');

            if(new_occupation.val() === "other")
            {
                if(new_occupation_other.val() === "")
                {
                    new_occupation_other.focus();
                    c5('請輸入自填內容');
                    return false;
                }
            }

            if(title.val() === "") {
                title.focus();
                c5('請輸入一句話形容自己');
                return false;
            }
            if($(county).find('.twzipcode').length == 0) {
                c5('請選擇地區');
                return false;
            } else {
                $(county).find('.twzipcode').each(function(index, element) {
                    if(index == 0 && $(element).find('select').val() === ""){
                    c5('請選擇地區');
                    return false;
                    }
                })
                if($('select[name=city]').val() === ""){
                    c5('請選擇地區');
                    return false;
                }
            }
            
            @if($user->engroup==2)
                if(budget.val() === "") {
                    budget.focus();
                    c5('請選擇預算');
                    return false;
                }
            @endif

            if($('select[name=year]').val() == "" || $('select[name=month]').val() == "" || age < 18){
                c5('您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。');
                // swal({
                //     title:'您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。',
                //     type:'warning'
                // });
                return false;
            }
            
            
            if(height.val()!=undefined) {
                if(height.val() == '' || height.val().charAt(0)==0 || height.val() < 140 || height.val() > 210) {
                    height.focus();
                    c5('請輸入身高140～210');
                    return false;
                }
            }
            @if($user->engroup==1)
                if(about.val().length < 4 || about.val().length > 300) {
                    about.focus();
                    c5('關於我：請輸入4～300個字');
                    return false;
                }
                if(style.val().length < 4 || style.val().length > 300) {
                    style.focus();
                    c5('期待約會模式：請輸入4～300個字');
                    return false;
                }
            @endif
            if('{{$user->engroup}}' == '2'){
                if(situation.val() === "") {
                situation.focus();
                c5('請選擇現況');
                return false;
                };
            }
            if(education.val() === "") {
                education.focus();
                c5('請選擇教育');
                return false;
            }
            if(marriage.val() === "") {
                marriage.focus();
                c5('請選擇婚姻');
                return false;
            }
            if(drinking.val() === "") {
                drinking.focus();
                c5('請選擇喝酒');
                return false;
            }
            if(smoking.val() === "") {
                smoking.focus();
                c5('請選擇抽煙');
                return false;
            }
            if(tattoo_part.val()=='' && tattoo_range.val()!='') {
                tattoo_part.focus();
                c5('請選擇刺青位置');
                return false;
            } 
            
            if(tattoo_range.val()=='' && tattoo_part.val()!='') {
                tattoo_range.focus();
                c5('請選擇刺青面積');
                return false;
            }  

            /*
            if('{{$user->engroup}}' == '1'){
                console.log(income.val())
                if(income.val() === "") {
                income.focus();
                c5('請選擇年收');
                return false;
                }
                if(assets.val() == '' || assets.val() < 0 || assets.val() > 10000000000) {
                assets.focus();
                c5('請輸入資產數字範圍0～10000000000');
                return false;
                }
            }
            */

            if(budget_per_month_min.val()!='' || budget_per_month_max.val()!='')
            {
                if(parseInt(budget_per_month_max.val()) > parseInt(budget_per_month_min.val()) * 2)
                {
                    budget_per_month_max.focus();
                    c5('您好，每月預算上下限最大差異不得超過 100%，您的下限為' + budget_per_month_min.val() + '，則必須降低上限最多至' + budget_per_month_min.val() * 2);
                    return false;
                }
                if(parseInt(budget_per_month_min.val()) > parseInt(budget_per_month_max.val()))
                {
                    budget_per_month_max.focus();
                    c5('每月預算下限不可大於上限');
                    return false;
                }
            }

            if(transport_fare_min.val()!='' || transport_fare_max.val()!='')
            {
                if(parseInt(transport_fare_max.val()) > parseInt(transport_fare_min.val()) * 2)
                {
                    transport_fare_max.focus();
                    c5('您好，車馬費預算上下限最大差異不得超過 100%，您的下限為' + transport_fare_min.val() + '，則必須降低上限最多至' + transport_fare_min.val() * 2);
                    return false;
                }
                if(parseInt(transport_fare_min.val()) > parseInt(transport_fare_max.val()))
                {
                    transport_fare_max.focus();
                    c5('車馬費預算下限不可大於上限');
                    return false;
                }
            }

            if(budget_per_month_min.val() == '' && budget_per_month_max.val() =='')
            {
                budget_per_month_min.val(-1);
                budget_per_month_max.val(-1);
            }
            if(transport_fare_min.val() == '' && transport_fare_max.val() =='')
            {
                transport_fare_min.val(-1);
                transport_fare_max.val(-1);
            }

            var form_dump = $(this);
            c4('確定要變更會員資料嗎?');
            // swal({
            //     title: '確定要變更會員資料嗎？',
            //     text: "",
            //     type: 'warning',
            //     showCancelButton: true,
            //     confirmButtonText: '確定'
            // }).then(function (isConfirm) {
            //   if (isConfirm.value) {
            //     $.ajax({
            //       url:'{{ route('dashboard2') }}',
            //       type: 'POST',
            //       dataType: 'JSON',
            //       data: form,
            //       beforeSend: function () {
            //         waitingDialog.show();
            //       },
            //       complete: function () {
            //         waitingDialog.hide();
            //       },
            //       success: function (result) {
            //         ResultData(result);
            //       }
            //     });
            //   }
            // });
        }
        return false;
      });
      
      let add_county = $("#add_county");
      $(add_county).click(function(){
          if($(county).find('.twzipcode').length < 3) {
              let county_div=`
                <div class="twzipcode">
                  <div class="twzip" data-role="county" data-name="city${$(county).find('.twzipcode').length}" data-value="">
                  </div>
                  <div class="twzip right" data-role="district" data-name="area${$(county).find('.twzipcode').length}" data-value="">
                  </div>
                </div>
              `;
              $(county).append(county_div)
              $('.twzipcode').twzipcode({
                  'detect': true, 'css':['select_xx2', 'select_xx2', 'd-none'], onCountySelect: function() {
                      //$("select[name='blockarea']").prepend('<option selected value="">全區</option>');
                  }
              });
          }else{
              c5('最多新增3筆');
              // swal({
              //     title:'最多新增3筆',
              //     type:'warning'
              // });
          }
      });

        let block_county = $("#block_county");
        let add_block_county = $("#add_block_county");
        $(add_block_county).click(function(){
          console.log($(block_county).find('.twzipcode').length)
            if($(block_county).find('.twzipcode').length < {{$blockcity_limit_count}}) {
                let county_div=`
                <div class="twzipcode">
                  <div class="twzip" data-role="county" data-name="blockcity${$(block_county).find('.twzipcode').length}" data-value="">
                  </div>
                  <div class="twzip right" data-role="district" data-name="blockarea${$(block_county).find('.twzipcode').length}" data-value="">
                  </div>
                </div>
              `;
                $(block_county).append(county_div)
                $('.twzipcode').twzipcode({
                    'detect': true, 'css':['select_xx2', 'select_xx2', 'd-none'], onCountySelect: function() {
                        /*for(let i = 1;i < {{$blockcity_limit_count}}; i++)
                        {
                            let blockcity_name = 'blockcity' + i;
                            let blockarea_name = 'blockarea' + i;
                            if($("select[name=" +blockcity_name+ "] option:eq(0)").text()!=='縣市')
                            {
                                if($("select[name=" +blockcity_name+ "] option:eq(0)").text()=='海外')
                                {
                                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全部</option>');
                                }
                                else
                                {
                                    $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全區</option>');
                                }
                            }
                        }*/
                    }
                });
            }else{
                c5('最多新增' +{{$blockcity_limit_count}}+ '筆');
                // swal({
                //     title:'最多新增3筆',
                //     type:'warning'
                // });
            }

            for(let i = 1;i < {{$blockcity_limit_count}}; i++)
            {
                let blockcity_name = 'blockcity' + i;
                let blockarea_name = 'blockarea' + i;
                $("select[name=" +blockcity_name+ "]").on('change', function() {
                    if($("select[name=" +blockcity_name+ "] option:selected" ).text() == '縣市')
                    {
                        $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">鄉鎮市區</option>');
                    }
                    else if($("select[name=" +blockcity_name+ "] option:selected" ).text() == '海外')
                    {
                        $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全部</option>');
                    }
                    else
                    {
                        $("select[name=" +blockarea_name+ "]").prepend('<option selected value="">全區</option>');
                    }
                });
            }
        });


      $(document).on('click','.n_left',function(event) {

          if( $('input[name=occupation]').val() == '' ) {
              $('#occupation').show();
          }else{
              $('#occupation').remove();
          }

        var form = $('form[name=user_data]').serialize();
        $.ajax({
          url:'{{ route('dashboard2') }}?{{csrf_token()}}={{now()->timestamp}}&{{$rap_service->isInRealAuthProcess()?'real_auth='.request()->real_auth:null}}',
          type: 'POST',
          dataType: 'JSON',
          data: form,
          beforeSend: function () {
              $('#tab04').hide();
            waitingDialog.show();
          },
          complete: function (xhr) {
            result = xhr.responseJSON;
            if(!(!!result.status && !!result.redirect))
                window.location.reload();
            waitingDialog.hide();

          },
          success: function (result) {
            $('body').attr('onbeforeunload','');
            console.log(result);
            ResultData(result);

            if(!!result.status && !!result.redirect) {
                location.href=result.redirect;
            }
            else
                window.location.reload();         
          }
        });
      });
      //變更會員類型提示
      $(document).on('change','input[name=engroup]',function(){
           @if($user->engroup_change > 0)
            var engroup = "{{ $user->engroup }}";
            if(engroup==1){
              //console.log('123');
              $("input[name=engroup][value=1]").prop('checked',true);
              $("input[name=engroup][value=2]").prop('checked',false);
            }else{
              $("input[name=engroup][value=2]").prop('checked',true);
              $("input[name=engroup][value=1]").prop('checked',false);
            }
                  c5('您已經改變過帳號類型(甜心大哥/大姐、甜心寶貝)，每個帳號只能變更一次');
            // swal({
            //   title:'您已經改變過帳號類型(甜心大哥/大姐、甜心寶貝)，每個帳號只能變更一次',
            //   type:'warning'
            // });
          @else
                  c5('確定要改變帳號類型(甜心大哥/大姐、甜心寶貝)嗎?，每個帳號只能變更一次');
            // swal(
            //   '確定要改變帳號類型(甜心大哥/大姐、甜心寶貝)嗎?，每個帳號只能變更一次',
            //   '',
            //   'warning'
            // );
          @endif
      });

      setDomain(1);
      $('#domain option[value="{{ $umeta->domain }}"]').attr('selected',true);


      //validation
        $("#name").keyup(function() {
            if(this.value.length>=8){
                c5('至多八個字');
            }
        });

        $("#height").on("change", function() {
            var val = Math.abs(parseInt(this.value, 10) || 1);
            if(this.value>210 || this.value<140) {
                c5('請輸入身高140～210');
                this.value = val > 210 ? 210 : val < 140 ? 140 : val ;
            }
        });

        $("#assets").keyup(function() {
            if($.isNumeric(this.value) == false){
                c5('請輸入數字範圍0～10000000000');
            }
        });


        @if( Session::get('messageBoard_enter_limit',true) ==false || Session::get('messageBoard_msg')!='')
            $('#tab05').hide();
        @endif

        @if( Session::get('messageBoard_enter_limit',true) ==false)
            $('#messageBoard_enter_limit').show();
            $('#announce_bg').show();
        @endif

        @if( Session::get('messageBoard_msg'))
            $('#messageBoard_msg').show();
            $('#announce_bg').show();
        @endif

    });

    $(".line_notify").on('click', function() {
        @if($user->isVipOrIsVvip())
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
    @if($user->isVipOrIsVvip() && !Session::has('original_user'))
    function popSwitchOtherEngroup() {
        c4('此功能可切換成{{$user->engroup == 1?'女':'男'}}會員的角度瀏覽網站。是否要切換成{{$user->engroup == 1?'女':'男'}}會員的角度?');
        $('#tab04 .n_bbutton .n_left').html('是');
        $('#tab04 .n_bbutton .n_right').html('否');
        $('#tab04 .n_bbutton .n_left').removeClass('n_left').addClass('switch_left');
        $('#tab04 .n_bbutton .n_right').removeClass('n_right').addClass('switch_right');
        $(document).off('click','.blbg',closeAndReload);
        $(document).on('click','#tab04 .n_bbutton .switch_right',function() {
            $('#tab04').hide();
            $('.blbg').hide();
        $('#tab04 .n_bbutton .switch_left').removeClass('switch_left').addClass('n_left');
        $('#tab04 .n_bbutton .switch_right').removeClass('switch_right').addClass('n_right');            
        }); 
        $(document).on('click','#tab04 .n_bbutton .switch_left',function() {
            location.href="{{route('switch_other_engroup')}}";
        });         
        
        $(document).on('click','.blbg',function() {
            $('#tab04').hide();
            $('.blbg').hide();
        $('#tab04 .n_bbutton .switch_left').removeClass('switch_left').addClass('n_left');
        $('#tab04 .n_bbutton .switch_right').removeClass('switch_right').addClass('n_right');             
        });     
    }   
    @endif

    //計算註冊時間
    @if(!$umeta->isAllSet( $user->engroup ))
        $(document).ready(function(){
            regist_start_time = new Date();
        });
    @endif
    //計算註冊時間

  </script>
    <script>
        budget_per_month_has_changed = false;
        transport_fare_has_changed = false;
        function budget_per_month_change()
        {
            if(!budget_per_month_has_changed)
            {
                c5('每月預算是非常重要的欄位，請確實填寫，如果被檢舉浮報或未給，查證屬實。將會有”預算不實”的標籤。')
                budget_per_month_has_changed = true;
            }
        }
        function transport_fare_change()
        {
            if(!transport_fare_has_changed)
            {
                c5('車馬費預算是非常重要的欄位，請確實填寫，如果被檢舉浮報或未給查證屬實。將會有”車馬費不實”的標籤。')
                transport_fare_has_changed = true;
            }
        }
    </script>
    <script>
        $('#new_occupation').change(function(){
            if($('#new_occupation').val() == 'other')
            {
                $('#new_occupation_other').val('');
                $('#new_occupation_other').show();
            }
            else
            {
                $('#new_occupation_other').hide();
            }
        });
    </script>

<script>
@if($rap_service->isInRealAuthProcess())
    $(document).ready(function() {
        if(real_auth_bad_count==0)
            real_auth_popup();
    });   


    active_onbeforeunload_hint();

function active_onbeforeunload_hint()
{
    $('body').attr('onbeforeunload',"return '';");
    $('body').attr('onkeydown',"if (window.event.keyCode == 116) $(this).attr('onbeforeunload','');");
}

function real_auth_popup() {
    $('#real_auth_hint_tab').show();
    $(".real_auth_bg").show();
    $('#exchange_period_readonly_block').focus();
}

function real_auth_backward_popup() {
    $('#real_auth_backward_tab').show();
    $(".real_auth_bg").show();
    $('body').attr('onbeforeunload','');
    real_auth_bad_count++;
}
@endif
function real_auth_tab_close(dom) {
    $(dom).closest('.bl_tab').hide();
    $(".real_auth_bg").hide();
}

function show_real_auth_new_height_tab() {
    $(".real_auth_bg").show();
    $('#new_height_modify_tab').show();
}

function show_real_auth_new_weight_tab() {
    $(".real_auth_bg").show();
    $('#new_weight_modify_tab').show();
}

function real_auth_input_new_height_handle() 
{
     var elt_container = $('#new_height_elt_container');
    var input_elt = elt_container.find('input');
    var confirm_block = $('#new_height_confirm_block');
    var error_msg_elt = $('#new_height_input_error_msg');
    error_msg_elt.html('').hide();
    
    if(confirm_block.is(':visible')) {
        confirm_block.hide();
        elt_container.show();
    }
    else {
        var input_value = input_elt.val();
        var error_msg = '';

        if(!input_value || input_value==null || input_value==undefined) error_msg='無法送出！請輸入身高';
        else if(input_value<input_elt.attr('min') || input_value>input_elt.attr('max')) {
            error_msg = '無法送出！請輸入'+input_elt.attr('min')+'～'+input_elt.attr('max')+'範圍的數字';
        }

        if(error_msg) {
            error_msg_elt.show().html(error_msg);
            return;
        }
        
        $('#new_height_modify_tab').hide();
        elt_container.hide();
        confirm_block.show(); 

        $.ajax({
          url:'{{ route('real_auth_update_profile') }}?{{csrf_token()}}={{now()->timestamp}}&{{$rap_service->isInRealAuthProcess()?'real_auth='.request()->real_auth:null}}',
          type: 'POST',
          data: {'new_height':$('#new_height_elt').val(),'_token':'{{csrf_token()}}'},
          beforeSend: function () {
            waitingDialog.show();
          },
          complete: function (xhr) {
            waitingDialog.hide();
            real_auth_tab_end_reload();

          },
          success: function (result) {
            rs_msg = '';
            result_msg_tab = $('#real_auth_result_msg_tab');
            if(result=='1') {
                rs_msg = '申請資料異動成功，正在審核中';
            }
            else {
                rs_msg = '申請資料異動失敗，請重新申請。如果問題持續發生，請反應給站方。';
            }
            
            result_msg_tab.show().find('.bltext').html(rs_msg);
            
          },
          error: function() {
              rs_msg = '申請資料異動失敗，請重新申請。如果問題持續發生，請反應給站方。';
              $('#real_auth_result_msg_tab').show().find('.bltext').html(rs_msg);
              real_auth_tab_end_reload();
          }
        });        
    }
}

function real_auth_input_new_weight_handle() 
{
    var elt_container = $('#new_weight_elt_container');
    var input_elt = elt_container.find('select');
    var confirm_block = $('#new_weight_confirm_block');

    if(confirm_block.is(':visible')) {
        confirm_block.hide();
        elt_container.show();
    }
    else {
        var input_value = input_elt.val();      
        
        $('#new_weight_modify_tab').hide();
        elt_container.hide();
        confirm_block.show(); 

        $.ajax({
          url:'{{ route('real_auth_update_profile') }}?{{csrf_token()}}={{now()->timestamp}}&{{$rap_service->isInRealAuthProcess()?'real_auth='.request()->real_auth:null}}',
          type: 'POST',
          data: {'new_weight':$('#new_weight_elt').val(),'_token':'{{csrf_token()}}'},
          beforeSend: function () {
            waitingDialog.show();
          },
          complete: function (xhr) {
            waitingDialog.hide();
            real_auth_tab_end_reload();

          },
          success: function (result) {
            rs_msg = '';
            result_msg_tab = $('#real_auth_result_msg_tab');
            if(result=='1') {
                rs_msg = '申請資料異動成功，正在審核中';
            }
            else {
                rs_msg = '申請資料異動失敗，請重新申請。如果問題持續發生，請反應給站方。';
            }
            
            result_msg_tab.show().find('.bltext').html(rs_msg);
          },
          error: function() {
              rs_msg = '申請資料異動失敗，請重新申請。如果問題持續發生，請反應給站方。';
              $('#real_auth_result_msg_tab').show().find('.bltext').html(rs_msg);
              real_auth_tab_end_reload();
          }
        });        
    }
}


    function real_auth_tab_end_reload() {
        result_msg_tab = $('#real_auth_result_msg_tab');
        result_msg_tab.find('a.n_bllbut').attr('onclick','location.reload();');
        result_msg_tab.find('a.bl_gb').attr('onclick','location.reload();');
        $('.real_auth_bg').attr('onclick','location.reload();');        
    }



</script>

<script>
    $('#about_other').keyup(function(){
        if($(this).val().length > 300)
        {
            c5('已超過限制字數300字');
        }
    });

    $('#style_other').keyup(function(){
        if($(this).val().length > 300)
        {
            c5('已超過限制字數300字');
        }
    });

    $('#apply_video_record_verify').click(function(){   
        $.ajax({
            url: '{{ route("apply_video_record_verify") }}',
            type: 'GET',
            data: {
                '_token': '{{ csrf_token() }}',
            },
            success: function(data) {
                if(data.status == 'success'){
                    c5('已申請，站方會再跟您約驗證時間，再請注意來訊。');
                    location.reload();
                }
            }
        });
    });
</script>


@stop