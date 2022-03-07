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
<script src="{{ mix('/js/app.js') }}"></script>
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
	<div id="app">
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou"><span>留言板</span>
						<font>Wishing Board</font>
						<a onclick="checkPosts()" class="xinzeng_but" style="cursor:pointer"><img src="/new/images/liuyan_03.png">新增留言</a>
					</div>
					<div class="liuy_qh">
						<ul>
							<li><a class="liy_hover" href="#" onclick='return changediv("eml")' id="eml_a" target=_parent >{{ $user->engroup==1 ? '她':'他' }}的留言</a></li>
							<li><a href="#" onclick='return changediv("eml2")' id="eml2_a" target=_parent >我的留言</a></li>
						</ul>
					</div>
					<script type="application/javascript">
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
						{{-- <div class="liuyan_nlist">
							<ul> --}}
								<div v-html="listOther"></div>
							{{-- </ul>
						</div> --}}
						<div class="fenye mabot30" v-html="other_pagination">
							<div class="fenye">
								<a href="">上一頁</a>
								<span class="new_page">第 1 頁</span>
								<a href="http://henry.test-tw.icu/MessageBoard/showList?msgBoardType=others_page&amp;othersDataPage=2">下一頁</a>
							</div>
						</div>
					</div>
					
					<div style="width: 100%; display: table;" id="eml2" style="display:none">
						{{-- <div class="liuyan_nlist">
							<ul> --}}
								<div v-html="listMyself"></div>
							{{-- </ul>
						</div> --}}
						<div class="fenye mabot30" v-html="myself_pagination">
							<div class="fenye">
								<a href="">上一頁</a>
								<span class="new_page">第 1 頁</span>
								<a href="http://henry.test-tw.icu/MessageBoard/showList?msgBoardType=others_page&amp;othersDataPage=2">下一頁</a>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>

	
<script type="application/javascript">
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

	var isAdminWarned ='{{ isset($data['isAdminWarned']) && $data['isAdminWarned'] }}';
	var isBanned ='{{ isset($data['isBanned']) && $data['isBanned'] }}';
	var post_too_frequently ='{{ isset($data['post_too_frequently']) && $data['post_too_frequently'] }}';

	function checkPosts() {
		if(isAdminWarned){
			c5('您目前為警示狀態，無法新增留言');
		}else if(post_too_frequently) {
			c5('您好，由於系統偵測到您的留言頻率太高(每封留言最低間隔 3hr)，為維護系統運作效率，請降低留言頻率。');
		}else{
			location.href= "/MessageBoard/posts";
		}
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


<script>
    const vm = new Vue({
            el: '#app',
            data () {
                return {
					"listOther":'<li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"> <div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>',
					"listMyself":'<li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"> <div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>  <li><a href="#"></a><a href="#"><div class="liuyan_prilist"><div class="liuyfont"><div class="liu_dq"> </div></div><div class="liu_text"><div class="liu_text_1"></div><div class="liu_text_2"></div></div></div></a></li>',
					"other_pagination":'<div class="fenye"><a href="#">上一頁</a><span class="new_page">第 - 頁</span><a href="#">下一頁</a></div>',
					"myself_pagination":'<div class="fenye"><a href="#">上一頁</a><span class="new_page">第 - 頁</span><a href="#">下一頁</a></div>',
				}
            },
        async mounted () {
				let uri = window.location.search.substring(1); 
    			let params = new URLSearchParams(uri);
				let myself_now_page = params.get('myself_now_page') != null ? params.get('myself_now_page') : 1;
				let other_now_page = params.get('other_now_page') != null ? params.get('other_now_page') : 1;
				let per_page_count = 10;
                await axios
                .post('/MessageBoard/showListOther', {myself_now_page:myself_now_page, other_now_page:other_now_page, per_page_count:per_page_count})
                .then(response => {
					console.log(response);
                    this.listOther = response.data.ssrData;

					let all_count = response.data.count;
					let other_last_page = response.data.other_last_page;
					let other_next_page = response.data.other_next_page;
					if(all_count>0){
						this.other_pagination = '<div class="fenye"><a href="/MessageBoard/showList?myself_now_page='+myself_now_page+'&other_now_page='+other_last_page+'">上一頁</a><span class="new_page">第 '+other_now_page+' 頁</span><a href="/MessageBoard/showList?myself_now_page='+myself_now_page+'&other_now_page='+other_next_page+'">下一頁</a></div>'
					}else{
						this.other_pagination = '';
					}
					
				})
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
   
                axios
                .post('/MessageBoard/showListMyself',{myself_now_page:myself_now_page, other_now_page:other_now_page, per_page_count:per_page_count})

                .then(response => {
					console.log(response);
                    this.listMyself = response.data.ssrData;

					let all_count = response.data.count;
					let myself_last_page = response.data.myself_last_page;
					let myself_next_page = response.data.myself_next_page;
					if(all_count>0){
						this.myself_pagination = '<div class="fenye"><a href="/MessageBoard/showList?myself_now_page='+myself_last_page+'&other_now_page='+other_now_page+'">上一頁</a><span class="new_page">第 '+myself_now_page+' 頁</span><a href="/MessageBoard/showList?myself_now_page='+myself_next_page+'&other_now_page='+other_now_page+'">下一頁</a></div>'
					}else{
						this.myself_pagination = '';
					}
					
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
        }
        });
</script>
@stop
