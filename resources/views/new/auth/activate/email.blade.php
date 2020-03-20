@extends('new.layouts.website')

@section('app-content')

	<div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="shou shou02 sh_line"><span>Email 驗證</span>
                    <font>Email confirmation</font>
                </div>
                <div class="email">
                @if(isset($user))
                    <h2>帳號註冊成功(請到Email信箱收信驗證)</h2>
                    <div class="embg">
                            <div class="embg_1">
                            <h3>您已註冊成功，以下是您所填寫的註冊資料：</h3>
                            <h3>暱稱：{{ $user->name }}</h3>
                            <h3>帳號類型：@if($user->engroup == 2)<b>甜心寶貝</b>@else<b>甜心爹地</b>@endif</h3>
                            <h3>一句話形容自己：{{ $user->title }}</h3>
                            <h3>Email：<span>{{ $user->email }} (若Email填寫錯誤，請重新註冊)</span></h3>
                            </div>
                    </div>
                    <div class="wxsy_title">站長的話</div>
                    <div class="wxsy_k">
                            <div class="wknr">
                                @if(Auth::check() && $user->engroup == 2)
                                <p>歡迎來到甜心花園，美麗的寶貝們都想找到一個大方多金的 Daddy。網路上，誰都不認識誰，有很多機會，但有更多陷阱。站長跟大家提示最重要的二件事。</p>
                                <h3>1. 最重要的第一守則：不論在任何情況下，一定先拿到費用才開始約會。</h3>
                                <p>在包養網站中，所有詐騙行為第一名就是男方花言巧語哄騙女方先約會，說好約會滿多久以後付款，最後時間快到就失聯。此類詐騙數量占所有詐騙的85%以上，所以記得一定要求先拿零用金。(建議第一次見面請男方使用車馬費邀請)</p>
                                <h3>2. 加入 VIP。</h3>
                                <p>女生加入VIP很簡單，不用付費。只要上傳頭像照+三張生活照。每周上線一次，就可以保持VIP資格。</p>
                                <p>獲得 VIP 可以看很多男會員的進階資料，對判斷男會員是真的大方Daddy 或者只是胡說八道的騙子很有幫助。</p>
                                <h3>
                                    <a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">3. 如果沒收到認證信/認證失敗，請點此聯繫站長。</a>
                                </h3><br>
                                <h4>建議第一次接觸包養的女孩，看完以下文章</h4><br>
                                @elseif(Auth::check() && $user->engroup == 1)
                                <p>歡迎來到甜心花園，多金的Daddy們都想找到一個美麗的 BaBy。</p>
                                <p>這邊，站長跟各位提點，想找到稱心如意的女伴的幾個重點：</p>
                                <p>1. 金錢就是最強大的武器。</p>
                                <p>在這邊，高不高帥不帥都不是妹妹關注的重點，你真不真誠，大不大方，才是勝出的關鍵。</p>
                                <p>2. 拒絕預支</p>
                                <p>女生能詐騙男生的方法不多，預支是最常見的。無論如何，認識不深的情況下。拒絕超過半個月的零用錢預支要求。</p>
                                <p>3. 要利用網站的功能</p>
                                <p>盡量加入 VIP，這是很多妹子第一個篩選的關卡。再來利用車馬費功能。雖說拒絕預支，但那是指萬計的大筆零用錢，初期千把塊的小額投資絕不可省。</p>
                                <p>4.<a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">如果沒收到認證信/認證失敗，請點此聯繫站長。</a></p>

                                <h4>建議第一次接觸包養的大叔，看完以下文章</h4>
                                @endif

                                <h4>
                                    <a style="font-weight: bold" href="{!! url('notification') !!}">站長開講</a>
                                    <a style="font-weight: bold" href="{!! url('feature') !!}">網站使用</a>
                                    <a style="font-weight: bold" href="http://blog-tw.net/Sugar/%E5%8C%85%E9%A4%8A%EF%BC%8D%E5%A4%A7%E5%8F%94%E7%AF%87/">站長的碎碎念(完整版)</a>
                                </h4>
                            </div>
                    </div>
                    <div class="yx_k">驗證碼已經寄到你的email : <a style="font-weight: bold">{{ $user->email }} (若Email填寫錯誤，請重新註冊)</a></div>
                    <a href="{{ url('activate/send-token') }}" class="vipbut">重新發送</a>
                @elseif(isset($register))
                    <h2>註冊失敗</h2>
                    <div class="yx_k">系統無法找到您所填寫的資料，敬請重新註冊。</div>
                @else
                    <h2>驗證失敗</h2>
                    <div class="yx_k">這個驗證碼已經無效或是您提供了錯誤的驗證碼，請先嘗試登入先前所註冊的Email，若問題仍舊存在，敬請聯絡站長，謝謝。</div>
                @endif
                </div>
            </div>

        </div>
    </div>
@stop