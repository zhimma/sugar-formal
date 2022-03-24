@extends('new.layouts.website')
@section('style')
		<link rel="stylesheet" href="/posts/css/style.css">
		<link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
		<link rel="stylesheet" href="/posts/css/font/iconfont.css">
		<link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
		<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/posts/js/bootstrap.min.js"></script>

		<style>
			.hycov_down{
				width: 28px;
				height: 28px;
			}

			.wt_txb{ position: relative; }

			.ta_sz{ position: absolute; width:15px; height:15px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 12px;}
			.ta_sz_ten{ position: absolute; width:15px; height:15px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 12px;}
			.ta_sz_hundred{ position: absolute; width:24px; height:24px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 13px;}
			
			.hycov{ border-radius: 100px;}

		</style>
@endsection
		@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou">
						<span>討論區</span><font>Discussion</font>
						<a @if(isset($forum)) onclick="forumTip({{$user->id}})"  @else onclick="ForumCheckEnterPop()" @endif
						   class="xinzeng_but" style="font-size: 12px;"><img src="/posts/images/liuyan_03.png" style="height:15px;">個人討論區</a>
					</div>

					<div class="tl_bbg">
						<a href="/dashboard/posts_list">
						<img src="/posts/images/taolq02.png" class="tl_bbg_img">
						<div class="te_ins">
							<div class="ta_wdka_text te_incob">主題數<span>{{$posts_list[0]->posts_num}}</span><i>丨</i>回覆數<span>{{$posts_list[0]->posts_reply_num}}</span></div>
							<div class="ta_witx_rig">
								<div class="wt_txb">
									@foreach($posts_list as $key=>$row)
										@if(count($posts_list)>5)
											@once
											<span class="ta_toxmr">
												<img src="/posts/images/imor.png" class="hycov">
											</span>
											@endonce
										@endif

										@if($key==0)
											<span class="ta_toxmr @if(count($posts_list)>5) xa_rig10 @endif">
												<img src="@if(file_exists( public_path().$row->umpic ) && $row->umpic != ""){{$row->umpic}} @elseif($row->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov">
											</span>
										@elseif($key>0 && $key<5)
											<span class="ta_toxmr xa_rig10">
												<img src="@if(file_exists( public_path().$row->umpic ) && $row->umpic != ""){{$row->umpic}} @elseif($row->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov">
											</span>
										@endif
									@endforeach
								</div>
							</div>
						</div>
						</a>
					</div>


					<div class="taolun_btl">
						@if(isset($posts) && count($posts)>0)
							@foreach($posts as $post)
								@php
									$show_a = 0;
									$getStatus = \App\Models\ForumManage::where('user_id', $user->id)->where('apply_user_id', $post->uid)->get()->first();
								@endphp
								<li @if($post->f_warned==1) class="huis_01" @endif>
									<div class="ta_lwid_left">
										<a href="/dashboard/viewuser/{{$post->uid}}">
										<img src="@if(file_exists( public_path().$post->umpic ) && $post->umpic != ""){{$post->umpic}} @elseif($post->uengroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov">
										</a>
									</div>
									<div class="ta_lwid_right">
										@if(($post->uid == $user->id || (isset($getStatus) && $getStatus->status==1 && $getStatus->forum_status ==1)) || (isset($getStatus) && $getStatus->status==1 && $getStatus->chat_status ==1) && $post->f_status==1)
											@php
												$show_a = 1;
											@endphp
											<a href="/dashboard/forum_personal/{{$post->f_id}}">
										@elseif($post->f_status==0 && $post->uid != $user->id)
											@php
												$show_a = 1;
											@endphp
											<a onclick="forumStatus({{$post->f_status}})">
										@elseif($post->f_status==0 && $post->uid == $user->id)
											@php
												$show_a = 1;
											@endphp
											<a onclick="forumTip({{$user->id}})">
										@elseif(isset($getStatus) && $getStatus->status==0)
											@php
												$show_a = 1;
											@endphp
											<a href="/dashboard/forum_manage_chat/{{$post->uid}}/{{$user->id}}">
										@elseif(isset($getStatus) && ($getStatus->status==2 || $getStatus->status==3))
											@php
												$show_a = 1;
											@endphp
											<a onclick="forumStatus({{$getStatus->status}})">
										@elseif(isset($getStatus) && $getStatus->forum_status==0 && $getStatus->chat_status==0)
											@php
												$show_a = 1;
											@endphp
											<a onclick="c5('您目前已被管理員限制使用')">
										@endif
										<h2>{{$post->f_title}}</h2>
										<h3>{!!  $post->f_sub_title !!}</h3>
										<div class="ta_wdka">
											<div class="ta_wdka_text">主題數<span>{{$post->posts_num}}</span><i>丨</i>回覆數<span>{{$post->posts_reply_num}}</span></div>
											<div class="ta_witx_rig">

												@if(isset($getStatus) && $getStatus->status==0)
													<a href="/dashboard/forum_manage_chat/{{$post->uid}}/{{$user->id}}" class="shenhe_z">審核中</a>
												@elseif(isset($getStatus) && ($getStatus->status==2 || $getStatus->status==3))
													<div class="wtg_z" onclick="forumStatus({{$getStatus->status}})">未通過</div>
												@elseif($post->uid != $user->id && !isset($getStatus) && $post->f_status==1)
													<a onclick="forum_manage_toggle({{$post->uid}}, 0, {{$post->f_id}})" class="seqr">申請加入</a>
												@endif
												<div class="wt_txb">
													@php 
													$getApplyUsers = \App\Models\ForumManage::select('user_meta.pic', 'users.engroup')
																						   ->LeftJoin('users', 'users.id','=','forum_manage.user_id')
																						   ->join('user_meta', 'user_meta.user_id','=','forum_manage.user_id')
																						   ->where('forum_manage.apply_user_id', $post->uid)
																						   ->where('forum_manage.status', 1)
																						   ->where('forum_manage.active',1)
																						   ->where(function($query){
																								return $query->where('forum_manage.forum_status',1)
																											->orwhere('forum_manage.chat_status',1);
																							})
																						   ->chunk(800, function($users) {
																								foreach ($users->lazy() as $key=>$user) {
																								
																									if(file_exists( public_path().$user->pic ) && $user->pic != ""){
																										$pic = $user->pic ;
																									}else if($user->engroup==2){
																										$pic= '/new/images/female.png' ;
																									}else{ 
																										$pic = '/new/images/male.png' ;
																									}

																									if($key==0){
																											echo '<span class="ta_toxmr" style="position: relative; left: -10px; z-index: -1;">';
																											echo '<img src="/posts/images/imor.png" class="hycov">';
																											echo '</span>';
																									}
																									
																									if($key<5){
																										echo '<span class="ta_toxmr xa_rig10">';
																										echo '<img src="'.$pic.'" class="hycov hycov_down">';
																										echo '</span>';
																									}
																									
																								}
																							});
													@endphp
													@if($forum_member_count->get($post->f_id)->forum_member_count??false)
														@if($forum_member_count->get($post->f_id)->forum_member_count >= 100)
														<div class="ta_sz_hundred">{{$forum_member_count->get($post->f_id)->forum_member_count}}</div>
														@elseif($forum_member_count->get($post->f_id)->forum_member_count >= 10)
														<div class="ta_sz_ten">{{$forum_member_count->get($post->f_id)->forum_member_count}}</div>
														@else
														<div class="ta_sz">{{$forum_member_count->get($post->f_id)->forum_member_count}}</div>
														@endif
													@endif
												</div>
											</div>
										</div>
											@if($show_a==1)
											</a>
											@endif
									</a>
								</li>
							@endforeach
						@else
							<li style="text-align: center;">
								<img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span>
							</li>
						@endif
					</div>

					<div class="fenye ba_but" style="margin-top: 10px;">
						{{ $posts->links('pagination::sg-pages2') }}
{{--						<a href="">上一頁</a><span class="new_page">1/5</span><a href="">下一頁</a>--}}
					</div>
				</div>
			</div>
		</div>
		@stop

