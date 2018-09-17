@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
            Email 驗證 <small></small>
            </h3>
        </div>
    </div>
</div>
<div class="m-portlet__body">
    <p><h3>站長的話</h3></p>

    @if(Auth::check() && $user->engroup == 2)
    <p>歡迎來到甜心花園，美麗的寶貝們都想找到一個大方多金的 Daddy。網路上，誰都不認識誰，有很多機會，但有更多陷阱。站長跟大家提示最重要的二件事。</p>
    <p>1.最重要的第一守則：不論在任何情況下，一定先拿到費用才開始約會。</p>
    <p>在包養網站中，所有詐騙行為第一名就是男方花言巧語哄騙女方先約會，說好約會滿多久以後付款，最後時間快到就失聯。此類詐騙數量占所有詐騙的85%以上，所以記得一定要求先拿零用金。(建議第一次見面請男方使用車馬費邀請)</p>
    <p>2.加入 VIP。</p>
    <p>女生加入VIP很簡單，不用付費。只要上傳頭像照+三張生活照。每周上線一次，就可以保持VIP資格。</p>
    <p>獲得 VIP 可以看很多男會員的進階資料，對判斷男會員是真的大方Daddy 或者只是胡說八道的騙子很有幫助。</p>
    <p>3.<a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">如果沒收到認證信/認證失敗，請點此聯繫站長。</a></p>

    <p>建議第一次接觸包養的女孩，看完以下文章</p>

    @elseif(Auth::check() && $user->engroup == 1)
    <p>歡迎來到甜心花園，多金的Daddy們都想找到一個美麗的 BaBy。</p>
    <p>這邊，站長跟各位提點，想找到稱心如意的女伴的幾個重點：</p>
    <p>1.金錢就是最強大的武器。</p>
    <p>在這邊，高不高帥不帥都不是妹妹關注的重點，你真不真誠，大不大方，才是勝出的關鍵。</p>
    <p>2.拒絕預支</p>
    <p>女生能詐騙男生的方法不多，預支是最常見的。無論如何，認識不深的情況下。拒絕超過半個月的零用錢預支要求。</p>
    <p>3.要利用網站的功能</p>
    <p>盡量加入 VIP，這是很多妹子第一個篩選的關卡。再來利用車馬費功能。雖說拒絕預支，但那是指萬計的大筆零用錢，初期千把塊的小額投資絕不可省。</p>
    <p>4.<a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">如果沒收到認證信/認證失敗，請點此聯繫站長。</a></p>

    <p>建議第一次接觸包養的大叔，看完以下文章</p>
    @endif

    <br>
    <a href="#">站長的話</a>
    <a href="#">網站使用</a>
    <a href="#">站長的碎碎念(完整版)</a>

    <hr>

    <p>驗證碼已經寄到你的email.</p>
    <a class="btn btn-danger" href="{{ url('activate/send-token') }}">重新發送</a>
</div>

@stop
