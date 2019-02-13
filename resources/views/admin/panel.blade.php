<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<a href="{{ route('stats/date_file_log') }}">異動檔上傳/檢查記錄</a>
	<a href="{{ route('stats/cron_log') }}">VIP排程檢查記錄</a>
    <a href="{{ route('stats/vip') }}">VIP會員統計資料</a>
	<a href="{{ route('users/customize_migration_files') }}">異動檔手動修改</a>
	<a href="{{ route('users/manager') }}">會員搜尋(變更男女、VIP資料)</a>
	<a href="{{ route('users/advSearch') }}">進階會員搜尋</a>
    <a href="{{ route('users/bannedList') }}">會員封鎖清單</a>
	<a href="{{ route('users/pictures') }}">會員照片管理</a>
	<a href="{{ route('users/message/search') }}">會員訊息管理</a>
	<a href="{{ route('users/reported') }}">被檢舉會員清單</a>
	<a href="{{ route('users/pics/reported') }}">被檢舉照片清單</a>
	<a href="{{ route('users/switch') }}">切換會員身份</a>
    <a href="{{ route('inactive') }}">未啟動會員</a>
	<a href="{{ route('admin/announcement') }}">站長公告</a>
	<a href="{{ route('admin/chat') }}">信箱</a>
</div>