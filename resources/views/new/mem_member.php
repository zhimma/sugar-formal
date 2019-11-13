@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
      <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-12">
          <div class="wxsy">
             <div class="wxsy_title">網站使用</div>
             <div class="wxsy_k">
                          <div class="wknr">
                                <p>網站的建設方向是要更有效的過濾出真心包養的Daddy，篩選掉無聊的網蟲。 各位大叔可能不知道，一個女會員註冊，會收到數十封的邀約信。 漂亮一點的信件數量更是驚人。所以本站的規畫就是盡量凸顯真心包養大叔的經濟優勢。 目前有更進一步的功能規劃中，如果各位有什麼意見歡迎跟站長聯絡提出。<a href="{!! url('contact') !!}">(聯絡我們)</a></p>
                                <h3>目前已上線的功能 </h3>
                                <h4>1：加入VIP</h4>
                                <p>站長建議各位大叔加入VIP，目前VIP的比較重要的功能是可以無限制收發信，開啟已讀功能以及可以看進階的統計資料。未來會規畫更具財力顯示的VIP階級。 另一方面，VIP算是女方最基本的篩選門檻了，有些女生甚至會關掉普通會員的來信。只看VIP會員的來信。 </p>
                                <h4>2：車馬費邀請</h4>
                                <p>站方設計車馬費制度，為了篩選信口開河邀約的Daddy。也就增加了真心約見daddy的能見度。 車馬費制度會由站方先跟Daddy收取一筆1788的車馬費費用。雙方約見。 <span class="org">只要當天雙方順利見完面，不論結果如何，站方扣除部分手續費後，會將 1500 匯入女方指定的銀行帳戶。</span> 當然免不了有些女生會想辦法賺車馬費，網站目前的功能是會以曾經約會的會員可以留言評價(需VIP才能看到)，另一方面網站也會管控銀行帳戶，被太多人投訴的女會員，站方會停權。但站長必須說，無法100%杜絕，所以大家在使用車馬費邀約時，站長只能說這是必要的支出之一。</p>

                          </div>
             </div>
          </div>
        </div>
      </div>
    </div>

@stop



<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>男會員</title>
		<!-- Bootstrap -->
		<link href="/new/css/bootstrap.min.css" rel="stylesheet">
		<link href="/new/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- owl-carousel-->
		<!--    css-->
		<link rel="stylesheet" href="/new/css/style.css">
		<link rel="stylesheet" href="/new/css/swiper.min.css">
		<script src="/new/js/bootstrap.min.js"></script>
		<script src="/new/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="/new/js/main.js" type="text/javascript"></script>

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
				<div class="col-sm-12 col-xs-12 col-md-12"><img src="/new/images/icon_41.png" class="logo" />
				</div>
			</div>
		</div>
		<div class="head heicon">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="commonMenu">
						<div class="menuTop">
							<img src="/new/images/icon_41.png" class="logo" />
							<span id="menuButton"><img src="/new/images/icon.png" class="he_img"></span>
						</div>
                        
						<ul id="menuList" class="change marg30">
                            <div class="comt"><img src="/new/images/t.png"></div>
                            <div class="coheight">
							<div class="heyctop">測試系統賬號</div>
							<div class="helist">
								<ul>
									<li><a href=""><img src="/new/images/icon_38.png">搜索</a></li>
									<li><a href=""><img src="/new/images/icon_45.png">訊息</a><span>10</span></li>
									<li><a href=""><img src="/new/images/icon_46.png">名單</a></li>
									<li><a href=""><img src="/new/images/icon_48.png">我的</a></li>
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
						<div class="leftimg"><img src="/new/images/icon_03.png">
							<h2>測試系統賬號</h2></div>
						<div class="leul">
							<ul>
									<li><a href=""><img src="/new/images/icon_38.png">搜索</a></li>
									<li><a href=""><img src="/new/images/icon_45.png">訊息</a><span>10</span></li>
									<li><a href=""><img src="/new/images/icon_46.png">名單</a></li>
									<li><a href=""><img src="/new/images/icon_48.png">我的</a></li>
								    <li><a href=""><img src="/new/images/iconout.png">退出</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="rightbg">
						<div class="metx">
							<div class="swiper-container photo">
								<div class="swiper-wrapper">
									<div class="swiper-slide"><img src="/new/images/icon_03.png"></div>
									<div class="swiper-slide"><img src="/new/images/icon_04.png"></div>
									<div class="swiper-slide"><img src="/new/images/icon_03.png"></div>
								</div>
								<!-- Add Arrows -->
								<div class="swiper-button-next"></div>
								<div class="swiper-button-prev"></div>
							</div>
                             <div class="n_jianj"><a href="">檢舉大頭照</a></div>
							<div class="tubiao">
								<ul>
									<li>
										<a onclick="cl()"><img src="/new/images/icon_06.png" class="tubiao_i"><span>發信</span></a>
									</li>
									<li>
										<a onclick="c2()"><img src="/new/images/icon_08.png" class="tubiao_i"><span>收藏</span></a>
									</li>
									<li>
										<a onclick="c3()"><img src="/new/images/icon_10.png" class="tubiao_i"><span>檢舉</span></a>
									</li>
									<li>
										<a onclick="c4()"><img src="/new/images/icon_12.png" class="tubiao_i"><span>封鎖</span></a>
									</li>
								</ul>
							</div>
							<div class="bottub">
								<ul>
									<li><img src="/new/images/icon_19.png"><span>新進甜心</span></li>
									<li><img src="/new/images/icon_21.png"><span>優選會員</span></li>
									<li><img src="/new/images/icon_23.png"><span>財力認證</span></li>
									<li><img src="/new/images/icon_25.png"><span>VIP</span></li>
									<li><img src="/new/images/icon_27.png"><span>警示帳戶</span></li>
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
										<dt><span>被收藏次數</span><font><img src="/new/images/icon_35.png"></font></dt>
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
			<img src="/new/images/bot_10.png">
		</div>
        
<div class="blbg" onclick="gmBtn1()"></div>
<div class="bl bl_tab" id="tab01">
    <div class="bltitle"><span>發送給HEIGH</span></div>
    <div class="n_blnr01 ">
    <textarea name="" cols="" rows="" class="n_nutext">請輸入內容</textarea>
    <a class="n_bllbut" href="">發信件</a>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
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
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>

<div class="bl bl_tab" id="tab04">
    <div class="bltitle"><span>是否要封鎖他</span></div>
    <div class="n_blnr01 matop20">
         <div class="n_fengs"><img src="/new/images/iconff_03.png"><span>對方不會知道您封鎖他 </span></div>
         <div class="n_fengs"><img src="/new/images/iconff_06.png"><span>會將對方顯示為退會的用戶</span></div>
         <div class="n_fengs"><img src="/new/images/iconff_08.png"><span>可從設定頁面的[已封鎖用戶名單]中解除</span></div>
        <a class="n_bllbut matop30" href="">封鎖</a>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
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