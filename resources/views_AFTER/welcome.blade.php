@include('partials.newheader')
<body>
<div class="part01">
	<div class="weui-pt30 weui-pb30 container">
        @include('layouts.newnavigation')
    		
	</div>
    <div class="part_box">
         <div>
              <h2 class="f40">成功人士认识魅力甜心</h2>
              <h3 class="weui-f28">全台人数最多的<br>Sugar Daddy/Baby 包养网站</h3>
              <h4 class="weui-f18 weui-pt30 wyz">我要找：</h4>
              <p>
              	@if(Auth::user())
					<a href="{!! url('dashboard/search') !!}" class="ddbtn weui-f18"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
                 	<a href="{!! url('dashboard/search') !!}" class="ddbtn ddbtn_01 weui-f18 weui-ml15"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
				@else
					<a href="{!! url('register') !!}" class="ddbtn weui-f18"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
                 	<a href="{!! url('register') !!}" class="ddbtn ddbtn_01 weui-f18 weui-ml15"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
				@endif
              </p>
         </div>
    </div>
</div>

<div class="container weui-pt30 weui-pb30">
     <h2 class="weui-t_c f40">
         如果您是富豪新贵
         <p><img src="images/line.png"></p>
     </h2>
     <div class="row weui-pt30">
         <div class="col-lg-5 col-md-5 col-sm-5 weui-t_c">
              <img src="images/shouye_22.png" class="m_img">
              <div class="m_bgcolor">
              <h3 class="weui-f16">薇雨</h3>
              <p>想找耐心陪我且经济上支持我的dadd</p>
              <p><a href="#" class="weui-red01">查看详情</a></p>
              </div>
         </div>
         <div class="col-lg-7 col-md-7 col-sm-7 weui-pt30 weui-c_6">
             <div class="man">
             <div class="media media_p">
                 <div class="media-left">
                      <span class="ico ico-1 ico01"></span>
                 </div>
                 <div class="media-body weui-pl15">
                      <h4 class="media-heading weui-f18">直接的经济援助-包养</h4>
                      <p class="weui-f16">让你不再为家庭困窘的经济烦恼</p>
                 </div>
             </div>
             <div class="media media_p">
                 <div class="media-left">
                      <span class="ico ico-1 ico02"></span>
                 </div>
                 <div class="media-body weui-pl15">
                      <h4 class="media-heading weui-f18">一圆各种梦想</h4>
                      <p class="weui-f16">有daddy包养网的帮忙，把握青春，出国留学，上课进修，提升自我能力。</p>
                 </div>
             </div>
             <div class="media media_p">
                 <div class="media-left">
                      <span class="ico ico-1 ico03"></span>
                 </div>
                 <div class="media-body weui-pl15">
                      <h4 class="media-heading weui-f18">被宠被疼</h4>
                      <p class="weui-f16">不同于身旁的小屁孩，daddy总是成熟稳重，疼你宠你，包容你一切的任性。</p>
                 </div>
             </div>
             </div>
         </div>
         
     </div>
</div>

