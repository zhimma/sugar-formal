@extends('new.layouts.website')
@section('style')
{{--	<link href="/posts/css/bootstrap.min.css" rel="stylesheet">--}}
{{--	<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">--}}
	<!-- owl-carousel-->
	<!--    css-->
	<link rel="stylesheet" href="/posts/css/style.css">
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
@endsection

		@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou" style="text-align: center; position: relative;">
						<a href="/dashboard/forum" class="toug_back btn_img" style=" position: absolute; left: 0;">
							<div class="btn_back"></div>
						</a>
						<div style="position: absolute; left:45px;">
							<span>官方討論區</span>
							<font>Discussion</font>
						</div>
						<a onclick="checkUserVip();" class="aid_but"><img src="/posts/images/tg_03.png">我要發表</a>
					</div>

{{--					<livewire:posts-list/>--}}
					@if(!isset($posts) || count($posts)==0)
						<div class="sjlist">
							<div class="fengsicon"><img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span></div>
						</div>
					@else
						<div class="tou_list">
							<ul>
								@foreach($posts as $post)
									<li {{ $post->uid==1049 && $post->top==0 ? 'style=background:#ddf3ff;padding:10px' : ''}} @if($post->top==1) style="background:#ffcf869e;padding:10px;" @endif @if($post->deleted_by != null || $post->deleted_at != null) class="huis_02" @endif>
										<div class="tou_tx">
											<a href="/dashboard/viewuser/{{$post->uid}}">
												<div class="tou_tx_img"><img src="@if(file_exists( public_path().$post->umpic ) && $post->umpic != ""){{$post->umpic}} @elseif($post->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
											</a>
											<a href="/dashboard/viewuser/{{$post->uid}}"><span>{{ $post->uname }}<i>{{ date('Y-m-d', strtotime($post->pcreated_at)) }}</i></span></a>
											<a @if($post->deleted_by == null) href="/dashboard/post_detail/{{$post->pid}}" @else onclick="delete_alert()" @endif>
												<font><i class="ne_talicon"><img src="/posts/images/tl_icon.png">{{ \App\Models\Posts::where('reply_id',$post->pid)->get()->count() }}</i></font>
											</a>
										</div>
										<a @if($post->deleted_by == null) href="/dashboard/post_detail/{{$post->pid}}" @else onclick="delete_alert()" @endif>
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

@section('javascript')
<script>

	function delete_alert() {
		c5('此文章已刪除');
	}

	function checkUserVip() {

		var checkUserVip='{{ $checkUserVip }}';
		var checkProhibit='{{ $user->prohibit_posts }}';
		var checkAccess='{{ $user->access_posts }}';
		if(checkUserVip==0) {
			c5('此功能目前開放給連續兩個月以上的VIP會員使用');
			return false;
		}else if(checkProhibit==1){
			c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');
			return false;
		}else if(checkAccess==1){
			c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');
			return false;
		} else{
			window.location.href = "/dashboard/posts";
		}
	}
</script>
@endsection