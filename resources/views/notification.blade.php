@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
        站長開講 <small></small>
        </h3>
    </div>			
</div>
</div>
<div class="m-portlet__body">
    @if(Auth::check() && isset($user))
        {{-- female --}}
        @if($user->engroup == 2)
            <center><font face="DFKai-sb" size=6>初入這一行的基本注意事項</font></center></br>
		    </br>
			網路上騙子網蟲很多，站長跟來到本站的甜心寶貝們，分享一些初入這行的重要注意事項。<br>
			<br>
			<font color="red">第一：先拿錢後約會</font></br>
			雖說之前有提過，但這點實在太重要。多講幾次都無妨。</br>
			一般來說，關係的建立分成兩個階段</br>
			<font color="orange">a:確認關係前的普通見面</font></br>
			這邊指的是前幾次見面，雙方還沒確認關係之前。通常只是單純的喝杯咖啡，吃個飯。
			這邊建議使用站方的車馬費(參見網站功能說明)邀請功能。
			由站方幫忙把關第一次見面的風險，因為曾經有過男方吃飯尿遁留女方付費的案例發生。<br>
			<font color="orange">b:確認關係後的約會</font></br>
			雙方確認完關係，要正式約會時。也是先拿零用錢。
			站長建議一開始可以按次，或者按周收費。這樣數字不大，雙方都能接受。
			按次的好處是清楚明白，但是交易感重，感覺像援交。
			按周是比較建議的方式，男方付得起。感覺也比較好一點。<br>
			</br>
			<font color="red">第二：只要感覺不對就放棄</font></br>
			包養是很簡單的事情。就是大家交往，然後男生給女生一筆零用錢。</br>
			所以各種奇怪的事跟要求，舉一些站長聽過的怪例子</br>
			a：傳裸照</br>
			b：零用錢交付含糊不清，站長聽說過甚麼按業績算的</br>
			c：不給現金，給支票的。</br>
			d：約見地點奇怪的，</br>
			e：男方說詞反覆的不合常理的。</br>
			總之相信自己的直覺。只要有一點點覺得不對勁就放棄。</br>
			</br>
			<font color="red">第三：條件太好的<font color="orange"></br>
			富二代或長太帥的：站長看過正統富二代，或者長得帥的都是去夜店把妹，不需要花這麼多錢。</br>
			承諾下的太輕易的：人都沒見過就開價碼</br>
			價錢開的太誇張的：隨隨便便信口開河一個月幾十萬的</br>
			這些不能說全部都有問題，但都很容易碰到地雷， 更多注意事項限於篇幅就說到這。</br>
			建議可以進一步參考 <a href="http://www.sugar-garden.org/feature">網站使用</a> / <a href="http://blog-tw.net/Sugar/%E5%8C%85%E9%A4%8A%EF%BC%8D%E5%A5%B3%E5%AD%A9%E7%AF%87/" target="_blank">站長的碎碎念(完整版)</a></br>

        {{-- male --}}
        @elseif($user->engroup == 1)
            <p><center><font face="DFKai-sb" size=6>新手大叔的注意事項</font></center></br>
			<br>
			
			站長在這一行很多年了，這邊提點各位一些在包養這一行，打滾的訣竅
			<br>
			<br>
			<font color="red">1：拒絕預支零用金</font><br>
			這點是我多年來，看過男方最容易吃虧上當的項目，因為零用金數字並不小，都是以萬計。而男生在上了床往往無法拒絕女生的要求。以至於人財兩失。
			站長這邊跟各位說，除非你很有把握，否則千萬別預支零用金。<font color="orange">真正找包養的妹子，只要你按月付款準時而且不拖欠，很少會有為了不願意預支這種事情鬧分的。</font>
			(但也不排除特種情況，所以這邊講的是原則，實際上請自行拿捏)
			<br><br>
			<font color="red">2：有車很重要</font><br>
			這是站長的慘痛教訓。因為包養這種關係沒有任何女生願意張揚，所以每次跟你約會都要做大眾交通工具或者taxi，女方心中各種覺得大家都在看你們，
			小劇場演了十幾集，跟你在一起根本無法放鬆更不用談約會品質了，哪種慘狀難以描述。
			<br><br>
			<font color="red">3：小錢不要省</font><br>
			一開始的各種小額花銷千萬不要省。因為<font color="orange">女方決定是否與你開啟一段關係，你最初期的表現都會被放大檢視，稍稍有可能被貼上"小氣"的標籤，就完蛋了。</font>
			特別是美女人人愛，請盡量在一開始就展現你的經濟實力。這網站的妹子不看你高不看你帥，只看你大不大方，這是事業成功大叔的優勢啊。
			
			<br><br>
			建議可以進一步參考 <a href="http://www.sugar-garden.org/feature">網站使用</a> / <a href="http://blog-tw.net/Sugar/%E5%8C%85%E9%A4%8A%EF%BC%8D%E5%A4%A7%E5%8F%94%E7%AF%87/" target="_blank">站長的碎碎念(完整版)</a></br>

			</p>
        @endif
    @else
        {{-- no login --}}
        <p>請註冊會員，或者參考<a href="http://blog-tw.net/Sugar/">站長的碎碎念</a></p>
    @endif
</div>

@stop