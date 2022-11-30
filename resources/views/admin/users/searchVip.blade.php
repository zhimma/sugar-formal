@extends('admin.main')
@section('app-content')
<style>
    .center{
        text-align: center;
    }
</style>
<body style="padding: 15px;">
<h1>VIP調整</h1>
<form method="POST" action="{{ route('users/vip/search') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="email" class="">輸入會員 Email</label>	
		<input type="text" name='search' class="" style="width:300px;" id="email" required>
	</div>
	<button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
</form><br>
@if(isset($users))
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員 Email</th>
		<th class="center">新增天數</th>
		<th class="center">移轉給其他人</th>
		<th class="center">進階驗證次數調整</th>
	</tr>
	@forelse ($users as $user)
		@php
			$result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $user->id)->get()->first();
            if(!isset($result['isBlocked'])){
                $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $user->id)->get()->first();
            }
            $userInfo=\App\Models\User::findById($user->id);
            $user['name'] = $userInfo->name;
            $user['engroup'] = $userInfo->engroup;
            $user['last_login'] = $userInfo->last_login;
            $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
            $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
            $user['exchange_period'] = $userInfo->exchange_period;
            $user['warnedicon'] = \App\Models\User::warned_icondata($user->id);

		@endphp
	<tr>
		<td>{{ $user->email }}</td>
		<td class="center"><input class="center" type="text" name='extend'></td>
		<td class="center"><input type="text" name='transfer'></td>
		<td class="center">{{ $user->advance_auth_count }}</td>
	</tr>
	<tr>
			<td></td>
			<td class="center">
				<button type="button" class="btn btn-info" onclick="periodExtend({{$user->id}})">submit</button>
			</td>
			<td class="center">
				<button type="button" class="btn btn-info" onclick="periodTransfer({{$user->id}})">submit</button>
			</td>
			<td class="center">
				<button type="button" class="btn btn-info" onclick="updateVipAdvandceAuthCount({{$user->id}})">調整次數</button>
			</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
@endif
</body>
<script>
	function periodExtend(id) {
		let extend = $("input[name=extend]").val();
		if ( extend != parseInt(extend) ) {
			$("input[name=extend]").val('');
			alert("請輸入正整數");
		} else {
			$.ajax({
				type: 'POST',
				url: "/admin/users/vip/period/extend",
				data:{
					_token: '{{csrf_token()}}',
					user_id: id,
					extend: $("input[name=extend]").val(),
				},
				dataType:"json",
				success: function(res){
					alert(res.msg)
					location.reload();
			}});
		}
    }
	function periodTransfer(id) {
        $.ajax({
            type: 'POST',
            url: "/admin/users/vip/period/transfer",
            data:{
                _token: '{{csrf_token()}}',
                user_id: id,
                transfer_to: $("input[name=transfer]").val(),
            },
            dataType:"json",
            success: function(res){
                alert(res.msg)
                location.reload();
        }});
    }
	function updateVipAdvandceAuthCount(id) {
		$.ajax({
			type: 'POST',
			url: "/admin/users/vip/adv_auth_count/save",
			data:{
				_token: '{{csrf_token()}}',
				user_id: id,
				adv_auth_count: $("input[name=adv_auth_count]").val(),
			},
			dataType:"json",
			success: function(res){
				alert(res.msg);
				location.reload();
		}});
    }
</script>
</html>
@stop