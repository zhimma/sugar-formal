@extends('new.layouts.website')

@section('style')
	<script src="/js/app_1.js" type="text/javascript"></script>
	<!--    css-->
{{--	<link href="/posts/css/bootstrap.min.css" rel="stylesheet">--}}
{{--	<link href="/posts/css/bootstrap-theme.min.css" rel="stylesheet">--}}
	<link rel="stylesheet" href="/posts/css/style.css">
	<link rel="stylesheet" href="/posts/css/iconfont.css">
	<style>
		img{
			width: auto;
			height: auto;
			max-width: 100%;
			max-height: 100%;
		}
		.show{
			margin-top: unset !important;
		}

		.toug_back:hover{ color:white !important; text-decoration:none !important}
		.commonMenu{z-index: 10001;}
		.blbg_new{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
		.adminReply{
			background-color:#ddf3ff;
		}
	</style>
@endsection

@section('app-content')
		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
					@include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou">
						<span>文章詳情</span>
						<font>Article</font>
						<a href="{{ $goBackPage }}" class="toug_back btn_img">
							<div class="btn_back"></div>
						</a>
					</div>
					<div>
						@php
							$admin_info=\App\Models\User::leftJoin('user_meta', 'users.id','=','user_meta.user_id')->where('users.id', 1049)->first();
						@endphp
						@if(Request()->get('article')=='law_protection_sample')
							<div class="toug_xq" style="position: relative;">
								<div class="tougao_xnew">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $admin_info->name }}<i class="tou_fi">2023-05-30 16:40</i></span>
									</div>
								</div>
								<div class="xq_text">窈窕淑女人見人愛怎麼追?君子的妙法寶就是要尊重!</div>
								<div class="xq_text01">
									<span style="font-size: 16px;font-weight: bold;">懶人大綱：小王愛慕小美追求未果因而心生怨恨，在花園網發現與小美相似的女會員照片，將其截圖私下散布於工作群組，經起訴判刑小王加重毀謗罪，判處拘役1個月。</span><br><br>
									<div>&nbsp;&nbsp; 完整細節:「窈窕淑女、君子好逑。」不過若是一昧罔顧對方意願，甚而因為被拒絕惱羞成怒，做出種種逾越法律分際的行為，那可是會吃上官司的!這是一則根據事實案例判決改編的甜心網故事，奉勸各位想追求心儀的對象時，就是一定要先好好尊重女孩子，這才是君子們代代相傳、千古不敗的妙法寶喔!</div><br>
									<div>&nbsp;&nbsp; 小王(基於個資法規定文中皆採化名)與小美(基於個資法規定文中皆採化名)因為工作關係認識以後，小王即屢傳訊息給小美，這讓小美感覺已經開始讓她困擾，只能先採取冷處理的態度因應。某天早上在小美至台北某大醫院附近時，小王卻突然靠近小美表示想跟她說說話，小美加快腳步轉進醫院地下街的商店尋求店員的協助，小王見狀先在商店門口外徘徊，過沒多久欲直接進入店內，小王在店員的阻攔下大聲嚷嚷，威脅小美若再不肯跟他說話，他就要在小美上班的公司散布對小美不利的消息，說罷小王才悻悻然地離開店家，而直至小美同事到來後，小美在同事的陪同下安然離開。</div><br>
									<div>&nbsp;&nbsp;「小王在這件事情過了大約兩週後，他在花園網截圖了多張長相疑似小美的會員照片，並附加了很多不雅的揣測詞句在公務群組裡散佈，例如 : 「很可能是某人的秘密」、「她是會為了自己目的，不擇手段到連身體都會賣的人，她有在做包養的賣喔」等等….後續經小美輾轉從同事收到訊息關心她發生了甚麼事後，赫然發現自己莫名其妙的平白受辱，因此憤而報警。</div><br>
									<div>&nbsp;&nbsp;「經由檢察官提起公訴開庭時，小王還試圖為自己的行為辯解，他聘請了律師幫其主張 : 「公司群組是屬於私人群組必須被邀請才能加入，所以他不算是在公眾散布流言因此並沒有構成毀謗。」此案件法官最後裁定，小王觸犯了文字加重毀謗罪，判處拘役1個月。</div>
								</div>
							</div>
						@elseif(Request()->get('article')=='law_protection_sample_2')
							<div class="toug_xq" style="position: relative;">
								<div class="tougao_xnew">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $admin_info->name }}<i class="tou_fi">2023-06-17 16:10</i></span>
									</div>
								</div>
								<div class="xq_text">紳士的禮儀來自好聚好散 風度轉身迎接下一位佳人</div>
								<div class="xq_text01">
									<span style="font-size: 16px;font-weight: bold;">懶人大綱：小陳於花園網結識小晴後，因不滿小晴後來拒絕聯絡而多次騷擾小晴，小晴不堪其擾報警處理，經檢察官起訴後判決小陳嚇危害安全罪，判處拘役20日。</span><br><br>
									<div>&nbsp;&nbsp; 完整細節：這是一則根據事實案例判決改編的甜心網故事，小陳一時的執迷不悟卻換來讓自己成為階下囚，很是可惜，讓每次的相遇都能好聚好散，是每個紳士都該具備的標準禮儀喔!當你風度轉身時，下一個美好佳人已等著要與你認識，所以切勿讓一時的錯誤，釀成對雙方都無法彌補的傷害。</div><br>
									<div>&nbsp;&nbsp; 小陳(基於個資法規定文中皆採化名)與小晴(基於個資法規定文中皆採化名)在花園網相識了一段時間後，基於一些個人原因小晴覺得不再適合繼續往來，就逐漸不再與小陳有所往來，小陳不滿小晴拒絕聯絡，心生怨恨後開始陸續於109年起以手機發送多起帶有恐嚇意味的訊息給小晴，包括 : 「我所掌握的資料比妳想像的還多，包括你的日常、妳工作、等等…包括我的刷卡、提款資料，更重要的，還有我手錶上的錄音檔」、「今天晚上妳昏了直播，我會上去給驚喜，到時候妳就更睡不著了」…..等等，讓小晴不勝其擾。</div><br>
									<div>&nbsp;&nbsp; 由於小晴閒暇時也跟現下的年輕人一樣，喜歡玩玩直播，小陳在多次發送手機訊息未能得到小晴的回應後，更變本加厲到小晴的直播間，當小晴在愛xx直播平台直播時，以暱稱「明歌」「神秘嘉賓」「大偵探」公開留言 : 「主播去**網站找**，然後*了四十萬，現在官司還在，你們都知道嗎」、 「你們真是大盤子，DONET給一個炸欺最犯，真可憐」、「想了解主播詐欺證據的+籟」等等言語，讓小晴更是長期處於被小陳的威脅畏懼而惴惴不安，小晴因此決定勇敢報案。</div><br>
									<div>&nbsp;&nbsp; 警方受理小晴的報案後偵辦案件，經小晴遞交的多項證據包括 : 手機簡訊翻拍照片與直播平台直播時之翻拍畫面，提交檢察官提起公訴，而小陳也坦承不諱關於上述自己的犯行，被法官裁定判刑恐嚇危害安全罪，判處拘役20日。</div>
								</div>
							</div>
						@elseif(Request()->get('article')=='law_protection_sample_3')
							<div class="toug_xq" style="position: relative;">
								<div class="tougao_xnew">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $admin_info->name }}<i class="tou_fi">2023-06-17 16:10</i></span>
									</div>
								</div>
								<div class="xq_text">誠實是建立長期關係的橋樑 編造的謊言終會被正義擊破</div>
								<div class="xq_text01">
									<span style="font-size: 16px;font-weight: bold;">懶人大綱：大明在花園網編造虛假的收入條件，相約小莉出遊約會後未履行與小莉的約定且將她封鎖，小莉報警後經起訴判決詐欺罪成立，賠償12,500元判處拘役55日。</span><br><br>
									<div>&nbsp;&nbsp; 完整細節：多金帥氣又溫柔的對象向來是女生的夢中情人，不過若為了讓自己受異性歡迎，反而刻意造假自己的個人資料，比如偽裝自己從事高所得收入的職務…且更有甚者以偽造的條件對女生騙財騙色，那就一定逃不過法律的制裁。這是一則根據事實案例判決改編的甜心網故事，希望藉此提醒男性會員們誠實才是建立長期關係最好的橋樑，女生也能從中學習如何保護自己，編造的謊言終會被正義擊破!</div><br>
									<div>&nbsp;&nbsp; 大明(基於個資法規定文中皆採化名)是一個經濟狀況普通的上班族，就跟大多數的男生一樣，平日喜歡上上各大知名交友網站，作為認識異性的管道。然而大明為了凸顯自己的個人簡介在眾多會員中更為優秀，明知自己僅為一個普通上班族，卻以假名在108年於XX花園網註冊會員，更大肆虛構自己的個人簡介資料，包括任職於會計事務所收入200-300萬元、資產1800萬元等不實訊息，來吸引並誘騙女性會員。</div><br>
									<div>&nbsp;&nbsp; 在註冊會員資料後大約半年後，實際上沒有足夠經濟能力的大明，透過微信跟小莉(基於個資法規定文中皆採化名)聊天，期間佯稱自己過去有4段的包養經歷，並謊稱可支付小莉每月5萬元的生活費，來換取與小莉進行每月4次的約會。小莉不疑有他與大明相約在某間MTV約會，結束後大明並無依約支付生活費給小莉，還假借外出包廂打電話名義，將小莉微信給拉黑封鎖，小莉驚覺吃虧上當後對大明憤而提出告訴。</div><br>
									<div>&nbsp;&nbsp; 經法官審理後大明對其犯行坦承不諱，法官依據小莉提供的證據包括 : 「XX花園網」、微信之對話紀錄截圖、案發現場附近之監視錄影畫面截圖、被告之稅務電子閘門財產所得調件明細表、「花園網」網頁資料與真實姓名對照表，裁定大明犯罪實事證據確鑿，參照對話紀錄中大明對小莉謊稱可支付包養費用每月5萬元，每月約會4次的比例，判決大明詐欺罪成立，除了需賠償小莉12,500元的不法所得外，並判處拘役55日。</div>
								</div>
							</div>
						@elseif(Request()->get('article')=='law_protection_sample_4')
							<div class="toug_xq" style="position: relative;">
								<div class="tougao_xnew">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $admin_info->name }}<i class="tou_fi">2023-06-17 16:10</i></span>
									</div>
								</div>
								<div class="xq_text">截圖貼貼貼看我一秒變身?! 變身千面女郎小心誤觸法網</div>
								<div class="xq_text01">
									<span style="font-size: 16px;font-weight: bold;">懶人大綱：小可日前於花園網註冊會員，卻盜用名人小玲的照片做為個人資料使用，尋求長期關係，經檢察官起訴後判決小可散布文字、圖畫誹謗罪，判處拘役2個月。</span><br><br>
									<div>&nbsp;&nbsp; 完整細節：若能有張媲美明星的照片，相信在交友平台上會非常吃香，畢竟在尚未有機會深入認識以前，高顏值還是最能先讓對方留下深刻印象。不過若因為羨慕明星的亮麗而隨意截圖盜用挪為已用，那可是會不小心誤觸法網的喔~這是一則根據事實案例判決改編的甜心網故事，所以奉勸各位女會員們即使明星照再美，也萬萬不可任意盜用，相信只要多加打扮每個女孩都能美麗動人喔!</div><br>
									<div>&nbsp;&nbsp; 小可(基於個資法規定文中皆採化名)透過手機行動上網以某一電子信箱帳號，註冊花園網站會員取得貼文權限後，於住處用家用網路連結網際網路登入花園網，在不特定公眾均得登入瀏覽花園網站上張貼小玲(基於個資法規定文中皆採化名)照片，並在基本資料關係欄中填載「尋求長期關係」，以此方式散布有關小玲的不實之事，足以貶損其名譽。經花園網的網站管理者瀏覽網頁發覺後通知小玲，經其報警處理而由警方循線查獲，並由檢察官提起公訴。</div><br>
									<div>&nbsp;&nbsp; 小可被起訴後砌詞狡辯，承認門號跟信箱雖然是她所用，但沒有張貼小玲的相關資料，且檢察官也無法證明她有犯案動機，然而檢察官表示從google註冊資料、行動電話申設與各次上網IP位址，皆與小可持用的手機門號行動上網時間相符;且小玲於Facebook社群網站個人頁面張貼文章時所發布之照片，與小可所張貼之照片相同，有文章截圖及本案網站截圖佐證，由於小玲為公眾人物，所以小可的行為明顯已侵害告訴人所享有之社會名聲與形象，至於行為動機存於個人內心，只會成為法官判刑的審酌事項，並不能因此可為其行為開脫。此案件法官最後裁定，小可觸犯了散布文字、圖畫誹謗罪，判處拘役2個月。</div><br>
								</div>
							</div>
						@elseif(Request()->get('article')=='law_protection_sample_5')
							<div class="toug_xq" style="position: relative;">
								<div class="tougao_xnew">
									<div class="tou_img_1">
										<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $admin_info->name }}<i class="tou_fi">2023-06-17 16:10</i></span>
									</div>
								</div>
								<div class="xq_text">神鬼交鋒真人版假名行騙 相交貴在誠信勿以身試法</div>
								<div class="xq_text01">
									<span style="font-size: 16px;font-weight: bold;">懶人大綱：大勇與小愛在花園網結識，幾次出遊大勇皆請小愛墊付購買高價精品，且提供假名給店家供後續手機取貨，佯稱月底還款卻將小愛封鎖，經小愛報警檢察官起訴判決大勇詐欺取財罪以及偽造私文書罪，宣告沒收不法所得，判處拘役11個月。</span><br><br>
									<div>&nbsp;&nbsp; 完整細節：來到花園網與心儀的對象相約出遊，應該帥哥美女們都會滿心期待，而在約會的過程中，若是相處愉快相信男士們多少都會想送點小禮物給出遊的女伴，為這美好的約會畫下完美的句點，既然是禮物當然是要自己出錢才有誠意囉!所以若是女孩遇到男士約會時藉詞購買情侶禮品卻要你先行墊付，那不如請對方下次方便時再購買也不遲~這是一則根據事實案例判決改編的甜心網故事，除了警醒男士勿貪圖不法所得而吃上官司，也提醒女孩對雙方金錢往來要多加注意喔!</div><br>
									<div>&nbsp;&nbsp; 大勇(基於個資法規定文中皆採化名)與小愛(基於個資法規定文中皆採化名)在花園網結識後，一個月內前後相約出遊多次，而每次大勇都會藉故以雙人使用為由購買高價精品，比如於3C精品店購買情人用的iPhone手機與AirPods耳機 ; 百貨公司購買精品對錶、IPad平板電腦、包包、鞋子、衣服等等…總計價值高達近20萬，期間大勇明知沒有出錢購買這些高價精品但仍意圖將物品占為己有，故出遊期間所購買的高價物品皆藉詞請小愛代為墊付，並佯稱將會連同約定好8次6萬元不等的約會費用，月底一併結算給小愛，讓小愛提供帳號給他匯款。</div><br>
									<div>&nbsp;&nbsp; 轉眼到了月底小愛卻沒有看到有款項入帳，多次發訊息催促大勇並苦苦哀求需繳交信用卡費請大勇能遵守承諾盡快匯款，大勇卻將小愛封鎖不再來往，小愛驚覺被騙後報警。大勇經檢察官起訴後還欲砌詞狡辯，不承認有購買高價商品甚而說此為小愛自願贈與，幸而小愛在與大勇失去聯繫後有立即向花園網求援，對照花園網配合協助調查後的證據，與小愛手機留存與大勇對話的截圖相符，足以佐證大勇所購物品皆非小愛自願贈與 ; 而大勇在3C商品店留下假名購買手機，亦被大勇以商家調貨手機後續要包膜等等藉口先被大勇以假名取走。因此本案件法官最後裁定，大勇同時觸犯詐欺取財罪與偽造私文書罪兩罪併罰，須全數歸還所有詐欺得利高達近20萬的物品，並判處拘役11個月。</div><br>
								</div>
							</div>
						@else
						<div class="toug_xq" style="position: relative; {{ $postDetail->uid==1049 ? 'background:#ddf3ff;' : ''}} @if($postDetail->top==1) background:#ffcf869e !important; @endif">
							<div class="tougao_xnew">
								@php
									$uID=\App\Models\User::findById($postDetail->uid);
                                    $isBlurAvatar = \App\Services\UserService::isBlurAvatar($uID, $user);
								@endphp
								<a href="/dashboard/viewuser{{$uID->isVVIP()?'_vvip':null}}/{{$postDetail->uid}}?via_by_essence_article_enter={{ $postDetail->pid }}">
									<div class="tou_img_1">
										<div class="tou_tx_img @if($isBlurAvatar) blur_img @endif"><img src="@if(file_exists( public_path().$postDetail->umpic ) && $postDetail->umpic != ""){{$postDetail->umpic}} @elseif($postDetail->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
										<span>{{ $postDetail->uname }}<i class="tou_fi">{{ date('Y-m-d H:i',strtotime($postDetail->pupdated_at)) }}</i></span>
									</div>
								</a>
								{{--<div class="tog_time">{{ date('Y-m-d H:i',strtotime($postDetail->pcreated_at)) }}</div>--}}
							</div>
							@if(auth()->user()->id ==1049 || $postDetail->uid == auth()->user()->id)
{{--								<div class="ap_but" style="margin-top: 10px; margin-right:5px;">--}}
{{--									<a id="repostLink" href="/dashboard/postsEdit/{{ $postDetail->pid }}/all"><span class="iconfont icon-xiugai_nn"></span>修改</a>--}}
{{--									<a onclick="postDelete({{ $postDetail->pid }})"><span class="iconfont icon-lajitong"></span>刪除</a>--}}
{{--								</div>--}}
								<div class="ap_butnew" style="margin-top: 10px; margin-right:10px;">
									@if($postDetail->pdeleted_at == null)
										@if(auth()->user()->id == 1049)
											<a onclick="postVerifyStatus({{ $postDetail->pid }})" class="sc_cc verify_text">{{ $postDetail->pverify_status==0 ? '尚未審核' : ($postDetail->pverify_status==1 ? '通過':'取消通過' ) }}</a>
										@endif
										<a onclick="postDelete({{ $postDetail->pid }})" class="sc_cc"><img src="/posts/images/del_03n.png">刪除</a>
										<a id="repostLink" href="/dashboard/essence_postsEdit/{{ $postDetail->pid }}/all" class="sc_cc"><img src="/posts/images/xiugai.png">修改</a>
									@endif
									@if($postDetail->pdeleted_at != null && auth()->user()->id == 1049)
										<a onclick="recover_post({{ $postDetail->pid }});" class="sc_cc">回復文章</a>
									@endif
								</div>
							@endif
							<div id="ptitle" class="xq_text">{{ $postDetail->ptitle }}</div>
							<div id="pcontents" class="xq_text01">{!! \App\Models\Posts::showContent($postDetail->pcontents) !!}</div>
							{{--<div class="xq_textbot"><img src="/posts/images/tg_10.png"></div>--}}
						</div>
						@endif
						<div class="botline_fnr" style="margin-bottom:0px;"></div>
						<!--  -->
						<style>
							.dropup,
							.dropdown {
								position: absolute;
								right: 0;
								top: 0;
							}
							.tgxq_nr li{
								padding: unset;
							}
						</style>
						<!--  -->
					</div>
				</div>
			</div>
		</div>
		{{--<div class="botfasont">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-md-10" style=" float: right ;">
						<div class="bot_wid">
							<div class="bot_wid_nr">
								<form action="/dashboard/posts_reply" id="posts" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
									<input type="hidden" name="article_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="reply_id" id ="reply_id" value="{{ $postDetail->pid }}">
									<input type="hidden" name="tag_user_id" id ="tag_user_id" value="">
									<div class="bot_nnew">
										<div id="tagAcc" class="blue" style=" padding-top: 2px; margin-left:8px;"></div>
										<textarea id="contents" name="contents" rows="1" class="select_xx05 bot_input" placeholder="回應此篇文章"></textarea>
									</div>
									<button id="response_send" type="submit" class="bot_cir_1" style="border: none;"></button>
									--}}{{--<button id="response_send" type="submit" class="bot_cir" style="border: none;"><i class="iconfont icon-fasong bot_fs"></i></button>--}}{{--
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>--}}

		<!--弹框-->
		<div class="blbg_new" onclick="gmBtn1()" style="display: none;"></div>
		<div class="bl bl_tab bl_tab_01" id="tab_title" style="display: none;">
			<div class="bltitle_a"><span class="font14" style="text-align:center;float:none !important">提示</span></div>
			<div class="n_blnr02 matop10">
				<div class="n_fengs" style="text-align:center;width:100%;">{{ Session::get('message') }}</div>
			</div>
			<a onclick="gmBtn1()" class="bl_gb01"><img src="/posts/images/gb_icon.png"></a>
		</div>