@section('javascript')
<script>

	function forumTip(uid) {
		@if(isset($forum) && $forum->status==0 )
		let script = '<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0"></a>';
		c5('您好，您的版面被系統關閉，如有意見請聯絡站長LINE@');
		$('.bltext').append(script);
		@elseif(isset($forum) && $forum->status==1 )
		c5('您已經新增過');
		$(".n_bllbut").on('click', function() {
			window.location.href = "/dashboard/forum_personal/{{$forum->id}}";
		});
		@endif
	}

	function forumStatus(status) {
		if(status == 0){
			c5('您好，目前此版面暫不開放');
		}else if(status == 2 || status == 3){
			c5('您目前已無法申請進入');
		}
	}

	function forum_manage_toggle(auid, status) {
        // c5('功能維修中');
        // return false;
		var msg, uid;
		var fid = '';

		uid = '{{ $user->id }}';
		if(status==0){
			msg='您確定要申請加入嗎?'
		}else{
			return false;
		}
		c4(msg);
		$(".n_left").on('click', function() {
			$.post('{{ route('forum_manage_toggle') }}', {
				uid: uid,
				auid: auid,
				fid: fid,
				status: status,
				_token: '{{ csrf_token() }}'
			}, function (data) {
				$("#tab04").hide();
				var obj = JSON.parse(data);
				// c5(obj.message);
				// $(".n_bllbut").on('click', function() {
					if(obj.message=='申請成功'){
						window.location.href = "/dashboard/forum_manage_chat/" + auid + "/" + uid + "";
						// window.location.href = "/dashboard/forum";
					}else {
						location.reload();
					}
				// });
			});
		});
	}

	let script = '<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="26" border="0"></a>';
	function ForumCheckEnterPop() {
		@if(!$user->isCanPosts_vip())
			c5('您成為VIP未達滿三個月以上');
		@elseif($user->isEverBanned())
			@php
			$record = $user->isEverBanned();
			$reason = str_replace('(未續費)','', $record->reason);
			$text = '您於'.substr($record->created_at, 0, 10).'曾被站方因'.$reason.'封鎖，不符合開設個人討論區資格，若有意見反應，請洽站長Line@';
			@endphp
			let text = '{{$text}}';
			c5(text);
			$('.bltext').append(script);
		@elseif($user->isEverWarned())
			@php
			$record = $user->isEverWarned();
			$reason = str_replace('(未續費)','', $record->reason);
			$text = '您於'.substr($record->created_at, 0, 10).'曾被站方因'.$reason.'警示，不符合開設個人討論區資格，若有意見反應，請洽站長Line@';
			@endphp
			let text2 = '{{$text}}';
			c5(text2);
			$('.bltext').append(script);
		@elseif(\App\Models\Forum::withTrashed()->where('user_id', $user->id)->first()->deleted_at ?? false)
			@if(\App\Models\Forum::withTrashed()->where('user_id', $user->id)->first()->deleted_at > \Carbon\Carbon::now()->subYear())
				c5('專屬討論區下架一年內不得再申請專屬討論區');
			@else
				window.location.href = "/dashboard/ForumEdit/{{$user->id}}";
			@endif
		@else
			window.location.href = "/dashboard/ForumEdit/{{$user->id}}";
		@endif
	}
	

</script>
@endsection
