@extends('new.layouts.website')
@section('style')
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
	<link rel="stylesheet" href="/posts/css/font/iconfont.css">
	<link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
	/>

	<style>
		.icon-shenqing:before {
			content: "\e61a";
		}

		.fileuploader {
			max-width: 643px;
			margin: 16px 0 0 !important;
		}

		/*.tia_icon{*/
		/*	position: relative;*/
		/*	left: -16px;*/
		/*	top: -30px;*/
		/*}*/

		.fs_icon{
			border: unset;
		}

		.tao_qu_1{
			display: block;
			min-height: 58px;

		}

		/*.fileuploader-thumbnails-input{*/
		/*	display: none;*/
		/*}*/
		/*.fileuploader-items{*/
		/*	margin-top: 16px;*/
		/*}*/
		/*.fileuploader-items-list{*/
		/*	margin: -16px 0 0 32px !important;*/
		/*}*/

		/*.fileuploader-item-inner{*/
		/*	left: -32px !important;*/
		/*}*/
		/*@media (max-width: 480px){*/
		/*	.fileuploader-item {*/
		/*		width: calc(44% - 16px) !important;*/
		/*	}*/
		/*}*/

		/*input[type="file"] {*/
			/*display: none;*/
		/*}*/

		.removeImg{
			border: unset;
			position: relative;
			float: right;
			left: 5px;
			background: unset;
		}

		.tempImg{
			display: inline-block;

		}
		.tempImg img{
			max-width: 100px;
		}
		.msgPics{
			text-align: center;
			position: relative;
		}
		.chatShowAvatarLeft{
			width: 40px;
			height: 40px;
			float: left;
			object-fit: contain;
			border-radius: 100px;
		}
		.chatShowAvatarRight{
			width: 40px;
			height: 40px;
			float: right;
			object-fit: contain;
			border-radius: 100px;
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


					<div class="shou" style="text-align: @if($user->id != $checkStatus->apply_user_id) center @else left @endif ; position: relative;">
						<a href="/dashboard/forum" class="toug_back btn_img" style=" position: absolute; left: 0;">
							<div class="btn_back"></div>
						</a>
						<span style="margin: 0 auto; position: relative;line-height: 44px;padding-bottom: 3px;@if($user->id == $checkStatus->apply_user_id) left: 40px; @endif font-size: 18px;">{{$forumInfo->title}}</span>
						@if($user->id == $checkStatus->apply_user_id /*&& $checkStatus->status==0*/)
						<a class="toug_back btn_img01 userlogo xzgn">
							<div class="btn_back">功能選單<img src="/posts/images/jiant_a.png"></div>
						</a>
						<div class="fabiao showslide" style="text-align: center;">
							<a onclick="checkUserVip();">我要發表</a>
							<a href="/dashboard/forum_manage">會員管理</a>
						</div>
						@endif
					</div>
					<div class="fadeinboxs"></div>
					<script>
						$('.userlogo').click(function(){
							event.stopPropagation()
							if($(this).hasClass('')){
								$(this).removeClass('')
								$('.fadeinboxs').fadeOut()
								$('.showslide').fadeOut()
							}else{
								$(this).addClass('')
								$('.fadeinboxs').fadeIn()
								$('.showslide').fadeIn()
							}
						})
						$('body').click(function(){
							$('.showslide').fadeOut()
							$('.fadeinboxs').fadeOut()
						})

						//切换,第一个盒子和菜单默认显示

					</script>

					<div class="taol_lt">
						<!-- 聊天室 -->
						@if($checkStatus->status==0 && $user->id ==$checkStatus->apply_user_id)
							<div class="sqnc">
								<span class="iconfont icon-shenqing"></span><a href="/dashboard/viewuser/{{$uidInfo->id}}" style="color: white;">{{$uidInfo->name}}</a>
							</div>
						@endif
						@php
							$forum_id = $forumInfo->id;

                           if($user->id ==$checkStatus->apply_user_id){
                               $to_id = $checkStatus->user_id;
                           }elseif($user->id != $checkStatus->apply_user_id){
                               $to_id = $checkStatus->apply_user_id;
                           }

						@endphp

						<div class="taol_tab01" >
							<div class="tao_qu" style="overflow: unset;">
								<div style="overflow: auto; position: relative; max-height: 550px;">
									<livewire:forum-manage-chat-show :forum_id="$forum_id" :to_id="$to_id" :user="$user"/>
								</div>

								<style>
									.dc-button1{overflow: hidden;width:120px; height: 40px;display: block; margin: 0 auto; float: left;
										border-radius:100px;background:linear-gradient(to top, #ffe3e6, #fff); cursor: pointer; font-size:15px;;box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);color: #fe92a8;
										display: flex;align-items: center;justify-content: center;border: #ffe2e7 1px solid;box-shadow: 0 5px 5px #ffc9d3;}
									.dc-anniudh.hover{box-shadow:4px 4px 6px 0 rgba(255,255,255,.5),-4px -4px 6px 0 rgba(116,125,136,.5),inset -4px -4px 6px 0 rgba(255,255,255,.2),inset 4px 4px 6px 0 rgba(0,0,0,.4)!important;
										color:#fe92a8; background: #ffe3e6;}
									.dc_l{ margin-left: 5px; margin-right: 5px;}
								</style>
								<script type="text/javascript" src="/posts/js/self.js"></script>
								<div class="shenqing">
									@if($user->id ==$checkStatus->apply_user_id && $checkStatus->status==0)
										<div style=" margin: 0 auto; display: table">
											<a onclick="forum_manage_toggle({{$checkStatus->user_id}}, 1)" class="dc-button1 dc-anniudh dc-tcbox1-open1 dc_l">通過</a>
											<a onclick="forum_manage_toggle({{$checkStatus->user_id}}, 2)" class="dc-button1 dc-anniudh dc-tcbox1-open1 dc_l">不通過</a>
										</div>									@endif--}}
									@elseif($user->id != $checkStatus->apply_user_id && $checkStatus->status==0)
										<a onclick="forum_manage_toggle({{$checkStatus->apply_user_id}}, 4)" class="dc_anniudh shenq_button">取消申請</a>
									@endif
								</div>

							</div>


							<div class="tao_qu_1">
								<livewire:forum-manage-chat-submit :forum_id="$forum_id" :to_id="$to_id" />
							</div>
						</div>
						<!-- 结束 -->

					</div>


					<!--  -->
				</div>
			</div>
		</div>
		@endsection

@section('javascript')

	<script>
		function forum_manage_toggle(auid, status) {
			var msg, apply_user_id, user_id;
			var fid = '{{$forumInfo->id}}';
			if(status==1){
				user_id = auid;
				apply_user_id = '{{$user->id}}';
				msg='您確定要通過該會員加入嗎?';
			}else if(status==2){
				user_id = auid;
				apply_user_id = '{{$user->id}}';
				msg='您確定要拒絕該會員加入嗎?';
			}else if(status==4){
				user_id = '{{$user->id}}';
				apply_user_id = auid;
				msg='您確定要取消申請嗎?';
			}else{
				return false;
			}
			c4(msg);
			$('.shenq_button:hover').css("box-shadow","0 5px 5px #ffc9d3 !important");
			$('.shenq_button_a:hover').css("box-shadow","0 5px 5px #ffc9d3 !important");
			$(".n_left").on('click', function() {
				$.post('{{ route('forum_manage_toggle') }}', {
					uid: user_id,
					auid: apply_user_id,
					fid: fid,
					status: status,
					_token: '{{ csrf_token() }}'
				}, function (data) {
					$("#tab04").hide();
					var obj = JSON.parse(data);
					c5(obj.message);
					$(".n_bllbut").on('click', function() {
                        if(obj.message == '該會員已通過'){
							window.location.href = "/dashboard/forum_manage";
						}else if(obj.message == '已取消申請'){
							window.location.href = "/dashboard/forum";
						}else {
							location.reload();
						}
					});
				});
			});
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
				window.location.href = "/dashboard/forum_posts/{{$forumInfo->id}}";
			}
		}

	</script>
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endsection