@php
    $admin_info=\App\Models\User::leftJoin('user_meta', 'users.id','=','user_meta.user_id')->where('users.id', 1049)->first();
@endphp

{{--女生法律保護文章詳情 (開始)--}}
@if($user->engroup==2)
	@if(Request()->get('article')=='law_protection_sample_0907_1')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">追求者太過熱情拒絕卻被威脅? 仔細蒐證大膽拒絕法律保護妳</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三愛慕小美追求未果因而心生怨恨，在花園網發現與小美相似的女會員照片，將其截圖私下散布於工作群組，經起訴判決張三加重毀謗罪，判處拘役1個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：「窈窕淑女、君子好逑。」對於追求者的熱情感到無福消受，甚而因為拒絕對方造成他惱羞成怒，做出種種逾越法律分際的行為，感到害怕嗎?不用怕，只要仔細蒐證大膽拒絕，讓法律保護妳哦！<span style="color: blue;">以花園網來說若您覺得疑似被騷擾，隨時可以透過站方Line與站長聯繫，反映遇到的問題，請注意務必暱稱是站長，訊息有特殊的藍底以及右下角聯絡我們，官方的email/line@帳號，才是正確聯繫站上的方式喔!</span>這是一則根據事實案例判決改編的甜心網故事，也讓各位女會員能好好學習，如何跟騷擾者SAY NO!!</div><br>
				<div>&nbsp;&nbsp; 張三與小美在民國110年因為工作關係認識以後，張三即屢傳訊息給小美，這讓小美感覺已經開始讓她困擾，只能先採取冷處理的態度因應。民國110年11月某天早上在小美至台大醫院附近時，張三卻突然靠近小美表示想跟她說說話，小美加快腳步轉進醫院地下街的商店尋求店員的協助，張三見狀先在商店門口外徘徊，過沒多久欲直接進入店內，張三在店員的阻攔下大聲嚷嚷，威脅小美若再不肯跟他說話，他就要在小美上班的公司散布對小美不利的消息，說罷張三才悻悻然地離開店家，而直至小美同事到來後，小美在同事的陪同下安然離開。</div><br>
				<div>&nbsp;&nbsp; 張三在這件事情過了大約兩週後，他在花園網截圖了多張長相疑似小美的會員照片，並附加了很多不雅的揣測詞句在公務群組裡散佈，例如 : 「很可能是某人的秘密」、「她是會為了自己目的，不擇手段到連身體都會賣的人，她有在做包養的賣喔」等等….後續經小美輾轉從同事收到訊息關心她發生了甚麼事後，赫然發現自己莫名其妙的平白受辱，因此憤而報警。 </div><br>
				<div>&nbsp;&nbsp; 經由檢察官提起公訴開庭時，張三還試圖為自己的行為辯解，他聘請了律師幫其主張 : 「公司群組是屬於私人群組必須被邀請才能加入，所以他不算是在公眾散布流言因此並沒有構成毀謗。」，經檢察官起訴後法庭判決:張三文字加重毀謗罪成立，判處拘役1個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_2')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">人人都怕遇到恐怖情人怎麼辦? 清楚表態保存訊息截圖曝警去!</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三於花園網結識小美後，因不滿小美後來拒絕聯絡而多次騷擾小美，小美不堪其擾報警處理，經檢察官起訴後判決張三嚇危害安全罪，判處拘役20日。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：這是一則根據事實案例判決改編的甜心網故事，張三一時的執迷不悟卻換來讓自己成為階下囚，美麗佳人的魅力人人愛，女孩子面對追求者時難免會遇到過於執著的恐怖情人，除了清楚跟對方表明態度外，保存訊息截圖證據報警去~!<span style="color: blue;">而若是在一開始認識會員時就可以多些注意會更有幫助，以花園網來說日常即有將會員注重分級管理，若有標示警示會員：警示原因會有多種，也許是被檢舉也許是站長設定為警示。跟此區會員互動請務必務必務必提高十二萬分警覺喔!</span></div><br>
				<div>&nbsp;&nbsp; 張三與小美109年6月在花園網相識了一段時間後，基於一些個人原因小美覺得不再適合繼續往來，就逐漸不再與張三有所往來，張三不滿小美拒絕聯絡，心生怨恨後開始陸續於109年起以手機發送多起帶有恐嚇意味的訊息給小美，包括 : 「我所掌握的資料比妳想像的還多，包括你的日常、妳工作、等等…包括我的刷卡、提款資料，更重要的，還有我手錶上的錄音檔」、「今天晚上妳昏了直播，我會上去給驚喜，到時候妳就更睡不著了」…..等等，讓小美不勝其擾。</div><br>
				<div>&nbsp;&nbsp; 由於小美閒暇時也跟現下的年輕人一樣，喜歡玩玩直播，張三在多次發送手機訊息未能得到小美的回應後，更變本加厲到小美的直播間，當小美在愛xx直播平台直播時，以暱稱「明歌」「神秘嘉賓」「大偵探」公開留言 : 「主播去**網站找**，然後*了四十萬，現在官司還在，你們都知道嗎」、 「你們真是大盤子，DONET給一個炸欺最犯，真可憐」、「想了解主播詐欺證據的+籟」等等言語，讓小美更是長期處於被張三的威脅畏懼而惴惴不安，小美因此決定勇敢報案。</div><br>
				<div>&nbsp;&nbsp; 警方受理小美的報案後偵辦案件，經小美遞交的多項證據包括 : 手機簡訊翻拍照片與直播平台直播時之翻拍畫面，提交檢察官提起公訴，而張三也坦承不諱關於上述自己的犯行，經檢察官起訴後法庭判決:張三恐嚇危害安全罪成立，判處拘役20日。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_3')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">編造不實高所得條件誘騙約會，未依約定匯款逃不了法律制裁</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三在花園網編造虛假的收入條件，相約小莉出遊約會後未履行與小莉的約定且將她封鎖，小莉報警後經起訴判決詐欺罪成立，賠償12,500元判處拘役55日。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：多金帥氣又溫柔的對象向來是女生的夢中情人，不過若為了讓自己受異性歡迎，反而刻意造假自己的個人資料，比如偽裝自己從事高所得收入的職務…且更有甚者以偽造的條件對女生騙財騙色，那就一定逃不過法律的制裁。這是一則根據事實案例判決改編的甜心網故事，女會員若是遇到這種無恥之徒，不用忍氣吞聲委屈吃虧，把手上的往來訊息證據準備好，讓法庭好好審判還一個公道給妳。<span style="color: blue;">而花園網也特別有在站上聲明，若已經談妥包養條件，正式約會前。最重要的一件事就是：先收錢、先收錢、先收錢!重要的事情講三遍~~~都不嫌多喔！</span></div><br>
				<div>&nbsp;&nbsp; 張三是一個經濟狀況普通的上班族，就跟大多數的男生一樣，平日喜歡上上各大知名交友網站，作為認識異性的管道。然而張三為了凸顯自己的個人簡介在眾多會員中更為優秀，明知自己僅為一個普通上班族，卻以假名在108年8月於花園網註冊會員，更大肆虛構自己的個人簡介資料，包括任職於會計事務所收入200-300萬元、資產1800萬元等不實訊息，來吸引並誘騙女性會員。</div><br>
				<div>&nbsp;&nbsp; 在註冊會員資料後大約半年後，實際上沒有足夠經濟能力的張三，透過微信跟小莉聊天，期間佯稱自己過去有4段的包養經歷，並謊稱可支付小莉每月5萬元的生活費，來換取與小莉進行每月4次的約會。小莉不疑有他與張三相約在某間MTV約會，結束後張三並無依約支付生活費給小莉，還假借外出包廂打電話名義，將小莉微信給拉黑封鎖，小莉驚覺吃虧上當後對張三憤而提出告訴。</div><br>
				<div>&nbsp;&nbsp; 經法官審理後張三對其犯行坦承不諱，法官依據小莉提供的證據包括 : 「花園網」、微信之對話紀錄截圖、案發現場附近之監視錄影畫面截圖、被告之稅務電子閘門財產所得調件明細表、「花園網」網頁資料與真實姓名對照表，裁定張三犯罪實事證據確鑿，參照對話紀錄中張三對小莉謊稱可支付包養費用每月5萬元，每月約會4次的比例，經檢察官起訴後法庭判決:張三詐欺罪成立，除了需賠償小莉12,500元的不法所得外，並判處拘役55日。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_4')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">偷用名人美照看我一秒變身 ?! 以非本人假個資小心誤觸法網</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：小莉日前於花園網註冊會員，卻盜用名人小美的照片做為個人資料使用，尋求長期關係，經檢察官起訴後判決小莉散布文字、圖畫誹謗罪，判處拘役2個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：若能有張媲美明星的照片，相信在交友平台上會非常吃香，畢竟在尚未有機會深入認識以前，高顏值還是最能先讓對方留下深刻印象。不過若因為羨慕明星的亮麗而隨意截圖盜用挪為已用，那可是會不小心誤觸法網的喔~<span style="color: blue;">所以花園網也有不時於站上提醒大家上傳的照片，要注意是否曾經公開過(FB/iG/PTT/DCARD等，千萬不可以盜竊他人美圖</span>，這是一則根據事實案例判決改編的甜心網故事，所以奉勸各位女會員們即使明星照再美，也萬萬不可任意盜用，相信只要多加打扮每個女孩都能美麗動人喔!</div><br>
				<div>&nbsp;&nbsp; 小莉109年10月透過手機行動上網以某一電子信箱帳號，註冊花園網站會員取得貼文權限後，於住處用家用網路連結網際網路登入花園網，在不特定公眾均得登入瀏覽花園網站上張貼小美照片，並在基本資料關係欄中填載「尋求長期關係」，以此方式散布有關小美的不實之事，足以貶損其名譽。經花園網的網站管理者瀏覽網頁發覺後通知小美，經其報警處理而由警方循線查獲，並由檢察官提起公訴。</div><br>
				<div>&nbsp;&nbsp; 小莉被起訴後砌詞狡辯，承認門號跟信箱雖然是她所用，但沒有張貼小美的相關資料，且檢察官也無法證明她有犯案動機，然而檢察官表示從google註冊資料、行動電話申設與各次上網IP位址，皆與小莉持用的手機門號行動上網時間相符;且小美於Facebook社群網站個人頁面張貼文章時所發布之照片，與小莉所張貼之照片相同，有文章截圖及本案網站截圖佐證，由於小美為公眾人物，所以小莉的行為明顯已侵害告訴人所享有之社會名聲與形象，至於行為動機存於個人內心，只會成為法官判刑的審酌事項，並不能因此可為其行為開脫。經檢察官起訴後法庭判決:小莉觸犯了散布文字、圖畫誹謗罪，判處拘役2個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_5')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">神鬼交鋒真人版假名行騙，相交貴在誠信勿以身試法</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美在花園網結識，幾次出遊張三皆請小美墊付購買高價精品，且提供假名給店家供後續手機取貨，佯稱月底還款卻將小美封鎖，經小美報警檢察官起訴判決張三詐欺取財罪以及偽造私文書罪，宣告沒收不法所得，判處拘役11個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：來到花園網與心儀的對象相約出遊，應該帥哥美女們都會滿心期待，而在約會的過程中，若是相處愉快相信男士們多少都會想送點小禮物給出遊的女伴，為這美好的約會畫下完美的句點，既然是禮物當然是要自己出錢才有誠意囉!所以若是女孩遇到男士約會時藉詞購買情侶禮品卻要你先行墊付，那就要小心了。這是一則根據事實案例判決改編的甜心網故事，<span style="color: blue;">花園網特別列出凡有男會員以下列三種付款方式:1.初識的男會員說零用錢分次給太麻煩,月底財務會計一次結清。2.叫你下載app給虛擬幣。3.給支票。一定要拒絕他，只收現金才最有保障喔!</span></div><br>
				<div>&nbsp;&nbsp; 張三與小美民國108年8月在花園網結識後，一個月內前後相約出遊多次，而每次張三都會藉故以雙人使用為由購買高價精品，比如於3C精品店購買情人用的iPhone手機與AirPods耳機 ; 百貨公司購買精品對錶、IPad平板電腦、包包、鞋子、衣服等等…總計價值高達近20萬，期間張三明知沒有出錢購買這些高價精品但仍意圖將物品占為己有，故出遊期間所購買的高價物品皆藉詞請小美代為墊付，並佯稱將會連同約定好8次6萬元不等的約會費用，月底一併結算給小美，讓小美提供帳號給他匯款。</div><br>
				<div>&nbsp;&nbsp; 轉眼到了月底小美卻沒有看到有款項入帳，多次發訊息催促張三並苦苦哀求需繳交信用卡費請張三能遵守承諾盡快匯款，張三卻將小美封鎖不再來往，小美驚覺被騙後報警。張三經檢察官起訴後還欲砌詞狡辯，不承認有購買高價商品甚而說此為小美自願贈與，幸而小美在與張三失去聯繫後有立即向花園網求援，對照花園網配合協助調查後的證據，與小美手機留存與張三對話的截圖相符，足以佐證張三所購物品皆非小美自願贈與 ; 而張三在3C商品店留下假名購買手機，亦被張三以商家調貨手機後續要包膜等等藉口先被張三以假名取走。參照小美保存得LINE對話等與張三簽具假名辦手機等多項證據，經檢察官起訴後法庭判決 :張三同時觸犯詐欺取財罪與偽造私文書罪兩罪併罰，須全數歸還所有詐欺得利高達近20萬的物品，並判處拘役11個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_6')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">誤信詐騙哭哭約完會不匯款怎麼辦? 隨手保持通訊紀錄勇敢提告沒在怕！</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三加入花園網會員，謊稱自己為年收入百萬坐擁千萬資產的老闆，向小美提出給每月5萬元的約會費用，騙取小美外出約會後未匯款且惡意將她封鎖 ;另又故技重施邀約小莉，經檢察官起訴後判決張三詐欺罪名成立，判處拘役三個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：希望有機會拓展眼界認識各階層朋友的女會員們，對於個人簡介若敘述自己是坐擁千萬資產實業家的男會員們，那應該多少能成功引起女會員們的注意，這時候站上要提醒女會員們，建議多花些時間與對方聊天，比如針對他的個人簡介的成功經驗請教心得….等等，藉此好更深入了解男會員背景的真實性。這是一則根據事實案例判決改編的甜心網故事，一個捏造自己坐擁千萬身家、藉此獲得約會機會卻沒有依約匯款給女會員約會費用而被告詐欺，<span style="color: blue;">所以花園網一再提醒女會員，談好約會費用後見面一定要先收錢喔!而且即使對方傳來手機轉帳截圖，還是要檢查自己帳戶是否有入帳，因為截圖可能是造假的。</span>所以女會員們與男會員間往來的站上對話紀錄與私訊也記得都要隨時保存，藉此保護自己權益喔！</div><br>
				<div>&nbsp;&nbsp; 張三以「kevin」之暱稱在花園網建立帳號，於民國107年8月期間張貼他自己的個人資料為傳產製造產業老闆、收入為新臺幣300萬以上、坐擁資產千萬等不實訊息，向小美謊稱願意以每月新臺幣5萬元與小美約會2個月，使小美以為真外出與張三相約至北市某旅館約會 ; 然而約會完後張三向小美謊稱會匯款給小美，實際上卻沒有將約會費用匯款給小美，且惡意將小美封鎖。張三用欺騙小美的方式約會成功後，食髓知味又再故技重施，於108年3至5月間花園網上，另外結識了一位女會員小莉，也謊稱會支付零用錢給小莉作為約會的費用，並在約會過後並沒有匯款給約會對象小莉、且惡意封鎖小莉，這讓前後兩位女會員都非常生氣並分別向警方報案。</div><br>
				<div>&nbsp;&nbsp; 經警方調查後張三對自己的犯行坦承不諱，包括兩位女會員皆提供了花園網對話證據與張三的微信通話紀錄證據確鑿，經檢察官起訴後法庭判決:張三詐欺罪名成立，判處拘役三個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_7')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">ATM提款機超方便提領各國外幣都嘜通，請女生墊付換匯費用再匯款直接拒絕他!</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三在花園網結識小美，佯稱要匯新台幣400萬元給她，但需要換匯讓小美先付手續費1萬元，小美依約先墊付手續費後卻未收到匯款，驚覺被騙憤而報警，檢察官起訴後判決張三詐欺罪名成立，判處拘役五個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：在身處地球村的現代，各國貨幣的流通往來頻繁，大家也對於若需要兌換各國之間的外幣，需要支付銀行手續費的情形很習以為常，但反而成為另一個有心詐騙的犯案手法、不可不慎。這是一則根據事實案例判決改編的甜心網故事，提醒女會員們若遇到有類似以換匯名目索要手續費才可取得約會費的狀況時，千萬別因此受騙上當，務必請對方自行支付匯兌手續費用，而別輕信謊言讓自己掏腰包墊付喔！現在國內也有些銀行有提供可直接以金融卡進行外幣提領的ATM，領出後再親至銀行櫃檯換匯，都是很方便的喔!<span style="color: blue;">而若女會員們真想特別鎖定超級優質的會員認識的話，花園網特有新增 VVIP 會員，此等級會員經過站方審核，財力無虞。強烈建議女會員優先考慮。如果與VVIP的互動中有出現問題，可隨時向站方反應。</span></div><br>
				<div>&nbsp;&nbsp; 張三過往曾因詐欺案件被法庭判處有期徒刑3個月，卻仍不知悔改，民國110年1月期間又在花園網出沒結識小美，以通訊軟體微信暱稱「ANDY LEE」向小美佯稱要匯新臺幣400萬元給小美，但錢存在香港銀行，須找私人換匯公司換成新臺幣，請小美先支付手續費新台幣1萬元，這樣就能將港幣兌換成新台幣，匯款給答應小美的約會費用。當天小美因而誤信張三詐騙的謊言，配合張三提供的國內銀行帳戶進行匯款，再被張三以無卡提款的方式取得小美的匯款，小美事後遲遲未收到匯兌後的約會費用才發覺被騙憤而報警。</div><br>
				<div>&nbsp;&nbsp; 經警察偵訊張三坦承犯案，承認案情皆與小美所言吻合，並有小美與張三往來的微信對話紀錄截圖，及張三提供給小美匯款帳戶之交易明細、證據確鑿，經檢察官起訴後法庭判決:張三詐欺罪名成立，判處拘役五個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_9')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">約會費用事前談清楚大家都開心，不談錢事後反悔誣告徒勞又無功</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：小莉與張三於花園網結識，小莉事後控告張三對她性侵，但小莉卻一直跟張三說害怕懷孕要求張三每月給生活費，且也對其他網友抱怨張三不願支付約會費用，經檢察官調查後裁定小莉無法舉證，判決張三罪名不成立。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：相信大家來到交友網站，都有自己的想法跟需求，其實旦凡在約會前大家把話說清楚講明白那都沒有問題，最怕就是前面甚麼都不說事後卻覺得吃虧後悔，且甚而捏造不實謊言與證據誣告對方，那可真的就是會讓人感到失望。這是一則根據事實案例判決改編的甜心網故事，奉勸女會員們約會前務必跟對方談清楚，自己對於約會的費用的真實想法，不要因為沒談好約會費用，結果約會完事後反悔誣告對方，只會浪費時間徒勞又無功喔！</div><br>
				<div>&nbsp;&nbsp; 張三與小莉相識於花園網，兩人於111 年2 月相約於法堤商旅約會，然約會過後小莉卻對張三提告，原因是說張三在約會時做出違反她意願的事情。經檢察官查證小莉在張三進入法堤商旅的房間約會時，張三先進浴室沖澡，當時小莉人身自由皆未受張三限制、張三亦無法看到浴室外之動靜，檢察官推論小莉應有充足時間將褲子、內褲穿妥並離開房間，或持手機撥打電話向親友、旅館人員求助，但反而都沒有做任何求救的行為 ; 再加上對照兩人事後的對話截圖皆是小莉跟張三索要約會費用，包括 : 「你會付錢給我嗎你都做了我的第一次」「我以為我們彼此有感覺…有點失望…妳會想要多少呢？」「上包養網就是因為經濟有需求」、「長期的話一個月能提供多少生活費」「你希望多少呢？」「我的第一次都給你了　希望可以有六萬」之訊息…</div><br>
				<div>&nbsp;&nbsp; 張三與小莉既是在包養網站認識，該交友網站性質約會本來就要有對價，既然有對價就不會是違反意願，另外小莉附上的中國醫藥大學附設醫院診斷證明書、員榮醫院診斷證明書都是案發前的證明，沒有辦法作為本案補強證據使用，且小莉在本案有明顯說謊跟推翻前詞的情況，推論應是張三沒有給她約會費用而誣告，因此法庭判決張三無罪駁回。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_10')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">遇到優質男性要睜大眼，開口借現金一定要拒絕</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小莉在花園網結識後，張三跟小莉佯稱身上無現金擁股票價值3000萬且有意跟小莉維持長期約會，遂跟小莉借款10萬元並承諾以後會返還，小莉提款後張三後卻避不見面，經檢察官調查覺得倆人在交往期間為了討好異性難以判定張三詐欺，駁回小莉提告。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：會想到交友網站的女會員們，自然很期望遇到身家上千萬的成功人士，不過對於對方聲稱的背景資料，建議要經過一段時間的相處觀察，好判斷是否可信喔!這是一則根據事實案例判決改編的甜心網故事，若遇到投資家聲稱資金都在股市或房地產身上缺現金，欲借來短期週轉，女會員們千萬別一頭熱的因為好感而相信對方，一定要拒絕拒絕再拒絕喔！<span style="color: blue;">以花園網為例，都有將VIP分類，尤其是VVIP： VVIP 為經過站方認證高資產人士，站方強力推薦!!! 若甜心在與vvip互動時出現任何詐騙問題。請隨時回報站方，站方將提供免費法律諮詢，並將重新審核該位vvip的資格!</span></div><br>
				<div>&nbsp;&nbsp; 張三與小莉相識於花園網，倆人於10年9月相約於臺南荷峰汽車旅館約會，張三在花園網內自稱是醫生，說身上新臺幣3千萬的資產在股票裡，身上沒有任何現金，遂跟小莉借款10萬元並承諾以後一定會返還，小莉不疑有他就去ATM領錢，後來在車上把錢交給張三，張三謊稱工作現職業為醫師且財產均以股票形式持有共計3,000餘萬，膨脹他自身的財產及信用，並與小莉一同飲酒，降低小莉對他的防備心，才導致小莉被張三所騙。小莉心有不甘因此對張三提告詐欺。</div><br>
				<div>&nbsp;&nbsp; 然經檢察官調查後表示，男女中之一方縱使係著眼於對方之經濟能力而願意與對方交往，而他方依其智識程度、社會閱歷，對於是否因此交付財物尚有充分判斷之餘地，倘若出於討好對方、獲取對方好感，而願意貸予金錢甚至贈與財物，即難認係遭到小莉是被詐欺陷於錯誤所為。小莉已年滿24歲、教育程度為專科畢業，從事酒店工作，依其智識程度及社會閱歷，對於在交友網站認識、僅見面2次、不知真實姓名、聯絡電話的張三，還願意提領10萬元借給他，顯然小莉是出於對張三的好感想與其維持交往情誼才同意借款，難以判定張三詐欺，因此法庭判定駁回小莉提告。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_11')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">切不可詐騙約會費用後神隱消失，女生小心遇到暴力男更會吃官司</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：小莉在花園網認識張三，說好3萬元為約會費用後，小莉收到錢藉故離開且封鎖張三; 張三發現小莉開了新帳號，假意與小莉邀約，見面搶走小莉皮包所有財物後離開，經檢察官起訴後判決小莉詐欺罪成立，判處拘役55天並沒收犯案手機，張三判決強制罪成立，判處拘役50天。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：在交友網站的天地裡悠遊，好處就是可以先藉由網站的自我介紹與網站通訊認識彼此後，確認雙方的需求再安排進一步的約會，但若是女會員抱持一顆不誠實的心哄騙男會員，那可別以為騙走約會費用神隱就可以高枕無憂，夜路走多總會遇到鬼，對方不只有可能提告詐欺罪，還可能會以暴力相向。這是一則根據事實案例判決改編的甜心網故事，提醒女會員們要以誠實相待，若詐騙男會員約會費用，不只會吃上官司還可能會被惡意報復喔！</div><br>
				<div>&nbsp;&nbsp; 張三於民國109年6月在花園網結識小莉，小莉跟張三談好每月可以給她3萬元作為約會費用，張三同意後遂於7月初在臺中市全家便利商店內，張三先取款3萬元給小莉，其後雙方步行至臺中市的老乾杯燒烤店用餐。嗣小莉藉上廁所為由離去，經張三多次聯絡，小莉皆未理會，甚而封鎖張三，張三始知受騙。張三後來於109年7月中旬，發現小莉疑似以其他帳號在交友網站貼文徵求約會，遂隱瞞身分與小莉聯繫，相約同日下    午在臺中市路易莎咖啡市政店見面。兩人碰面後，張三不滿小莉先前詐取他的約會費用，要求小莉提出手機供其檢視，小莉不從，張三便徒手拉扯並強行取走小莉所有皮包（內有口紅1支、手機1支、鑰匙1串及4千元），張三得手後隨即離去，小莉憤而報警。</div><br>
				<div>&nbsp;&nbsp; 張三與小莉到案後皆對自己犯行坦承不諱，警方並從現場監視器與張三存簿核對張三強取小莉皮包與小莉詐欺張三無誤，經檢察官起訴後判決小莉詐欺罪成立，判處拘役55天並沒收犯案手機，張三判決強制罪成立，判處拘役50天。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_12')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">被騙約會後對方拒匯款被封鎖就沒轍? 女生只要有對話證據報警讓他吃官司!</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美相識於Isugar，109年12月張三約小美至「艾曼汽車旅館」約會，然約會完後未依約定匯款1萬元及旅館費用880元給小美，經小美詢問後謊稱已轉帳並將小美封鎖，經檢察官起訴法庭宣判詐欺得利罪成立，判處拘役50天沒收不法所得1萬880元。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：在交友網站與app百花齊放的現代，對於想找各式各樣的約會對象都非常方便，所謂青菜蘿蔔各有所好，只要是自己喜歡的就好，可能會有一些女生會想，也不用一定找可以給高額約會費用的菁英人士，這樣找到適合的對象會更容易些，而且約會費用不高應該對方通常會願意給吧?比較不容易被騙…..NONONO~~~千萬別有這樣的想法喔!反而容易被有心人士利用這樣的話術給蒙蔽。這是一則根據事實案例判決改編的包養網故事，提醒女孩子若跟對方談好約會費用，最好要碰面時先拿約會費用，若到旅館約會千萬別答應代墊旅館費用，這也是看對方誠意的好機會。<span style="color: blue;">以花園網來說，特別都有在會員專屬頁-站長公告上提醒女性會員們下列注意事項 : 1:先收錢，再"深入約會行為"  2:先收錢，再"深入約會行為" 3:先收錢，再"深入約會行為" 重要的事說三次！就是希望讓女會員們都能在尋找約會對象時也務必要學會保護自己喔!</span></div><br>
				<div>&nbsp;&nbsp; 張三與小美相識於Isugar，張三以「韋小寶」之暱稱，在包養網及通訊APP「微信」中，與小美談妥給小美1萬元作為約會費用，且佯稱「只不過要等結束完我回家再下樓轉（帳）給妳噢」、「如果是一兩百萬我或許會（跑）」云云….小美不疑有他兩人相約於民國109年12月在淡水的艾曼汽車旅館約會，結束後提供國泰世華銀行之帳號給張三匯款，然張三卻未依約支付約會費用1萬元及旅館費用880元，經小美質問後，張三謊稱已轉帳完成並封鎖小美帳號，小美始知被騙並報警處理。</div><br>
				<div>&nbsp;&nbsp; 經檢察官調查後查證張三與小美在包養網及「微信」對話紀錄核對小美的證詞吻合，經法庭判決張三詐欺得利罪名成立，判處拘役三個月，並沒收張三不法所得10880元。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_13')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">假意用手機網銀已轉帳約會費用 ? 千萬確認自己帳戶收到錢才算數！</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美相識於Isugar，110年2月相約至臺中歐風商旅約會，約會前張三佯裝未帶手機已完成手機轉帳2萬元約會費用給小美，騙取小美代墊旅館費用680元並完成約會。後經小美查證並未收到匯款並連繫張三未果始知受騙報警。經檢察官起訴法庭宣判詐欺得利罪成立，判處拘役6個月，並沒收20680不法所得。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：人手一支的手機在科技進步之下已經不只是手機，儼然甚而成為人們的手掌錢包，因為透過網路銀行即可完成轉帳與付款，但千萬小心會有人想利用科技之便進行犯罪，這是一則根據事實案例判決改編的包養網故事，提醒女生一定要先收錢再深入約會喔！<span style="color: blue;">以花園網來說，在會員專屬頁FAQ常見問題更特別提醒，若對方用手機轉帳截圖，還是要先檢查自己帳戶是否有入帳，因為截圖有可能是造假，仔細確認現金入帳再與對方約會喔！</span></div><br>
				<div>&nbsp;&nbsp; 張三與小美相識於包養網，小美一開始是在網站上刊登可接受付費約會的訊息，先後有暱稱JAY及KAI之人於110年1月與小美聯繫，張三即為其中之一，後續兩人持續透過微信對話，兩人談好張三會給小美約會費用2萬元後，就相約在臺中歐風商旅約會。張三開車去接小美，小美並沒特別記下張三的車牌號碼，只知道張三汽車的顏色及廠牌，張三先在上車時就用手機的網銀操作，然後在小美還沒看清楚時，張三就按掉畫面並說他已經匯款了，小美因此被蒙騙認為張三的確已完成匯款，兩人就去「歐風汽車旅館」約會且進去「歐風汽車旅館」前，張三還謊稱說沒帶錢請小美幫他付房間費$680，說房間費之後會匯給小美，房間費不包含在2萬元內；兩人約會完張三謊稱說他有事就匆匆離開，當小美回家要去領錢時，才發現錢沒匯進來，小美趕緊以微信問張三，發現張三已經將她微信帳號封鎖，始知被騙憤而報警。</div><br>
				<div>&nbsp;&nbsp; 一開始張三還矢口否認說並沒有談好需要他給付約會費用，兩人是自願外出約會，經檢察官查證小美提供包養網與微信的對話紀錄截圖歐風商旅住宿登記資料各1份及監視器畫面截圖都能呼應的確張三有答應小美給付1次2萬元的約會費用，法庭判決張三詐欺罪名成立，判處拘役六個月，並沒收不法所得20680元。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_14')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-07 17:00</i></span>
				</div>
			</div>
			<div class="xq_text">以為幸運遇到公司老闆卻變身惡狼，見面覺不妥立即藉故離開保護自己</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美相識於「包養網站」，張三假扮成功人士能提供小美每月8萬元的約會，騙取小美於109年8月至嘉義某旅館與他約會，並答應隔日載小美去新竹與朋友碰面，然而當小美發現張三未開車前來憤而離開時，張三卻憑藉其體格優勢強迫小美發生關係。經小美報警檢察官起訴法庭宣判強制性交罪成立，判處拘役4年。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：當大家藉由約會網站認識異性相談甚歡後，對於下一步約會見面一定是充滿期待，不過女生千萬要多多注意保護自己，不只是確定先拿到約會費用再深入約會，更要相信自己的觀察趨吉避凶。這是一則根據事實案例判決改編的包養網故事，當與對方見面後若覺得對方舉止不像自己介紹的身份，建議藉故提早離開現場，待日後更深入認識對方後為佳。以花園網為例，都有將VIP分類，尤其是VVIP： VVIP 為經過站方認證高資產人士，站方強力推薦!!! 若甜心在與vvip互動時出現任何詐騙問題。請隨時回報站方，站方將提供免費法律諮詢，並將重新審核該位vvip的資格!</div><br>
				<div>&nbsp;&nbsp; 張三與小美相識於包養網，張三對小美佯稱是公司老闆，每月可提供小美８萬元約會費用，並以ＬＩＮＥ通訊軟體邀約小美見面，兩人於１０９年８月相約於嘉義某間汽車旅館１１５號房約會，當小美見到張三後，雖因張三之外貌年紀、穿著打扮、言談舉止不似公司老闆，已生疑竇，然因張三哄騙小美隔天會駕車載她去新竹赴朋友聚會、挑選禮物，小美乃對張三是公司老闆的身份抱持一絲希望，遂同意張三留宿在該旅館房間內，但當小美於翌日上午８時要離開旅館時，發現張三根本未依約開車前來憤而欲離開時，張三竟強拉小美手臂，將小美拉摔到床上，不顧小美之推拒，仍憑藉體型、氣力上之優勢，違反小美之意願強迫小美發生關係。小美因驟遇此事，覺遭玷污，衝進浴室沖洗身體，張三趁隙逃離現場，小美出浴室後，見張三已離去且聯絡無著，不堪受辱報警處理。</div><br>
				<div>&nbsp;&nbsp; 經檢察官調查後雖然張三坦承有與小美發生關係的事實，卻拒絕承認是強迫小美僅說是雙方合意，惟從小美與張三之ＬＩＮＥ對話紀錄截圖與傷勢照片證據顯示的確違反小美意願，因此法庭宣判張三強制性交罪成立，判處拘役4年。</div><br>
			</div>
		</div>
	@endif
