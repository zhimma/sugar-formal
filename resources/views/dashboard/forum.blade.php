@extends('new.layouts.website')

		<link rel="stylesheet" href="/posts/css/style.css">
		<link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
		<link rel="stylesheet" href="/posts/css/font/iconfont.css">
		<link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
		<script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/posts/js/bootstrap.min.js"></script>

		@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou">
						<span>討論區</span><font>Discussion</font>
						<a @if(isset($post_forum)>0) href="/dashboard/forum_personal/{{$user->id}}" @else href="/dashboard/ForumEdit/{{$user->id}}" @endif class="xinzeng_but" style="font-size: 12px;"><img src="/posts/images/liuyan_03.png" style="height:15px;">個人討論區</a>
					</div>
					<!--  -->
					<a class="tl_button_PC" href="/dashboard/posts_list" style="width: 95%;"><img src="/posts/images/taolun_but_pc.png"></a>
					<a class="tl_button" href="/dashboard/posts_list"><img src="/posts/images/taolun_but.png"></a>




					<div class="taolun_btl">
						@if(isset($posts) && count($posts)>0)
							@foreach($posts as $post)
								<li>
									<div class="ta_lwid_left">
										<a href="/dashboard/viewuser/{{$post->uid}}">
										<img src="@if(file_exists( public_path().$post->umpic ) && $post->umpic != ""){{$post->umpic}} @elseif($post->uengroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov">
										</a>
									</div>
									<div class="ta_lwid_right">
										@if($post->uid == $user->id || (isset($getStatus) && $getStatus->status==1))
											<a href="/dashboard/forum_personal/{{$post->uid}}">
												@endif
										<h2>{{$post->f_title}}</h2>
										<h3>{{$post->f_sub_title}}</h3>
										<div class="ta_wdka">
											<div class="ta_wdka_text">主題數<span>{{$post->posts_num}}</span><i>丨</i>回覆數<span>{{$post->posts_reply_num}}</span></div>
											<div class="ta_witx_rig">
												@php
												$getStatus = \App\Models\ForumManage::where('user_id', $user->id)->where('apply_user_id', $post->uid)->get()->first();
												@endphp
{{--												@if($post->uid == $user->id || (isset($getStatus) && $getStatus->status==1))--}}
{{--													<a href="/dashboard/posts_personal/{{$post->uid}}" class="shenhe_z">--}}
												@if(isset($getStatus) && $getStatus->status==0)
													<a href="/dashboard/forum_manage_chat/{{$post->uid}}/{{$user->id}}" class="shenhe_z">審核中</a>
												@elseif(isset($getStatus) && $getStatus->status==2)
													<a href="javascript:void(0);" class="wtg_z">未通過</a>
												@elseif($post->uid != $user->id && !isset($getStatus))
													<a onclick="forum_manage_toggle({{$post->uid}}, 0, {{$post->f_id}})" class="seqr">申請加入</a>
												@endif

												@php
												$getApplyUsers = \App\Models\ForumManage::select('user_meta.pic', 'users.engroup')
																						   ->LeftJoin('users', 'users.id','=','forum_manage.user_id')
																						   ->join('user_meta', 'user_meta.user_id','=','forum_manage.user_id')
																						   ->where('forum_manage.apply_user_id', $post->uid)
																						   ->where('forum_manage.status', 0)
																						   ->get();
												@endphp
												<div class="wt_txb">
													@foreach($getApplyUsers as $key=>$row)
		{{--												@php--}}
		{{--													echo $row->pic;--}}
		{{--												@endphp--}}
														@if($key==0)
															<span class="ta_toxmr"><img src="@if(file_exists( public_path().$row->pic ) && $row->pic != ""){{$row->pic}} @elseif($row->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></span>
														@elseif($key>0 && $key<5)
															<span class="ta_toxmr xa_rig10"><img src="@if(file_exists( public_path().$row->pic ) && $row->pic != ""){{$row->pic}} @elseif($row->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></span>
														@elseif($key>=5)
															<span class="ta_toxmr xa_rig10"><img src="/posts/images/zx.jpg" class="hycov"></span>
															@break
														@endif
													@endforeach

		{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/icon_010.png" class="hycov"></span>--}}
		{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/zx.jpg" class="hycov"></span>--}}
												</div>
											</div>
										</div>
											@if($post->uid == $user->id || (isset($getStatus) && $getStatus->status==1))
											</a>
											@endif
									</div>
								</li>
							@endforeach
						@else
							<li style="text-align: center;">
								<img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span>
							</li>
						@endif
{{--						<li>--}}
{{--							<div class="ta_lwid_left"><img src="/posts/images/zx.jpg" class="hycov"></div>--}}
{{--							<div class="ta_lwid_right">--}}
{{--								<h2>阿龍的聊天室阿龍的聊天室</h2>--}}
{{--								<h3>阿龍的聊天室阿龍的聊天室阿</h3>--}}
{{--								<div class="ta_wdka">--}}
{{--									<div class="ta_wdka_text">主題數<span>20</span><i>丨</i>回覆數<span>20</span></div>--}}
{{--									<div class="ta_witx_rig">--}}
{{--										<a href="" class="seqr">申請加入</a>--}}
{{--										<div class="wt_txb">--}}
{{--											<span class="ta_toxmr"><img src="/posts/images/imor.png" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/zx.jpg" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/icon_010.png" class="hycov"></span>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</li>--}}
{{--						<li>--}}
{{--							<div class="ta_lwid_left"><img src="/posts/images/zx.jpg" class="hycov"></div>--}}
{{--							<div class="ta_lwid_right">--}}
{{--								<h2>阿龍的聊天室阿龍的聊天室</h2>--}}
{{--								<h3>阿龍的聊天室阿龍的聊天室阿龍的聊天室阿龍的聊天室阿阿</h3>--}}
{{--								<div class="ta_wdka">--}}
{{--									<div class="ta_wdka_text">主題數<span>20</span><i>丨</i>回覆數<span>20</span></div>--}}
{{--									<div class="ta_witx_rig">--}}
{{--										<a href="" class="shenhe_z">審核中</a>--}}
{{--										<div class="wt_txb">--}}
{{--											<span class="ta_toxmr"><img src="/posts/images/imor.png" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/icon_010.png" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/zx.jpg" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/icon_010.png" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/zx.jpg" class="hycov"></span>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</li>--}}
{{--						<li>--}}
{{--							<div class="ta_lwid_left"><img src="/posts/images/zx.jpg" class="hycov"></div>--}}
{{--							<div class="ta_lwid_right">--}}
{{--								<h2>阿龍的聊天室阿龍的聊天室</h2>--}}
{{--								<h3>阿龍的聊天室阿龍的聊天室阿龍的聊天室阿龍的聊天室阿阿阿龍的聊天室阿龍的聊天室阿龍的聊阿龍的聊天室阿龍的聊天室阿龍的聊天室阿龍的聊天室阿阿阿龍的聊天室阿龍的聊天室阿龍的聊天室阿龍的聊天室阿龍的聊</h3>--}}
{{--								<div class="ta_wdka">--}}
{{--									<div class="ta_wdka_text">主題數<span>20</span><i>丨</i>回覆數<span>20</span></div>--}}
{{--									<div class="ta_witx_rig">--}}
{{--										<a href="" class="wtg_z">未通過</a>--}}
{{--										<div class="wt_txb">--}}
{{--											<span class="ta_toxmr"><img src="/posts/images/imor.png" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/zx.jpg" class="hycov"></span>--}}
{{--											<span class="ta_toxmr xa_rig10"><img src="/posts/images/icon_010.png" class="hycov"></span>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</li>--}}
					</div>

					<div class="fenye ba_but" style="margin-top: 10px;">
						{{ $posts->links('pagination::sg-pages2') }}
{{--						<a href="">上一頁</a><span class="new_page">1/5</span><a href="">下一頁</a>--}}
					</div>
					<!--  -->
				</div>
			</div>
		</div>
		@stop
<script>

	$(document).ready(function() {
		@if(Session::has('message'))
		c5('{{Session::get('message')}}');
		<?php session()->forget('message');?>
		@endif
	});

	function forum_manage_toggle(auid, status) {
        c5('功能維修中');
        return false;
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
				c5(obj.message);
				$(".n_bllbut").on('click', function() {
					if(obj.message=='申請成功'){
						window.location.href = "/dashboard/forum_manage_chat/" + auid + "/" + uid + "";
					}else {
						location.reload();
					}
				});
			});
		});
	}

</script>
