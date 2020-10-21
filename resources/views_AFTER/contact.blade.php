@include('partials.newheader')



<body>
<div class="tkheader weui-p_r">
    <div class="weui-pt30 weui-pb30 container">
               @include('layouts.newnavigation')
            <div class="tk_title">
            聯系我們
            </div>
        </div>
</div>
<div class="mbx">
     <div class="container">
          您的所在當前位置：<a href="{!! url('') !!}"  class="weui-white">首頁</a> > 聯系我們
     </div>
</div>
   
   <div class="minh">
       <div class="container">
           <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 weui-t_r">
                   <img src="images/lianxi_18.png" class="m_img">
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6">
                   <ul class="contentus m_bgcolor">
                       <li>站長line：@giv4956r(包含@哦)</li>
                       <li>站長email：admin@taiwan-sugar.net</li>
                       <li>網站問題回報：@giv4956r</li>
                       <li>網站問題回報：admin@taiwan-sugar.net</li>
                   </ul>
               </div>
           </div>
       </div>
   </div>
   
  @include('partials.newfooter')
        @include('partials.newscripts')
</body>
</html>