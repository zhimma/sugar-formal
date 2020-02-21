<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>男生搜索</title>
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

<style>
.n_dlbut2 {
    width: 150px;
    height: 40px;
    border-radius: 200px;
    color: #ffffff;
    text-align: center;
    line-height: 40px;
    display: table;
    font-size: 16px;
    float: left;
}
</style>
  </head>
  <body>
    <div class="nowpage" style="display:hidden"></div>
    <div class="head hetop">
      <div class="container">
        <div class="col-sm-12 col-xs-12 col-md-12">
            <a href="/"><img src="/new/images/icon_41.png" class="logo" /></a>
        </div>
      </div>
    </div>
    <div class="head heicon">
      <div class="container">
        <div class="col-sm-12 col-xs-12 col-md-12">
          <div class="commonMenu">
            <div class="menuTop">
              <a href="/"><img src="/new/images/icon_41.png" class="logo" /></a>
              <span id="menuButton"><img src="/new/images/icon.png" class="he_img"></span>
            </div>
            <ul id="menuList" class="change marg30">
                            <div class="comt"><img src="/new/images/t.png"></div>
                            <div class="coheight">
              <div class="heyctop">測試系統賬號</div>
              <div class="helist">
                <ul>
                  <li><a href="/new/mem_search"><img src="/new/images/icon_38.png">搜索</a></li>
                  <li><a href="/dashboard/chat2"><img src="/new/images/icon_45.png">訊息</a><span>10</span></li>
                  <li><a href="/browse"><img src="/new/images/icon_46.png">名單</a></li>
                  <li><a href="/dashboard"><img src="/new/images/icon_48.png">我的</a></li>
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
          <div class="shou shou02 sh_line"><span>搜索</span>
            <font>search</font>
          </div>
                    
                    <div class="n_search">
                   <div class="n_input">
                                <dt>
                                                        <span>區市</span>
                                                        <span>
                                                        <!-- <select name="city" id="city" class="select_xx06"><option value="連江縣">連江縣</option><option value="B">B</option></select> -->
                                                        <select name="city" id="city" class="select_xx06">
                                                          <option value="">選擇縣市</option>
                                                          @foreach($city as $city_rows)
                                                            <option value="<?php echo $city_rows->AutoNo ?>"><?php echo $city_rows->Name ?></option>
                                                          @endforeach
                                                        </select>
                                                        <!-- <select name="area" id="area" class="select_xx06 right"><option value="南竿鄉">南竿鄉</option><option value="B">B</option></select> -->
                                                        <select name="area" id="area" class="select_xx06 right">
                                                            <option value="">選擇鄉鎮</option>
                                                        </select>
                                                        </span>
                                                    </dt>
                                   <dt>
                                                        <span>年齡範圍</span>
                                                        <span>
                                                        <select name="age_pre" id="age_pre" class="select_xx06 left">
                                                            @for ($i = 1; $i <= 10; $i++)
                                                            <option value="{{$i*10}}">{{$i*10}}</option>
                                                            @endfor
                                                        </select>
                                                        <div class="sew6">至</div>
                                                        <select name="age_next" id="age_next" class="select_xx06  right">
                                                            @for ($i = 1; $i <= 10; $i++)
                                                            <option value="{{$i*10}}">{{$i*10}}</option>
                                                            @endfor
                                                        </select>
                                                        </span>
                                                    </dt>
                                                     <dt>
                                                         <div class="n_se left">
                                                             <span>預算</span>
                                                           <!-- <select name="" class="select_xx01"><option>100—200萬</option><option>300—400萬</option></select> -->
                                                           <select class="form-control m-bootstrap-select m_selectpicker" name="budget" id="budget">

                                                            <option value="-1">請選擇</option>

                                                            <option value="基礎">基礎</option>

                                                            <option value="進階">進階</option>

                                                            <option value="高級">高級</option>

                                                            <option value="最高">最高</option>

                                                            <option value="可商議">可商議</option>

                                                        </select>
                                                         </div>
                                                         <div class="n_se right">
                                                             <span>抽菸</span>
                                                           <select name="smoking" id="smoking" class="select_xx01">
                                                              <option value="-1">請選擇</option>
                                                              <option value="常抽">常抽</option>
                                                              <option value="偶爾抽">偶爾抽</option>
                                                              <option value="不抽">不抽</option>
                                                           </select>
                                                         </div>
                                </dt>
                                <dt>
                                                        <span>體型</span>
                                                        <span class="line20">
                                                                <label class="n_tx"><input type="radio" name="body" value="瘦" id="body"><i>瘦</i></label>
                                                                <label class="n_tx"><input type="radio" name="body" value="標準" id="body"><i>標準</i></label>
                                                                <label class="n_tx"><input type="radio" name="body" value="微胖" id="body"><i>微胖</i></label>
                                                                <label class="n_tx"><input type="radio" name="body" value="胖" id="body"><i>胖</i></label>
                                                        </span>
                                </dt>
                                <dt class="matopj15">
                                                        <span>CUP</span>
                                                        <span class="line20">
                                                                <label class="n_tx"><input type="radio" name="cup_size" value="A" id="cup_size"><i>A</i></label>
                                                                <label class="n_tx"><input type="radio" name="cup_size" value="B" id="cup_size"><i>B</i></label>
                                                                <label class="n_tx"><input type="radio" name="cup_size" value="C" id="cup_size"><i>C</i></label>
                                                                <label class="n_tx"><input type="radio" name="cup_size" value="D" id="cup_size"><i>D</i></label>
                                                                <label class="n_tx"><input type="radio" name="cup_size" value="E" id="cup_size"><i>E</i></label>
                                                                <label class="n_tx"><input type="radio" name="cup_size" value="F" id="cup_size"><i>F</i></label>
                                                        </span>
                                </dt>
                                                     <dt class="matopj15">
                                                         <div class="n_se left">
                                                             <span>婚姻</span>
                                                           <select name="marriage" id="marriage" class="select_xx01">
                                                              <option value="-1">請選擇</option>
                                                              <option value="單身">單身</option>
                                                              <option value="有男友">有男友</option>
                                                              <option value="未婚">未婚</option>
                                                              <option value="已婚">已婚</option>
                                                           </select>
                                                         </div>
                                                         <div class="n_se right">
                                                             <span>喝酒</span>
                                                          <select name="drinking" id="drinking" class="select_xx01">
                                                              <option value="-1">請選擇</option>
                                                              <option value="常喝">常喝</option>
                                                              <option value="偶爾喝">偶爾喝</option>
                                                              <option value="不喝">不喝</option>
                                                          </select>
                                                         </div>
                                </dt>
                                <dt>
                                                       <span>搜索排列顺序</span>
                                                       <span>
                                                       <select name="search_sort" id="search_sort" class="select_xx01">
                                                          <option value="-1">登入時間</option>
                                                          <option value="2019">2019</option>
                                                          <option value="2018">2018</option>
                                                          <option value="2017">2017</option>
                                                          <option value="2016">2016</option>
                                                          <option value="2015">2015</option>
                                                       </select>
                                                       </span>
                                                  </dt>
                                   </div>
                                             <div class="n_txbut"><div class="n_dlbut">搜索</div><div href="" class="n_zcbut">取消</div></div>
                                    
                </div>                    
                    
                               <div class="n_searchtit"><div class="n_seline"><span>搜索结果</span></div></div>
                               <div class="n_sepeop">
                                      <!-- <li class="nt_fg">
                                           <div class="n_seicon">
                                               <img src="/new/images/b_01.png">
                                               <img src="/new/images/b_02.png">
                                               <img src="/new/images/b_03.png">
                                               <img src="/new/images/b_04.png">
                                               <img src="/new/images/b_05.png">
                                           </div>
                                           <div class="nt_photo"><img src="/new/images/icon_03.png"></div> 
                                           <div class="nt_bot nt_bgco">
                                                  <h2>小周傑倫<span>25歲</span></h2>
                                                  <h3>臺北市<span>臺中區</span><span>職業<img src="/new/images/icon_35.png" class="nt_img"></span></h3>
                                                  <h3>最後上線時間</h3>
                                           </div>         
                                      </li> -->
                                      
                               </div>
                                 <div class="fenye mabot30">
                                    <a class="prevpage n_dlbut2" id="1">上一頁</a>
                                    <a class="nextpage n_dlbut2" id="1">下一頁</a>
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

  </body>

