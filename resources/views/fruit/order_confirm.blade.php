<!DOCTYPE html>
<html class="no-js" lang="en-US" prefix="og: http://ogp.me/ns#">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>訂單確認</title>
		<link href="/fruit/css/common.css" type="text/css" rel="stylesheet">
		<link href="/fruit/css/bootstrap.min.css" type="text/css" rel="stylesheet">
		<link href="/fruit/css/bootstrap-theme.min.css" type="text/css" rel="stylesheet">
		<script src="/fruit/js/jquery.min.js"></script>
	</head>
<body class="bg_v">
<div class="top">
    <div class="container">
        <div class="row">
            <div class="col-xs-12  col-sm-4  col-md-4 col-lg-4">
                 <img src="/fruit/images/logo.png" class="logo"> 
            </div>
            <div class="col-xs-6  col-sm-8  col-md-8 col-lg-8">
            <div class="nav">
                     <a href="/fruits/">首頁</a>
                     <a href="/fruits#brand">品牌理念</a>
                     <a href="/fruits/health_info">健康資訊</a>
                     <a href="/fruits/shop">立即購買</a>
                     <a href="/fruits/contactus">聯絡我們</a>
                 </div>
            </div>

        </div>
    </div>
</div>



<!--手机端-->
        <div class="pho_nav">
             <div class="pho_nav_img" onclick="showhide()"><img src="/fruit/images/dhicon.png"></div>
             <div class="pho_nav_ul" id="div1"> 
                   <img src="/fruit/images/ticon.png" class="pho_jt">
                   <ul>
                   <li><a href="/fruits/">首頁</a></li>
                   <li><a href="/fruits#brand">品牌理念</a></li>
                   <li><a href="/fruits/health_info">健康資訊</a></li>
                   <li><a href="/fruits/shop">立即購買</a></li>
                   <li><a href="/fruits/contactus">聯絡我們</a></li>
                   </ul>
             </div>
        </div>
     
<script>
	function showhide()
	{
var odiv=document.getElementById("div1")
if(odiv.style.display=="block")
{
	odiv.style.display="none"
	}

else
{
	odiv.style.display="block"
	}
	}
	
