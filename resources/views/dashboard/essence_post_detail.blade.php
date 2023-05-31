@extends('new.layouts.website')

@section('style')
	<script src="/js/app_1.js" type="text/javascript"></script>
	<!--    css-->
{{--	<link href="/posts/css/bootstrap.min.css" rel="stylesheet">--}}
{{--	<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">--}}
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/iconfont.css">
	<style>
		img{
			width: auto;
			height: auto;
			max-width: 100%;
			max-height: 100%;
		}
		.show{
			margin-top: unset !important;
		}

		.toug_back:hover{ color:white !important; text-decoration:none !important}
		.commonMenu{z-index: 10001;}
		.blbg_new{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
		.adminReply{
			background-color:#ddf3ff;
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
					<div class="shou">
						<span>文章詳情</span>
						<font>Article</font>
						<a href="{{ $goBackPage }}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
					</div>
					<div>
						@if(Request()->get('article')=='law_protection_sample')
							@php
								$admin_info=\App\Models\User::leftJoin('user_meta', 'users.id','=','user_meta.user_id')->where('users.id', 1049)->first();
							@endphp
							<div class="toug_xq" style="position: relative;">
								<div class="tougao_xnew">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $admin_info->name }}<i class="tou_fi">2023-05-30 16:40</i></span>
									</div>
								</div>
								<div class="xq_text">窈窕淑女人見人愛怎麼追?君子的妙法寶就是要尊重!</div>
								<div class="xq_text01">
									<span style="font-size: 16px;font-weight: bold;">小王愛慕小美追求未果因而心生怨恨，在花園網發現與小美相似的女會員照片，將其截圖私下散布於工作群組，經起訴判刑小王加重毀謗罪，判處拘役1個月。</span><br><br>
									<div>&nbsp;&nbsp;「窈窕淑女、君子好逑。」不過若是一昧罔顧對方意願，甚而因為被拒絕惱羞成怒，做出種種逾越法律分際的行為，那可是會吃上官司的!這是一則根據事實案例判決改編的甜心網故事，奉勸各位想追求心儀的對象時，就是一定要先好好尊重女孩子，這才是君子們代代相傳、千古不敗的妙法寶喔!</div><br>
									<div>&nbsp;&nbsp;小王(基於個資法規定文中皆採化名)與小美(基於個資法規定文中皆採化名)因為工作關係認識以後，小王即屢傳訊息給小美，這讓小美感覺已經開始讓她困擾，只能先採取冷處理的態度因應。某天早上在小美至台北某大醫院附近時，小王卻突然靠近小美表示想跟她說說話，小美加快腳步轉進醫院地下街的商店尋求店員的協助，小王見狀先在商店門口外徘徊，過沒多久欲直接進入店內，小王在店員的阻攔下大聲嚷嚷，威脅小美若再不肯跟他說話，他就要在小美上班的公司散布對小美不利的消息，說罷小王才悻悻然地離開店家，而直至小美同事到來後，小美在同事的陪同下安然離開。</div><br>
									<div>&nbsp;&nbsp;「小王在這件事情過了大約兩週後，他在花園網截圖了多張長相疑似小美的會員照片，並附加了很多不雅的揣測詞句在公務群組裡散佈，例如 : 「很可能是某人的秘密」、「她是會為了自己目的，不擇手段到連身體都會賣的人，她有在做包養的賣喔」等等….後續經小美輾轉從同事收到訊息關心她發生了甚麼事後，赫然發現自己莫名其妙的平白受辱，因此憤而報警。</div><br>
									<div>&nbsp;&nbsp;「經由檢察官提起公訴開庭時，小王還試圖為自己的行為辯解，他聘請了律師幫其主張 : 「公司群組是屬於私人群組必須被邀請才能加入，所以他不算是在公眾散布流言因此並沒有構成毀謗。」此案件法官最後裁定，小王觸犯了文字加重毀謗罪，判處拘役1個月。</div>
								</div>
							</div>
						@else
						<div class="toug_xq" style="position: relative; {{ $postDetail->uid==1049 ? 'background:#ddf3ff;' : ''}} @if($postDetail->top==1) background:#ffcf869e !important; @endif">
							<div class="tougao_xnew">
								@php
									$uID=\App\Models\User::findById($postDetail->uid);
                                    $isBlurAvatar = \App\Services\UserService::isBlurAvatar($uID, $user);
								@endphp
								<a href="/dashboard/viewuser{{$uID->isVVIP()?'_vvip':null}}/{{$postDetail->uid}}?via_by_essence_article_enter={{ $postDetail->pid }}">
									<div class="tou_img_1">
										<div class="tou_tx_img @if($isBlurAvatar) blur_img @endif"><img src="@if(file_exists( public_path().$postDetail->umpic ) && $postDetail->umpic != ""){{$postDetail->umpic}} @elseif($postDetail->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $postDetail->uname }}<i class="tou_fi">{{ date('Y-m-d H:i',strtotime($postDetail->pupdated_at)) }}</i></span>
									</div>
								</a>
								{{--<div class="tog_time">{{ date('Y-m-d H:i',strtotime($postDetail->pcreated_at)) }}</div>--}}
							</div>
							@if(auth()->user()->id ==1049 || $postDetail->uid == auth()->user()->id)
{{--								<div class="ap_but" style="margin-top: 10px; margin-right:5px;">--}}
{{--									<a id="repostLink" href="/dashboard/postsEdit/{{ $postDetail->pid }}/all"><span class="iconfont icon-xiugai_nn"></span>修改</a>--}}
{{--									<a onclick="postDelete({{ $postDetail->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>--}}
{{--								</div>--}}
								<div class="ap_butnew" style="margin-top: 10px; margin-right:10px;">
									@if($postDetail->pdeleted_at == null)
										@if(auth()->user()->id == 1049)
											<a onclick="postVerifyStatus({{ $postDetail->pid }})" class="sc_cc verify_text">{{ $postDetail->pverify_status==0 ? '尚未審核' : ($postDetail->pverify_status==1 ? '通過':'取消通過' ) }}</a>
										@endif
										<a onclick="postDelete({{ $postDetail->pid }})" class="sc_cc"><img src="/posts/images/del_03n.png">刪除</a>
										<a id="repostLink" href="/dashboard/essence_postsEdit/{{ $postDetail->pid }}/all" class="sc_cc"><img src="/posts/images/xiugai.png">修改</a>
									@endif
									@if($postDetail->pdeleted_at != null && auth()->user()->id == 1049)
										<a onclick="recover_post({{ $postDetail->pid }});" class="sc_cc">回復文章</a>
									@endif
								</div>
							@endif
							<div id="ptitle" class="xq_text">{{ $postDetail->ptitle }}</div>
							<div id="pcontents" class="xq_text01">{!! \App\Models\Posts::showContent($postDetail->pcontents) !!}</div>
							{{--<div class="xq_textbot"><img src="/posts/images/tg_10.png"></div>--}}
						</div>
						@endif
						<div class="botline_fnr" style="margin-bottom:0px;"></div>
						<!--  -->
						<style>
							.dropup,
							.dropdown {
								position: absolute;
								right: 0;
								top: 0;
							}
							.tgxq_nr li{
								padding: unset;
							}
						</style>
						<!--  -->
					</div>
				</div>
			</div>
		</div>
		{{--<div class="botfasont">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-md-10" style=" float: right ;">
						<div class="bot_wid">
							<div class="bot_wid_nr">
								<form action="/dashboard/posts_reply" id="posts" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
									<input type="hidden" name="article_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="reply_id" id ="reply_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="tag_user_id" id ="tag_user_id" value="">
									<div class="bot_nnew">
										<div id="tagAcc" class="blue" style=" padding-top: 2px; margin-left:8px;"></div>
										<textarea id="contents" name="contents" rows="1" class="select_xx05 bot_input" placeholder="回應此篇文章"></textarea>
									</div>
									<button id="response_send" type="submit" class="bot_cir_1" style="border: none;"></button>
									--}}{{--<button id="response_send" type="submit" class="bot_cir" style="border: none;"><i class="iconfont icon-fasong bot_fs"></i></button>--}}{{--
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>--}}

		<!--弹框-->
		<div class="blbg_new" onclick="gmBtn1()" style="display: none;"></div>
		<div class="bl bl_tab bl_tab_01" id="tab_title" style="display: none;">
			<div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
			<div class="n_blnr02 matop10">
				<div class="n_fengs" style="text-align:center;width:100%;">{{ Session::get('message') }}</div>
			</div>
			<a onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
		</div>
@stop

@section('javascript')
	<script>

		function readyNumber() {

			$('textarea').each(function () {
				this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
			}).on('input', function () {
				this.style.height = 'auto';
				this.style.height = (this.scrollHeight) + 'px';
				//alert((this.scrollHeight) + 'px');

				var textAreaHeight = parseInt(this.scrollHeight);
				if($("#tagAcc").text()!==''){
					textAreaHeight = parseInt(this.scrollHeight+22);
				}
				if(textAreaHeight<50){
					textAreaHeight=50;
				}
				$(".bot_nnew").css('height',textAreaHeight + 'px');
				$("#response_send").css('margin-top',(textAreaHeight-34) + 'px');
			})
		}

		readyNumber();

		function gmBtn1(){
			$(".blbg_new").hide();
			$(".bl").hide();
		}

		function show(index) {
			$('.needToHide_'+index).show();
			document.getElementById('btn_'+index).innerHTML = "收起更多>";
			document.getElementById('btn_'+index).href = "javascript:hide("+index+");";
		}

		function hide(index) {
			$('.needToHide_'+index).hide();
			document.getElementById('btn_'+index).innerHTML = "展開更多>";
			document.getElementById('btn_'+index).href = "javascript:show("+index+");";
		}

		function postReply(pid, tag_name, tag_user_id) {
			$('#reply_id').val(pid);
			$('#tag_user_id').val(tag_user_id);
			$('#tagAcc').text('@'+tag_name);
		}

		function postDelete(pid) {
			c4('確定要刪除嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/essence_posts_delete?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid
					},
					dataType: 'json',
					success: function(data) {
						c5(data.msg);
						window.location.href=data.redirectTo;
					}
				});
			});
		}

		$(document).on('click','.announce_bg, #tab05',function() {
			window.location.reload();
		});

		$('#response_send').on('click', function() {

			{{--var checkUserVip='{{ $checkUserVip }}';--}}
			{{--var checkProhibit='{{ $user->prohibit_posts }}';--}}
			{{--var checkAccess='{{ $user->access_posts }}';--}}
			{{--if(checkUserVip==0) {--}}
			{{--	c5('此功能目前開放給連續兩個月以上的VIP會員使用');--}}
			{{--	return false;--}}
			{{--}else if(checkProhibit==1){--}}
			{{--	c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else if(checkAccess==1){--}}
			{{--	c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else{--}}
				var form = $(this).closest("form");
				if($("#contents").val().length ==0){
					c5('請輸入文字再送出');
					return false;
				}
				form.submit();
			// }
		});

		$('.bot_wid_nr').on('click', function() {

			{{--var checkUserVip='{{ $checkUserVip }}';--}}
			{{--var checkProhibit='{{ $user->prohibit_posts }}';--}}
			{{--var checkAccess='{{ $user->access_posts }}';--}}
			{{--if(checkUserVip==0) {--}}
			{{--	c5('此功能目前開放給連續兩個月以上的VIP會員使用');--}}
			{{--	return false;--}}
			{{--}else if(checkProhibit==1){--}}
			{{--	c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else if(checkAccess==1){--}}
			{{--	c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}--}}
		});

		$(document).keydown(function (event) {
			if (event.keyCode == 8 || event.keyCode == 46) {
				if($("#contents").val().length ==0){
					$('#tagAcc').text('');
				}
			}
		});

		function reposts() {
			$('#repostLink').hide();
			$('#ptitle').hide();
			$('#pcontents').hide();
		}

		function recover_post(pid)
		{
			c4('確定要回復嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/posts_recover?{{ csrf_token() }}={{now()->timestamp}}',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						pid: pid
					},
					success: function(data) {
						if(data.postType=='main'){
							c5(data.msg);
							window.location.href=data.redirectTo;
						}
						else
							c5(data.msg);
					}
				});
			});
		}



		function postVerifyStatus(pid) {
			var verify_str=$('.verify_text').text();
			if(verify_str=='通過' || verify_str=='尚未審核'){
				verify_str='通過審核';
			}
			c4('確定要'+verify_str+'嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/essence_verify_status?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid
					},
					dataType: 'json',
					success: function(data) {
						c5(data.msg);
						window.reload();
					}
				});
			});
		}

	</script>
@endsection