@stop

@section('javascript')
	<script>

		function readyNumber() {

			$('textarea').each(function () {
				this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
			}).on('input', function () {
				this.style.height = 'auto';
				this.style.height = (this.scrollHeight) + 'px';
				//alert((this.scrollHeight) + 'px');

				var textAreaHeight = parseInt(this.scrollHeight);
				if($("#tagAcc").text()!==''){
					textAreaHeight = parseInt(this.scrollHeight+22);
				}
				if(textAreaHeight<50){
					textAreaHeight=50;
				}
				$(".bot_nnew").css('height',textAreaHeight + 'px');
				$("#response_send").css('margin-top',(textAreaHeight-34) + 'px');
			})
		}

		readyNumber();

		function gmBtn1(){
			$(".blbg_new").hide();
			$(".bl").hide();
		}

		function show(index) {
			$('.needToHide_'+index).show();
			document.getElementById('btn_'+index).innerHTML = "收起更多>";
			document.getElementById('btn_'+index).href = "javascript:hide("+index+");";
		}

		function hide(index) {
			$('.needToHide_'+index).hide();
			document.getElementById('btn_'+index).innerHTML = "展開更多>";
			document.getElementById('btn_'+index).href = "javascript:show("+index+");";
		}

		function postReply(pid, tag_name, tag_user_id) {
			$('#reply_id').val(pid);
			$('#tag_user_id').val(tag_user_id);
			$('#tagAcc').text('@'+tag_name);
		}

		function postDelete(pid) {
			c4('確定要刪除嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/essence_posts_delete?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid
					},
					dataType: 'json',
					success: function(data) {
						c5(data.msg);
						window.location.href=data.redirectTo;
					}
				});
			});
		}

		$(document).on('click','.announce_bg, #tab05',function() {
			window.location.reload();
		});

		$('#response_send').on('click', function() {

			{{--var checkUserVip='{{ $checkUserVip }}';--}}
			{{--var checkProhibit='{{ $user->prohibit_posts }}';--}}
			{{--var checkAccess='{{ $user->access_posts }}';--}}
			{{--if(checkUserVip==0) {--}}
			{{--	c5('此功能目前開放給連續兩個月以上的VIP會員使用');--}}
			{{--	return false;--}}
			{{--}else if(checkProhibit==1){--}}
			{{--	c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else if(checkAccess==1){--}}
			{{--	c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else{--}}
				var form = $(this).closest("form");
				if($("#contents").val().length ==0){
					c5('請輸入文字再送出');
					return false;
				}
				form.submit();
			// }
		});

		$('.bot_wid_nr').on('click', function() {

			{{--var checkUserVip='{{ $checkUserVip }}';--}}
			{{--var checkProhibit='{{ $user->prohibit_posts }}';--}}
			{{--var checkAccess='{{ $user->access_posts }}';--}}
			{{--if(checkUserVip==0) {--}}
			{{--	c5('此功能目前開放給連續兩個月以上的VIP會員使用');--}}
			{{--	return false;--}}
			{{--}else if(checkProhibit==1){--}}
			{{--	c5('您好，您目前被站方禁止發言，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}else if(checkAccess==1){--}}
			{{--	c5('您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');--}}
			{{--	return false;--}}
			{{--}--}}
		});

		$(document).keydown(function (event) {
			if (event.keyCode == 8 || event.keyCode == 46) {
				if($("#contents").val().length ==0){
					$('#tagAcc').text('');
				}
			}
		});

		function reposts() {
			$('#repostLink').hide();
			$('#ptitle').hide();
			$('#pcontents').hide();
		}

		function recover_post(pid)
		{
			c4('確定要回復嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/posts_recover?{{ csrf_token() }}={{now()->timestamp}}',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						pid: pid
					},
					success: function(data) {
						if(data.postType=='main'){
							c5(data.msg);
							window.location.href=data.redirectTo;
						}
						else
							c5(data.msg);
					}
				});
			});
		}



		function postVerifyStatus(pid) {
			var verify_str=$('.verify_text').text();
			if(verify_str=='通過' || verify_str=='尚未審核'){
				verify_str='通過審核';
			}
			c4('確定要'+verify_str+'嗎?');
			$(".n_left").on('click', function() {
				$.ajax({
					url: '/dashboard/essence_verify_status?{{ csrf_token() }}={{now()->timestamp}} ',
					method: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						'user_id': "{{ auth()->user()->id }}",
						'pid': pid
					},
					dataType: 'json',
					success: function(data) {
						c5(data.msg);
						window.reload();
					}
				});
			});
		}

	</script>
@endsection