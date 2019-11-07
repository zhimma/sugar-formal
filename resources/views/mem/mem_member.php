<!DOCTYPE html>
<html lang="en">
@php dump('123')@endphp
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>男會員</title>
		<!-- Bootstrap -->
		<link href="/css/mem/bootstrap.min.css" rel="stylesheet">
		<link href="/css/mem/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/css/mem/style.css">
		<link rel="stylesheet" href="/css/mem/swiper.min.css">
		<script src="/js/mem/bootstrap.min.js"></script>
		<script src="/js/mem/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/js/mem/main.js" type="text/javascript"></script>

	</head>
	<style>
		.swiper-container {
			width: 100%;
			height: auto;
		}
		
		.swiper-slide {
			width: 100%;
			height: 280px;
			margin: 0 auto;
			padding: 0px;
			display: table
		}
		
		.swiper-slide img {
			width: 100%;
			height: 100%;
		}
		
		@media (max-width:767px) {
			.swiper-container {
				width: 100%;
				height: auto;
			}
			.swiper-slide {
				width: 100%;
				height: 200px !important;
				margin: 0 auto;
				padding: 0px;
				display: table
			}
			.swiper-slide img {
				width: 100%;
				height: 100%;
			}
		}
		@media (max-width:992px) {
			.swiper-container {
				width: 100%;
				height: auto;
			}
			.swiper-slide {
				width: 100%;
				height: 280px;
				margin: 0 auto;
				padding: 0px;
				display: table
			}
			.swiper-slide img {
				width: 100%;
				height: 100%;
			}
		}
		
		
	</style>

	<body>
		<div class="head hetop">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12"><img src="/img/mem/icon_41.png" class="logo" />
				</div>
			</div>
		</div>
		<div class="head heicon">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<img src="/img/mem/icon_41.png" class="logo" />
							<span id="menuButton"><img src="/img/mem/icon.png" class="he_img"></span>
						</div>
                        
						<ul id="menuList" class="change marg30">
                            <div class="comt"><img src="/img/mem/t.png"></div>
                            <div class="coheight">
							<div class="heyctop">測試系統賬號</div>
							<div class="helist">
								<ul>
									<li><a href=""><img src="/img/mem/icon_38.png">搜索</a></li>
									<li><a href=""><img src="/img/mem/icon_45.png">訊息</a><span>10</span></li>
									<li><a href=""><img src="/img/mem/icon_46.png">名單</a></li>
									<li><a href=""><img src="/img/mem/icon_48.png">我的</a></li>
								</ul>
							</div>
							<a href="" class="tcbut">LOGOUT</a>
                            </div>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!---->
		<div class="container matop80">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					<div class="leftbg">
						<div class="leftimg"><img src="/img/mem/icon_03.png">
							<h2>測試系統賬號</h2></div>
						<div class="leul">
							<ul>
									<li><a href=""><img src="/img/mem/icon_38.png">搜索</a></li>
									<li><a href=""><img src="/img/mem/icon_45.png">訊息</a><span>10</span></li>
									<li><a href=""><img src="/img/mem/icon_46.png">名單</a></li>
									<li><a href=""><img src="/img/mem/icon_48.png">我的</a></li>
								    <li><a href=""><img src="/img/mem/iconout.png">退出</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="rightbg">
						<div class="metx">
							<div class="swiper-container photo">
								<div class="swiper-wrapper">
									<div class="swiper-slide"><img src="/img/mem/icon_03.png"></div>
									<div class="swiper-slide"><img src="/img/mem/icon_04.png"></div>
									<div class="swiper-slide"><img src="/img/mem/icon_03.png"></div>
								</div>
								<!-- Add Arrows -->
								<div class="swiper-button-next"></div>
								<div class="swiper-button-prev"></div>
							</div>
                             <div class="n_jianj"><a href="">檢舉大頭照</a></div>
							<div class="tubiao">
								<ul>
									<li>
										<a onclick="cl()"><img src="/img/mem/icon_06.png" class="tubiao_i"><span>發信</span></a>
									</li>
									<li>
										<a onclick="c2()"><img src="/img/mem/icon_08.png" class="tubiao_i"><span>收藏</span></a>
									</li>
									<li>
										<a onclick="c3()"><img src="/img/mem/icon_10.png" class="tubiao_i"><span>檢舉</span></a>
									</li>
									<li>
										<a onclick="c4()"><img src="/img/mem/icon_12.png" class="tubiao_i"><span>封鎖</span></a>
									</li>
								</ul>
							</div>
							<div class="bottub">
								<ul>
									<li><img src="/img/mem/icon_19.png"><span>新進甜心</span></li>
									<li><img src="/img/mem/icon_21.png"><span>優選會員</span></li>
									<li><img src="/img/mem/icon_23.png"><span>財力認證</span></li>
									<li><img src="/img/mem/icon_25.png"><span>VIP</span></li>
									<li><img src="/img/mem/icon_27.png"><span>警示帳戶</span></li>
								</ul>
							</div>

						</div>
						<!-- Swiper JS -->
						<script src="/js/mem/swiper.min.js"></script>
						<!-- Initialize Swiper -->
						<script>
							var swiper = new Swiper('.swiper-container', {
								pagination: '.swiper-pagination',
								nextButton: '.swiper-button-next',
								prevButton: '.swiper-button-prev',
								slidesPerView: 1,
								paginationClickable: true,
								spaceBetween: 30,
								loop: true
							});
						</script>
					</div>
					<!--基本资料-->
					<div class="mintop">
						<div class="">
							<div class="ziliao">
								<div class="ztitle"><span>基本資料</span>Basic information</div>
								<div class="xiliao_input">
									<div class="xl_input">
										<dt>
                                                        <span>地區</span>
                                                        <span><input name="" type="text" class="select_xx senhs"  placeholder="連江縣">
                                                        <input name="" type="text" class="select_xx senhs right"  placeholder="南竿鄉"></span>
                                                    </dt>
										
										<dt>
                                                        <span>預算</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="可商議"></span>
                                                    </dt>
										<dt>
                                                        <span>年齡</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="28"></span>
                                                    </dt>
										<dt>
                                                        <span>身高（cm）</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="160"></span>
                                                    </dt>
										<dt>
                                                        <span>體型</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="瘦"></span>
                                                    </dt>
										<dt>
                                                        <span>CUP</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="A"></span>
                                                    </dt>
										<dt>
                                                        <span>關於我</span>
                                                        <span><div class="select_xx03" >喜歡簡單自然平談喜歡簡單自然平談喜歡簡單自然平談喜歡簡單自然平談喜歡簡單自然平談喜歡簡單自然平談喜歡簡單自然平談素質高喜歡簡單自然平談喜歡簡單自然平談喜歡簡單自然平談素質高</div></span>
                                                    </dt>
										<dt>
                                                        <span>期待的約會模式</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="看感覺"></span>
                                                    </dt>
										<dt>
                                                        <span>產業</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="民生服務"></span>
                                                    </dt>
										<dt>
                                                        <span>職業</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="公務人員"></span>
                                                    </dt>
										<dt>
                                                        <span>教育</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="研究生"></span>
                                                    </dt>
										<dt>
                                                        <span>婚姻</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="未婚"></span>
                                                    </dt>
										<dt>
                                                        <span>喝酒</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="喝"></span>
                                                    </dt>
										<dt>
                                                        <span>抽煙</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="不抽"></span>
                                                    </dt>
										<dt>
                                                        <span>收入</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="100—200萬"></span>
                                                    </dt>
										<dt>
                                                        <span>資產</span>
                                                        <span><input name="" type="text" class="select_xx01 senhs"  placeholder="房地產"></span>
                                                    </dt>
									</div>
								</div>
							</div>
							<div class="line"></div>
							<div class="ziliao">
								<div class="ztitle"><span>進階資料</span>Advanced materials</div>
								<div class="xiliao_input">
									<div class="xl_text">
										<dt><span>帳號建立時間</span><font>2018-02-03  12:20:36</font></dt>
										<dt><span>登入時間</span><font>2018-02-03  12:20:36</font></dt>
										<dt><span>被收藏次數</span><font><img src="/img/mem/icon_35.png"></font></dt>
										<dt><span>收藏會員次數</span><font>26</font></dt>
										<dt><span>車馬費邀請次數</span><font>36</font></dt>
										<dt><span>發信次數</span><font>20</font></dt>
										<dt><span>過去7天發信次數</span><font>20</font></dt>
										<dt><span>是否封鎖我</span><font>是</font></dt>
										<dt><span>是否看過我</span><font>否</font></dt>
										<dt><span>瀏覽其他會員次數</span><font>20</font></dt>
										<dt><span>被瀏覽次數</span><font>20</font></dt>
										<dt><span>過去7天被瀏覽次數</span><font>20</font></dt>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--基本资料-->
				</div>

			</div>
		</div>

		<div class="bot">
			<a href="">站長開講</a> 丨
			<a href=""> 網站使用</a> 丨
			<a href=""> 使用條款</a> 丨
			<a href=""> 聯絡我們</a>
			<img src="/img/mem/bot_10.png">
		</div>
        
