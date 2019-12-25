@extends('new.layouts.website')

@section('app-content')
		<!---->
		<div class="container matop80">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					<div class="leftbg">
						<div class="leftimg"><img src="/new/images/icon_03.png">
							<h2>測試系統賬號</h2></div>
						<div class="leul">
							<ul>
									<li><a href="/new/mem_member"><img src="/new/images/icon_38.png">搜索</a></li>
									<li><a href="/dashboard/chat2"><img src="/new/images/icon_45.png">訊息</a><span>10</span></li>
									<li><a href="/browse"><img src="/new/images/icon_46.png">名單</a></li>
									<li><a href="/dashboard"><img src="/new/images/icon_48.png">我的</a></li>
								    <li><a href="/logout"><img src="/new/images/iconout.png">退出</a></li>
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
                             <div class="n_jianj"><a class="report_avatar">檢舉大頭照</a></div>
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
									<li><a href="#"><img src="/new/images/icon_19.png"><span>新進甜心</span></a></li>
									<li><a href="#"><img src="/new/images/icon_21.png"><span>優選會員</span></a></li>
									<li><a href="#"><img src="/new/images/icon_23.png"><span>財力認證</span></a></li>
									<li><a href="#"><img src="/new/images/icon_25.png"><span>VIP</span></a></li>
									<li><a href="#"><img src="/new/images/icon_27.png"><span>警示帳戶</span></a></li>
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
					<form action="#" method="POST" id="basic_info">
					<input type="hidden" name="id" id="id" value="{{$user->user_id}}">
					<div class="mintop">
						<div class="">
							<div class="ziliao">
								<div class="ztitle"><span>基本資料</span>Basic information</div>
								<!-- <div class="updatedata">更新</div> -->
								<div class="xiliao_input">
									<div class="xl_input">
										<dt>
                                                        <span>地區</span>
                                                        <span>
														<input name="city" type="text" class="select_xx senhs" id="city" placeholder="<?=$user->city;?>" value="<?=$user->city;?>">
                                                        <input name="area" type="text" class="select_xx senhs right" id="area" placeholder="<?=$user->area;?>" value="<?=$user->area;?>">
														</span>
                                                    </dt>
										
										<dt>
                                                        <span>預算</span>
                                                        <span><input name="budget" type="text" class="select_xx01 senhs" id="budget" placeholder="<?=$user->budget;?>" value="<?=$user->budget;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>年齡</span>
                                                        <span><input name="age" type="text" class="select_xx01 senhs" id="age" placeholder="<?=$user->age;?>" value="<?=$user->age;?>"></span><!--需新增資料庫資料-->
                                                    </dt>
										<dt>
                                                        <span>身高（cm）</span>
                                                        <span><input name="height" type="text" class="select_xx01 senhs" id="height" placeholder="<?=$user->height;?>" value="<?=$user->height;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>體型</span>
                                                        <span><input name="body" type="text" class="select_xx01 senhs" id="body" placeholder="<?=$user->body;?>" value="<?=$user->body;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>CUP</span>
                                                        <span><input name="cup" type="text" class="select_xx01 senhs" id="cup" placeholder="<?=$user->cup;?>" value="<?=$user->cup;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>關於我</span>
                                                        <span><div name="title" class="select_xx03" id="title"><?=$user->title;?></div></span>
                                                    </dt>
										<dt>
                                                        <span>期待的約會模式</span>
                                                        <span><input name="date_mode" type="text" class="select_xx01 senhs" id="date_mode" placeholder="看感覺"></span><!--需新增資料庫資料-->
                                                    </dt>
										<dt>
                                                        <span>產業</span>
                                                        <span><input name="domainType" type="text" class="select_xx01 senhs" id="domainType" placeholder="<?=$user->domainType;?>" value="<?=$user->domainType;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>職業</span>
                                                        <span><input name="occupation" type="text" class="select_xx01 senhs" id="occupation" placeholder="公務人員"></span><!--需新增資料庫資料-->
                                                    </dt>
										<dt>
                                                        <span>教育</span>
                                                        <span><input name="education" type="text" class="select_xx01 senhs" id="education" placeholder="研究生"></span><!--需新增資料庫資料-->
                                                    </dt>
										<dt>
                                                        <span>婚姻</span>
                                                        <span><input name="marriage" type="text" class="select_xx01 senhs" id="marriage" placeholder="<?=$user->marriage;?>" value="<?=$user->marriage;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>喝酒</span>
                                                        <span><input name="drinking" type="text" class="select_xx01 senhs" id="drinking" placeholder="<?=$user->drinking;?>" value="<?=$user->drinking;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>抽煙</span>
                                                        <span><input name="smoking" type="text" class="select_xx01 senhs" id="smoking" placeholder="<?=$user->smoking;?>" value="<?=$user->smoking;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>收入</span>
                                                        <span><input name="income" type="text" class="select_xx01 senhs" id="income" placeholder="<?=$user->income;?>" value="<?=$user->income;?>"></span>
                                                    </dt>
										<dt>
                                                        <span>資產</span>
                                                        <span><input name="assets" type="text" class="select_xx01 senhs" id="assets" placeholder="<?=$user->assets;?>" value="<?=$user->assets;?>"></span>
                                                    </dt>
									</div>
								</div>
							</div>
							<div class="line"></div>
							<div class="ziliao">
								<div class="ztitle"><span>進階資料</span>Advanced materials</div>
								<div class="xiliao_input">
									<div class="xl_text">
										<dt><span>帳號建立時間</span><font><?=$user->created_at;?></font></dt>
										<dt><span>登入時間</span><font><?=$user->last_login;?></font></dt>
										<dt><span>被收藏次數</span><font><img src="/new/images/icon_35.png"><?php if($is_vip){ echo $be_fav_count??0;}?></font></dt>
										<dt><span>收藏會員次數</span><font><?=$fav_count??0;?></font></dt>
										<dt><span>車馬費邀請次數</span><font><?=$tip_count??0;?></font></dt>
										<dt><span>發信次數</span><font><?=$message_count??0;?></font></dt>
										<dt><span>過去7天發信次數</span><font><?=$message_count_7??0;?></font></dt>
										<dt><span>是否封鎖我</span><font><?=$is_block_mid??'否';?></font></dt>
										<dt><span>是否看過我</span><font><?=$is_visit_mid??'是';?></font></dt>
										<dt><span>瀏覽其他會員次數</span><font><?=$visit_other_count??0?></font></dt>
										<dt><span>被瀏覽次數</span><font><?=$be_visit_other_count??0;?></font></dt>
										<dt><span>過去7天被瀏覽次數</span><font><?=$be_visit_other_count_7??0;?></font></dt>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--基本资料-->
					</form>
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
    <div class="n_bllbut addmessage">發信件</div>
    </div>
    <a id="" onclick="gmBtn1()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