<div class=" weui-bgcolor">
        <div class="container weui-pt30 weui-pb30">
             <h2 class="weui-t_c f40">
                 如果您是魅力宝贝
                 <p><img src="images/line.png"></p>
             </h2>
             <div class="row weui-pt30">
                 <div class="col-lg-7 col-md-7 col-sm-7 weui-pt30 weui-c_6">
                     <div class="media media_p">
                         <div class="media-left">
                              <span class="ico ico01"></span>
                         </div>
                         <div class="media-body weui-pl15">
                              <h4 class="media-heading weui-f18">直接的经济援助-包养</h4>
                              <p class="weui-f16">让你不再为家庭困窘的经济烦恼</p>
                         </div>
                     </div>
                     <div class="media media_p">
                         <div class="media-left">
                              <span class="ico ico02"></span>
                         </div>
                         <div class="media-body weui-pl15">
                              <h4 class="media-heading weui-f18">一圆各种梦想</h4>
                              <p class="weui-f16">有daddy包养网的帮忙，把握青春，出国留学，上课进修，提升自我能力。</p>
                         </div>
                     </div>
                     <div class="media media_p">
                         <div class="media-left">
                              <span class="ico ico03"></span>
                         </div>
                         <div class="media-body weui-pl15">
                              <h4 class="media-heading weui-f18">被宠被疼</h4>
                              <p class="weui-f16">不同于身旁的小屁孩，daddy总是成熟稳重，疼你宠你，包容你一切的任性。</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-5 col-md-5 col-sm-5 weui-t_c">
                      <img src="images/shouye_30.png" class="m_img">
                      <h3 class="weui-f16">薇雨</h3>
                      <p>想找耐心陪我且经济上支持我的dadd</p>
                      <p><a href="#" class="weui-red01">查看详情</a></p>
                 </div>
             </div>
        </div>
        
        </div>
    <div class="td">
        <div class="container">
            <div class="row">
                 <div class="col-lg-4 col-md-4">
                      <dl class="td_bg1">
                          <dt class="weui-f32">快速</dt>
                          <dd>本站注册快速，一分钟即可注册完成，使用服务。</dd>
                      </dl>
                 </div>
                 <div class="col-lg-4 col-md-4">
                      <dl class="td_bg2">
                          <dt class="weui-f32">安全</dt>
                          <dd>本站不会与任何其他网站交换资料。事实上，您只需要一个电子邮箱注册，其他不需要任何留下私人资料。</dd>
                      </dl>
                 </div>
                 <div class="col-lg-4 col-md-4">
                      <dl class="td_bg3">
                          <dt class="weui-f32">高品质</dt>
                          <dd>全台最大的Sugar Daddy/Baby 包养网站，所有会员均经过的审核机制。杜绝非法以及別有用心的使用者。</dd>
                      </dl>
                 </div>
            </div>
        </div>
    </div>
    

<!-- 
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-footer--push" >
	<div class="m-grid m-grid--hor m-grid--root m-page">
		
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" style="background-color: #f7eeeb">
			<div class="m-grid__item m-grid__item--fluid m-login__wrapper" style="padding: 0px;">
				<div>
					<div class="row bg-idx">
					</div>
					<div class="row" style="margin-top: 100px; z-index: 2">
						<img src="img/banner.jpg" style="text-align: center; margin:0 auto; border-radius: 30px; height: 100%; width: 84%; z-index: 2; box-shadow: inset 0 0 0 500px rgba(255, 0150, 0.3);">
					
						<h1 class="t-idx">成功人士認識魅力甜心 <i class=" ti-idx fa fa-heart"></i></h1>
							<h3 class="st-idx">全台人數最多的Sugar Daddy/Baby 包養網站。<h3>
							<h4 class="f-idx">我要找:</h4>
							@if(Auth::user())
								<a href="{!! url('dashboard/search') !!}" class="btn btn-idx btn-success" style="position: absolute; z-index: 4; margin-left: 74%; margin-top: 15%; border-radius: 30px;"> 甜心爹地 </a>
								<a href="{!! url('dashboard/search') !!}" class="btn btn2-idx btn-outline-danger"> 甜心寶貝 </a>
							@else
								<a href="{!! url('register') !!}" class="btn btn-idx btn-success" style="position: absolute; z-index: 4; margin-left: 74%; margin-top: 15%; border-radius: 30px;"> 甜心爹地 </a>
								<a href="{!! url('register') !!}" class="btn btn2-idx btn-outline-danger"> 甜心寶貝 </a>
							@endif

					</div>
					<div class="row justify-content-center" style="margin-top: 60px">
						<div class="col-lg-3" style="text-align: center">
							<div class="m-portlet m-portlet--mobile m-portlet-idx">
								<div class="m-portlet__body">
									<p><img class="img-idx" src="img/sugar_0926-20.png"></p>
									<h3>快速</h3>
									<p>本站註冊快速，一分鐘即可註冊完成，使用服務。</p>
								</div>
							</div>
						</div>
						<div class="col-lg-3" style="text-align: center">
							<div class="m-portlet m-portlet--mobile m-portlet-idx" >
								<div class="m-portlet__body">
									<p><img class="img-idx" src="img/sugar_0926-23.png" style="margin-left: -9%; margin-top: -7%; z-index: 2; position: absolute; margin-bottom: 0px; width: 45%"><img class="img-idx" src="img/sugar_0926-21.png"></p>
									<h3>安全</h3>
									<p>本站不會與任何其他網站交換資料。