</html>

<script>

$(".n_dlbut").click(function(){
  if($(this).attr('id')==null){
    $(this).attr('id',1);
  }
    data = {
      "_token"      : "{{ csrf_token() }}",
      "city"        : $("#city").val(),
      "area"        : $("#area").val(),
      "age_pre"     : $("#age_pre").val(),
      "age_next"    : $("#age_next").val(),
      "budget"      : $("#budget").val(),
      "smoking"     : $("#smoking").val(),
      "body"        : $("#body").val(),
      "cup"         : $("#cup_size").val(),
      "marriage"    : $("#marriage").val(),
      "drinking"    : $("#drinking").val(), 
      "search_sort" : $("#search_sort").val(),
      'page'        : $(this).attr('id'),
      };
    $.ajax({
    type: "POST",
    url: "/searchData",
    data: data,
    dataType: "json",
    success: function(res){
      console.log(res['page'])
      $(".n_sepeop").html('');
      $(".prevpage").attr('id',parseInt(res['page'])-1);
      $(".nowpage").attr('id',parseInt(res['page']));
      $(".nextpage").attr('id',parseInt(res['page'])+1);
      for(key in res['user']){
      $(".n_sepeop").append('<li class="nt_fg">'+
                      '<div class="n_seicon">'+
                          '<img src="/new/images/b_01.png">'+
                          '<img src="/new/images/b_02.png">'+
                          '<img src="/new/images/b_03.png">'+
                          '<img src="/new/images/b_05.png">'+
                      '</div>'+
                      '<div class="nt_photo"><img src="'+res['user'][key].pic+'"></div>'+
                      '<div class="nt_bot nt_bgco">'+
                            '<h2>'+res['user'][key].name+'<span>25歲</span></h2>'+
                            '<h3>'+res['user'][key].city+'<span>'+res['user'][key].area+'</span><span>'+res['user'][key].occupation+'<img src="/new/images/icon_35.png" class="nt_img"></span></h3>'+
                            '<h3>最後上線時間</h3>'+
                      '</div>'+
                '</li>');
      }
    }
  });
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		//利用jQuery的ajax把縣市編號(CNo)傳到Town_ajax.php把相對應的區域名稱回傳後印到選擇區域(鄉鎮)下拉選單
		$('#myCity').change(function(){
      console.log('test');
			var CNo= $('#myCity').val();
      var data = {
        "_token"      : "{{ csrf_token() }}",
        'CNo': CNo,
      };
			$.ajax({
				type: "POST",
				url: '/town_ajax',
				cache: false,
				data:data,
				error: function(){
					alert('Ajax request 發生錯誤');
				},
				success: function(data){
					$('#myTown').html(data);
					$('#myZip').val("");//避免重新選擇縣市後郵遞區號還存在，所以在重新選擇縣市後郵遞區號欄位清空
				}
			});
		});
		
	});
</script>
<style>
.n_dlbut{ cursor: pointer; }
.n_zcbut{ cursor: pointer; }
</style>
