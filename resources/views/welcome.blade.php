@include('partials.header')
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-footer--push" >
<div class="m-grid m-grid--hor m-grid--root m-page">
	@include('layouts.navigation')
	<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" style="background-color: #f7eeeb">
		<div class="m-grid__item m-grid__item--fluid m-login__wrapper" style="padding: 0px;">
			<div>
				<div class="row bg-idx">
				</div>
				<div class="row" style="margin-top: 100px; z-index: 2">
					<img src="img/banner.jpg" style="text-align: center; margin:0 auto; border-radius: 30px; height: 100%; width: 84%; z-index: 2; box-shadow: inset 0 0 0 500px rgba(255, 0150, 0.3);">
					<!--	<div style="margin-left: 63%; z-index: 3; background-color: #ffcdd2; opacity: 0.45; width: 30.5%; border-radius: 0px 30px 30px 0px;"></div>-->
					<h1 class="t-idx">成功人士認識魅力甜心 <i class=" ti-idx fa fa-heart"></i></h1>
					<h3 class="st-idx">全台人數最多的Sugar Daddy/Baby 包養網站。不管你是富豪新貴，或是甜心寶貝，都可以在這找到屬於你的對象。<h3>
							<h4 class="f-idx">我要找:</h4>
							<a href="{!! url('dashboard/search') !!}" class="btn btn-idx btn-success" style="position: absolute; z-index: 4; margin-left: 74%; margin-top: 15%; border-radius: 30px;"> 甜心爹地 </a>
							<a href="{!! url('dashboard/search') !!}" class="btn btn2-idx btn-outline-danger"> 甜心寶貝 </a>
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
								<?php $user1 = $imgUserF[0]; ?>
								@if(isset($user1))
									<img class="d-block img-fluid" src="{{$user1->pic}}">
									<div class="carousel-caption cara-g-idx d-none d-md-block">
										<h3>{{$user1->name}} <i class="fa fa-heart"></i></h3>
										<p>{{$user1->title}}</p>
									</div>
								@endif
							</div>
							<div class="carousel-item">
								<?php $user2 = $imgUserF[1]; ?>
								@if(isset($user2))
									<img class="d-block img-fluid" src="{{$user2->pic}}">
									<div class="carousel-caption cara-g-idx d-none d-md-block">
										<h3>{{$user2->name}} <i class="fa fa-heart"></i></h3>
										<p>{{$user2->title}}</p>
									</div>
								@endif
							</div>
							<div class="carousel-item">
								<?php $user3 = $imgUserF[2]; ?>
								@if(isset($user3))
									<img class="d-block img-fluid" src="{{$user3->pic}}">
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
								<?php $userD1 = $imgUserM[0]; ?>
								@if(isset($userD1))
									<img class="d-block img-fluid" src="{{$userD1->pic}}">
									<div class="carousel-caption cara-b-idx d-none d-md-block">
										<h3>{{$userD1->name}} <i class="fa fa-heart"></i></h3>
										<p>{{$userD1->title}}</p>
									</div>
								@endif
							</div>
							<div class="carousel-item">
								<?php $userD2 = $imgUserM[1]; ?>
								@if(isset($userD2))
									<img class="d-block img-fluid" src="{{$userD2->pic}}">
									<div class="carousel-caption cara-b-idx d-none d-md-block">
										<h3>{{$userD2->name}} <i class="fa fa-heart"></i></h3>
										<p>{{$userD2->title}}</p>
									</div>
								@endif
							</div>
							<div class="carousel-item">
								<?php $userD3 = $imgUserM[2]; ?>
								@if(isset($userD3))
									<img class="d-block img-fluid" src="{{$userD3->pic}}">
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
			<div style="margin-bottom: 10px;"></div>
@include('partials.footer')
@include('partials.scripts')
</body>
</html>
