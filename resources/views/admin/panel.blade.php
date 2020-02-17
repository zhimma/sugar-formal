<div id="mySidenav" class="sidenav">
	@if (Auth::user()->can('admin'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="{{ route('stats/date_file_log') }}">異動檔上傳/檢查記錄</a>
		<a href="{{ route('stats/cron_log') }}">VIP排程檢查記錄</a>
		<a href="{{ route('stats/vip') }}">VIP會員統計資料</a>
		<a href="{{ route('users/VIP/ECCancellations') }}">綠界 VIP 付費取消資料</a>
		<a href="{{ route('users/customize_migration_files') }}">異動檔手動修改</a>
		<a href="{{ route('users/manager') }}">會員搜尋(變更男女、VIP資料)</a>
		<a href="{{ route('users/advSearch') }}">進階會員搜尋</a>
		<a href="{{ route('users/bannedList') }}">會員封鎖清單</a>
		<a href="{{ route('users/pictures') }}">會員照片管理</a>
		<a href="{{ route('users/reported/count') }}">會員被檢舉次數</a>
		<a href="{{ route('users/board') }}">留言板管理</a>
		<a href="{{ route('users/message/search') }}">會員訊息管理</a>
		<a href="{{ route('users/reported') }}">被檢舉會員清單</a>
		<a href="{{ route('users/pics/reported') }}">被檢舉照片清單</a>
		<a href="{{ route('users/basic_setting') }}">基本設定</a>
		<a href="{{ route('users/changePassword') }}">修改會員密碼</a>
		<a href="{{ route('users/switch') }}">切換會員身份</a>
		<a href="{{ route('inactive') }}">未啟動會員</a>
		<a href="{{ route('admin/announcement') }}">站長公告</a>
		<a href="{{ route('admin/web/announcement') }}">網站公告本月封鎖名單</a>
		<a href="{{ route('admin/chat') }}">站長信箱</a>
		<a href="{{ route('admin/commonetext') }}">編輯文案</a>
	@elseif (Auth::user()->can('readonly'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="{{ route('users/VIP/ECCancellations/readOnly') }}">綠界 VIP 付費取消資料</a>
	@endif
</div>