<div class="blbg" onclick="gmBtn1()"></div>
<div class="bl bl_tab" id="tab01">
    <div class="bltitle"><span>發送給HEIGH</span></div>
    <div class="n_blnr01 ">
    <textarea name="" cols="" rows="" class="n_nutext">請輸入內容</textarea>
    <a class="n_bllbut" href="">發信件</a>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/img/mem/gb_icon.png"></a>
</div>


<div class="bl gtab" id="tab02">
<a href="" class="gxbut">收藏成功！</a>
</div>



<div class="bl bl_tab" id="tab03">
    <div class="bltitle"><span>檢舉HAY</span></div>
    <div class="n_blnr01 ">
    <textarea name="" cols="" rows="" class="n_nutext">請輸入檢舉理由</textarea>
    <div class="n_bbutton">
        <span><a class="n_left" href="">送出</a></span>
        <span><a class="n_right" href="">返回</a></span>
    </div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/img/mem/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tab04">
    <div class="bltitle"><span>是否要封鎖他</span></div>
    <div class="n_blnr01 matop20">
         <div class="n_fengs"><img src="/img/mem/iconff_03.png"><span>對方不會知道您封鎖他 </span></div>
         <div class="n_fengs"><img src="/img/mem/iconff_06.png"><span>會將對方顯示為退會的用戶</span></div>
         <div class="n_fengs"><img src="/img/mem/iconff_08.png"><span>可從設定頁面的[已封鎖用戶名單]中解除</span></div>
        <a class="n_bllbut matop30" href="">封鎖</a>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/img/mem/gb_icon.png"></a>
</div>


<script>
   
	function cl() {
		 $(".blbg").show()
         $("#tab01").show()
    }
	
	function c2() {
		 $(".blbg").show()
         $("#tab02").show()
    }
	function c3() {
		 $(".blbg").show()
         $("#tab03").show()
    }
	function c4() {
		 $(".blbg").show()
         $("#tab04").show()
    }
	
	
    function gmBtn1(){
        $(".blbg").hide()
        $(".bl").hide()	
			
    }
    
	
</script>





	</body>

</html>