@extends('layouts.newmaster')

@section('app-content')

<?php


$block_people =  Config::get('social.block.block-people'); 
$code = Config::get('social.payment.code');
$umeta = $user->meta_();

?>
                        <div class="photo weui-t_c">
                            <img src="{{$umeta->pic}}">
                            <p class="weui-pt20 weui-f18">{{$user->name}}</p>
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
                        </div>
        </div>
  </div>




    <div class="container weui-pt30">
<div class="weui-box_s weui-p20">
    <form action="{!! url('dashboard/search') !!}" class="m-form m-form--fit m-form--label-align-right" method="GET">
    <input type="hidden" name="_token" value="{{csrf_token()}}" >

             <div class="form-inline">
                 <div class="form-group weui-pr10">
                      縣市 
                 </div>
                 <div class="form-group"  id="twzipcode" >
                        <div class="twzip" data-role="county" data-name="county">
                        </div>
                        <div class="twzip" data-role="district" data-name="district">
                        </div>
                         @if ($user->isVip())
                        <div class="twzip"><input class="m-input" type="checkbox" id="pic" name="pic"> 照片</div>
                         @endif
                 </div>

                <div class="form-group weui-pr10">
                      縣市 
                 </div>

                 <div class="form-group">

                     <select class="form-control m-bootstrap-select m_selectpicker" name="budget">

                        <option value="">請選擇</option>

                        <option value="基礎">基礎</option>

                        <option value="進階">進階</option>

                        <option value="高級">高級</option>

                        <option value="最高">最高</option>

                        <option value="可商議">可商議</option>

                    </select>

                </div>

                @if ($user->isVip())
                <div class="form-group weui-pr10">年齡</div>

                
                 <div class="form-group">
                     <input class="form-control m-input twzip" name="agefrom" type="number">

                </div>


                 至 

                 <div class="form-group">



                     <input class="form-control m-input twzip" name="ageto" type="number"> 歲

                </div>


            </br>
               <div class="form-group weui-pr10">預算</div>

               <div class="form-group">

                     <select class="form-control m-bootstrap-select m_selectpicker" name="budget">

                        <option value="">請選擇</option>

                        <option value="基礎">基礎</option>

                        <option value="進階">進階</option>

                        <option value="高級">高級</option>

                        <option value="最高">最高</option>

                        <option value="可商議">可商議</option>

                    </select>

                </div>

                <div class="form-group weui-pr10">體型</div>

                  <div class="form-group">

                     <select class="form-control m-bootstrap-select m_selectpicker" name="body">

                        <option value="">請選擇</option>

                        <option value="瘦">瘦</option>

                        <option value="標準">標準</option>

                        <option value="微胖">微胖</option>

                        <option value="胖">胖</option>

                    </select>

                </div>


            @if ($user->engroup == 1)


               <div class="form-group weui-pr10">Cup</div>

                <div class="form-group">

                    <select class="form-control m-bootstrap-select m_selectpicker " name="cup">

                        <option value="">請選擇</option>

                        <option value="A">A</option>

                        <option value="B">B</option>

                        <option value="C">C</option>

                        <option value="D">D</option>

                        <option value="E">E</option>

                        <option value="F">F</option>

                    </select>

                </div>


            @else


                <div class="form-group weui-pr10">婚姻</div>

                    <div class="form-group">

                             <select class="form-control m-bootstrap-select m_selectpicker" name="marriage">

            <option value="">請選擇</option>

        <option value="已婚">已婚</option>

        <option value="分居">分居</option>

        <option value="單身">單身</option>

        <option value="有男友">有男友</option>

        </select>

                </div>
            </br>
                <div class="form-group weui-pr10">年收入</div>

           <div class="form-group">

           <select class="form-control m-bootstrap-select m_selectpicker" name="income">

                <option value="">請選擇</option>

        <option value="50萬以下">50萬以下</option>

        <option value="50~100萬">50~100萬</option>

        <option value="100-200萬">100-200萬</option>

                <option value="200-300萬">200-300萬</option>

                        <option value="300萬以上">300萬以上</option>



        </select>

                </div>



                <div class="form-group weui-pr10">喝酒</div>

                   <div class="form-group">

                     <select class="form-control m-bootstrap-select m_selectpicker" name="drinking">

            <option value="">請選擇</option>

        <option value="不喝">不喝</option>

        <option value="偶爾喝">偶爾喝</option>

        <option value="常喝">常喝</option>

        </select>

                </div>

                <div class="form-group weui-pr10">抽菸</div>

                <div class="form-group">

                     <select class="form-control m-bootstrap-select m_selectpicker" name="smoking">

            <option value="">請選擇</option>

        <option value="不抽">不抽</option>

        <option value="偶爾抽">偶爾抽</option>

        <option value="常抽">常抽</option>

        </select>

                </div>


        @endif

        @endif

              
                 <div class="form-group">
                     <input type="submit" class="btn btn-danger weui-f16 weui-box_s" value="搜索">

                    <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
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


