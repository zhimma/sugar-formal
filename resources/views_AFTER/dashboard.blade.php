@extends('layouts.newmaster')

@section('app-content')
<link rel="stylesheet" href="css/ssi-uploader.css"/>
<?php
if (isset($cur)) $orderNumber = $cur->id;
else $orderNumber = "";

$code = Config::get('social.payment.code');
$umeta = $user->meta_();
$cmeta = $cur->meta_();
$female = (str_contains(url()->current(), 'dashboard') && $user->engroup == 2) || (isset($cmeta) && $cur->engroup == 2);
?>
  
                @if($user->id == $cur->id)
                    @if(str_contains(url()->current(), 'dashboard'))
                        <div class="photo weui-t_c">
                            <img src="{{$umeta->pic}}">
                            <p class="weui-pt20 weui-f18">{{$user->name}}</p>
                            @if ((isset($cur) && $cur->isVip()) || $user->isVip() && str_contains(url()->current(), 'dashboard')) 
                                <p class="weui-pt10 m_p">
                                    <span class="weui-pl10 weui-pr10">
                                        <img src="/images/sousuo_03.png">
                                        <span class="weui-v_m gj">高级会员</span>
                                    </span>
                                    <!-- <span class="weui-pl10 weui-pr10">
                                        <img src="/images/sousuo_06.png">
                                        <span class="weui-v_m bzj"> 保证金</span>
                                    </span>
                                    <span class="weui-pl10 weui-pr10">
                                        <img src="/images/sousuo_08.png">
                                        <span class="weui-v_m bwj"> 百万级</span>
                                    </span> -->
                                </p>
                            @endif
                        </div>
                        @else
                    <script type="text/javascript">
                        location="/dashboard";
                    </script> 
                    @endif
                @else
                    @if (isset($cmeta->pic))
                    <div class="photo weui-t_c">
                        @if($cmeta->isAvatarHidden == 1)
                            @else
                            <img src="{{$cmeta->pic}}">
                            </br>
                            <a href="{{ route('reportPic', [$user->id, 'uid'.$cur->id]) }}">檢舉大頭照</a>
                            <p class="weui-pt20 weui-f18">{{$cur->name}}</p>
                            @if ((isset($cur) && $cur->isVip()) || $user->isVip() && str_contains(url()->current(), 'dashboard')) 
                                <p class="weui-pt10 m_p">
                                    <span class="weui-pl10 weui-pr10">
                                        <img src="/images/sousuo_03.png">
                                        <span class="weui-v_m gj">高级会员</span>
                                    </span>
                                    <!-- <span class="weui-pl10 weui-pr10">
                                        <img src="/images/sousuo_06.png">
                                        <span class="weui-v_m bzj"> 保证金</span>
                                    </span>
                                    <span class="weui-pl10 weui-pr10">
                                        <img src="/images/sousuo_08.png">
                                        <span class="weui-v_m bwj"> 百万级</span>
                                    </span> -->
                                </p>
                            @endif
                        @endif
                    </div>
                    @endif
                @endif
       
        </div>
  </div>

