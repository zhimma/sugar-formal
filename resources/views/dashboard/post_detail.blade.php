<style>
	.toug_back:hover{ color:white !important; text-decoration:none !important}
</style>
@extends('new.layouts.website')
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>投稿詳情</title>
		<!-- Bootstrap -->
		<link href="/posts/css/bootstrap.min.css" rel="stylesheet">
		<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/posts/css/style.css">
		<link rel="stylesheet" href="/posts/css/swiper.min.css">
		<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/posts/js/bootstrap.min.js"></script>
		<style>
			img{
				width: auto;
				height: auto;
				max-width: 100%;
				max-height: 100%;	
			}
		</style>
		<!-- <script src="/posts/js/main.js" type="text/javascript"></script> -->
@section('app-content')

		<!-- <div class="head hetop">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12"><img src="/posts/images/icon_41.png" class="logo" />
				</div>
			</div>
		</div>
		<div class="head heicon">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<img src="/posts/images/icon_41.png" class="logo" />
							<span id="menuButton"><img src="/posts/images/icon.png" class="he_img"></span>
						</div>
						<ul id="menuList" class="change marg30">
                            <div class="comt"><img src="images/t.png"></div>
                            <div class="coheight">
							<div class="heyctop">測試系統賬號</div>
							<div class="helist">
								<ul>
									<li><a href=""><img src="/posts/images/icon_38.png">搜索</a></li>
									<li><a href=""><img src="/posts/images/icon_45.png">訊息</a><span>10</span></li>
									<li><a href=""><img src="/posts/images/icon_46.png">名單</a></li>
									<li><a href=""><img src="/posts/images/icon_48.png">我的</a></li>
								</ul>
							</div>
							<a href="" class="tcbut">LOGOUT</a>
                            </div>
						</ul>
					</div>
				</div>
			</div>
		</div> -->

		<!---->
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou"><span>心情故事</span>
						<font>Whisper tale</font>
						<a href="{{url()->previous()}}" class="toug_back">返回</a>
					</div>
					@foreach($posts as $post)
                    <div class="t_xqheight">
                    <div class="toug_xq">
                          <div class="xq_text" style="word-break: break-all;">
                          	<div style="margin-left: 30px;margin-right:30px">
                          		{{$post->ptitle}}
                          		<span>{{date('Y-m-d',strtotime($post->pcreated_at))}}</span>
                          	</div>
                          </div>
                          <div class="xq_text01" style="word-break: break-all;">
							<div style="margin-left:30px;margin-right:30px">
						  		@php echo $post->pcontents @endphp
							</div>
                          </div>
                         <div class="xq_textbot"><img src="/posts/images/tg_10.png"></div>
                    </div>
                    
                    <div class="tou_xq">
                    <div class="touxqfont"><img src="/posts/images/ncion_13.png">瀏覽<span>{{$post->uviews}}</span></div>
                    <a href="{{$post->panonymous!='combine' ? '#':'/dashboard/viewuser/'.$post->uid}}"><div class="tou_img"><img src="{{$post->panonymous!='combine' ? ($post->uengroup=='1' ? '/posts/images/touxiang_wm.png':'/posts/images/touxiang_w.png') : $post->umpic }}"><span>{{$post->panonymous!='combine' ? '匿名' : $post->uname}}</span></div></a>
                    </div>
                    
					</div>
					@endforeach
				</div>

			</div>
		</div>


@stop