<div class="row" style="margin-bottom: 30px;" >
             
    
           

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
                            if (isset($_GET['photo'])) $photo = $_GET['photo'];
                            if (isset($_GET['ageto'])) $ageto = $_GET['ageto'];
                            if (isset($_GET['agefrom'])) $agefrom = $_GET['agefrom'];
                            $vis = \App\Models\UserMeta::search($county, $district, $cup, $marriage, $budget, $income, $smoking, $drinking, $photo, $agefrom, $ageto, $user->engroup, $user->city, $user->area, $user->domain, $user->domainType);
                            ?>
                            <?php $icc = 1; ?>
            @if (isset($vis) && sizeof($vis) > 0)
            @foreach ($vis as $vi)
                <div class="col-lg-3 col-sm-3 col-md-3 col-xs-6 weui-pt30">
                     <?php $visitor = $vi->user() ?>
                     @if ($visitor !== null && $visitor->engroup != $user->engroup && $visitor->meta_() !== null)
                    <?php $vmeta = $visitor->meta_(); ?>
                        <div class="weui-p_r">
                            <!-- <div class="card m-portlet__body col-lg-3 col-md-4" style="display:inline-block; padding: 1rem; max-width: 26%">
                                 <a href="/user/view/{{$visitor->id}}"><img class="m-widget3__img" src="{{$visitor->meta_()->pic}}" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif alt=""></a>
                             </div> -->
                            <a href="/user/view/{{$visitor->id}}" class=" weui-db prolist weui-bod " >
                                <img src="@if($visitor->meta_()->isAvatarHidden == 1) {{ 'makesomeerror' }} @else {{$visitor->meta_()->pic}} @endif" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif alt=""  width="100%">
                                <!-- <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif> -->
                                    <dl>
                                        <dt class="weui-f18">{{ $visitor->name }} @if ($visitor->isVip()) (VIP) @endif</dt>
                                        <dd>{{ $visitor->meta_()->age() }}歲 |  {{$visitor->meta_()->city}} {{$visitor->meta_()->area}}</dd>
                                        @if(\App\Models\Reported::cntr($visitor->id) >=  $block_people )
                                            <dd  style="color:red">(此人遭多人檢舉)</dd>
                                        @endif
                                    </dl>

                                </a>


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
                             <nav aria-label="Page navigation" class="weui-t_c weui-pb15">
                                {!! $vis->appends(request()->input())->links() !!}
                            </nav>
                            @else
                            <div class="m-widget3__item">沒有資料！</div></div>
                            @endif
                            @else
                            <?php $visitors = \App\Models\User::findByEnGroup($user->engroup) ?>
                            <?php $icc = 1; ?>
                            @foreach ($visitors as $visitor)
                            @if (isset($_GET['_token']))
                                <?php $visitor = $vi->user() ?>
                            @endif
                            
            @if ($visitor !== null && $visitor->meta_() !== null)
                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-6 weui-pt30">
                            <div class="weui-p_r">
                                <!-- <div class="m-portlet__body col-lg-3 col-md-3" style="display:inline-block; padding: 1rem; max-width: 26%">
                                     <a href="/user/view/{{$visitor->id}}"><img class="m-widget3__img" src="{{$visitor->meta_()->pic}}" onerror="this.src='/img/male-avatar.png'" alt=""></a>
                                </div> -->
                                <a href="/user/view/{{$visitor->id}}" class=" weui-db prolist weui-bod ">
                                    <img src="@if($visitor->meta_()->isAvatarHidden == 1) {{ 'makesomeerror' }} @else {{$visitor->meta_()->pic}} @endif" @if ($visitor->engroup == 1) onerror="this.src='/img/male-avatar.png'" @else onerror="this.src='/img/female-avatar.png'" @endif alt="" width="100%"  >
                                        <!-- <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif> -->

                                        <dl>
                                            <dt class="weui-f18">{{ $visitor->name }} @if ($visitor->isVip()) (VIP) @endif</dt>
                                            <dd>{{ $visitor->meta_()->age() }}歲 |  {{$visitor->meta_()->city}} {{$visitor->meta_()->area}}</dd>
                                            @if(\App\Models\Reported::cntr($visitor->id) >=  $block_people )
                                                <dd  style="color:red">(此人遭多人檢舉)</dd>
                                            @endif
                                        </dl>
                                </a>
                                        <!-- </a> -->

                        <!-- <div class="m-portlet__body col-lg-4 col-md-4" style="display:inline-block; max-width: 41%">
                            <a href="/user/view/{{$visitor->id}}" @if ($icc == 1) style="color: white" @endif>
                            <p>{{$visitor->meta_()->city}} {{$visitor->meta_()->area}}<br>年齡: {{$visitor->meta_()->age()}}<br>身高: {{$visitor->meta_()->height}}cm<br>體型: {{$visitor->meta_()->body}}</p></a>
                        </div> -->
                          </div>
                    </div>

                            @if ($icc == 1) <?php $icc = 0; ?> @else <?php $icc = 1; ?>@endif
                            @endif
                            @endforeach
                            

                             <nav aria-label="Page navigation" class="weui-t_c weui-pb15">
                               {!! $visitors->appends(request()->input())->links() !!}
                            </nav>

                            @endif
</div>
</div>

@stop

@section ('javascript')
<script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
<script>
		$(document).ready(function(){
			$('#twzipcode').twzipcode({
				'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode'], onCountySelect: function() {
				    $("select[name='district']").prepend('<option selected value="">全市</option>');
				}
			});
			$('input[name="zipcode"]').remove();
		});
</script>
@stop