@endif
{{--女生法律保護文章詳情 (結束)--}}



{{--男生法律保護文章詳情 (開始)--}}
@if($user->engroup==1)
	@if(Request()->get('article')=='law_protection_sample_0907_1')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">追求窈窕淑女烈女怕纏郎? 騷擾逾越法紀小心會被告!</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三愛慕小美追求未果因而心生怨恨，在花園網發現與小美相似的女會員照片，將其截圖私下散布於工作群組，經起訴判決張三加重毀謗罪，判處拘役1個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節:窈窕淑女、君子好逑。」不過若是一昧罔顧對方意願，甚而因為被拒絕惱羞成怒，做出種種逾越法律分際的行為，那可是會吃上官司的!這是一則根據事實案例判決改編的甜心網故事，奉勸各位想追求心儀的對象時，就是一定要先好好尊重女孩子，這才是君子們代代相傳、千古不敗的妙法寶喔! </div><br>
				<div>&nbsp;&nbsp; 張三與小美在民國110年因為工作關係認識以後，張三即屢傳訊息給小美，這讓小美感覺已經開始讓她困擾，只能先採取冷處理的態度因應。民國110年11月某天早上在小美至台大醫院附近時，張三卻突然靠近小美表示想跟她說說話，小美加快腳步轉進醫院地下街的商店尋求店員的協助，張三見狀先在商店門口外徘徊，過沒多久欲直接進入店內，張三在店員的阻攔下大聲嚷嚷，威脅小美若再不肯跟他說話，他就要在小美上班的公司散布對小美不利的消息，說罷張三才悻悻然地離開店家，而直至小美同事到來後，小美在同事的陪同下安然離開。</div><br>
				<div>&nbsp;&nbsp; 張三在這件事情過了大約兩週後，他在花園網截圖了多張長相疑似小美的會員照片，並附加了很多不雅的揣測詞句在公務群組裡散佈，例如 : 「很可能是某人的秘密」、「她是會為了自己目的，不擇手段到連身體都會賣的人，她有在做包養的賣喔」等等….後續經小美輾轉從同事收到訊息關心她發生了甚麼事後，赫然發現自己莫名其妙的平白受辱，因此憤而報警。</div><br>
				<div>&nbsp;&nbsp;「經由檢察官提起公訴開庭時，張三還試圖為自己的行為辯解，他聘請了律師幫其主張 : 「公司群組是屬於私人群組必須被邀請才能加入，所以他不算是在公眾散布流言因此並沒有構成毀謗。」，經檢察官起訴後法庭判決:張三文字加重毀謗罪成立，判處拘役1個月。</div>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_2')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">對感情執著到變恐怖情人，不只人財兩失還會吃官司</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三於花園網結識小美後，因不滿小美後來拒絕聯絡而多次騷擾小美，小美不堪其擾報警處理，經檢察官起訴後判決張三嚇危害安全罪，判處拘役20日。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：這是一則根據事實案例判決改編的甜心網故事，張三一時的執迷不悟卻換來讓自己成為階下囚，很是可惜，讓每次的相遇都能好聚好散，是每個紳士都該具備的標準禮儀喔!當你風度轉身時，下一個美好佳人已等著要與你認識，所以切勿讓一時的錯誤，釀成對雙方都無法彌補的傷害。</div><br>
				<div>&nbsp;&nbsp; 張三與小美109年6月在花園網相識了一段時間後，基於一些個人原因小美覺得不再適合繼續往來，就逐漸不再與張三有所往來，張三不滿小美拒絕聯絡，心生怨恨後開始陸續於109年起以手機發送多起帶有恐嚇意味的訊息給小美，包括 : 「我所掌握的資料比妳想像的還多，包括你的日常、妳工作、等等…包括我的刷卡、提款資料，更重要的，還有我手錶上的錄音檔」、「今天晚上妳昏了直播，我會上去給驚喜，到時候妳就更睡不著了」…..等等，讓小美不勝其擾。</div><br>
				<div>&nbsp;&nbsp; 由於小美閒暇時也跟現下的年輕人一樣，喜歡玩玩直播，張三在多次發送手機訊息未能得到小美的回應後，更變本加厲到小美的直播間，當小美在愛xx直播平台直播時，以暱稱「明歌」「神秘嘉賓」「大偵探」公開留言 : 「主播去**網站找**，然後*了四十萬，現在官司還在，你們都知道嗎」、 「你們真是大盤子，DONET給一個炸欺最犯，真可憐」、「想了解主播詐欺證據的+籟」等等言語，讓小美更是長期處於被張三的威脅畏懼而惴惴不安，小美因此決定勇敢報案。</div><br>
				<div>&nbsp;&nbsp; 警方受理小美的報案後偵辦案件，經小美遞交的多項證據包括 : 手機簡訊翻拍照片與直播平台直播時之翻拍畫面，提交檢察官提起公訴，而張三也坦承不諱關於上述自己的犯行，經檢察官起訴後法庭判決:張三恐嚇危害安全罪成立，判處拘役20日。</div>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_3')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">編造不實高所得條件誘騙約會，未依約定匯款逃不了法律制裁</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三在花園網編造虛假的收入條件，相約小莉出遊約會後未履行與小莉的約定且將她封鎖，小莉報警後經起訴判決詐欺罪成立，賠償12,500元判處拘役55日。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：多金帥氣又溫柔的對象向來是女生的夢中情人，不過若為了讓自己受異性歡迎，反而刻意造假自己的個人資料，比如偽裝自己從事高所得收入的職務…且更有甚者以偽造的條件對女生騙財騙色，那就一定逃不過法律的制裁。這是一則根據事實案例判決改編的甜心網故事，希望藉此提醒男性會員們誠實才是建立長期關係最好的橋樑，編造的謊言終會被正義擊破!</div><br>
				<div>&nbsp;&nbsp; 張三是一個經濟狀況普通的上班族，就跟大多數的男生一樣，平日喜歡上上各大知名交友網站，作為認識異性的管道。然而張三為了凸顯自己的個人簡介在眾多會員中更為優秀，明知自己僅為一個普通上班族，卻以假名在108年8月於花園網註冊會員，更大肆虛構自己的個人簡介資料，包括任職於會計事務所收入200-300萬元、資產1800萬元等不實訊息，來吸引並誘騙女性會員。</div><br>
				<div>&nbsp;&nbsp; 在註冊會員資料後大約半年後，實際上沒有足夠經濟能力的張三，透過微信跟小莉聊天，期間佯稱自己過去有4段的包養經歷，並謊稱可支付小莉每月5萬元的生活費，來換取與小莉進行每月4次的約會。小莉不疑有他與張三相約在某間MTV約會，結束後張三並無依約支付生活費給小莉，還假借外出包廂打電話名義，將小莉微信給拉黑封鎖，小莉驚覺吃虧上當後對張三憤而提出告訴。</div><br>
				<div>&nbsp;&nbsp; 經法官審理後張三對其犯行坦承不諱，法官依據小莉提供的證據包括 : 「花園網」、微信之對話紀錄截圖、案發現場附近之監視錄影畫面截圖、被告之稅務電子閘門財產所得調件明細表、「花園網」網頁資料與真實姓名對照表，裁定張三犯罪實事證據確鑿，參照對話紀錄中張三對小莉謊稱可支付包養費用每月5萬元，每月約會4次的比例，經檢察官起訴後法庭判決:張三詐欺罪成立，除了需賠償小莉12,500元的不法所得外，並判處拘役55日。</div>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_5')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">神鬼交鋒真人版假名行騙，相交貴在誠信勿以身試法</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美在花園網結識，幾次出遊張三皆請小美墊付購買高價精品，且提供假名給店家供後續手機取貨，佯稱月底還款卻將小美封鎖，經小美報警檢察官起訴判決張三詐欺取財罪以及偽造私文書罪，宣告沒收不法所得，判處拘役11個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：來到花園網與心儀的對象相約出遊，應該帥哥美女們都會滿心期待，而在約會的過程中，若是相處愉快相信男士們多少都會想送點小禮物給出遊的女伴，為這美好的約會畫下完美的句點，既然是禮物當然是要自己出錢才有誠意囉!這是一則根據事實案例判決改編的甜心網故事，提醒男生切不可詐財騙色，不只不法所得必須全數返還，還會因騙色而罪加一等喔！</div><br>
				<div>&nbsp;&nbsp; 張三與小美民國108年8月在花園網結識後，一個月內前後相約出遊多次，而每次張三都會藉故以雙人使用為由購買高價精品，比如於3C精品店購買情人用的iPhone手機與AirPods耳機 ; 百貨公司購買精品對錶、IPad平板電腦、包包、鞋子、衣服等等…總計價值高達近20萬，期間張三明知沒有出錢購買這些高價精品但仍意圖將物品占為己有，故出遊期間所購買的高價物品皆藉詞請小美代為墊付，並佯稱將會連同約定好8次6萬元不等的約會費用，月底一併結算給小美，讓小美提供帳號給他匯款。</div><br>
				<div>&nbsp;&nbsp; 轉眼到了月底小美卻沒有看到有款項入帳，多次發訊息催促張三並苦苦哀求需繳交信用卡費請張三能遵守承諾盡快匯款，張三卻將小美封鎖不再來往，小美驚覺被騙後報警。張三經檢察官起訴後還欲砌詞狡辯，不承認有購買高價商品甚而說此為小美自願贈與，幸而小美在與張三失去聯繫後有立即向花園網求援，對照花園網配合協助調查後的證據，與小美手機留存與張三對話的截圖相符，足以佐證張三所購物品皆非小美自願贈與 ; 而張三在3C商品店留下假名購買手機，亦被張三以商家調貨手機後續要包膜等等藉口先被張三以假名取走。參照小美保存得LINE對話等與張三簽具假名辦手機等多項證據，經檢察官起訴後法庭判決 :張三同時觸犯詐欺取財罪與偽造私文書罪兩罪併罰，須全數歸還所有詐欺得利高達近20萬的物品，並判處拘役11個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_6')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">假身分真詐騙！謊稱坐擁千萬資產? 約會完卻未依約定匯款小心吃官司。</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三加入花園網會員，謊稱自己為年收入百萬坐擁千萬資產的老闆，向小美提出給每月5萬元的約會費用，騙取小美外出約會後未匯款且惡意將她封鎖;另又故技重施邀約小莉，經檢察官起訴後判決張三詐欺罪名成立，判處拘役三個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：人人都嚮往富裕的生活，不過想成為坐擁千萬資產的實業家，還是必須勤勤勉勉的踏實做事，若謊稱千萬身家的不實身份甚而以此騙取多位異性好感與信任，作為獲得約會的籌碼，那可是會吃上官司的喔~這是一則根據事實案例判決改編的甜心網故事，千萬別以為捏造身家藉此獲得約會機會只是撒個無傷大雅的謊言，在此要真心提醒男會員們務必要誠實待人，才能有機會建立美好的約會關係喔!</div><br>
				<div>&nbsp;&nbsp; 張三以「kevin」之暱稱在花園網建立帳號，於民國107年8月期間張貼他自己的個人資料為傳產製造產業老闆、收入為新臺幣300萬以上、坐擁資產千萬等不實訊息，向小美謊稱願意以每月新臺幣5萬元與小美約會2個月，使小美以為真外出與張三相約至北市某旅館約會 ; 然而約會完後張三向小美謊稱會匯款給小美，實際上卻沒有將約會費用匯款給小美，且惡意將小美封鎖。張三用欺騙小美的方式約會成功後，食髓知味又再故技重施，於108年3至5月間花園網上，另外結識了一位女會員小莉，也謊稱會支付零用錢給小莉作為約會的費用，並在約會過後並沒有匯款給約會對象小莉、且惡意封鎖小莉，這讓前後兩位女會員都非常生氣並分別向警方報案。</div><br>
				<div>&nbsp;&nbsp; 經警方調查後張三對自己的犯行坦承不諱，包括兩位女會員皆提供了花園網對話證據與張三的微信通話紀錄證據確鑿，經檢察官起訴後法庭判決:張三詐欺罪名成立，判處拘役三個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_7')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">ATM提款機超方便提領各國外幣都嘜通，騙取換匯手續費且不給約會費真的目湯!</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三在花園網結識小美，佯稱要匯新台幣400萬元給她，但需要換匯讓小美先付手續費1萬元，小美依約先墊付手續費後卻未收到匯款，驚覺被騙憤而報警，檢察官起訴後判決張三詐欺罪名成立，判處拘役五個月。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：在身處地球村的現代，各國貨幣的流通往來頻繁，大家也對於若需要兌換各國之間的外幣，需要支付銀行手續費的情形很習以為常，但反而成為另一個有心詐騙的犯案手法、不可不慎。這是一則根據事實案例判決改編的甜心網故事，男會員們請勿以身試法，現在國內到處都有可提領外幣的提款機，所以這樣的詐騙手法絕對會被直接報警處理。若為了騙取微薄的手續費且不願依約匯款約會費用，一樣視同詐欺罪犯會被提告的喔！</div><br>
				<div>&nbsp;&nbsp; 張三過往曾因詐欺案件被法庭判處有期徒刑3個月，卻仍不知悔改，民國110年1月期間又在花園網出沒結識小美，以通訊軟體微信暱稱「ANDY LEE」向小美佯稱要匯新臺幣400萬元給小美，但錢存在香港銀行，須找私人換匯公司換成新臺幣，請小美先支付手續費新台幣1萬元，這樣就能將港幣兌換成新台幣，匯款給答應小美的約會費用。當天小美因而誤信張三詐騙的謊言，配合張三提供的國內銀行帳戶進行匯款，再被張三以無卡提款的方式取得小美的匯款，小美事後遲遲未收到匯兌後的約會費用才發覺被騙憤而報警。</div><br>
				<div>&nbsp;&nbsp; 經警察偵訊張三坦承犯案，承認案情皆與小美所言吻合，並有小美與張三往來的微信對話紀錄截圖，及張三提供給小美匯款帳戶之交易明細、證據確鑿，經檢察官起訴後法庭判決:張三詐欺罪名成立，判處拘役五個月。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_9')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">約會費用事前談清楚大家都開心，不談錢事後反悔誣告徒勞又無功</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：小莉與張三於花園網結識，小莉事後控告張三對她性侵，但小莉卻一直跟張三說害怕懷孕要求張三每月給生活費，且也對其他網友抱怨張三不願支付約會費用，經檢察官調查後裁定小莉無法舉證，判決張三罪名不成立。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：相信大家來到交友網站，都有自己的想法跟需求，其實旦凡在約會前大家把話說清楚講明白那都沒有問題，最怕就是前面甚麼都不說事後卻覺得吃虧後悔，且甚而捏造不實謊言與證據誣告對方，那可真的就是會讓人感到失望。這是一則根據事實案例判決改編的甜心網故事，提醒男會員們約會前可以了解一下，對於約會的型態與費用有沒有甚麼想法？<span style="color: blue;">以花園網來說女會員都會先標示自己對於約會的想法，是短期或中長期以及約會的費用等等，若是中長期為主的會員第一次發訊給他時，系統都會先有一段文字提示，都可以幫助男會員們事先更了解女會員的需求是否與其吻合喔！</span></div><br>
				<div>&nbsp;&nbsp; 張三與小莉相識於花園網，兩人於111 年2 月相約於法堤商旅約會，然約會過後小莉卻對張三提告，原因是說張三在約會時做出違反她意願的事情。經檢察官查證小莉在張三進入法堤商旅的房間約會時，張三先進浴室沖澡，當時小莉人身自由皆未受張三限制、張三亦無法看到浴室外之動靜，檢察官推論小莉應有充足時間將褲子、內褲穿妥並離開房間，或持手機撥打電話向親友、旅館人員求助，但反而都沒有做任何求救的行為 ; 再加上對照兩人事後的對話截圖皆是小莉跟張三索要約會費用，包括 : 「你會付錢給我嗎你都做了我的第一次」「我以為我們彼此有感覺…有點失望…妳會想要多少呢？」「上包養網就是因為經濟有需求」、「長期的話一個月能提供多少生活費」「你希望多少呢？」「我的第一次都給你了　希望可以有六萬」之訊息…</div><br>
				<div>&nbsp;&nbsp; 張三與小莉既是在包養網站認識，該交友網站性質約會本來就要有對價，既然有對價就不會是違反意願，另外小莉附上的中國醫藥大學附設醫院診斷證明書、員榮醫院診斷證明書都是案發前的證明，沒有辦法作為本案補強證據使用，且小莉在本案有明顯說謊跟推翻前詞的情況，推論應是張三沒有給她約會費用而誣告，因此法庭判決張三無罪駁回。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_10')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">假裝千萬身家缺現金跟女會員借款，若行為不當被檢舉站方會保存紀錄</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小莉在花園網結識後，張三跟小莉佯稱身上無現金擁股票價值3000萬且有意跟小莉維持長期約會，遂跟小莉借款10萬元並承諾以後會返還，小莉提款後張三後卻避不見面，經檢察官調查覺得倆人在交往期間為了討好異性難以判定張三詐欺，駁回小莉提告。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：會想到交友網站的男女會員們，自然都期望遇到適合的對象，但若是用假造的優質條件哄騙對方借款，站方不贊成這樣的行為，也會開放讓會員檢舉喔！這是一則根據事實案例判決改編的甜心網故事，一個男會員聲稱資金都在股市或房地產身上缺現金，欲跟女會員借現金來短期週轉，即使法律駁回詐欺提告，若有會員檢舉屬實站方也會保留紀錄喔！</div><br>
				<div>&nbsp;&nbsp; 張三與小莉相識於花園網，倆人於10年9月相約於臺南荷峰汽車旅館約會，張三在花園網內自稱是醫生，說身上新臺幣3千萬的資產在股票裡，身上沒有任何現金，遂跟小莉借款10萬元並承諾以後一定會返還，小莉不疑有他就去ATM領錢，後來在車上把錢交給張三，張三謊稱工作現職業為醫師且財產均以股票形式持有共計3,000餘萬，膨脹他自身的財產及信用，並與小莉一同飲酒，降低小莉對他的防備心，才導致小莉被張三所騙。小莉心有不甘因此對張三提告詐欺。</div><br>
				<div>&nbsp;&nbsp; 然經檢察官調查後表示，男女中之一方縱使係著眼於對方之經濟能力而願意與對方交往，而他方依其智識程度、社會閱歷，對於是否因此交付財物尚有充分判斷之餘地，倘若出於討好對方、獲取對方好感，而願意貸予金錢甚至贈與財物，即難認係遭到小莉是被詐欺陷於錯誤所為。小莉已年滿24歲、教育程度為專科畢業，從事酒店工作，依其智識程度及社會閱歷，對於在交友網站認識、僅見面2次、不知真實姓名、聯絡電話的張三，還願意提領10萬元借給他，顯然小莉是出於對張三的好感想與其維持交往情誼才同意借款，難以判定張三詐欺，因此法庭判定駁回小莉提告。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_11')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">被詐騙約會費用後女生神隱不見，切勿私下報復男生也會得不償失</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：小莉在花園網認識張三，說好3萬元為約會費用後，小莉收到錢藉故離開且封鎖張三; 張三發現小莉開了新帳號，假意與小莉邀約，見面搶走小莉皮包所有財物後離開，經檢察官起訴後判決小莉詐欺罪成立，判處拘役55天並沒收犯案手機，張三判決強制罪成立，判處拘役50天。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：在交友網站的天地裡悠遊，好處就是可以先藉由網站的自我介紹與網站通訊認識彼此後，確認雙方的需求再安排進一步的約會，但若是遇到女會員抱持一顆不誠實的心哄騙男會員，只要蒐集證據冷靜處理，對方是會被以詐欺犯論處，得到應有的懲罰，千萬別私下報復。這是一則根據事實案例判決改編的甜心網故事，提醒男會員們若遇到不誠實的女會員，千萬別一時衝動私下報復，這樣反而自己也會犯法喔！冷靜下來直接報警處理即可。<span style="color: blue;">以花園網為例，只要評價是事實並且沒有謾罵，站方不會刪除評價。也不會接受關閉舊帳號新開新帳號，所以男會員除了報警也可以把遇到的事實留下評價， 讓女會員受到警惕。</span></div><br>
				<div>&nbsp;&nbsp; 張三於民國109年6月在花園網結識小莉，小莉跟張三談好每月可以給她3萬元作為約會費用，張三同意後遂於7月初在臺中市全家便利商店內，張三先取款3萬元給小莉，其後雙方步行至臺中市的老乾杯燒烤店用餐。嗣小莉藉上廁所為由離去，經張三多次聯絡，小莉皆未理會，甚而封鎖張三，張三始知受騙。張三後來於109年7月中旬，發現小莉疑似以其他帳號在交友網站貼文徵求約會，遂隱瞞身分與小莉聯繫，相約同日下    午在臺中市路易莎咖啡市政店見面。兩人碰面後，張三不滿小莉先前詐取他的約會費用，要求小莉提出手機供其檢視，小莉不從，張三便徒手拉扯並強行取走小莉所有皮包（內有口紅1支、手機1支、鑰匙1串及4千元），張三得手後隨即離去，小莉憤而報警。</div><br>
				<div>&nbsp;&nbsp; 張三與小莉到案後皆對自己犯行坦承不諱，警方並從現場監視器與張三存簿核對張三強取小莉皮包與小莉詐欺張三無誤，經檢察官起訴後判決小莉詐欺罪成立，判處拘役55天並沒收犯案手機，張三判決強制罪成立，判處拘役50天。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_12')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">騙約會後拒匯款封鎖對方就沒事? 女生有對話證據報警包你吃官司!</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美相識於Isugar，109年12月張三約小美至「艾曼汽車旅館」約會，然約會完後未依約定匯款1萬元及旅館費用880元給小美，經小美詢問後謊稱已轉帳並將小美封鎖，經檢察官起訴法庭宣判詐欺得利罪成立，判處拘役50天沒收不法所得1萬880元。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：在交友網站與app百花齊放的現代，對於想找各式各樣的約會對象都非常方便，所謂青菜蘿蔔各有所好，只要是自己喜歡的就好，可能會有一些男生會想，故意答應女生給普通金額的約會費用，再以”這一點點錢又不是大錢不怕賴帳…”的話術降低對方戒心，到時約會完再封鎖女生，對方也拿自己沒轍的僥倖心態以身試法…NONONO千萬別有這樣的想法喔!法網恢恢疏而不漏，還是要秉持真心誠意交往為好。這是一則根據事實案例判決改編的包養網故事，警戒男生答應對方談好給的約會費用，一定要如數給付，若約會完後卻拒絕給付甚而封鎖對方，那可就是現行的詐欺犯，將會受到法律的制裁喔！</div><br>
				<div>&nbsp;&nbsp; 張三與小美相識於Isugar，張三以「韋小寶」之暱稱，在包養網及通訊APP「微信」中，與小美談妥給小美1萬元作為約會費用，且佯稱「只不過要等結束完我回家再下樓轉（帳）給妳噢」、「如果是一兩百萬我或許會（跑）」云云….小美不疑有他兩人相約於民國109年12月在淡水的艾曼汽車旅館約會，結束後提供國泰世華銀行之帳號給張三匯款，然張三卻未依約支付約會費用1萬元及旅館費用880元，經小美質問後，張三謊稱已轉帳完成並封鎖小美帳號，小美始知被騙並報警處理。</div><br>
				<div>&nbsp;&nbsp; 經檢察官調查後查證張三與小美在包養網及「微信」對話紀錄核對小美的證詞吻合，經法庭判決張三詐欺得利罪名成立，判處拘役三個月，並沒收張三不法所得10880元。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_13')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">假意用手機網銀已轉帳約會費用 ? 對方事實上沒收到款項就是詐欺！</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美相識於Isugar，110年2月相約至臺中歐風商旅約會，約會前張三佯裝未帶手機已完成手機轉帳2萬元約會費用給小美，騙取小美代墊旅館費用680元並完成約會。後經小美查證並未收到匯款並連繫張三未果始知受騙報警。經檢察官起訴法庭宣判詐欺得利罪成立，判處拘役6個月，並沒收20680不法所得。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：人手一支的手機在科技進步之下已經不只是手機，儼然甚而成為人們的手掌錢包，因為透過網路銀行即可完成轉帳與付款，但若是想利用科技之便進行犯罪，比如答應該給付的約會費用假裝用手機網銀完成匯款實則卻沒有給錢，也是會構成犯罪的行為的喔！這是一則根據事實案例判決改編的包養網故事，警惕男生別混水摸魚答應該給的約會一定要如數給付，即使假裝用手機轉帳對方卻事實上沒收到款項，那還是會被以詐欺論罪喔！</div><br>
				<div>&nbsp;&nbsp; 張三與小美相識於包養網，小美一開始是在網站上刊登可接受付費約會的訊息，先後有暱稱JAY及KAI之人於110年1月與小美聯繫，張三即為其中之一，後續兩人持續透過微信對話，兩人談好張三會給小美約會費用2萬元後，就相約在臺中歐風商旅約會。張三開車去接小美，小美並沒特別記下張三的車牌號碼，只知道張三汽車的顏色及廠牌，張三先在上車時就用手機的網銀操作，然後在小美還沒看清楚時，張三就按掉畫面並說他已經匯款了，小美因此被蒙騙認為張三的確已完成匯款，兩人就去「歐風汽車旅館」約會且進去「歐風汽車旅館」前，張三還謊稱說沒帶錢請小美幫他付房間費$680，說房間費之後會匯給小美，房間費不包含在2萬元內；兩人約會完張三謊稱說他有事就匆匆離開，當小美回家要去領錢時，才發現錢沒匯進來，小美趕緊以微信問張三，發現張三已經將她微信帳號封鎖，始知被騙憤而報警。</div><br>
				<div>&nbsp;&nbsp; 一開始張三還矢口否認說並沒有談好需要他給付約會費用，兩人是自願外出約會，經檢察官查證小美提供包養網與微信的對話紀錄截圖歐風商旅住宿登記資料各1份及監視器畫面截圖都能呼應的確張三有答應小美給付1次2萬元的約會費用，法庭判決張三詐欺罪名成立，判處拘役六個月，並沒收不法所得20680元。</div><br>
			</div>
		</div>
	@elseif(Request()->get('article')=='law_protection_sample_0907_14')
		<div class="toug_xq" style="position: relative;">
			<div class="tougao_xnew">
				<div class="tou_img_1">
					<div class="tou_tx_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="hycov"></div>
					<span>{{ $admin_info->name }}<i class="tou_fi">2023-09-14 14:10</i></span>
				</div>
			</div>
			<div class="xq_text">以為假扮公司老闆卻變身約會惡狼，違反女生意願將重判刑期絕不寬待</div>
			<div class="xq_text01">
				<span style="font-size: 16px;font-weight: bold;">懶人大綱：張三與小美相識於「包養網站」，張三假扮成功人士能提供小美每月8萬元的約會，騙取小美於109年8月至嘉義某旅館與他約會，並答應隔日載小美去新竹與朋友碰面，然而當小美發現張三未開車前來憤而離開時，張三卻憑藉其體格優勢強迫小美發生關係。經小美報警檢察官起訴法庭宣判強制性交罪成立，判處拘役4年。</span><br><br>
				<div>&nbsp;&nbsp; 完整細節：當大家藉由約會網站認識異性相談甚歡後，對於下一步約會見面一定是充滿期待，不過男生千萬不可以仗勢自己有體格優勢，在約會時做出違反對方意願的惡事，可是會付出慘痛代價喔！這是一則根據事實案例判決改編的包養網故事，男生不只謊稱自己是公司老闆身份，更在被對方識破企圖離開時欺負女生，結果當然是被重判刑期、嚴懲不殆。</div><br>
				<div>&nbsp;&nbsp; 張三與小美相識於包養網，張三對小美佯稱是公司老闆，每月可提供小美８萬元約會費用，並以ＬＩＮＥ通訊軟體邀約小美見面，兩人於１０９年８月相約於嘉義某間汽車旅館１１５號房約會，當小美見到張三後，雖因張三之外貌年紀、穿著打扮、言談舉止不似公司老闆，已生疑竇，然因張三哄騙小美隔天會駕車載她去新竹赴朋友聚會、挑選禮物，小美乃對張三是公司老闆的身份抱持一絲希望，遂同意張三留宿在該旅館房間內，但當小美於翌日上午８時要離開旅館時，發現張三根本未依約開車前來憤而欲離開時，張三竟強拉小美手臂，將小美拉摔到床上，不顧小美之推拒，仍憑藉體型、氣力上之優勢，違反小美之意願強迫小美發生關係。小美因驟遇此事，覺遭玷污，衝進浴室沖洗身體，張三趁隙逃離現場，小美出浴室後，見張三已離去且聯絡無著，不堪受辱報警處理。</div><br>
				<div>&nbsp;&nbsp; 經檢察官調查後雖然張三坦承有與小美發生關係的事實，卻拒絕承認是強迫小美僅說是雙方合意，惟從小美與張三之ＬＩＮＥ對話紀錄截圖與傷勢照片證據顯示的確違反小美意願，因此法庭宣判張三強制性交罪成立，判處拘役4年。</div><br>
			</div>
		</div>
	@endif
@endif
{{--男生法律保護文章詳情 (結束)--}}
