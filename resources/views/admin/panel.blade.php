<div id="mySidenav" class="sidenav">
	@if (Auth::user()->can('admin'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="{{ route('admin_item_folder_manage') }}"><font color="white">資料夾管理</font></a>
		@foreach(\App\Models\AdminMenuItemFolder::where('user_id', Auth::user()->id)->get() as $folder)
			<a class="folder"><img src="/new/images/afp_1.png" width="30" height="30"><font color="white">{{$folder->folder_name}}</font></a>
			<div class="folder_item" style="display:none">
				@foreach(\App\Models\AdminMenuItemXref::leftJoin('admin_menu_items', 'admin_menu_item_xref.item_id', '=', 'admin_menu_items.id')->where('folder_id', $folder->id)->where('admin_menu_items.status', 1)->get() as $item)
					<a href="{{ $item->route_path }}">{{ $item->title }}</a>
				@endforeach
			</div>
		@endforeach
		<a class="folder"><img src="/new/images/afp_1.png" width="30" height="30"><font color="white">所有選項</font></a>
		<div class="folder_item" style="display:none">
			@foreach(\App\Models\AdminMenuItems::where('status', 1)->orderBy('sort')->get() as $key =>$item)
				<a href="{{ $item->route_path }}" @if($item->title=='招手比調整') target="_blank" @endif>{{ $item->title }}</a>
			@endforeach
		</div>

	@elseif (Auth::user()->can('readonly'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="{{ route('users/VIP/ECCancellations/readOnly') }}">綠界 / FunPoint VIP 付費取消資料</a>
		<a href="{{ route('stats/vip/paid/readOnly') }}">付費 VIP 會員訂單資料</a>
		<a href="{{ route('users/pictures/readOnly/GET') }}">會員照片管理</a>

	@elseif (Auth::user()->can('juniorAdmin'))
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		@php
			$adminUser=\Illuminate\Support\Facades\Auth::user();
			$getPermission=\App\Models\RoleUser::where('user_id',$adminUser->id)->first()->item_permission;
			$getMenuList=explode(',',$getPermission);
		@endphp
		@if(!is_null($getPermission))
			@foreach(\App\Models\AdminMenuItems::whereIn('id',$getMenuList)->where('status', 1)->get() as $key =>$item)
				<a href="{{ $item->route_path }}" @if($item->title=='招手比調整') target="_blank" @endif>{{ $item->title }}</a>
			@endforeach
		@endif

	@endif
</div>
<script>
	$('.folder').click(function() {
		$(this).next('.folder_item').toggle();
	});
</script>