事實上，您只需要一個電子郵箱註冊，其他不需要任何留下私人資料。</p>
								</div>
							</div>
						</div>
						<div class="col-lg-3" style="text-align: center">
							<div class="m-portlet m-portlet--mobile m-portlet-idx">
								<div class="m-portlet__body">
									<p><img class="img-idx" src="img/sugar_0926-22.png"></p>
									<h3>高品質</h3>
									<p>全台最大的Sugar Daddy/Baby 包養網站，所有會員均經過嚴密的審核機制。杜絕非法以及別有用心的使用者。</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-1">
							<hr class="sg-hr">
						</div>
						<div class="col-lg-3">
						<img class="img-idx" src="img/sugar_0926-23.png" width="10%"><h4 style="display: inline-block"> 如果您是富豪新貴</h4>
						</div>
						<div class="col-lg-8">
							<hr class="sg-hr">
						</div>
					</div>
					<div class="row justify-content-center">
					<div id="sugarbabby" class="carousel slide col-lg-2" data-ride="carousel">
<div class="carousel-inner" role="listbox">
<div class="carousel-item active">
<?php $user1 = \App\Models\User::getRand() ?>
@if(isset($user1))
  <img class="d-block img-fluid" src="{{$user1->meta_()->pic}}">
  <div class="carousel-caption cara-g-idx d-none d-md-block">
  <h3>{{$user1->name}} <i class="fa fa-heart"></i></h3>
  <p>{{$user1->title}}</p>
	</div>
	@endif
</div>
<div class="carousel-item">
<?php $user2 = \App\Models\User::getRand() ?>
@if(isset($user2))
  <img class="d-block img-fluid" src="{{$user2->meta_()->pic}}">
  <div class="carousel-caption cara-g-idx d-none d-md-block">
  <h3>{{$user2->name}} <i class="fa fa-heart"></i></h3>
  <p>{{$user2->title}}</p>
	</div>
	@endif
</div>
<div class="carousel-item">
<?php $user3 = \App\Models\User::getRand() ?>
@if(isset($user3))
  <img class="d-block img-fluid" src="{{$user3->meta_()->pic}}">
  <div class="carousel-caption cara-g-idx d-none d-md-block">
  <h3>{{$user3->name}} <i class="fa fa-heart"></i></h3>
  <p>{{$user3->title}}</p>
	</div>
@endif
</div>
</div>
<a class="carousel-control-prev" href="#sugarbabby" role="button" data-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#sugarbabby" role="button" data-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
<div style="text-align:center"><a href="{!! url('dashboard/search') !!}">查看全部 >></a></div>
</div>
					<div class="col-lg-7">
						<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
							<div class="m-demo__preview">
								<div class="m-list-timeline">
									<div class="m-list-timeline__items">
										<div class="m-list-timeline__item">
											<span class="m-list-timeline__badge m-list-timeline__badge--danger"></span>
											<span class="m-list-timeline__text">
												<h5>最多的甜心寶貝</h5>
												<p>全台最大的交友包養網站，有最多的甜心寶貝</p>
											</span>
										</div>
										<div class="m-list-timeline__item">
											<span class="m-list-timeline__badge m-list-timeline__badge--danger"></span>
											<span class="m-list-timeline__text">
												<h5>直接坦白</h5>
												<p>這個包養網站就是讓大家直接坦白，不拐彎抹角，節省您兜圈子的時間。</p>
										</span>
										</div>
										<div class="m-list-timeline__item">
											<span class="m-list-timeline__badge m-list-timeline__badge--danger"></span>
											<span class="m-list-timeline__text">
											<h5>保密隱私</h5>
											<p>您可不需留下真實資料，站台並保證絕不洩漏會員資料，</p>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>

