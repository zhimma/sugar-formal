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
		<title>投稿列表</title>
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
					<div class="shou"><span>討論區列表</span>
						<font>Discussion</font>
						<a href="/dashboard/posts" class="toug_but"><img src="/posts/images/tg_03.png">我要發表</a>
					</div>

					@if(count($posts)==0)
						<div class="sjlist">
							<div class="fengsicon"><img src="/posts/images/bianji.png" class="feng_img"><span>暫無討論</span></div>
						</div>
					@else
						<div class="tou_list">
							<ul>
								@foreach($posts as $post)
									<li>
										<div class="tou_tx">
											<a href="/dashboard/viewuser/{{$post->uid}}">
												<div class="tou_tx_img"><img src="@if(file_exists( public_path().$post->umpic ) && $post->umpic != ""){{$post->umpic}} @elseif($post->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
											</a>
											<a href="/dashboard/viewuser/{{$post->uid}}"><span>{{ $post->uname }}<i>{{ date('Y-m-d', strtotime($post->pcreated_at)) }}</i></span></a>
											<a href="/dashboard/post_detail/{{$post->pid}}">
												<font><i class="ne_talicon"><img src="/posts/images/tl_icon.png">{{ \App\Models\Posts::where('reply_id',$post->pid)->get()->count() }}</i></font>
											</a>
										</div>
										<a href="/dashboard/post_detail/{{$post->pid}}">
											<div class="tc_text_aa"><span>{{$post->ptitle}}</span></div>
											<div class="tc_text_bb"><p>{!! \App\Models\Posts::showContent($post->pcontents) !!}</p></div>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
						<div class="fenye mabot30">
							{{ $posts->links('pagination::sg-pages2') }}
						</div>
					@endif
				</div>
			</div>
		</div>
		@stop

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
</style>