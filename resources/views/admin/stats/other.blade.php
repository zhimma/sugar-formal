@extends('admin.main')
@section('app-content')
<style>
	li{
		list-style: decimal;
	}
</style>
<body style="padding: 15px;">
<h1>其他 VIP 相關統計資料</h1>
<ol>
	<li>
		男 VIP 人數: {{--{{ $maleVip->count() }}--}}
	</li>
	<li>
		30 天內有上線的女 VIP 人數: {{--{{ $femaleVipLastLoginIn30DaysCount }}--}}
	</li>
	<li>
		30 天內男 VIP 發訊總數/獲得回應比例: {{--{{ $maleVipMessages[0]->count }} / {{ $maleVipMessagesReplied[0]->count }}--}}
	</li>
	<li>
		30 天內普通會員發訊總數/獲得回應比例: {{--{{ $maleNonVipMessages[0]->count - $maleVipMessages[0]->count }} / {{ $maleNonVipMessagesReplied[0]->count - $maleVipMessagesReplied[0]->count }}--}}
	</li>
	<li>
		車馬費邀請總數/有回應的比例: {{--{{ $tipsAllCount }} / {{ $tipsReplied }}--}}
	</li>
	<li>
		一個月內上站男會員總數:
	</li>
	<li>
		優選會員人數:
	</li>
	<li>
		30 天內優選會員發訊總數/獲得回應比例:
	</li>
	<li>
		三天內男 VIP 發訊總數/獲得回應比例:
	</li>
	<li>
		三天內普通(男)會員發訊總數/獲得回應比例:
	</li>
	<li>
		三天內優選會員發訊總數/獲得回應比例:
	</li>
</ol>
</body>
</html>
@stop