</div>
					<div class="row" style="margin-top: 30px;">
						<div class="col-lg-1">
							<hr class="sg-hr">
						</div>
						<div class="col-lg-3">
						<img src="img/sugar_0926-23.png" width="10%"><h4 style="display: inline-block"> 如果您是魅力寶貝</h4>
						</div>
						<div class="col-lg-8">
							<hr class="sg-hr">
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-lg-7">
							<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                            	<div class="m-demo__preview">
                        			<div class="m-list-timeline">
                                    	<div class="m-list-timeline__items">
                                        	<div class="m-list-timeline__item">
                                            	<span class="m-list-timeline__badge"></span>
												<span class="m-list-timeline__text">
													<h5>直接的經濟援助-包養</h5>
													<p>讓你不再為家庭困窘的經濟煩惱</p>
												</span>
                                      	  </div>
                                      	  <div class="m-list-timeline__item">
                                       	     <span class="m-list-timeline__badge"></span>
                                       	     <span class="m-list-timeline__text">
													<h5>一圓各種夢想</h5>
													<p>有daddy包養網的幫忙，把握青春，出國留學，上課進修，提昇自我能力。</p>
											</span>
                                     	   </div>
                                      	  <div class="m-list-timeline__item">
                                       	     <span class="m-list-timeline__badge"></span>
                                        	    <span class="m-list-timeline__text">
												<h5>被寵被疼</h5>
												<p>不同於身旁的小屁孩，daddy總是成熟穩重，疼你寵你，包容你一切的任性。</p>
											</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>
						<div id="sugardaddy" class="carousel slide col-lg-2" data-ride="carousel">
							<div class="carousel-inner" role="listbox">
								<div class="carousel-item active">
									<?php $userD1 = \App\Models\User::getRandD() ?>
									@if(isset($userD1))
									<img class="d-block img-fluid" src="{{$userD1->meta_()->pic}}">
									<div class="carousel-caption cara-b-idx d-none d-md-block">
										<h3>{{$userD1->name}} <i class="fa fa-heart"></i></h3>
										<p>{{$userD1->title}}</p>
									</div>
									@endif
								</div>
								<div class="carousel-item">
								<?php $userD2 = \App\Models\User::getRandD() ?>
								@if(isset($userD2))
								<img class="d-block img-fluid" src="{{$userD2->meta_()->pic}}">
								<div class="carousel-caption cara-b-idx d-none d-md-block">
								<h3>{{$userD2->name}} <i class="fa fa-heart"></i></h3>
								<p>{{$userD2->title}}</p>
								</div>
								@endif
								</div>
								<div class="carousel-item">
								<?php $userD3 = \App\Models\User::getRandD() ?>
								@if(isset($userD3))
								<img class="d-block img-fluid" src="{{$userD3->meta_()->pic}}">
								<div class="carousel-caption cara-b-idx d-none d-md-block">
								<h3>{{$userD3->name}} <i class="fa fa-heart"></i></h3>
								<p>{{$userD3->title}}</p>
								</div>
								@endif
							</div>
							<a class="carousel-control-prev" href="#sugardaddy" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#sugardaddy" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
							<div style="text-align:center"><a href="{!! url('dashboard/search') !!}">查看全部 >></a></div>
							</div>

			</div>
		</div>
	</div>
	<div style="margin-bottom: 10px;"></div> -->
        @include('partials.newfooter')
        @include('partials.newscripts')
</body>
</html>