</script>

    <style>
	input::-webkit-input-placeholder{ color:#999} 
	</style>

        <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>



<div class="container cptit"><a href="">首頁</a><span>/</span><a href="">立即購買</a><span>/</span>訂單確認</div>
<div class="container gw_bg">
     <div class="ngoum"><img src="/fruit/images/gm_img01.png" class="center-block"></div>
     <div class="ngoum_1">
          <div class="ngtab">
                <h2>訂單開始</h2>
                <div class="ng_xx ng_ys columns">
                              <b>
                                   <span class="pjfont pone">商品</span>
                                   <var class="ng_wd"><select name="" class="ng_but"><option>【超優惠】3盒孅酵素組</option></select></var>
                              </b>
                              <b>
                                   <span class="pjfont pone">價格</span>
                                   <var>1,480 NTD</var>
                              </b>
                              <b>
                                   <span class="pjfont pone">數量</span>
                                   <var class="ng_wd"><select name="" class="ng_but"><option>1</option></select></var>
                              </b>
                              <b>
                                   <span class="pjfont pone">總計</span>
                                   <var>1,480 NTD</var>
                              </b>
                              <!--<div class="columns delicon"><a href="" class="dynamicRemove"><img src="/fruit/images/gm_img04.png"></a></div>-->
                   </div>
                   <div class="tianjia columns"><a onclick="addByScript()"class="tjbutton tj_left dynamicAdd">+增加選項</a></div>
                   <div class="ng_ys_tab"><table class="ng_xx columns" id="tbls"></table></div>

<style>
</style>

    <script>
      var count = 0;
      
      function addByScript() {
        var table = document.getElementById("tbls");
        var newRow = table.insertRow(table.rows.length);
        newRow.id = "row" + count;
        
        var contentCell = newRow.insertCell(-1);
        contentCell.innerHTML = '<div class="ng_xx ng_ys columns"><div class="but_left"><b class="ng_oneb"><span class="pjfont pone">商品</span><var class="ng_wd"><select name="" class="ng_but"><option>請選擇</option><option>【超優惠】3盒孅酵素組</option></select></var></b><b><span class="pjfont pone">價格</span><var>1,480 NTD</var></b><b><span class="pjfont pone">數量</span><var class="ng_wd"><select name="" class="ng_but"><option>1</option></select></var></b><b><span class="pjfont pone">總計</span><var>1,480 NTD</var></b></div></div>';
        
        var delBtn = document.createElement("input");
        delBtn.type = "button";
        delBtn.className = "but_aa";
        delBtn.id = "btnDel"+count;
        delBtn.value = "";
        delBtn.onclick = new Function("del(this)");
        contentCell.appendChild(delBtn);
                
        count++;
      }
      
      function del(obj) {
        var row = obj.parentNode.parentNode;
        row.parentNode.removeChild(row);
      }      
    </script>
               
               
               
               
               
               
               

                
                <h2 style="margin-top:10px">客戶信息<i>*必填</i></h2>
                <div class="ng_xx ng_yb">
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">姓名</div></span>
                      <var>
                           <input name="" type="text" class="ng_bu_t" placeholder="姓" >
                           <input name="" type="text" class="ng_bu_t right" placeholder="名" >
                      </var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">性別</div></span>
                           <var>
                          <label class="ng_lable"><input type="radio" name="RadioGroup3" value="单选" id="RadioGroup3_0">男</label>
                          <label class="ng_lable"><input type="radio" name="RadioGroup3" value="单选" id="RadioGroup3_1">女</label>
                           </var>
                      </b>
                      <b style="margin-top:-8px">
                           <span><i class="bt left">*</i><div class="pjfont">生日</div></span>
                           <var>
                           <select name="" class="ng_bu_date"><option>年</option></select>
                           <select name="" class="ng_bu_date"><option>月</option></select>
                           <select name="" class="ng_bu_date right"><option>日</option></select>
                           </var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">縣市地區</div></span>
                      <var>
                           <select name="" class="ng_bu_t"><option>縣市</option></select>
                           <select name="" class="ng_bu_t right"><option>區域</option></select>
                      </var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">郵遞區號</div></span>
                           <var><input name="" type="text" class="ng_but ng_but_1" placeholder="101" ></var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">地址</div></span>
                           <var><input name="" type="text" class="ng_but ng_but_1" placeholder="請輸入完整地址" ></var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">手機號碼</div></span>
                           <var class="pjfont"><input name="" type="text" class="ng_but ng_but_1" placeholder="0912345678" ></var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">電子信箱</div></span>
                           <var><input name="" type="text" class="ng_but ng_but_1" placeholder="xxxx@xxxx" ></var>
                      </b>
                </div>
                <h2 class="xx_top">填寫信用卡資訊<i>*必填</i></h2>
                <div class="ng_xx ng_yb">
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">持卡人姓名</div></span>
                           <var><input name="" type="text" class="ng_but ng_but_1" placeholder="與身份證/居留證相同" ></var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">信用卡卡别</div></span>
                           <var><img src="/fruit/images/gm_img05.png"><font class="xx_kp">限臺灣核發之信用卡</font></var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">信用卡卡號</div></span>
                           <var>
                           <input name="" type="text" class="xx_khinput left" placeholder="" ><font class="xx_text">-</font>
                           <input name="" type="text" class="xx_khinput left" placeholder="" ><font class="xx_text">-</font>
                           <input name="" type="text" class="xx_khinput left" placeholder="" ><font class="xx_text">-</font>
                           <input name="" type="text" class="xx_khinput right" placeholder="" >
                           </var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">卡片有效日期</div></span>
                      <var>
                           <select name="" class="ng_bu_t"><option>月份</option></select>
                           <select name="" class="ng_bu_t right"><option>年份</option></select>
                      </var>
                      </b>
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">信用卡確認碼</div></span>
                           <var><div class="xcard"><input name="" type="text" class="xcard_input" placeholder="" ><img src="/fruit/images/gm_img03.png"></div></var>
                      </b>
                </div>                
                <h2 class="xx_top">送貨方式<i>*必填</i></h2>
                <div class="ng_xx ng_yb">
                      <b>
                           <span><i class="bt left">*</i><div class="pjfont">送貨方式</div></span>
                            <var><select name="" class="ng_but ng_but_1"><option>請選擇送貨方式</option></select></var>
                      </b>
                </div>
                <h2 class="xx_top">訂單備註</h2>
                <div class="ng_xx ng_yb">
                <textarea name="" cols="" rows="" class="xcard_text"></textarea>
                </div>
          </div>
     </div>
</div>

<div class="ngoum_jt"><span>運費<i>0NTD</i></span><span>手續費<i>100NTD</i></span><span>總金額<i>1,580NTD</i></span></div>
<!--<a href=""><img src="/fruit/images/gm_but.png" class="gmbut center-block"></a>
-->
<a href="/fruits/order_success" class="gm_b center-block gmbut"><!--<img src="/fruit/images/gmbut.png" class="cpbutgm">--></a>



<div class="bottom">
    <h2>跨境通商貿股份有限公司 
    <h2><span>服務電話：04-22031511</span><i>丨</i><span>客服信箱：kjtservice@taiwankjt.com</span></h2></h2>
    <h2>Copyright © 2019 TTSee All rights reserved.  </h2>
</div>

</body>
</html>