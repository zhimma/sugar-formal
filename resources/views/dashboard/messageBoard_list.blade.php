<style>
	.toug_but:hover{ color:white !important; text-decoration:none !important}

	.article{
		overflow : hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
	}

		@media (max-width:320px) {
			.contents{
				width: 200px !important;
			}
		}
		@media (min-width:321px) and (max-width:375px) {
			.contents{
				width:250px !important;
			}
		}
		@media (min-width:376px) and (max-width:414px) {
			.contents{
				width:300px !important;
			}
		}
		@media (min-width:415px) and (max-width:768px){
			.contents{
				width:520px !important;
			}
		}
		@media (min-width:769px) and (max-width:1024px){
			.contents{
				width:350px !important;
			}
		}
		.read-more:hover {
		  color:#e44e71;
		}
</style>
@extends('new.layouts.website')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<!-- Bootstrap -->
	<link href="/posts/css/bootstrap.min.css" rel="stylesheet">
	<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">
	<!-- owl-carousel-->
	<!--    css-->
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/swiper.min.css">
	<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="/posts/js/bootstrap.min.js"></script>

	@section('app-content')
	<div class="container matop70">
		<div class="row">
			<div class="col-sm-2 col-xs-2 col-md-2 dinone">
				@include('new.dashboard.panel')
			</div>
			<div class="col-sm-12 col-xs-12 col-md-10">
				<div class="shou"><span>留言板</span>
					<font>Wishing Board</font>
					<a href="/MessageBoard/posts" class="xinzeng_but"><img src="/new/images/liuyan_03.png">新增留言</a>
				</div>
				<div class="liuy_qh">
					<ul>
						<li><a class="liy_hover" href="#" onclick='return changediv("eml")' id="eml_a" target=_parent >{{ $user->engroup==1 ? '她':'他' }}的留言</a></li>
						<li><a href="#" onclick='return changediv("eml2")' id="eml2_a" target=_parent >我的留言</a></li>
					</ul>
				</div>
				<script>
					function changediv(id){
						document.getElementById("eml").style.display="none";
						document.getElementById("eml2").style.display="none";
						document.getElementById("eml_a").className="";
						document.getElementById("eml2_a").className="";
						document.getElementById(id).style.display="table";
						document.getElementById(id+"_a").className="liy_hover";
						return false;
					}
				</script>
				<div style="width: 100%; display: table;" id="eml">
					@if($getLists_others->count())
						<div class="liuyan_nlist">
							<ul>
								@foreach($getLists_others as $list)
									@php
										$userMeta=\App\Models\UserMeta::findByMemberId($list->uid);
										$msgUser=\App\Models\User::findById($list->uid);
				                        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($msgUser, $user);

				                        $cityList=explode(',',$list->city);
									   	$areaList=explode(',',$list->area);
									   	$cityAndArea='';
									   	foreach ($cityList as $key => $city){
									   	    $cityAndArea.= $cityList[$key].$areaList[$key] . ((count($cityList)-1)==$key ? '':', ');
									   	}
									@endphp
									<li>
										<a href="/dashboard/viewuser/{{$list->uid}}">
											<div class="liuyan_img"><img class="hycov @if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$list->umpic ) && $list->umpic != ""){{$list->umpic}} @elseif($list->uengroup==2)/new/images/female.png @else/new/images/male.png @endif"></div>
										</a>
										<a href="/MessageBoard/post_detail/{{ $list->mid }}">
											<div class="liuyan_prilist">
												<div class="liuyfont">
													<div class="liu_name">{{ $list->uname }} , {{ $userMeta ? $userMeta->age() : '' }}<span>{{ substr($list->mcreated_at,0,10) }}</span></div>
													<div class="liu_dq">{{ $cityAndArea }}</div>
												</div>
												<div class="liu_text">
													<div class="liu_text_1">{{ $list->mtitle }}</div>
													<div class="liu_text_2">{{ $list->mcontents }}</div>
												</div>
											</div>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
						<div class="fenye mabot30">
							{{ $getLists_others->appends(['msgBoardType'=>'others_page'])->links('pagination::sg-pages2') }}
						</div>
					@else
						<div class="ddt_list matop5">
							<div class="zap_ullist matop5" >
								<div class="n_dtwu_nr">
									<img src="/new/images/liuyan_no.png">
									<p>目前無紀錄</p>
								</div>
							</div>
						</div>
					@endif
				</div>
				<div style="width: 100%; display: table;" id="eml2" style="display:none">
					@if($getLists_myself->count())
						<div class="liuyan_nlist">
							<ul>
								@foreach($getLists_myself as $list)
									@php
										$userMeta=\App\Models\UserMeta::findByMemberId($list->uid);
										$msgUser=\App\Models\User::findById($list->uid);
				                        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($msgUser, $user);

				                        $cityList=explode(',',$list->city);
									   	$areaList=explode(',',$list->area);
									   	$cityAndArea='';
									   	foreach ($cityList as $key => $city){
									   	    $cityAndArea.= $cityList[$key].$areaList[$key] . ((count($cityList)-1)==$key ? '':', ');
									   	}
									@endphp
									<li>
										<a href="/dashboard/viewuser/{{$list->uid}}">
											<div class="liuyan_img"><img class="hycov @if($isBlurAvatar) blur_img @endif" src="@if(file_exists( public_path().$list->umpic ) && $list->umpic != ""){{$list->umpic}} @elseif($list->uengroup==2)/new/images/female.png @else/new/images/male.png @endif"></div>
										</a>
										<a href="/MessageBoard/post_detail/{{ $list->mid }}">

											<div class="liuyan_prilist">
												<div class="liuyfont">
													<div class="liu_name">{{ $list->uname }} , {{ $userMeta ? $userMeta->age() : '' }}<span>{{ substr($list->mcreated_at,0,10) }}</span></div>
													<div class="liu_dq">{{ $cityAndArea }}</div>
												</div>
												<div class="liu_text">
													<div class="liu_text_1">{{ $list->mtitle }}</div>
													<div class="liu_text_2">{{ $list->mcontents }}</div>
												</div>
											</div>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
						<div class="fenye mabot30">
							{{ $getLists_myself->appends(['msgBoardType'=>'my_page'])->links('pagination::sg-pages2') }}
						</div>
					@else
						<div class="ddt_list matop5">
							<div class="zap_ullist matop5" >
								<div class="n_dtwu_nr">
									<img src="/new/images/liuyan_no.png">
									<p>目前無紀錄</p>
								</div>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>

	@stop