@if($user->id == $cur->id)
@if (str_contains(url()->current(), 'dashboard'))
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-lg-push-2 col-md-push-2 col-sm-push-2">
            <ul id="myTab" class="nav nav-tabs nav-tabs01 weui-f16 weui-box_s">
                <li class="active">
                    <a href="#tab1" data-toggle="tab">
                        基本資料
                    </a>
                </li>
                <li><a href="#tab2" data-toggle="tab">照片管理</a></li>
                <li><a href="#tab3" data-toggle="tab">更改密碼</a></li>
                <li><a href="#tab4" data-toggle="tab">設定</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade in active" id="tab1">
                     <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <table class="table01 weui-mt20 weui-mb30" width="100%">
                         <tr>
                             <td class="weui-t_r" width="110">暱稱 <span class=" weui-red weui-db">(必填)</span></td>
                             <td><input   class="form-control"  name="name" type="text" maxlength="10" value="{{$user->name}}"> </td>
                         </tr>
                         <tr class="weui-bb_d">
                             <td class="weui-t_r">標題 <span class=" weui-red weui-db">(必填)</span></td>
                             <td><input  class="form-control" name="title" type="text" maxlength="20" value="{{$user->title}}" ></td>
                         </tr>
                         <tr>
                             <td class="weui-t_r">縣市 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                  <div class="form-inline">
                                     <div class="form-group  twzipcode" id="twzipcode">
                                        <div class="form-group">
                                            <div class="twzip" data-role="county" data-name="city" data-value="{{$umeta->city}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="twzip" data-role="district" data-name="area" data-value="{{$umeta->area}}">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                         <input type="hidden" name="isHideArea" value="0">
                                         <input type="checkbox" class="weui-v_m" name="isHideArea" @if($umeta->isHideArea == true) checked @endif value="1" >
                                         <span class="weui-v_m weui-f14">隱藏鄉鎮區</span>
                                     </div>
                                    </div>
                                 </div>
                             </td>
                         </tr>
                         @if ($user->engroup == 2)
                         <tr>
                             <td class="weui-t_r">拒絕接受搜索縣市</td>
                             <td>
                                 <div class="form-inline">
                                    <div class="form-group  twzipcode" id="twzipcode">
                                        <div class="form-group">
                                            <div class="twzip" data-role="county" data-name="blockcity" data-value="{{$umeta->blockcity}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="twzip" data-role="district" data-name="blockarea" data-value="{{$umeta->blockarea}}">
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </td>
                         </tr>
                        @endif

                         <tr>
                             <td class="weui-t_r">預算 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                         <select class="form-control  " name="budget">
                                            <option value="">請選擇</option>
                                            <option value="基礎" @if($umeta->budget == '基礎') selected @endif>基礎</option>
                                            <option value="進階" @if($umeta->budget == '進階') selected @endif>進階</option>
                                            <option value="高級" @if($umeta->budget == '高級') selected @endif>高級</option>
                                            <option value="最高" @if($umeta->budget == '最高') selected @endif>最高</option>
                                            <option value="可商議" @if($umeta->budget == '可商議') selected @endif>可商議</option>
                                        </select>
                                    </div>
                                </div>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r">出生日期 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <div class="form-inline">
                                    <div class="form-group">
                                    <select name="year" class="form-control"></select>年
                                    </div>
                                    <div class="form-group">
                                        <select name="month" class="form-control">
                                            <option value="1" @if($month == '01') selected @endif>1</option>
                                            <option value="2" @if($month == '02') selected @endif>2</option>
                                            <option value="3" @if($month == '03') selected @endif>3</option>
                                            <option value="4" @if($month == '04') selected @endif>4</option>
                                            <option value="5" @if($month == '05') selected @endif>5</option>
                                            <option value="6" @if($month == '06') selected @endif>6</option>
                                            <option value="7" @if($month == '07') selected @endif>7</option>
                                            <option value="8" @if($month == '08') selected @endif>8</option>
                                            <option value="9" @if($month == '09') selected @endif>9</option>
                                            <option value="10" @if($month == '10') selected @endif>10</option>
                                            <option value="11" @if($month == '11') selected @endif>11</option>
                                            <option value="12" @if($month == '12') selected @endif>12</option>
                                        </select>月
                                    </div>
                                    <div class="form-group">
                                        <select name="day" class="form-control"></select>日
                                    </div>
                                </div>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r">身高 (cm)  <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <input class="form-control" name="height" maxlength="3"  minlength="3"  type="number" id="input-height" value="{{$umeta->height}}">
                             </td>
                         </tr>


                         @if ($female)
                         <tr>
                             <td class="weui-t_r">體重 (kg) <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <input class="form-control " type="number" name="weight" id="input-weight" value="{{$umeta->weight}}">
                                <div class="twzip">
                                    <input type="hidden" name="isHideWeight" value="0">
                                    <input type="checkbox" name="isHideWeight" @if($umeta->isHideWeight == true) checked @endif value="1">
                                    <!-- <input class="m-input" type="checkbox" id="isHideWeight" name="isHideWeight" value="{{ $umeta->isHideWeight }}"> -->
                                    隱藏體重
                                </div>
                                    
                            </td>
                         </tr>
                        @endif

                        @if ($female)
                        <tr>
                             <td class="weui-t_r">Cup <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <div class="form-inline">
                                    
                                    <div class="form-group">
                                        <select  name="cup" class="form-control">
                                            <option value="">請選擇</option>
                                            <option value="A" @if($umeta->cup == 'A') selected @endif>A</option>
                                            <option value="B" @if($umeta->cup == 'B') selected @endif>B</option>
                                            <option value="C" @if($umeta->cup == 'C') selected @endif>C</option>
                                            <option value="D" @if($umeta->cup == 'D') selected @endif>D</option>
                                            <option value="E" @if($umeta->cup == 'E') selected @endif>E</option>
                                            <option value="F" @if($umeta->cup == 'F') selected @endif>F</option>
                                        </select>
                                    </div>
                                    <div class="twzip">
                                        <input type="hidden" name="isHideCup" value="0">
                                        <input type="checkbox" name="isHideCup" @if($umeta->isHideCup == true) checked @endif value="1">
                                        隱藏
                                    </div>
                                </div>
                            </td>
                         </tr>
                        @endif



                         <tr>
                             <td class="weui-t_r">體型</td>
                             <td>
                                 <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                         <select class="form-control" name="body">
                                            <option value="">請選擇</option>
                                            <option value="瘦" @if($umeta->body == '瘦') selected @endif>瘦</option>
                                            <option value="標準" @if($umeta->body == '標準') selected @endif>標準</option>
                                            <option value="微胖" @if($umeta->body == '微胖') selected @endif>微胖</option>
                                            <option value="胖" @if($umeta->body == '胖') selected @endif>胖</option>
                                         </select>
                                    </div>
                                </div>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r weui-v_t">關於我 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                 <textarea class="form-control" type="textarea" id="about" name="about" rows="3" maxlength="300">{{$umeta->about}}</textarea>
                             </td>
                         </tr>
                         <tr class="weui-bb_d">
                             <td class="weui-t_r weui-v_t">約會模式 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <textarea class="form-control" type="textarea" name="style" rows="3" maxlength="300">{{$umeta->style}}</textarea>
                             </td>
                         </tr>

                         @if($female)
                         <tr>
                             <td class="weui-t_r">現況</td>
                             <td>
                                 <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                         <select class="form-control" name="situation">
                                            <option value="">請選擇</option>
                                            <option value="學生" @if($umeta->situation == '學生') selected @endif>學生</option>
                                            <option value="待業" @if($umeta->situation == '待業') selected @endif>待業</option>
                                            <option value="休學" @if($umeta->situation == '休學') selected @endif>休學</option>
                                            <option value="打工" @if($umeta->situation == '打工') selected @endif>打工</option>
                                            <option value="上班族" @if($umeta->situation == '上班族') selected @endif>上班族</option>
                                         </select>
                                    </div>
                                </div>
                             </td>
                         </tr>
                        @endif

                        @if(!$female)
                        <tr>
                             <td class="weui-t_r">產業</td>
                             <td>
                                <div class="form-inline">
                                     <div class="form-group">
                                        <select class="form-control m-bootstrap-select m_selectpicker twzip" name="domainType" id="domainType" onchange="setDomain(0);">
                                            <option value="">請選擇</option>
                                            <option value="資訊科技" @if($umeta->domainType == '資訊科技') selected @endif>資訊科技</option>
                                            <option value="傳產製造" @if($umeta->domainType == '傳產製造') selected @endif>傳產製造</option>
                                            <option value="工商服務" @if($umeta->domainType == '工商服務') selected @endif>工商服務</option>
                                            <option value="民生服務" @if($umeta->domainType == '民生服務') selected @endif>民生服務</option>
                                            <option value="文教傳播" @if($umeta->domainType == '文教傳播') selected @endif>文教傳播</option>
                                        </select>
                                     </div>
                                     <div class="form-group">
                                         <select class="form-control  " name="domain" id="domain">
                                            @if(isset($cmeta->domain))
                                            <option value="{{ $cmeta->domain }}" selected>{{ $cmeta->domain }}</option>
                                            @else
                                            <option value="" selected>請選擇</option>
                                            @endif
                                        </select>
                                     </div>
                                 </div>
                             </td>
                        </tr>
                        @endif

                         @if ($female)
                        <tr>
                             <td class="weui-t_r">職業 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <div class="form-inline">
                                    
                                    <div class="form-group">
                                         <select class="form-control m-bootstrap-select m_selectpicker twzip" name="occupation">
                                            <option value="">請選擇</option>
                                            <option value="學生" @if($umeta->occupation == '學生') selected @endif>學生</option>
                                            <option value="無業" @if($umeta->occupation == '無業') selected @endif>無業</option>
                                            <option value="人資" @if($umeta->occupation == '人資') selected @endif>人資</option>
                                            <option value="業務銷售" @if($umeta->occupation == '業務銷售') selected @endif>業務銷售</option>
                                            <option value="行銷企劃" @if($umeta->occupation == '行銷企劃') selected @endif>行銷企劃</option>
                                            <option value="行政助理" @if($umeta->occupation == '行政助理') selected @endif>行政助理</option>
                                            <option value="專案管理" @if($umeta->occupation == '專案管理') selected @endif>專案管理</option>
                                            <option value="餐飲類服務業" @if($umeta->occupation == '餐飲類服務業') selected @endif>餐飲類服務業</option>
                                            <option value="旅遊類服務業" @if($umeta->occupation == '旅遊類服務業') selected @endif>旅遊類服務業</option>
                                            <option value="美容美髮美甲芳療" @if($umeta->occupation == '美容美髮美甲芳療') selected @endif>美容美髮美甲芳療</option>
                                            <option value="操作員" @if($umeta->occupation == '操作員') selected @endif>操作員</option>
                                            <option value="文字工作者" @if($umeta->occupation == '文字工作者') selected @endif>文字工作者</option>
                                            <option value="學術研究" @if($umeta->occupation == '學術研究') selected @endif>學術研究</option>
                                            <option value="教育輔導" @if($umeta->occupation == '教育輔導') selected @endif>教育輔導</option>
                                            <option value="金融營業交易" @if($umeta->occupation == '金融營業交易') selected @endif>金融營業交易</option>
                                            <option value="財務會計" @if($umeta->occupation == '財務會計') selected @endif>財務會計</option>
                                            <option value="總機秘書櫃檯" @if($umeta->occupation == '總機秘書櫃檯') selected @endif>總機秘書櫃檯</option>
                                            <option value="法務記帳代書" @if($umeta->occupation == '法務記帳代書') selected @endif>法務記帳代書</option>
                                            <option value="資訊軟體" @if($umeta->occupation == '資訊軟體') selected @endif>資訊軟體</option>
                                            <option value="客服" @if($umeta->occupation == '客服') selected @endif>客服</option>
                                            <option value="貿易船務" @if($umeta->occupation == '貿易船務') selected @endif>貿易船務</option>
                                            <option value="交通運輸物流" @if($umeta->occupation == '交通運輸物流') selected @endif>交通運輸物流</option>
                                            <option value="倉管採購" @if($umeta->occupation == '倉管採購') selected @endif>倉管採購</option>
                                            <option value="設計美術" @if($umeta->occupation == '設計美術') selected @endif>設計美術</option>
                                            <option value="模特演員" @if($umeta->occupation == '模特演員') selected @endif>模特演員</option>
                                            <option value="傳播藝術" @if($umeta->occupation == '傳播藝術') selected @endif>傳播藝術</option>
                                          <!--  <option value="1" @if($umeta->job == '1') selected @endif>其他(自填)</option> -->
                                        </select>
                                    </div>
                                    <div class="twzip">
                                        <input type="hidden" name="isHideOccupation" value="0">
                                        <input type="checkbox" name="isHideOccupation" @if($umeta->isHideOccupation == true) checked @endif value="1">
                                        隱藏職業
                                    </div>
                                </div>
                            </td>
                         </tr>
                        @endif
                         
                         <tr>
                             <td class="weui-t_r weui-v_t">職業</td>
                             <td><input class="form-control " name="occupation" value="{{$umeta->occupation}}" maxlength="15"></td>
                         </tr>
                         <tr>
                             <td class="weui-t_r weui-v_t">教育 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                 <select class="form-control m-bootstrap-select m_selectpicker" name="education">
                                    <option value="">請選擇</option>
                                    <option value="國中" @if($umeta->education == '國中') selected @endif>國中</option>
                                    <option value="高中" @if($umeta->education == '高中') selected @endif>高中</option>
                                    <option value="大學" @if($umeta->education == '大學') selected @endif>大學</option>
                                    <option value="研究所" @if($umeta->education == '研究所') selected @endif>研究所</option>
                                </select>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r weui-v_t">婚姻 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <select class="form-control m-bootstrap-select m_selectpicker" name="marriage">
                                    <option value="">請選擇</option>
                                    <option value="已婚" @if($umeta->marriage == '已婚') selected @endif>已婚</option>
                                    <option value="分居" @if($umeta->marriage == '分居') selected @endif>分居</option>
                                    <option value="單身" @if($umeta->marriage == '單身') selected @endif>單身</option>
                                    @if($female)
                                    <option value="有男友" @if($umeta->marriage == '有男友') selected @endif>有男友</option>
                                    @else
                                    <option value="有女友" @if($umeta->marriage == '有女友') selected @endif>有女友</option>
                                    @endif
                                </select>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r weui-v_t">喝酒  <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <select class="form-control m-bootstrap-select m_selectpicker" name="drinking">
                                    <option value="">請選擇</option>
                                    <option value="不喝" @if($umeta->drinking == '不喝') selected @endif>不喝</option>
                                    <option value="偶爾喝" @if($umeta->drinking == '偶爾喝') selected @endif>偶爾喝</option>
                                    <option value="常喝" @if($umeta->drinking == '常喝') selected @endif>常喝</option>
                                </select>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r weui-v_t">抽菸 <span class=" weui-red weui-db">(必填)</span></td>
                             <td>
                                <select class="form-control m-bootstrap-select m_selectpicker" name="smoking">
                                    <option value="">請選擇</option>
                                    <option value="不抽" @if($umeta->smoking == '不抽') selected @endif>不抽</option>
                                    <option value="偶爾抽" @if($umeta->smoking == '偶爾抽') selected @endif>偶爾抽</option>
                                    <option value="常抽" @if($umeta->smoking == '常抽') selected @endif>常抽</option>
                                </select>
                             </td>
                         </tr>

                        @if (!$female)
                         <tr>
                             <td class="weui-t_r weui-v_t">年收</td>
                             <td>
                                <select class="form-control m-bootstrap-select m_selectpicker" name="income">
                                    <option value="">請選擇</option>
                                    <option value="50萬以下" @if($umeta->income == '50萬以下') selected @endif>50萬以下</option>
                                    <option value="50~100萬" @if($umeta->income == '50~100萬') selected @endif>50~100萬</option>
                                    <option value="100-200萬" @if($umeta->income == '100-200萬') selected @endif>100-200萬</option>
                                    <option value="200-300萬" @if($umeta->income == '200-300萬') selected @endif>200-300萬</option>
                                    <option value="300萬以上" @if($umeta->income == '300萬以上') selected @endif>300萬以上</option>
                                </select>
                             </td>
                         </tr>
                         <tr>
                             <td class="weui-t_r weui-v_t">資產  <span class=" weui-red weui-db">(必填)</span> </td>
                             <td>
                                  <input class="form-control  " type="number" name="assets" value="{{$umeta->assets}}">
                             </td>
                         </tr>
                         @endif
                    </table>
                    <div class=" weui-pb30 weui-t_c weui-mb30">
                         <input type="submit" class="btn btn-danger weui-f16 weui-box_s" value="更新資料">
                         <!-- <input type="button" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10" value="取消"> -->
                    </div>
                </form>
                </div>
                <div class="tab-pane fade" id="tab2">
                    <div class="weui-pt30 weui-pb30">
                         <h3 class="title_photo weui-f18 weui-f_b">上傳頭像</h3>
                         <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/header" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                            <input type="hidden" name="userId" value="{{$user->id}}">

                             <div class=" weui-mt30 weui-mb30 weui-pb30" >
                                <span class="weui-dnb weui-p_r upbtn weui-mr30 weui-mb10" style="padding-top: 0px!important; ">
                                    @if (isset($umeta->pic))
                                    <img src="{{$umeta->pic}}"  id="avatar" style="width: 179px;height: 179px;" >
                                        @if($user->meta_()->isAvatarHidden == 1) <p style="color: red; text-align: center; font-weight: bold;">大頭照已被隱藏</p> @endif
                                    @else
                                    <img src=""   id="avatar" style="width: 179px;height: 179px;" >
                                   @endif
                                  <!-- <p style="padding-top: 120px">點擊上傳圖片</p> -->
                                  <input type="file" name="image" id="image"  accept="image/png,image/jpeg" />
                                </span>
                                 <input type="submit" style="display: none;" id="imageBtn" value="上傳" class="btn btn-primary weui-f18">
                                 <span class="weui-f16 weui-v_m weui-c_9 weui-dnb">請點擊上傳圖片上傳200*200，大小不超過2MB的jpg、png、gif圖</span>
                             </div>
                        </form>
                        <div class="row">
                            
                        </div>

                         
                                <h3 class="title_photo weui-f18 weui-f_b">上傳生活照</h3>
                         <div class=" weui-mt30 weui-mb30">
                             
                            <div class=" weui-t_c weui-f18">
                                
                                <form id="myForm" action="/dashboard/image" method="post" >

                                     <input type="file" name="/images[]" multiple id="ssi-upload"/>
                                        
                                    <div class=" weui-pb30 weui-t_c weui-mb30 weui-mt30 ">
                                        <input type="submit" class="btn btn-danger weui-f16 weui-box_s" id="upBtn" value="上傳">
                                     </div>
                                </form>
                            </div>
                            <ul class="clearfix shz weui-mt20">
                                <?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
                                @foreach ($pics as $pic)
                                    <form id="del{{$pic->id}}"  method="POST" action="/dashboard/imagedel">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                        <input type="hidden" name="imgId" value="{{$pic->id}}">
                                        <li class="weui-fl weui-mb10 " style="    margin-right: 5px;">
                                            <img src="{{$pic->pic}}" >
                                            <p>  
                                            @if($pic->isHidden == 1) <span style="color: red; text-align: center; font-weight: bold;">此照片已被隱藏</span> @endif
                                            <!-- <span class="weui-dnb weui-c_p weui-v_m"><img src="/images/zhaopianguanli_10.png"></span> -->
                                            <!-- <span class="weui-dnb weui-c_p weui-v_m"><img src="/images/zhaopianguanli_12.png"></span> -->
                                            <span class="weui-dnb weui-c_p weui-f16 weui-v_m"><a href="#" name="submit" onclick="document.getElementById('del{{$pic->id}}').submit();return false" ><img src="/images/zhaopianguanli_07.png"></a></span>
                                            </p>
                                        </li>
                                    </form>
                                @endforeach
                            </ul>
                         </div>

                         
                    </div>
                </div>
                <div class="tab-pane fade" id="tab3">
                    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/user/password">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <table class="table01 weui-mt20 weui-mb30" width="100%">
                             <tr class="weui-bb">
                                 <td class="weui-v_t">當前密碼</td>
                                 <td>
                                 <input   class="form-control " type="password" name="old_password" placeholder="輸入妳的當前密碼">
                                 </td>
                             </tr>
                             <tr class="weui-bb">
                                 <td class="weui-v_t">新密碼</td>
                                 <td>
                                 <input   class="form-control " name="new_password" type="password" placeholder="選擇新密碼">
                                 </td>
                             </tr>
                             <tr class="weui-bb">
                                 <td class="weui-v_t">確認新密碼</td>
                                 <td>
                                 <input   class="form-control " name="new_password_confirmation" type="password"  placeholder="確認妳的新密碼">
                                 </td>
                             </tr>
                        </table>
                        <div class=" weui-pb30 weui-t_c weui-mb30 weui-pt20">
                             <input type="submit" class="btn btn-danger weui-f16 weui-box_s" value="更新資料">
                             <input type="button" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10" value="取消">
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="tab4">
                    <div class="minh">
                    <div class="row">   
                        <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/settings">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" >
                            <input type="hidden" name="userId" value="{{$user->id}}">

                            <div class="col-lg-8 col-md-8 col-sm-8 col-lg-push-2 col-md-push-2 col-sm-push-2 weui-f16">
                                <h3 class="weui-pb10 weui-pt30">信息通知</h3>
                                <select class="form-control select" name="notifmessage">
                                    <option value="收到即通知" @if($umeta->notifmessage == '收到即通知') selected @endif>收到即通知</option>
                                    <option value="每天通知一次" @if($umeta->notifmessage == '每天通知一次') selected @endif>每天通知一次</option>
                                    <option value="不通知" @if($umeta->notifmessage == '不通知') selected @endif>不通知</option>
                                </select>
                                @if ($user->isVip())
                                    <h3 class="weui-pb10 weui-pt30">信息通知</h3>
                                    <select class="form-control select" name="notifhistory">
                                        <option value="收到即通知" @if($umeta->notifhistory == '收到即通知') selected @endif>收到即通知</option>
                                        <option value="每天通知一次" @if($umeta->notifhistory == '每天通知一次') selected @endif>每天通知一次</option>
                                        <option value="不通知" @if($umeta->notifhistory == '不通知') selected @endif>不通知</option>
                                    </select>

                                    <h3 class="weui-pb10 weui-pt30">信息通知</h3>
                                    <select class="form-control select" name="notifhistory">
                                        <option value="顯示普通會員信件" @if($umeta->notifhistory == '顯示普通會員信件') selected @endif>顯示普通會員信件</option>
                                        <option value="顯示VIP會員信件" @if($umeta->notifhistory == '顯示VIP會員信件') selected @endif>顯示VIP會員信件</option>
                                        <option value="顯示全部會員信件" @if($umeta->notifhistory == '顯示全部會員信件') selected @endif>顯示全部會員信件</option>
                                    </select>
                                @endif
                                <div class=" weui-pt30 weui-mt30 weui-t_c">
                                    <input type="submit" class="btn btn-danger weui-f16 weui-box_s" value="确定">
                                </div>
                            </div>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<script type="text/javascript">
    location="/dashboard";
