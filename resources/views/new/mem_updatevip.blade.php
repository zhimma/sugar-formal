 <!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="format-detection" content="telephone=no" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>男升級VIP</title>
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
<script>
function changediv(id){
document.getElementById("vip").style.display="none";
document.getElementById("vip2").style.display="none";
document.getElementById("vip_a").className="";
document.getElementById("vip2_a").className="";
document.getElementById(id).style.display="table";
document.getElementById(id+"_a").className="n_viphover";
 return false;
}
</script>

	<body>
		
		<div class="head hetop">
			<div class="container">
				<div class="col-sm-12 col-xs-12 col-md-12">
					<a href="/"><img src="/new/images/icon_41.png" class="logo" /></a>
				</div>
			</div>
		</div>
		<div class="head heicon" >
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
		<div class="container matop70">
			<div class="row">
                <div class="col-sm-2 col-xs-2 col-md-2 dinone">
					<div class="leftbg">
						<div class="leftimg"><img src="/new/images/icon_03.png">
							<h2>測試系統賬號</h2></div>
						<div class="leul">
							<ul>
									<li><a href="/new/mem_search"><img src="/new/images/icon_38.png">搜索</a></li>
									<li><a href="/dashboard/chat2"><img src="/new/images/icon_45.png">訊息</a><span>10</span></li>
									<li><a href="/browse"><img src="/new/images/icon_46.png">名單</a></li>
									<li><a href="/dashboard"><img src="/new/images/icon_48.png">我的</a></li>
								    <li><a href="/logout"><img src="/new/images/iconout.png">退出</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					    <div class="g_password">
                             <div class="g_pwicon">
                                  <li><a href="/dashboard"><img src="/new/images/mm_03.png"><span>基本資料</span></a></li> 
                                  <li><a href="/dashboard_img"><img src="/new/images/mm_05.png"><span>照片管理</span></a></li> 
                                  <li><a href="/dashboard"><img src="/new/images/mm_07.png"><span>更改密碼</span></a></li> 
                                  <li><a href="/dashboard"><img src="/new/images/mm_18.png"><span>VIP</span></a></li> 
                             </div>       
                             <div class="n_viptitle">
                                  <a href="#" onclick='return changediv("vip")' id="vip_a" class="n_viphover" target=_parent>升级VIP</a>
                                  <a href="#" onclick='return changediv("vip2")'id="vip2_a" target=_parent>取消VIP</a>
                             </div>
                            <div class="n_sjvip"  id="vip">
                                 <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                                 <div class="viplist">
                                            <ul>
                                                <li>
                                                    <div class="vipcent">cooming soon</div>
                                                </li>
                                                <li>
                                                    <div class="vipfont viptop15">
                                                        <h2>888</h2>
                                                        <h3>$NTD/每月</h3>
                                                    </div>
                                                    <div class="vipcion"><img src="/new/images/bicon.png"></div>
                                                </li>
                                                <li>
                                                    <div class="vipcent">cooming soon</div>
                                                    <div class="vipcion"></div>
                                                </li>
                                            </ul>
                                </div>
								<form class="m-form m-form--fit" action="http://localsugargarden.org/dashboard/esafeCreditCard" method="post" id="Form">
									<input type="hidden" name="_token" value="{{ csrf_token() }}" >
									<input type="hidden" name="userId" value="{{$mid}}" id="userId">
									<div class="n_vipbut">
										<span>
											<a  id="buyvip1" class="n_vip01 v_butleft">購買方式1</a>
										</span>
										<span><a  id="buyvip2" class="n_vip01 v_butright">購買方式2
											</a>
										</span>
									</div>
								</form>
                               <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                               <div class="vipbongn">
                                        <h2>VIP功能</h2>
                                        <h3><span>●</span>解鎖信箱限制</h3>
                                        <h3><span>●</span>解鎖發訊限制</h3>
                                        <h3><span>●</span>解鎖足跡功能</h3>
                                        <h3><span>●</span>解鎖進階搜尋功能</h3>
                                        <h3><span>●</span>解鎖車馬費評價功能</h3>
                                        <h3><span>●</span>可以看進階資料</h3>
                                        <h3><span>●</span>可以看已讀未讀</h3>
                                        <h3><span>●</span>擁有vip title 並取得優選會原累積資格</h3>
                              </div> 
                              <div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>
                                 
                                        
                                        
                                        
                         
                             </div>      
							
                            <div class="de_input n_viptop20 n_viphig"  id="vip2" style="display:none">
                                  <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_03.png"></div><input name="acc" id="acc" type="text" class="d_input" placeholder="帳號 (您的Email)"></div>
                                  <div class="de_input01 dlmarbot"><div class="de_img"><img src="/new/images/lo_11.png"></div><input name="pwd" id="pwd" type="password" class="d_input" placeholder="密碼"></div>
								  <input type="hidden" name="userId" value="{{$mid}}" id="userId">
                                  <a href="http://localsugargarden.org/password/reset" class="dlpassword">忘記密碼 ?</a>
                                  <a  class="dlbut" onclick="cl()">確認</a>
                            </div>      
                                                       
                        </div>
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
<div class="gtab" id="tab01">
<a  href="" class="gxbut">更新成功！</a>
</div>

<script>
	function cl() {
		var r=confirm("確認取消VIP?")
		if (r==true)
		{
			$.ajax({
			type: 'POST',
			url: "/cancelVip",
			data:{
				_token: '{{csrf_token()}}',
				acc   : $("#acc").val(),
				pwd   : $("#pwd").val(),
				userId   : $("#userId").val(),
			},
			dataType:"json",
			success: function(res){
				if(res.code=='200'){
					$(".blbg").show();
					$("#tab01").show();
					location.reload();
				}else{
					alert('更改失敗');
					location.reload();
				}
			}});
		}

		
		 
    }
    function gmBtn1(){
        $(".blbg").hide()
        $(".gtab").hide()	
			
    }
</script>
<script>
$("#buyvip1").click(function(){
	$("#Form").submit();
});
</script>

	</body>

</html>