<script>
	$(document).ready(function () {
		document.getElementById("eml").style.display="block";
		document.getElementById("eml2").style.display="none";
		document.getElementById("eml_a").className="";
		document.getElementById("eml2_a").className="";
		document.getElementById("eml").style.display="table";
		document.getElementById("eml_a").className="liy_hover";

		@if(Session::has('message'))
		c5("{{Session::get('message')}}");
		<?php session()->forget('message');?>
		@endif

		var pageDefault='{{ Request()->get('msgBoardType') }}';
		if( pageDefault =='my_page'){
			changediv("eml2");
		}else{
			changediv("eml");
		}
	});

	function send_posts_submit() {

		var title = $("#title").val();
		if (title == '') {
			c5('您的標題不可以為空！');
			return false;
		}
		var content =$('#contents').val();
		if(content.length <=0 ){
			c5('您的內容不可以為空！');
			return false;
		}
		$("#posts").submit();
	}
</script>
<style>
	.pagination > li > a:focus,
	.pagination > li > a:hover,
	.pagination > li > span:focus,
	.pagination > li > span:hover{
		z-index: 3;
		color: #23527c !important;
		background-color: #f5c2c0 !important;
		border-color: #ddd !important;
		border-color:#ee5472 !important;
		color:white !important;
	}

	.pagination > .active > a,
	.pagination > .active > span,
	.pagination > .active > a:hover,
	.pagination > .active > span:hover,
	.pagination > .active > a:focus,
	.pagination > .active > span:focus {
		z-index: 3;
		color: #23527c !important;
		background-color: #f5c2c0 !important;
		border-color:#ee5472 !important;
		color:white !important;
	}
	.blnr{padding-bottom: 14px;}
</style>