</script> 
@endif

@else

<div class="container weui-pt30">
         <div  class="line" >
               <h3 class="timetitle" >
               TA的照片
                </h3>
                <ul class="clearfix ta_photo ">
                    <?php $pics = \App\Models\MemberPic::getSelf($cur->id) ?>
                    @foreach ($pics as $k=>$pic)
                    {{$k}}
                        @switch($k)
                            @case(0)
                                <li class="weui-fl weui-ml5 weui-mt5" style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="275" width="193"></a>
                                </li>
                                @break
                            @case(1)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="275"></a>
                                </li>
                                @break
                            @case(2)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="275"></a>
                                </li>
                                @break
                            @case(3)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="275"></a>
                                </li>
                                @break
                            @case(4)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="191"></a>
                                </li>
                                @break
                            @case(5)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="191"></a>
                                </li>
                                @break
                            @case(6)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">
                                    <a href="#"><img src="{{$pic->pic}}" height="191"></a>
                                </li>
                                @break
                            @case(7)
                                <li class="weui-fl weui-ml5 weui-mt5"  style="float: left;  position: relative; ">  
                                    <p style="background: rgba(0,0,0,0.5);position: absolute;width: 100%;top: 0;right: 0;box-sizing: border-box;padding: 5px;color: #fff;text-align: right;">  
                                    @if($pic->isHidden == 1) <span style="color: red; text-align: center; font-weight: bold;">此照片已被隱藏</span> @endif
                                    <span class="weui-dnb weui-c_p weui-f16 weui-v_m"><a href="{{ route('reportPic', [$user->id, $pic->id, $cur->id]) }}">檢舉這張照片</a></span>
                                    </p>
                                    <img src="{{$pic->pic}}" height="191" width="280">
                                </li>
                                @break
                            @default
                        @endswitch
                    @endforeach
                </ul>
               <!-- <p class="weui-t_c weui-pt20 weui-pb20"><a href="#" class="btn btn-danger1">加载更多</a></p> -->
               <h3 class="timetitle" >
                   TA的基本資料
                </h3>
                <div class="weui-pl30 weui-pr30" style="background:#F3D6D8;">
                    <div class="weui-bb weui-pb20">
                        <div class="row jbzl">
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">縣   市：</span>@if (!$cmeta->isHideArea){{$cmeta->city}} {{$cmeta->area}}@else{{$cmeta->city}}@endif
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">預 算：</span>{{$cmeta->budget}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">出生日期：</span>{{$cmeta->birthdate}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">身   高：</span>{{ $cmeta->height }}CM
                             </div>
                            @if ($female)
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <span class="weui-c_6">體重 (kg)：</span>{{$cmeta->weight}}kg
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <span class="weui-c_6">Cup ：</span>{{$cmeta->cup}}
                                </div>
                            @endif
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">體 型：</span>{{$cmeta->body}}
                             </div>
                             <div class="col-lg-12 col-md-12 col-sm-12">
                             <span class="weui-c_6">關於我：</span>
                             {{$cmeta->about}}
                             </div>
                             <div class="col-lg-12 col-md-12 col-sm-12">
                             <span class="weui-c_6">期待的約會模式：</span>
                             {{$cmeta->style}}
                             </div>
                        </div>
                    </div>
                    <div class="row jbzl weui-pt20">

                            @if($female)
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <span class="weui-c_6">現 況：</span>{{$cmeta->situation}}
                            </div>
                            @endif
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">產 業：</span>{{$cmeta->domainType}} {{$cmeta->domain}}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">職 業：</span>{{$cmeta->occupation}}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">教 育：</span>{{$cmeta->education}}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">婚 姻：</span>{{$cmeta->marriage}}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">喝 酒：</span>{{ $cmeta->drinking }}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">抽 菸：</span>{{$cmeta->smoking}}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">年 收：</span>{{$cmeta->income}}
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                             <span class="weui-c_6">資 產：</span>{{$cmeta->assets}}
                         </div>
                      </div>
                </div>
                @if ($user->isVip())
                <h3 class="timetitle" >
                   TA的進階資料
                </h3>
                <div class="weui-pl30 weui-pr30" style="background:#F3D6D8;text-align: initial!important;">
                    <div class="weui-bb weui-pb20">
                        <div class="row jbzl">
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">帳號建立時間：</span>{{$cur->created_at}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">登入時間：</span>{{$cur->last_login}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">被收藏次數：</span>{{$cur->favedCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">收藏會員次數：</span>{{$cur->favCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">車馬費邀請次數：</span>{{$cur->tipCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">發信次數：</span>{{$cur->msgCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">過去7天發信次數：</span>{{$cur->msgsevenCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">是否封鎖我：</span>{{$cur->isBlocked($user->id)}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">是否看過我：</span>{{$cur->isSeen($user->id)}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">瀏覽其他會員次數：</span>{{$cur->visitCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">被瀏覽次數：</span>{{$cur->visitedCount()}}
                             </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                 <span class="weui-c_6">過去7天被瀏覽次數：</span>{{$cur->visitedsevenCount()}}
                             </div>
                            @if ($female && $user->isVip() && ($user->id != $cur->id))
                             <div class="col-lg-12 col-md-12 col-sm-12" style="display: none;" >
                                <div class="form-group m-form__group row vipadd" >
                                    <label class="col-form-label col-lg-2 col-sm-12">評價</label>
                                    <div class="col-lg-5 col-md-10 col-sm-12">
                                        <?php $comments = \App\Models\Tip::getAllComment($cur->id); ?>
                                        @foreach($comments as $comment)
                                            <span class="weui-c_6">{{ $comment->message }}</span>
                                        @endforeach
                                    </div>
                                </div>
                             </div>
                           @endif

                        </div>
                    </div>
                </div>
                @endif

                <form id='fengsuobtn' action="{!! url('dashboard/block') !!}" class="nav-link m-tabs__link" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="to" value="{{$cur->id}}">
                </form>
                <form id='jiechufengsuo'  action="{!! url('dashboard/unblock') !!}"  method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="to" value="{{$cur->id}}">
                </form>
                <form id="jianjubtn" action="{!! url('dashboard/report') !!}"  method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="to" value="{{$cur->id}}">           
                </form>
                <form id="shouchangbtn" action="{!! url('dashboard/fav') !!}" class="nav-link m-tabs__link" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="to" value="{{$cur->id}}">
                </form>

                <div class=" weui-pb30 weui-t_c weui-mb30 weui-pt30">
                    @if (!$user->isVip())
                     <a href="{{ url("dashboard/upgrade") }}" class="btn btn-danger1 weui-f16 weui-box_s weui-mb10">進階資料</a>
                    @endif
                        <a data-toggle="modal" href="#m_modal_1" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10  weui-mb10">发信件</a>
                        
                    @if ($user->isVip())
                        <a href="#" name="submit" onclick="document.getElementById('shouchangbtn').submit();return false" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10  weui-mb10 cmfbtn" >收藏</a>
                        <?php $isBlocked = \App\Models\Blocked::isBlocked($user->id, $cur->id);?>
                        @if(!$isBlocked)
                            <a href="#"  name="submit" onclick="document.getElementById('fengsuobtn').submit();return false" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10  weui-mb10 cmfbtn" >封鎖</a>
                        @else
                            <a href="#"  name="submit" onclick="document.getElementById('jiechufengsuo').submit();return false" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10  weui-mb10 cmfbtn" >解除封鎖</a>
                         @endif
                    @endif
                        <a href="#" name="submit" onclick="document.getElementById('jianjubtn').submit();return false" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10  weui-mb10">檢舉</a>
                        @if(!\App\Models\Tip::isComment($user->id, $cur->id) && $user->isVip() && \App\Models\Tip::isCommentNoEnd($user->id, $cur->id))
                                @include('partials.newtip-comment')
                        @else
                             <a data-toggle="modal" href="#myModal" class="btn btn-danger1 weui-f16 weui-box_s weui-ml10  weui-mb10">車馬費邀請</a>
                        @endif

                </div>
         </div>
    </div>



    <div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">發信給 @if(isset($cur)) {{ $cur->name }} @endif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="/dashboard/chat">
        <div class="modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
        <input type="hidden" name="userId" value="{{$user->id}}">
        <input type="hidden" name="to" value="@if(isset($cur)){{$cur->id}}@endif">
            <textarea class="form-control m-input" name="msg" id="msg" rows="4" maxlength="200"></textarea>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-danger">送出</button>
            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
        </div>
        </form>
    </div>
  </div>
</div>




<div class="modal fade" id="m_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">車馬費信息給 @if(isset($cur)) {{ $cur->name }} @elseif(isset($to)) {{ $to->name }} @endif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="/dashboard/chatpaycomment">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
        <input type="hidden" name="userId" value="{{$user->id}}">
        <input type="hidden" name="to" value="@if(isset($cur)){{$cur->id}} @elseif(isset($to)){{$to->id}}@endif">
            <textarea class="form-control m-input" name="msg" id="msg" rows="4"></textarea>
        </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-danger">送出</button>

        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
        </form>
      </div>
    </div>
  </div>
</div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header weui-f16 weui-white" style="background:#fa6175; border-radius:5px 5px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">車馬費說明</h4>
            </div>
            <form id="chemafeibtn" action=<?php echo Config::get('social.payment.actionURL'); ?>  method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                <input type="hidden" name="userId" value="{{$user->id}}">
                <input type="hidden" name="to" value="@if(isset($cur)){{$cur->id}}@endif">
                <input type=hidden name="MerchantNumber" value="761404">
                <input type=hidden name="OrderNumber" value="<?php echo $orderNumber; ?>">
                <input type=hidden name="OrgOrderNumber" value="SG-車馬費({{$user->id}})">
                <input type=hidden name="ApproveFlag" value="1">
                <input type=hidden name="DepositFlag" value="1">
                <input type=hidden class="device" name="iphonepage" value="">
                <input type=hidden name="Amount" value=<?php echo Config::get('social.payment.tip-amount'); ?>>
                <input type=hidden name="op" value="AcceptPayment">
                <input type=hidden name="checksum" value="<?php print md5("761404".$orderNumber.$code.Config::get('social.payment.tip-amount')); ?>">
                <input type=hidden name="ReturnURL" value="{{ route('chatpay') }}">
                <input type=hidden name="OrderURL" value=<?php echo Config::get('social.payment.orderURL'); ?>>
                <!-- <button class="invite" type="submit" style="background: none; border: none; padding: 0">
                    <i class="m-nav__link-icon flaticon-profile"></i>
                    <span class="m-nav__link-text">車馬費邀請</span>
                </button> -->
           



            <div class="modal-body">
                <h4>這筆費用是用來向女方表達見面的誠意。 </h4>
                <p class="weui-red01 weui-f_b weui-f16"><img src="/images/07_07.png">  若約見順利   </p>
                <h4>站方扣除288手續費，交付1500於女方  </h4>
                <p class="weui-red01 weui-f_b weui-f16"><img src="/images/07_07.png">  若有爭議（例如放鴿子）   </p>
                <h4>站方將依女方提供的證明資料，決定是否交付款項於女方。</h4>
                <p class="weui-red01 weui-f_b weui-f16"><img src="/images/07_07.png">  爭議處理   </p>
                <p>
                若女方提出證明文件，則交付款項予女方
                <br>
若女方於約見日五日內未提出相關證明文件，將扣除手續費後返回男方指定賬戶。  
</p>
                <p class="weui-orange weui-pt10">註意：此費用壹經匯出，即全權交由本站裁決處置。本人絕無異議，若不同意請按取消鍵返回。</p>
            </div>
            <div class="modal-footer weui-t_c">
                <button type="submit" class="btn btn-qd">確定</button>
                <button type="button" class="btn btn-qx" data-dismiss="modal">取消</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>



@endif

@stop


@section ('javascript')
<script type="text/javascript">
    var max_fields      = <?php echo \App\Models\MemberPic::getPicNums($user->id); ?>;
        max_fields = 15-max_fields;
        if(max_fields<0){
            max_fields=0;
            $('#upBtn').attr('disabled',true);
        }
</script>
@if(Session::has('announcement'))
<script type="text/javascript">
    alert('{{ Session::get('announcement') }}');
</script>
@endif

@if (str_contains(url()->current(), 'dashboard')  && ($user->id == $cur->id))
    <script src="js/ssi-uploader.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <script type="text/javascript">
         $(function(){
            var uploader = $('#ssi-upload').ssi_uploader({
                url: '/dashboard/image',
                inForm:true,
                maxFileSize:20000,
                locale: "zh_CN",
                data:{"nameId":"{{$user->id}}","_token":"{{ csrf_token() }}"},
            });

            $( "#myForm" ).on( "submit", function( event ) {
                event.preventDefault();
                uploader.data('ssi_upload').uploadFiles();
                uploader.on('onUpload.ssi-uploader',function(){
                    $( "#myForm" ).submit();
                });
            });

            $('#image').change(function () {
            var $file = $(this);
            var objUrl = $file[0].files[0];

            var name = $(this)[0].files[0].name;
            var ext=(/[.]/.exec(name)) ? /[^.]+$/.exec(name.toLowerCase()) : '';

            if(ext[0]!='jpg' && ext[0]!='png'&& ext[0]!='jpeg'){
                alert('请上传图片');
                return false;
            }

            //获得一个http格式的url路径:mozilla(firefox)||webkit or chrome
            var windowURL = window.URL || window.webkitURL;
            //createObjectURL创建一个指向该参数对象(图片)的URL
            var dataURL = windowURL.createObjectURL(objUrl);
            $("#avatar").attr("src", dataURL);
            $('#imageBtn').css('display','initial');
            });
        });
     </script>
    <script>

        var ysel = document.getElementsByName("year")[0],
                msel = document.getElementsByName("month")[0],
                dsel = document.getElementsByName("day")[0],
                firstTime = 0;
                for (var i = {{ date("Y") }}; i>=1930; i--){
                    var opt = new Option();
                    opt.value = opt.text = i;
                    if(opt.value == {{ $year }}){
                        opt.selected = true;
                    }
                    ysel.add(opt);
                }
                ysel.addEventListener("change",validate_date);
                msel.addEventListener("change",validate_date);

            function validate_date(){
                var y = +ysel.value, m = msel.value, d = dsel.value;
                if (m === "2") {
                    var mlength = 28 + (!(y & 3) && ((y % 100) !== 0 || !(y & 15)));
                }
                else {
                    var mlength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][m - 1];
                }
                dsel.length = 0;
                for(var i=1;i<=mlength;i++){
                    var opt=new Option();
                    opt.value = opt.text = i;
                    if(i==d) {
                        opt.selected=true;
                    }
                    if(opt.value == {{ $day }} && firstTime == 0){
                        opt.selected = true;
                        firstTime = 1;
                    }
                    dsel.add(opt);
                }
            }
            validate_date();

            var domainJson = ({
                '請選擇': ['請選擇'],
                '資訊科技': ['軟體網路','電信通訊','光電光學','半導體業','電腦週邊','電子相關'],
                '傳產製造': ['食品飲料','紡織相關','鞋類紡織','家具家飾','紙製製造','印刷相關','化學製造','石油製造','橡膠塑膠','非金屬製造','金屬製造','機械設備','電力機械','運輸工具','儀器醫材','育樂用品','其他製造','物流倉儲','營建土木','農林漁牧','礦業土石'],
                '工商服務': ['法律服務','會計服務','顧問研發','人力仲介','租賃業','汽車維修','徵信保全'],
                '民生服務': ['批發零售','金融機構','投資理財','保險業','電影業','旅遊休閒','美容美髮','醫療服務','環境衛生','住宿服務','餐飲服務'],
                '文教傳播': ['教育服務','印刷出版','藝文相關','廣播電視','廣告行銷','政治社福']
            });

            setDomain(1);

            function setDomain(initial) {
                var domain = eval(domainJson);
                var type = $("#domainType").val();
                //console.log('type is ' + type);
                if(!initial) {
                    $("#domain option").remove();
                    $("#domain").append('<option value="">請選擇</option>');
                }
                for (var i in domain[type]) {
                    //console.log(domain[type][i]);
                    if(domain[type][i] != $("#domain option:selected").val()) {
                        $("#domain").append('<option value="' + domain[type][i] + '">' + domain[type][i] + '</option>');
                        $("#domain").selectpicker('refresh');
                    }
                }
            }

            jQuery(document).ready(function(){
            @if(!$user->isAdmin())
                @if (!$umeta->isAllSet())
                        alert('請寫上基本資料');
                @elseif (empty($umeta->pic))
                        alert('請加上頭像照');
                @endif
            @endif

            

            $('.twzipcode').twzipcode({
                'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode']
            });
        });
    </script>
@endif

@stop


