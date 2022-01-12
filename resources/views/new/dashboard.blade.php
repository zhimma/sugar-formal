<?php
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')
@section('app-content')

  <?php
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
          $umeta->blockcity = explode(",",$umeta->blockcity);
          $umeta->blockarea = explode(",",$umeta->blockarea);
      }
    }
    $blockcity_limit_count = 3;
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
              <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
              <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t g_hicon1"><span>基本資料</span></a></li>
              <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
              <li><a href="{!! url('dashboard/account_manage') !!}" class="g_pwicon_t3"><span>帳號設定</span></a></li>
{{--              <li><a href="{!! url('dashboard/vipSelect') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
          </div>
          <div class="addpic g_inputt">
                    @if($user->isVip() && !Session::has('original_user'))
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
                      <span class="engroup_type_title">帳號類型</span>
                        @if($user->isVip() && !Session::has('original_user'))
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
                        <span>包養關係</span>
                        @php
                            $exchange_period_name = DB::table('exchange_period_name')->where('id',$user->exchange_period)->first();
                        @endphp
                        <span>
{{--                            <input name="" id="" type="text" class="select_xx01" value="{{$exchange_period_name->name}}" data-parsley-errors-messages-disabled disabled style="background-color: #d2d2d2;">--}}
                            <div class="select_xx01 senhs hy_new" style="background: #d2d2d2;">{{$exchange_period_name->name}}</div>
                        </span>
                        <input name="exchange_period" id="" type="hidden" class="select_xx01" value="{{$user->exchange_period}}" data-parsley-errors-messages-disabled disabled style="background-color: #d2d2d2;">
                    </dt>
                    @endif
                  <dt>
                    <span>地區<i>(必填)</i></span>
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
                                if($i == 0)
                                {
                                    $blockarea_selected[] = isset($umeta->blockarea[$i]) ? ($umeta->blockarea[$i] == "" ? "全區" : str_replace($umeta->blockcity[$i],'',$umeta->blockarea[$i])) : '全區';
                                }
                                else
                                {
                                    $blockarea_selected[] = isset($umeta->blockarea[$i]) ? str_replace($umeta->blockcity[$i],'',$umeta->blockarea[$i]) :'全區';
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
                  <dt class="">
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
                  </dt>
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
                  <dt>
                      <span>身高（cm）<i>(必填)</i></span>
                      <span><input name="height" id="height" type="number" class="select_xx01"  placeholder="請輸入數字範圍140～210" value="{{$umeta->height}}" title="請輸入140~210範圍"></span>
                  </dt>
                  @if($user->engroup==2)
{{--                  <dt>--}}
{{--                      <span>體重（kg）</span>--}}
{{--                      <span><input minlength="2"  data-parsley-minlength="2" name="weight" type="text" class="select_xx01"  placeholder="請填入體重" value="{{$umeta->weight}}"></span>--}}
{{--                      <div class="right" style="margin: 10px 0 -5px 10px;">--}}
{{--                        <input type="hidden" name="isHideWeight" value="0">--}}
{{--                        <input name="isHideArea" type="checkbox"  @if($umeta->isHideWeight == true) checked--}}
{{--                                                                       @endif value="1"> 隱藏體重--}}
{{--                      </div>--}}
{{--                  </dt>--}}
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
                  <dt>
                      <span>關於我<i>(必填)</i></span>
                      <span><textarea data-parsley-errors-messages-disabled name="about" cols="" rows="3" class="select_xx05">{{$umeta->about}}</textarea></span>
                  </dt>
                  <dt class="matopj15">
                      <span>期待的約會模式<i>(必填)</i></span>
                      <span><textarea data-parsley-errors-messages-disabled name="style" cols="" rows="3" class="select_xx05">{{$umeta->style}}</textarea></span>
                  </dt>
                  @if($user->engroup==2)
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
                        </select>
                      </span>
                  </dt>
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
                        </select>
                      </span>
                      <div class="right" style="margin: 10px 0 -5px 10px;">
                        <input type="hidden" name="isHideOccupation" value="0">
                        <input type="checkbox" name="isHideOccupation"  @if($umeta->isHideOccupation == true) checked @endif value="1"> 隱藏職業
                      </div>
                  </dt>
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
                  <dt>
                      <span>資產<i>(必填)</i></span>
                      <span><input data-parsley-errors-messages-disabled name="assets" id="assets" value="{{$umeta->assets}}" type="number" class="select_xx01"  placeholder="請輸入數字範圍0～10000000000"></span>
                  </dt>
                  @endif
                </div>
                <a class="dlbut g_inputt20 abtn" onclick="$('form[name=user_data]').submit();">確定更新</a>
                <a href="" class="zcbut matop20">取消</a>
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
                      <td class="">@if(!$user->isVip())<img src="/new/images/ticon_02.png">@else<img src="/new/images/ticon_01.png">@endif</td>
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
<script>
    function pr() {
        $(".blbg").show();
        $(".prz").show();
    }

    $(document).ready(function() {
        @if(Session::has('message'))
        c5('{{Session::get('message')}}');
        <?php session()->forget('message');?>
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

        /*取代*/var blockarea_selected = '{{ isset($umeta->blockarea[0]) ? ($umeta->blockarea[0] == "" ? "全區" : str_replace($umeta->blockcity[0],'',$umeta->blockarea[0])) : '全區' }}';
        /*取代*/var blockarea1_selected = '{{ isset($umeta->blockarea[1]) ? str_replace($umeta->blockcity[1],'',$umeta->blockarea[1]) :'全區'  }}';
        /*取代*/var blockarea2_selected = '{{ isset($umeta->blockarea[2]) ? str_replace($umeta->blockcity[2],'',$umeta->blockarea[2]) : '全區'  }}';


        if ($("select[name='blockarea'] option:eq(0)").text() !== '全區') {
            //$("select[name='blockarea']").prepend('<option value="">全區</option>');
            if (blockarea_selected == '全區') {
                if ($("select[name='blockcity']").val() !== '') {
                    $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
                }
            } else {
                $("select[name='blockarea'] option[value=" + blockarea_selected + "]").attr('selected', true);
            }
        }
        if ($("select[name='blockarea1'] option:eq(0)").text() !== '全區') {
            //$("select[name='blockarea1']").prepend('<option value="">全區</option>');
            if (blockarea1_selected == '全區') {
                $("select[name='blockarea1']").prepend('<option selected value="">全區</option>');
            } else {
                $("select[name='blockarea1'] option[value=" + blockarea1_selected + "]").attr('selected', true);
            }
        }
        if ($("select[name='blockarea2'] option:eq(0)").text() !== '全區') {
            //$("select[name='blockarea2']").prepend('<option value="">全區</option>');
            if (blockarea2_selected == '全區') {
                $("select[name='blockarea2']").prepend('<option selected value="">全區</option>');
            } else {
                $("select[name='blockarea2'] option[value=" + blockarea2_selected + "]").attr('selected', true);
            }
        }

        $("select[name='blockcity']").on('change', function () {
            if ($("select[name='blockcity'] option:selected").text() == '縣市') {
                if ($("select[name='blockarea'] option:eq(0)").text() !== '鄉鎮市區')
                    $("select[name='blockarea']").prepend('<option selected value="">鄉鎮市區</option>');
            } else {
                if ($("select[name='blockarea'] option:eq(0)").text() !== '全區')
                    $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
            }
        });
        $("select[name='blockcity1']").on('change', function () {
            if ($("select[name='blockcity1'] option:selected").text() == '縣市') {
                if ($("select[name='blockarea1'] option:eq(0)").text() !== '鄉鎮市區')
                    $("select[name='blockarea1']").prepend('<option selected value="">鄉鎮市區</option>');
            } else {
                if ($("select[name='blockarea1'] option:eq(0)").text() !== '全區')
                    $("select[name='blockarea1']").prepend('<option selected value="">全區</option>');
            }
        });
        $("select[name='blockcity2']").on('change', function () {
            if ($("select[name='blockcity2'] option:selected").text() == '縣市') {
                if ($("select[name='blockarea2'] option:eq(0)").text() !== '鄉鎮市區')
                    $("select[name='blockarea2']").prepend('<option selected value="">鄉鎮市區</option>');
            } else {
                if ($("select[name='blockarea2'] option:eq(0)").text() !== '全區')
                    $("select[name='blockarea2']").prepend('<option selected value="">全區</option>');
            }
        });

        function getAge(birth) {
            birth = Date.parse(birth.replace('/-/g', "/"));
            var year = 1000 * 60 * 60 * 24 * 365;
            var now = new Date();
            var birthday = new Date(birth);
            var age = parseInt((now - birthday) / year);
            return age;
        }

        @php
            $ckBarCodeLog = DB::table('payment_get_barcode_log')->where('user_id',$user->id)->where('ExpireDate','>=',now())->where('isRead',0)->count();
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
            @elseif($ckBarCodeLog>0 && !$user->isVip())
                $('#isGetBarCodeNotVIP').show();
                $('#announce_bg').show();
                @php
                    DB::table('payment_get_barcode_log')->where('user_id',$user->id)->where('ExpireDate','>=',now())->where('isRead',0)->update(['isRead' => 1]);
                @endphp
            @endif
            @if (!$umeta->isAllSet( $user->engroup ))
                c5('請寫上基本資料。');
            @elseif (empty($umeta->pic))
                c5("{{$add_avatar}}");
            @elseif ($umeta->age()<18)
                c5('您好，您的年齡低於法定18歲，請至個人基本資料設定修改，否則您的資料將會被限制搜尋。');
            @endif
        @endif

        @php
            $exchange_period_read = DB::table('exchange_period_temp')->where('user_id',$user->id)->count();
        @endphp
        @if($exchange_period_read==0 && $user->engroup==2)
            $('#isExchangePeriod').show();
            $('#announce_bg').show();
        @endif



      //ajax_表單送出
      $('form[name=user_data]').submit(function(e){
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
          }
          
          
          if(budget.val() === "") {
            budget.focus();
            c5('請選擇預算');
            return false;
          }
          if($('select[name=year]').val() == "" || $('select[name=month]').val() == "" || age < 18){
            c5('您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。');
            // swal({
            //     title:'您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。',
            //     type:'warning'
            // });
            return false;
          }
          if(height.val() == '' || height.val().charAt(0)==0 || height.val() < 140 || height.val() > 210) {
            height.focus();
            c5('請輸入身高140～210');
            return false;
          }
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
                      $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
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
            $("select[name='blockcity1']").on('change', function() {
                if($("select[name='blockcity1'] option:selected" ).text() == '縣市'){
                    if($("select[name='blockarea1'] option:eq(0)").text()!=='鄉鎮市區')
                        $("select[name='blockarea1']").prepend('<option selected value="">鄉鎮市區</option>');
                }
                else{
                   if($("select[name='blockarea1'] option:eq(0)").text()!=='全區')
                    $("select[name='blockarea1']").prepend('<option selected value="">全區</option>');
                }

                $("select[name='blockcity2']").on('change', function() {
                    if($("select[name='blockcity2'] option:selected" ).text() == '縣市'){
                        if($("select[name='blockarea2'] option:eq(0)").text()!=='鄉鎮市區')
                            $("select[name='blockarea2']").prepend('<option selected value="">鄉鎮市區</option>');
                    }
                    else{
                       if($("select[name='blockarea2'] option:eq(0)").text()!=='全區')
                            $("select[name='blockarea2']").prepend('<option selected value="">全區</option>');
                    }
                });
            });

          console.log($(block_county).find('.twzipcode').length)
            if($(block_county).find('.twzipcode').length < 3) {
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
                        if($("select[name='blockcity1'] option:eq(0)").text()!=='縣市'){
                            $("select[name='blockarea1']").prepend('<option selected value="">全區</option>');
                        }
                        if($("select[name='blockcity2'] option:eq(0)").text()!=='縣市'){
                            $("select[name='blockarea2']").prepend('<option selected value="">全區</option>');
                        }
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


      $(document).on('click','.n_left',function(event) {

          if( $('input[name=occupation]').val() == '' ) {
              $('#occupation').show();
          }else{
              $('#occupation').remove();
          }

        var form = $('form[name=user_data]').serialize();
        $.ajax({
          url:'{{ route('dashboard2') }}',
          type: 'POST',
          dataType: 'JSON',
          data: form,
          beforeSend: function () {
              $('#tab04').hide();
            waitingDialog.show();
          },
          complete: function () {

            window.location.reload();
            waitingDialog.hide();

          },
          success: function (result) {
            ResultData(result);
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
            window.location.href = URL;
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
    @if($user->isVip() && !Session::has('original_user'))
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
  </script>

@stop