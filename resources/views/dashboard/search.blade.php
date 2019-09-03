@extends('layouts.master')
@section('app-content')
<style>
    .MW4BW_ {
        position: absolute;
        left: 14px;
        /*top: 1rem;*/
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -moz-box-orient: vertical;
        -moz-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: start;
        -webkit-align-items: flex-start;
        -moz-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        z-index: 1;
    }
    ._3BQlNg {
        position: relative;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        height: 30px;
        padding: 0 3px;
        border-top-right-radius: .18rem;
        border-bottom-right-radius: .18rem;
        border-top-left-radius: .18rem;
        border-bottom-left-radius: .18rem;
        /*background: currentColor;*/
        background: -webkit-linear-gradient(left, #F45670, #FD7087);
        background: -o-linear-gradient(right, #F45670, #FD7087);
        background: -moz-linear-gradient(right, #F45670, #FD7087);
        background: linear-gradient(to right, #F45670, #FD7087);
        left: -.05rem;
    }
    .preferred{
        float: left;
        margin-left: 43px;
        margin-top: -30px;
    }
    @media  screen and (max-width: 575px) {
        input[type="radio"] {
            position: relative;
            top: 18px;
        }
    }
</style>
<?php $block_people =  Config::get('social.block.block-people'); ?>
<div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                搜索
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-widget3">
<form action="{!! url('dashboard/search') !!}" class="m-form m-form--fit m-form--label-align-right" method="GET">
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <div class="form-group m-form__group row" id="twzipcode"><label class="col-form-label col-lg-2 col-sm-12">縣市</label>
            <div class="col-lg-6 col-md-10 col-sm-12">
                <div class="twzip" data-role="county" data-name="county">
                </div>
                <div class="twzip" data-role="district" data-name="district">
                </div>
                 @if ($user->isVip())
                <div class="twzip"><input class="m-input" type="checkbox" id="pic" name="pic"> 照片</div>
                 @endif
            </div>
            </div>
            @if ($user->isVip())
            <div class="form-group m-form__group row">
                <label for="agefrom" class="col-lg-2 col-md-3 col-form-label">年齡</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
                     <input class="form-control m-input twzip" name="agefrom" type="number">
                </div>
                <label for="ageto" class="col-lg-2 col-md-3 col-form-label">至</label>
                <div class="col-lg-3 col-md-5 col-sm-6">

                     <input class="form-control m-input twzip" name="ageto" type="number"> 歲
                </div>
            </div>
            <div class="form-group m-form__group row">
                <label for="budget" class="col-lg-2 col-md-3 col-form-label">預算</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
                     <select class="form-control m-bootstrap-select m_selectpicker" name="budget">
                        <option value="">請選擇</option>
                        <option value="基礎">基礎</option>
                        <option value="進階">進階</option>
                        <option value="高級">高級</option>
                        <option value="最高">最高</option>
                        <option value="可商議">可商議</option>
                    </select>
                </div>
                <label for="body" class="col-lg-2 col-md-3 col-form-label">體型</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
                     <select class="form-control m-bootstrap-select m_selectpicker" name="body">
                        <option value="">請選擇</option>
                        <option value="瘦">瘦</option>
                        <option value="標準">標準</option>
                        <option value="微胖">微胖</option>
                        <option value="胖">胖</option>
                    </select>
                </div>
            </div>
            @if ($user->engroup == 1)
                        <div class="form-group m-form__group row">
                <label for="cup" class="col-lg-2 col-md-3 col-form-label">Cup</label>
                 <div class="col-lg-6 col-md-10 col-sm-12">
                    <select class="m-bootstrap-select m_selectpicker twzip" name="cup">
                        <option value="">請選擇</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                    </select>
                </div>
            </div>
            @else
                        <div class="form-group m-form__group row">
                <label for="marriage" class="col-lg-2 col-md-3 col-form-label">婚姻</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
                             <select class="form-control m-bootstrap-select m_selectpicker" name="marriage">
            <option value="">請選擇</option>
        <option value="已婚">已婚</option>
        <option value="分居">分居</option>
        <option value="單身">單身</option>
        <option value="有男友">有男友</option>
        </select>
                </div>
                <label for="income" class="col-lg-2 col-md-3 col-form-label">年收入</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
           <select class="form-control m-bootstrap-select m_selectpicker" name="income">
                <option value="">請選擇</option>
        <option value="50萬以下">50萬以下</option>
        <option value="50~100萬">50~100萬</option>
        <option value="100-200萬">100-200萬</option>
                <option value="200-300萬">200-300萬</option>
                        <option value="300萬以上">300萬以上</option>

        </select>
                </div>
            </div>
                                    <div class="form-group m-form__group row">
                <label for="drinking" class="col-lg-2 col-md-3 col-form-label">喝酒</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
                     <select class="form-control m-bootstrap-select m_selectpicker" name="drinking">
            <option value="">請選擇</option>
        <option value="不喝">不喝</option>
        <option value="偶爾喝">偶爾喝</option>
        <option value="常喝">常喝</option>
        </select>
                </div>
                <label for="smoking" class="col-lg-2 col-md-3 col-form-label">抽菸</label>
                 <div class="col-lg-3 col-md-5 col-sm-6">
                     <select class="form-control m-bootstrap-select m_selectpicker" name="smoking">
            <option value="">請選擇</option>
        <option value="不抽">不抽</option>
        <option value="偶爾抽">偶爾抽</option>
        <option value="常抽">常抽</option>
        </select>
                </div>
            </div>

        @endif
        <div class="form-group m-form__group row">
            <label for="user_engroup" class="col-lg-2 col-md-3 col-form-label">搜索排列順序(降冪)</label>
            <div class="col-lg-7 form-inline">
                <input class="form-control m-input" name="seqtime" value="1"
                   @if( empty( $_GET["seqtime"] ) || $_GET["seqtime"] == 1 ) checked @endif   checked  type="radio"> 登入時間&nbsp;&nbsp;
                <input class="form-control m-input" name="seqtime" value="2"
                  @if( !empty( $_GET["seqtime"] ) && $_GET["seqtime"] == 2 ) checked @endif  type="radio"> 註冊時間
            </div>
        </div>
        @endif
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions">
                <div class="row">
                    <div class="col-lg-2">
                    </div>
                    <div class="col-lg-7">
                        <button type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom">搜索</button>&nbsp;&nbsp;
                        <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
                    </div>
                </div>
            </div>
        </div>
</form>
                        </div>
                        @if (isset($_GET['county']) && isset($_GET['district']))
                        <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-widget3">
                            <div class="m-widget3__item">
                                <div class="m-widget3__header"></div>
                                <div class="m-widget3__info">
                                    <h4 style="margin-top: 10px;">
                                        <?php echo $_GET['county'].' '.$_GET['district'].':'; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        @endif

                    <div class="m-portlet__foot m-portlet__foot--fit" style="text-align: center">
                        <div class="row m-widget3">
                            @if (isset($_GET['_token']))
                            <?php
                            $district = "";
                            $county = "";
                            $cup = "";
                            $marriage = "";
                            $budget = "";
                            $income = "";
                            $smoking = "";
                            $drinking = "";
                            $photo = "";
                            $ageto = "";
                            $agefrom = "";
                            if (isset($_GET['district'])) $district = $_GET['district'];
                            if (isset($_GET['county'])) $county = $_GET['county'];
                            if (isset($_GET['cup'])) $cup = $_GET['cup'];
                            if (isset($_GET['marriage'])) $marriage = $_GET['marriage'];
                            if (isset($_GET['budget'])) $budget = $_GET['budget'];
                            if (isset($_GET['income'])) $income = $_GET['income'];
                            if (isset($_GET['smoking'])) $smoking = $_GET['smoking'];
                            if (isset($_GET['drinking'])) $drinking = $_GET['drinking'];
                            if (isset($_GET['pic'])) $photo = $_GET['pic'];
                            if (isset($_GET['ageto'])) $ageto = $_GET['ageto'];
                            if (isset($_GET['agefrom'])) $agefrom = $_GET['agefrom'];
                            if (isset($_GET['seqtime'])) $seqtime = $_GET['seqtime'];
                            $vis = \App\Models\UserMeta::search($county, $district, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $user->engroup, $user->city, $user->area, $user->domain, $user->domainType,$seqtime);
                            ?>
                            <?php $icc = 1; ?>
            @if (isset($vis) && sizeof($vis) > 0)
            @foreach ($vis as $vi)
                <div class="col-md-3 m-widget3__item"  style="border-bottom: none; margin:50px 0;">
                    <?php $visitor = $vi->user() ?>
                    <?php 
                        $umeta = $visitor->meta_();
                        if(isset($umeta->city)){
                            $umeta->city = explode(",",$umeta->city);
                            $umeta->area = explode(",",$umeta->area);
                        }
                    ?>
                    @if ($visitor !== null && $visitor->engroup != $user->engroup && $visitor->meta_() !== null)
                    <?php $vmeta = $visitor->meta_(); ?>
                    <? $data = \App\Services\UserService::checkRecommendedUser($visitor); ?>
                        @if($visitor->isVip())
                            <div class="MW4BW_">
                                @if ($visitor->engroup == 1) <a class="_3BQlNg bgXBUk"  style="color: white; font-weight: bold; font-size: 16px;">&nbsp;VIP&nbsp;</a> @endif @if(isset($data['description'])) <img src="{{ $data['button'] }}" alt="" height="30px" class="preferred"> @endif
                            </div>
                        @endif
                        <div class="card m-portlet m-portlet--mobile" style="display: inline-block; width: 100%; margin-bottom: 0; box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7);">
                            <!-- <div class="card m-portlet__body col-lg-3 col-md-4" style="display:inline-block; padding: 1rem; max-width: 26%">
		                         <a href="/user/view/{{$visitor->id}}"><img class="m-widget3__img" src="{{$visitor->meta_()->pic}}" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif alt=""></a>
		                     </div> -->
                            <a href="/user/view/{{$visitor->id}}"><img src="@if($visitor->meta_()->isAvatarHidden == 1) {{ 'makesomeerror' }} @else {{$visitor->meta_()->pic}} @endif" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif alt="" width="100%" height="100%"></a>
                			<div class="card-inner" style="display:inline-block;">
                			    <!-- <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif> -->
                                <p class="user-card" id="card-basic">{{ $visitor->name }}, {{ $visitor->meta_()->age() }}歲
                                    @if(\App\Models\Reported::cntr($visitor->id) >=  $block_people )
                                        <span class="m-widget3__username" style="color:red">(此人遭多人檢舉)</span>
                                    @endif
                                    <br />
                                
                                    @foreach($umeta->city as $key => $cityval)
                                    <p class="user-card" id="card-area">
                                        {{$umeta->city[$key]}} {{$umeta->area[$key]}}
                                    </p>
                                    @endforeach
                                                            <!-- {{$visitor->title}} -->
                                </p>
                		    </div>
                			<!-- <div class="m-portlet__body col-lg-4 col-sm-5" style="display:inline-block; max-width: 41%">
                			    <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif>
                				    <p>{{$visitor->meta_()->city}} {{$visitor->meta_()->area}}<br>年齡: {{$visitor->meta_()->age()}}<br>身高: {{$visitor->meta_()->height}}cm<br>體型: {{$visitor->meta_()->body}}</p>
                                </a>
                			</div> -->
		              </div>
                </div>
                            @if ($icc == 1) <?php $icc = 0; ?> @else <?php $icc = 1; ?>@endif
                            @endif
                            @endforeach
                            <div class="page m-form__actions">
                                {!! $vis->appends(request()->input())->links() !!}
                            </div>
                            @else
                            <div class="m-widget3__item">沒有資料！</div>
                            @endif
                            @else
                            <?php $visitors = \App\Models\User::findByEnGroup($user->engroup) ?>
                            <?php $icc = 1; ?>
                            @foreach ($visitors as $visitor)
                            @if (isset($_GET['_token']))
                                <?php $visitor = $vi->user() ?>
                            @endif
            @if ($visitor !== null && $visitor->meta_() !== null)
                <div class="col-md-3 m-widget3__item" style="border-bottom: none; margin:50px 0;">
                    <? $data = \App\Services\UserService::checkRecommendedUser($visitor); ?>
                        @if($visitor->isVip())
                            <div class="MW4BW_">
                                @if ($visitor->engroup == 1) <a class="_3BQlNg bgXBUk"  style="color: white; font-weight: bold; font-size: 16px;">&nbsp;VIP&nbsp;</a> @endif @if(isset($data['description'])) <img src="{{ $data['button'] }}" alt="" height="30px" class="preferred"> @endif
                            </div>
                        @endif
                        <div class="card m-portlet m-portlet--mobile" style="display: inline-block; width: 100%; margin-bottom: 0; box-shadow: 0 1px 15px 1px rgba(244, 164, 164, 0.7);">
                            <a href="/user/view/{{$visitor->id}}"><img src="@if($visitor->meta_()->isAvatarHidden == 1) {{ 'makesomeerror' }} @else {{$visitor->meta_()->pic}} @endif" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif alt="" width="100%" height="100%"></a>
                                <div class="card-inner" style="display:inline-block;">
                                    <!-- <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif> -->
                                        <p class="user-card" id="card-basic">{{ $visitor->name }}, {{ $visitor->meta_()->age() }}歲
                                            @if(\App\Models\Reported::cntr($visitor->id) >=  $block_people )
                                                <span class="m-widget3__username" style="color:red">(此人遭多人檢舉)</span>
                                            @endif
                                            <br />
                                        <p class="user-card" id="card-area">{{$visitor->meta_()->city}} {{$visitor->meta_()->area}}</p>
                                                                    <!-- {{$visitor->title}} -->
                                        </p>
                                    <!-- </a> -->
                                 </div>
                    <!-- <div class="m-portlet__body col-lg-4 col-md-4" style="display:inline-block; max-width: 41%">
                        <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif>
                        <p>{{$visitor->meta_()->city}} {{$visitor->meta_()->area}}<br>年齡: {{$visitor->meta_()->age()}}<br>身高: {{$visitor->meta_()->height}}cm<br>體型: {{$visitor->meta_()->body}}</p></a>
                    </div> -->
                      </div>
                </div>
                        @if ($icc == 1) <?php $icc = 0; ?> @else <?php $icc = 1; ?>@endif
                        @endif
                        @endforeach
                        <div class="page m-form__actions row">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                {!! $visitors->appends(request()->input())->links() !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
@stop
@section ('javascript')
<script src="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.0.0/cropper.min.js"></script>
<script>
		$(document).ready(function(){
			var BootstrapDatepicker=function(){var t=function(){$("#m_datepicker_1, #m_datepicker_1_validate").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_1_modal").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_2, #m_datepicker_2_validate").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_2_modal").datepicker({todayHighlight:!0,orientation:"bottom left",templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_3, #m_datepicker_3_validate").datepicker({todayBtn:"linked",clearBtn:!0,todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_3_modal").datepicker({todayBtn:"linked",clearBtn:!0,todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_1").datepicker({orientation:"top left",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_2").datepicker({orientation:"top right",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_3").datepicker({orientation:"bottom left",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_4_4").datepicker({orientation:"bottom right",todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_5").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}}),$("#m_datepicker_6").datepicker({todayHighlight:!0,templates:{leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'}})};return{init:function(){t()}}}();jQuery(document).ready(function(){BootstrapDatepicker.init()});
			var BootstrapSelect=function(){var t=function(){$(".m_selectpicker").selectpicker()};return{init:function(){t()}}}();jQuery(document).ready(function(){BootstrapSelect.init()});
			$('#twzipcode').twzipcode({
				'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode'], onCountySelect: function() {
				    $("select[name='district']").prepend('<option selected value="">全市</option>');
				}
			});
			$('input[name="zipcode"]').remove();
		});
	</script>
@stop
