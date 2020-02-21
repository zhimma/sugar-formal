@extends('new.layouts.website')

@section('app-content')

    <style>
        .swiper-container {width: 100%;max-height: 600px;border-radius:15px} 
        .swiper-container1 {width: 100%;max-height: 600px;border-radius:15px}    
        .swiper-slide {width: 100%;height: 280px;margin: 0 auto;padding: 0px;display: table}        
        .swiper-slide img {width: 100%;height: 100%;}       
        @media (max-width:767px) {
        .swiper-container {width: 100%;height: auto;border-radius:15px}
        .swiper-container1 {width: 100%;height: auto;border-radius:15px}
        .swiper-slide {width: 100%;height: 280px !important;margin: 0 auto;padding: 0px;display: table}
        .swiper-slide img {width: 100%;height: 100%;}
        }
        @media (max-width:992px) {
        .swiper-container {width: 100%;height: auto;border-radius:15px}
        .swiper-container1 {width: 100%;height: auto;border-radius:15px}
        .swiper-slide {width: 100%;height: 280px;margin: 0 auto;padding: 0px;display: table;}
        .swiper-slide img {width: 100%;height: 100%;}
        }
    </style>

	<div class="container matop70 swbot30">
        <div class="col-sm-12 col-xs-12 col-md-12">
            <div class="shye"><img src="/new/images/sy_10.png"></div>
            <div class="shyepc"><img src="/new/images/sypc.png"></div>
            <div class="n_sybut"><a href="{!! url('dashboard/search') !!}" class="n_sybut_left">甜心爹地</a><a href="{!! url('dashboard/search') !!}" class="n_sybut_right">甜心寶貝</a></div>
            <div class="n_tbox">
               <div class="col-sm-12 col-xs-12 col-md-4">
                       <div class="n_tbox01 n_tbox02">
                       <font><img src="/new/images/sy_03.png"></font>
                       <div class="n_bt"><h2>快速</h2><span>本站註冊快速，一分鐘即可註冊完成，使用服務。</span></div>
                       </div>
               </div>
               <div class="col-sm-12 col-xs-12 col-md-4">
                       <div class="n_tbox01 n_tbox02">
                       <font><img src="/new/images/sy_13.png"></font>
                       <div class="n_bt"><h2>安全</h2><span>本站不會與任何其他網站交換資料。 事實上，您只需要一個電子信箱註冊，其他不需要任何留下私人資料。</span></div>
                       </div>
               </div>
               <div class="col-sm-12 col-xs-12 col-md-4">
                       <div class="n_tbox01">
                       <font><img src="/new/images/sy_17.png"></font>
                       <div class="n_bt"><h2>高品質</h2><span>全台最大的Sugar Daddy/Baby 交友網站，所有會員均經過嚴密的審核機制。杜絕非法以及別有用心的使用者。</span></div>
                       </div>
               </div>
            </div>
        </div>

        <div class="n_tuijian_tit"><img src="/new/images/sy_14.png">如果您是富豪新貴</div>
        <div class="n_tjleft left">
            <div class="swesy">
              <div class="swiper-container photo">
                  <div class="swiper-wrapper">
                      @foreach ($imgUserF as $k => $v)
                      @if(isset($v))
                      <div class="swiper-slide">
                        <div class="swname">{{$v->name}}<span>{{$v->title}}</span></div>
                        <img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_03.png'">
                      </div>
                      @endif
                      @endforeach
                  </div>
                  <!-- Add Arrows -->
                  <div class="swiper-button-next"></div>
                  <div class="swiper-button-prev"></div>
              </div>
            </div>
            <!-- Swiper JS -->
            <script src="/new/js/swiper.min.js"></script>
            <!-- Initialize Swiper -->
            <script>
                var swiper = new Swiper('.swiper-container', {
                    autoHeight: true,
                    pagination: '.swiper-pagination',
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    slidesPerView: 1,
                    paginationClickable: true,
                    autoplay: 4000,
                    spaceBetween: 30,
                    loop: true,
                });
            </script>
        </div>
        <!--基本資料-->
        <div class="n_tjleft right">
            <div class="swtext swtop30">
                <h2><i>最多的甜心寶貝</i></h2><h3>全台最大的交友交友網站，有最多的甜心寶貝</h3>
            </div>
            <div class="swtext">
                <h2><i>直接坦白</i></h2><h3>這個交友網站就是讓大家直接坦白，不拐彎抹角，節省您兜圈子的時間。</h3>
            </div>
            <div class="swtext">
                <h2><i>保密隱私</i></h2><h3>您可不需留下真實資料，站台並保證絕不洩漏會員資料。</h3>
            </div>
        </div>

        <div class="n_tuijian_tit swpptop30"><img src="/new/images/sy_07.png">如果您是魅力寶貝</div>
        <div class="n_tjleft right">
            <div class="swesy">
                  <div class="swiper-container photo">
                      <div class="swiper-wrapper">
                          @foreach ($imgUserM as $k => $v)
                          @if(isset($v))
                          <div class="swiper-slide">
                            <div class="swname">{{$v->name}}<span class="swname_s">{{$v->title}}</span></div>
                            <img src="https://www.sugar-garden.org/{{$v->pic}}" onerror="this.src='/new/images/icon_04.png'">
                          </div>
                          @endif
                          @endforeach
                      </div>
                      <!-- Add Arrows -->
                      <div class="swiper-button-next"></div>
                      <div class="swiper-button-prev"></div>
                  </div>
            </div>
            <!-- Swiper JS -->
            <script src="/new/js/swiper.min.js"></script>
            <!-- Initialize Swiper -->
            <script>
                var swiper = new Swiper('.swiper-container', {
                    pagination: '.swiper-pagination',
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    slidesPerView: 1,
                    paginationClickable: true,
                    autoHeight: true,
                    autoplay: 4000,
                    spaceBetween: 30,
                    loop: true
                });
            </script>
        </div>

        <div class="n_tjleft left">
              <div class="swtext swtop30">
                <h4><i>直接的經濟援助-交友</i></h4><h3>讓你不再為家庭困窘的經濟煩惱</h3>
              </div>
              <div class="swtext">
                <h4><i>一圓各種夢想</i></h4><h3>有daddy交友網的幫忙，把握青春，出國留學，上課進修，提昇自我能力。</h3>
              </div>
              <div class="swtext">
                <h4><i>被寵被疼</i></h4><h3>不同於身旁的小屁孩，daddy總是成熟穩重，疼你寵你，包容你一切的任性。</h3>
              </div>
        </div>
    </div>

@stop