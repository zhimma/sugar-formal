@extends('new.layouts.website')

@section('app-content')

  <?php
    if (!isset($user)) {
        $umeta = null;
    } else {
        $umeta = $user->meta_();
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
    
  ?>
  <style type="text/css">
    .abtn{cursor: pointer;}
    .twzip {display: inline-block !important;width: auto !important;min-width: 49%;/*margin-right: 10PX;*/}
    .select_xx2{width: 100%;border: #d2d2d2 1px solid;border-radius: 4px;height: 40px;padding: 0 6px;color:#555;background:#ffffff;font-size: 15px;margin-top: 10px;}
  </style>

	<div class="container matop70 chat">
    <div class="row">
      <div class="col-sm-2 col-xs-2 col-md-2 dinone">
          @include('new.dashboard.panel')
      </div>
      <div class="col-sm-12 col-xs-12 col-md-10">
        <div class="g_password">
          <div class="g_pwicon">
            <li><a href="{!! url('dashboard') !!}"><img src="/new/images/mm_15.png"><span>基本資料</span></a></li>
            <li><a href="{!! url('dashboard_img') !!}"><img src="/new/images/mm_05.png"><span>照片管理</span></a></li>
            <li><a href="{!! url('/dashboard/password') !!}"><img src="/new/images/mm_07.png"><span>更改密碼</span></a></li>
            <li><a href="{!! url('/dashboard/vip') !!}"><img src="/new/images/mm_09.png"><span>VIP</span></a></li>
          </div>
          <div class="addpic g_inputt">

            <div class="n_adbut">
                <a href="/dashboard/viewuser/{{$user->id}}"><img src="/new/images/1_06.png">預覽</a></div>
              <div class="n_adbut"><a href="" style="padding-left: 10px;">身份驗證</a></div>
            <div class="xiliao_input">
               <form class="m-form m-form--fit m-form--label-align-right" method="POST" name="user_data" action="" id="information" data-parsley-validate novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="userId" value="{{$user->id}}">
                <div class="n_input">
                  <dt>
                    <span>暱稱<i>(必填)</i></span>
                    <span><input name="name" id="name" type="text" class="select_xx01"  placeholder="至多八個字" value="{{$user->name}}" required data-parsley-errors-messages-disabled maxlength="8"></span>
                  </dt>
                  <dt>
                    <span>一句話形容自己<i>(必填)</i></span>
                    <span><input name="title" type="text" class="select_xx01"  placeholder="請輸入" value="{{$user->title}}" required data-parsley-errors-messages-disabled></span>
                  </dt>
                  <dt>
                      <span>帳號類型</span>
                      <div class="n_heg" style="margin-top:-2px">
                        <form name="form1" method="post" action="">
                            <label class="n_lod"><input required data-parsley-errors-messages-disabled type="radio" name="engroup" value="1" id="RadioGroup1_0" {{ ($user->engroup == 1)?"checked":"" }} ><font class="n_loleft">甜心大哥</font></label>
                            <label class="n_lod"><input required data-parsley-errors-messages-disabled type="radio" name="engroup" value="2" id="RadioGroup1_1" {{ ($user->engroup == 2)?"checked":"" }}><font class="n_loleft">甜心寶貝</font></label>
                        </form>
                        <div class="n_red">註：每個帳號只能變更一次</div>
                      </div>
                  </dt>
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
                                    data-name="blockcity"
                                    data-value="{{$umeta->blockcity[$key]}}">
                                </div>
                                <div class="twzip right" data-role="district"
                                    data-name="blockarea"
                                    data-value="{{$umeta->blockarea[$key]}}">
                                </div>
                              </div>
                          @endforeach
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
                        <select required data-parsley-errors-messages-disabled name="budget"  class="select_xx01">
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
                          <select required data-parsley-errors-messages-disabled name="year"  class="select_xx04 sel_year" data-value="{{ $year }}">
                          </select>
                          <i class="right">年</i>
                        </div>
                        <div class="se_zlman right">
                          <select required data-parsley-errors-messages-disabled name="month"  class="select_xx04 sel_month" data-value="{{ $month }}">
                          </select>
                          <i class="right">月</i>
                        </div>
                      </span>
                  </dt>
                  <dt>
                      <span>身高（cm）<i>(必填)</i></span>
                      <span><input minlength="3" data-parsley-minlength="3" name="height" id="height" type="number" class="select_xx01"  placeholder="請輸入數字範圍140～210" value="{{$umeta->height}}" title="請輸入140~210範圍"></span>
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
                  <dt>
                      <span>CUP</span>
                      <span>
                        <select name="cup"  class="select_xx01" >
                          <option value="">請選擇</option>
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
                      <span><textarea minlength="4"  data-parsley-minlength="4" required data-parsley-errors-messages-disabled name="about" cols="" rows="3" class="select_xx05">{{$umeta->about}}</textarea></span>
                  </dt>
                  <dt class="matopj15">
                      <span>期待的約會模式<i>(必填)</i></span>
                      <span><textarea minlength="4"  data-parsley-minlength="4" required data-parsley-errors-messages-disabled name="style" cols="" rows="3" class="select_xx05">{{$umeta->style}}</textarea></span>
                  </dt>
                  @if($user->engroup==2)
                  <dt class="matopj15">
                      <span>現況<i>(必填)</i></span>
                      <span>
                        <select required data-parsley-errors-messages-disabled name="situation"  class="select_xx01">
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
                          <option value="">請選擇</option>
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
                  <dt>
                      <span>職業<i></i></span>
                      <span>
                          <input name="occupation" type="text" class="select_xx01"  placeholder="請填入職業" @if(!empty($umeta->occupation))value="{{$umeta->occupation}}"@endif>
                      </span>
                  </dt>
                  @endif
                  <dt>
                      <span>教育<i>(必填)</i></span>
                      <span>
                        <select required data-parsley-errors-messages-disabled name="education"  class="select_xx01">
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
                        <select required data-parsley-errors-messages-disabled name="marriage"  class="select_xx01">
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
                        <select required data-parsley-errors-messages-disabled name="drinking"  class="select_xx01">
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
                        <select required data-parsley-errors-messages-disabled name="smoking"  class="select_xx01">
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
                        <select required data-parsley-errors-messages-disabled name="income"  class="select_xx01">
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
                      <span><input required data-parsley-errors-messages-disabled name="assets" id="assets" value="{{$umeta->assets}}" type="number" class="select_xx01"  placeholder="請輸入數字範圍0～10000000000"></span>
                  </dt>
                  @endif
                </div>
                <a class="dlbut g_inputt20 abtn" onclick="$('form[name=user_data]').submit();">更新資料</a>
                <a href="" class="zcbut matop20">取消</a>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
<script>
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
          $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
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
        $("#domain").html('<option value="">請選擇</option>');
        //删除所有的option
        $('#domain option:not(:first)').remove();

        var type = $("#domainType").val();
        //console.log('type is ' + type);
        if (!initial) {
            $("#domain option").remove();
            $("#domain").append('<option value="">請選擇</option>');
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
      function getAge(birth) {
        birth = Date.parse(birth.replace('/-/g', "/"));
        var year = 1000 * 60 * 60 * 24 * 365;
        var now = new Date();
        var birthday = new Date(birth);
        var age = parseInt((now - birthday) / year);
        return age;
      }

      @if(!$user->isAdmin())
        @if (!$umeta->isAllSet())
        c5('請寫上基本資料。');
          // swal({
          //   title:'請寫上基本資料。',
          //   type:'warning'
          // });
        @elseif (empty($umeta->pic))
        c5('請加上頭像照。');
          // swal({
          //   title:'請加上頭像照。',
          //   type:'warning'
          // });
        @elseif ($umeta->age()<18)
        c5('您好，您的年齡低於法定18歲，請至個人基本資料設定修改，否則您的資料將會被限制搜尋。');
          // swal({
          //   title:'您好，您的年齡低於法定18歲，請至個人基本資料設定修改，否則您的資料將會被限制搜尋。',
          //   type:'warning'
          // });
        @endif
      @endif
      //ajax_表單送出
      $('form[name=user_data]').submit(function(e){
        e.preventDefault();
        if($(this).parsley().isValid()){
          let birth = $('select[name=year]').val()+'/'+$('select[name=month]').val()+'/'+$('input[name=day]').val();
          console.log(birth);
          let age = getAge(birth);
          if(age < 18){
          c5('您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。');
            // swal({
            //     title:'您的年齡低於法定18歲，請於基本資料設定修改，否則您的資料將會被限制搜尋。',
            //     type:'warning'
            // });
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
      let county = $("#county");
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
                        // $("select[name='blockarea']").prepend('<option selected value="">全區</option>');
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
                  c4('您已經改變過帳號類型(甜心大哥/大姐、甜心寶貝)，每個帳號只能變更一次');
            // swal({
            //   title:'您已經改變過帳號類型(甜心大哥/大姐、甜心寶貝)，每個帳號只能變更一次',
            //   type:'warning'
            // });
          @else
                  c4('確定要改變帳號類型(甜心大哥/大姐、甜心寶貝)嗎?，每個帳號只能變更一次');
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
                c3('至多八個字');
            }
        });

        $("#height").on("change", function() {
            var val = Math.abs(parseInt(this.value, 10) || 1);
            if(this.value>210 || this.value<140) {
                c3('請輸入數字範圍140～210');
                this.value = val > 210 ? 210 : val < 140 ? 140 : val ;
            }
        });

        $("#assets").keyup(function() {
            if($.isNumeric(this.value) == false){
                c3('請輸入數字範圍0～10000000000');
            }
        });

    });


  </script>

@stop