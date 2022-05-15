<div id="mySidenav" class="sidenav">
	@if (Auth::user()->can('admin'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="{{ route('users/video_chat_verify') }}">視訊驗證</a>
		<a href="{{ route('order') }}">訂單查詢</a>
		<a href="{{ route('accessPermission') }}">初階站長權限管理</a>
{{--		<a href="{{ route('stats/date_file_log') }}">異動檔上傳/檢查記錄</a>--}}
		<a href="{{ route('stats/set_autoBan') }}">自動封鎖警示設定</a>
{{--		<a href="{{ route('stats/cron_log') }}">VIP 排程檢查記錄</a>--}}
		{{-- <a href="{{ route('stats/vip') }}">VIP 會員統計資料</a> --}}
		{{-- <a href="{{ route('stats/vip/paid') }}">付費 VIP 會員訂單資料</a> --}}
		<a href="{{ route('stats/vip/other/GET') }}">其他 VIP 相關統計資料</a>
		<a href="{{ route('users/VIP/ECCancellations') }}">綠界 VIP 付費取消資料</a>
{{--		<a href="{{ route('users/VVIP') }}">VVIP申請管理</a>--}}
{{--		<a href="{{ route('users/customize_migration_files') }}">異動檔手動修改</a>--}}
		<a href="{{ route('users/picMemberList') }}?reset=1">照片會員列表</a>
		<a href="{{ route('users/memberList') }}">會員列表</a>
		<a href="{{ route('users/manager/GET') }}">會員搜尋(變更男女、VIP資料)</a>
		<a href="{{ route('users/advSearch') }}">進階會員搜尋</a>
		<a href="{{ route('users/bannedList') }}">會員封鎖清單</a>
		{{--<a href="{{ route('implicitlyBanned') }}">指紋比對清單</a>--}}
		<a href="{{ route('warningUsers') }}">警示名單</a>
		{{--<a href="{{ route('users/multipleLogin/GET') }}">多重登入名單</a>--}}
		{{--<a href="{{ route('users/multipleLogin/GET') }}?old_version=1">多重登入名單(舊版)</a>--}}
		<a href="/admin/users/showDuplicate?only=cfpid">多重登入_CFP</a>
		<a href="/admin/users/showDuplicate">多重登入_完整版</a>
		<a href="{{ route('tooMantRequests') }}">異常連線記錄</a>
		<a href="{{ route('tooMantRequests') }}?pseudo=1">異常連線記錄(純記錄)</a>
		{{--<a href="{{ route('suspectedMultiLogin') }}">疑似多重登入名單</a>--}}
		<a href="{{ route('users/suspiciousUser') }}">可疑名單列表</a>
		<a href="{{ route('users/pictures') }}">會員照片管理</a>
		<a href="{{ route('users/picturesSimple') }}">會員檢查 step 1</a>
		<a href="{{ route('users/picturesSimilar') }}">會員檢查 step 2</a>
		<a href="{{ route('users/picturesSimilarLog') }}">會員照片管理結果列表圖</a>
		<a href="{{ route('users/reported/count/GET') }}">會員被檢舉次數</a>
		<a href="{{ route('users/posts') }}">討論區管理</a>
		{{--		<a href="{{ route('users/board') }}">留言板管理</a>--}}
		<a href="{{ route('users/messageBoardList') }}">留言板管理</a>
		<a href="{{ route('users/message/search') }}">會員訊息管理</a>
		<a href="{{ route('statistics') }}">會員訊息統計</a>
		<a href="{{ route('searchSpamTextMessage') }}">罐頭訊息查詢</a>
		<a href="{{ route('users/showAnonymousChatPage') }}">匿名聊天室</a>
		<a href="{{ route('users/reported/GET') }}">被檢舉會員清單</a>
		<a href="{{ route('users/pics/reported/GET') }}">被檢舉照片清單</a>
		<a href="{{ route('users/basic_setting') }}">基本設定</a>
		<a href="{{ route('users/changePassword/GET') }}">修改會員密碼</a>
		<a href="{{ route('users/switch') }}">切換會員身份</a>
		<a href="{{ route('users/closeAccountReasonList') }}">關閉會員帳號原因統計</a>
		{{-- <a href="{{ route('inactive/GET') }}">未啟動會員</a> --}}
		{{-- <a href="{{ route('admin/showSendUserMessage') }}">指定會員發訊息</a> --}}
		<a href="{{ route('admin/announcement') }}">站長公告</a>
		<a href="{{ route('admin/masterwords') }}">站長的話</a>
		<a href="{{ route('admin/web/announcement') }}">網站公告本月封鎖名單</a>
		<a href="{{ route('admin/chat') }}">站長信箱</a>
		<a href="{{ route('admin/commontext') }}">編輯文案</a>
		<a href="{{ route('admin/check') }}">站長審核</a>
		<a href="{{ route('admin/getAdminActionLog') }}">Admin後台操作記錄</a>
		<a href="{{ route('users/filterByInfo') }}">發信_檢舉_封鎖異常查詢</a>
		<a href="{{ route('maillog') }}">寄退信Log紀錄查詢</a>
		<a href="{{ route('fakeMail') }}">寄送統計沖洗郵件</a>
		<a href="{{ route('users/informationStatistics') }}">進階資訊統計工具</a>
        <a href="{{ route('admin/faq') }}">FAQ機制</a>
		<a href="{{ route('admin/advertiseStatistics') }}">廣告紀錄統計</a>
		<a href="{{ route('admin/user_record_view') }}">停留時間</a>

	@elseif (Auth::user()->can('readonly'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="{{ route('users/VIP/ECCancellations/readOnly') }}">綠界 VIP 付費取消資料</a>
		<a href="{{ route('stats/vip/paid/readOnly') }}">付費 VIP 會員訂單資料</a>
		<a href="{{ route('users/pictures/readOnly/GET') }}">會員照片管理</a>

	@elseif (Auth::user()->can('juniorAdmin'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		@php
			$adminUser=\Illuminate\Support\Facades\Auth::user();
			$getPermission=\Illuminate\Support\Facades\DB::table('role_user')->where('user_id',$adminUser->id)->first()->item_permission;
			$getMenuList=explode(',',$getPermission);
		@endphp
		@if(!is_null($getPermission))
			@foreach(\Illuminate\Support\Facades\DB::table('admin_menu_items')->whereIn('id',$getMenuList)->get() as $key =>$item)
				<a href="{{ $item->route_path }}">{{ $item->title }}</a>
			@endforeach
		@endif
		{{--<a href="{{ route('users/manager') }}">會員搜尋(變更男女、VIP資料)</a>
		<a href="{{ route('users/advSearch') }}">進階會員搜尋</a>
		<a href="{{ route('users/message/search') }}">會員訊息管理</a>
		<a href="{{ route('users/reported') }}">被檢舉會員清單</a>
		<a href="{{ route('users/pics/reported') }}">被檢舉照片清單</a>
		<a href="{{ route('admin/check') }}">站長審核</a>
		<a href="{{ route('users/picturesSimple') }}">會員檢查 step 1</a>--}}
	@endif
</div>