</div>


<div class="bl gtab addcollection" id="tab02">
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
        <a class="n_bllbut matop30 addblock">封鎖</a>
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



<script>
$(".report_avatar").on('click', function(){
	var msg = "您確定要檢舉大頭貼嗎?"; 
	if (confirm(msg)==true){ 
		var data = {
			"_token"      : "{{ csrf_token() }}",
			id            : "{{$user->user_id}}",
		};
		$.ajax({
			type: "POST",
			url: "/addReportAvatar",
			data: data,
			dataType: "json",
			success: function(res){
				if(res.code=='200'){
					alert(res.msg);
					gmBtn1();
				}
			}
		});
	}

});

$(".collection").on('click', function(){
	console.log('collection')
	var data = {
		"_token"      : "{{ csrf_token() }}",
		id            : "{{$user->user_id}}",
	};
	$.ajax({
    type: "POST",
    url: "/addCollection",
    data: data,
    dataType: "json",
    success: function(res){
		if(res.code=='200'){
			alert(res.msg);
		}
    }
});
});
$(".addmessage").on('click', function(){
	console.log('message')
	var data = {
		"_token"      : "{{ csrf_token() }}",
		id            : "{{$user->user_id}}",
		msg           : $(".n_nutext").val(),
	};
	$.ajax({
    type: "POST",
    url: "/addMessage",
    data: data,
    dataType: "json",
    success: function(res){
		if(res.code=='200'){
			alert(res.msg);
			gmBtn1();
		}
    }
});
});
$(".report").on('click', function(){
	console.log('report')
	var data = {
		"_token"      : "{{ csrf_token() }}",
		id            : "{{$user->user_id}}",
	};
	$.ajax({
    type: "POST",
    url: "/addReport",
    data: data,
    dataType: "json",
    success: function(res){
		if(res.code=='200'){
			alert(res.msg);
			gmBtn1();
		}
    }
});
});
$(".addblock").on('click', function(){
	console.log('block');
	var data = {
		"_token"      : "{{ csrf_token() }}",
		id            : "{{$user->user_id}}",
	};
	$.ajax({
    type: "POST",
    url: "/addBlock",
    data: data,
    dataType: "json",
    success: function(res){
		if(res.code=='200'){
			alert(res.msg);
			gmBtn1();
		}
    }
});
});
$(".updatedata").click(function(){
	var data = {
		"_token"      : "{{ csrf_token() }}",
		"data"        :$("form").serializeArray()
	};

	console.log(data);
	$.ajax({
    type: "POST",
    url: "/updateMemberData",
    data: data,
    // dataType: "json",
    success: function(res){

    }
  });
});


</script>




@stop

</html>