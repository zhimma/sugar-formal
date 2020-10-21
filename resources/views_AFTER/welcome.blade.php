@include('partials.newheader')
<body>
<div class="part01">
	<div class="weui-pt30 weui-pb30 container">
        @include('layouts.newnavigation')
    		
	</div>
    <div class="part_box">
         <div>
              <h2 class="f40">成功人士认识魅力甜心</h2>
              <h3 class="weui-f28">全台人数最多的<br>Sugar Daddy/Baby 包养网站</h3>
              <h4 class="weui-f18 weui-pt30 wyz">我要找：</h4>
              <p>
              	@if(Auth::user())
					<a href="{!! url('dashboard/search') !!}" class="ddbtn weui-f18"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
                 	<a href="{!! url('dashboard/search') !!}" class="ddbtn ddbtn_01 weui-f18 weui-ml15"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
				@else
					<a href="{!! url('register') !!}" class="ddbtn weui-f18"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
                 	<a href="{!! url('register') !!}" class="ddbtn ddbtn_01 weui-f18 weui-ml15"><span class="weui-v_m">甜心爹地</span> <img src="images/j_03.png"></a>
				@endif
              </p>
         </div>
    </div>
</div>

<div class="container weui-pt30 weui-pb30">
     <h2 class="weui-t_c f40">
         如果您是富豪新贵
         <p><img src="images/line.png"></p>
     </h2>
     <div class="row weui-pt30">
         <div class="col-lg-5 col-md-5 col-sm-5 weui-t_c">
              <img src="images/shouye_22.png" class="m_img">
              <div class="m_bgcolor">
              <h3 class="weui-f16">薇雨</h3>
              <p>想找耐心陪我且经济上支持我的dadd</p>
              <p><a href="#" class="weui-red01">查看详情</a></p>
              </div>
         </div>
         <div class="col-lg-7 col-md-7 col-sm-7 weui-pt30 weui-c_6">
             <div class="man">
             <div class="media media_p">
                 <div class="media-left">
                      <span class="ico ico-1 ico01"></span>
                 </div>
                 <div class="media-body weui-pl15">
                      <h4 class="media-heading weui-f18">直接的经济援助-包养</h4>
                      <p class="weui-f16">让你不再为家庭困窘的经济烦恼</p>
                 </div>
             </div>
             <div class="media media_p">
                 <div class="media-left">
                      <span class="ico ico-1 ico02"></span>
                 </div>
                 <div class="media-body weui-pl15">
                      <h4 class="media-heading weui-f18">一圆各种梦想</h4>
                      <p class="weui-f16">有daddy包养网的帮忙，把握青春，出国留学，上课进修，提升自我能力。</p>
                 </div>
             </div>
             <div class="media media_p">
                 <div class="media-left">
                      <span class="ico ico-1 ico03"></span>
                 </div>
                 <div class="media-body weui-pl15">
                      <h4 class="media-heading weui-f18">被宠被疼</h4>
                      <p class="weui-f16">不同于身旁的小屁孩，daddy总是成熟稳重，疼你宠你，包容你一切的任性。</p>
                 </div>
             </div>
             </div>
         </div>
         
     </div>
</div>

<div class=" weui-bgcolor">
        <div class="container weui-pt30 weui-pb30">
             <h2 class="weui-t_c f40">
                 如果您是魅力宝贝
                 <p><img src="images/line.png"></p>
             </h2>
             <div class="row weui-pt30">
                 <div class="col-lg-7 col-md-7 col-sm-7 weui-pt30 weui-c_6">
                     <div class="media media_p">
                         <div class="media-left">
                              <span class="ico ico01"></span>
                         </div>
                         <div class="media-body weui-pl15">
                              <h4 class="media-heading weui-f18">直接的经济援助-包养</h4>
                              <p class="weui-f16">让你不再为家庭困窘的经济烦恼</p>
                         </div>
                     </div>
                     <div class="media media_p">
                         <div class="media-left">
                              <span class="ico ico02"></span>
                         </div>
                         <div class="media-body weui-pl15">
                              <h4 class="media-heading weui-f18">一圆各种梦想</h4>
                              <p class="weui-f16">有daddy包养网的帮忙，把握青春，出国留学，上课进修，提升自我能力。</p>
                         </div>
                     </div>
                     <div class="media media_p">
                         <div class="media-left">
                              <span class="ico ico03"></span>
                         </div>
                         <div class="media-body weui-pl15">
                              <h4 class="media-heading weui-f18">被宠被疼</h4>
                              <p class="weui-f16">不同于身旁的小屁孩，daddy总是成熟稳重，疼你宠你，包容你一切的任性。</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-5 col-md-5 col-sm-5 weui-t_c">
                      <img src="images/shouye_30.png" class="m_img">
                      <h3 class="weui-f16">薇雨</h3>
                      <p>想找耐心陪我且经济上支持我的dadd</p>
                      <p><a href="#" class="weui-red01">查看详情</a></p>
                 </div>
             </div>
        </div>
        
        </div>
    <div class="td">
        <div class="container">
            <div class="row">
                 <div class="col-lg-4 col-md-4">
                      <dl class="td_bg1">
                          <dt class="weui-f32">快速</dt>
                          <dd>本站注册快速，一分钟即可注册完成，使用服务。</dd>
                      </dl>
                 </div>
                 <div class="col-lg-4 col-md-4">
                      <dl class="td_bg2">
                          <dt class="weui-f32">安全</dt>
                          <dd>本站不会与任何其他网站交换资料。事实上，您只需要一个电子邮箱注册，其他不需要任何留下私人资料。</dd>
                      </dl>
                 </div>
                 <div class="col-lg-4 col-md-4">
                      <dl class="td_bg3">
                          <dt class="weui-f32">高品质</dt>
                          <dd>全台最大的Sugar Daddy/Baby 包养网站，所有会员均经过的审核机制。杜绝非法以及別有用心的使用者。</dd>
                      </dl>
                 </div>
            </div>
        </div>
    </div>
        @include('partials.newfooter')
        @include('partials.newscripts')
</body>
</html>
