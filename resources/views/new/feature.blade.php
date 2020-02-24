@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
      <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-12">
          <div class="wxsy">
             <div class="wxsy_title">網站使用</div>
             <div class="wxsy_k">
                  <div class="wknr">
                      @if(Auth::check() && isset($user))
                          {{-- female --}}
                          @if($user->engroup == 2)
                              <p>網站的建設方向是要更有效的過濾出真心包養的Daddy，篩選掉無聊的網蟲。
                              很多功能規劃中，如果各位有什麼意見歡迎跟站長聯絡提出。<a href="http://www.sugar-garden.org/contact">(聯絡我們)</a></p>
                              <p>目前已上線的功能</p>
                              <h4>1：成為VIP</h4>
                              <p style="margin: 0!important;">站長強烈建議各位寶貝成為網站VIP，因為VIP可以看到一些男會員很重要的進階資訊，可以幫助判斷男會員是真的大方Daddy 或者只是胡說八道。</p>
                              <span class="org">本站寶貝們要成為VIP不用花錢，上傳一張你的頭像照+3張生活照，只要兩天上線一次就可以擁有VIP權力。</span>
                              <h4>2：車馬費邀請</h4>
                              <p style="margin: 0!important;">站方設計車馬費制度，為了篩選信口開河邀約的Daddy。<br>
                              車馬費制度會由站方先跟Daddy收取一筆1788的車馬費費用。雙方約見。</p>
                              <span class="org">只要當天雙方順利見完面，不論結果如何，站方扣除部分手續費後，會將 1500 匯入各位指定的銀行帳戶。</span>
                              <p>或者採用西聯匯款(但西聯匯款有一筆高額匯款手續費，可能由女孩們自行吸收)</p>

                              {{-- male --}}
                          @elseif($user->engroup == 1)
                                <p>網站的建設方向是要更有效的過濾出真心包養的Daddy，篩選掉無聊的網蟲。 各位大叔可能不知道，一個女會員註冊，會收到數十封的邀約信。 漂亮一點的信件數量更是驚人。所以本站的規畫就是盡量凸顯真心包養大叔的經濟優勢。 目前有更進一步的功能規劃中，如果各位有什麼意見歡迎跟站長聯絡提出。<a href="{!! url('contact') !!}">(聯絡我們)</a></p>
                                <h3>目前已上線的功能 </h3>
                                <h4>1：加入VIP</h4>
                                <p>站長建議各位大叔加入VIP，目前VIP的比較重要的功能是可以無限制收發信，開啟已讀功能以及可以看進階的統計資料。未來會規畫更具財力顯示的VIP階級。 另一方面，VIP算是女方最基本的篩選門檻了，有些女生甚至會關掉普通會員的來信。只看VIP會員的來信。 </p>
                                <h4>2：車馬費邀請</h4>
                                <p>站方設計車馬費制度，為了篩選信口開河邀約的Daddy。也就增加了真心約見daddy的能見度。 車馬費制度會由站方先跟Daddy收取一筆1788的車馬費費用。雙方約見。 <span class="org">只要當天雙方順利見完面，不論結果如何，站方扣除部分手續費後，會將 1500 匯入女方指定的銀行帳戶。</span> 當然免不了有些女生會想辦法賺車馬費，網站目前的功能是會以曾經約會的會員可以留言評價(需VIP才能看到)，另一方面網站也會管控銀行帳戶，被太多人投訴的女會員，站方會停權。但站長必須說，無法100%杜絕，所以大家在使用車馬費邀約時，站長只能說這是必要的支出之一。</p>
                          @endif
                      @else
                          {{-- no login --}}
                          <p>請註冊會員，或者參考<a href="http://blog-tw.net/Sugar/">站長的碎碎念</a></p>
                      @endif
                  </div>
             </div>
          </div>
        </div>
      </div>
    </div>

@stop