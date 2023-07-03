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

<hr style="border-top-width:medium;"/>
<h1>清除手機驗證紀錄</h1>
<form method="POST" action="{{ route('users/short_message/search') }}" class="short_message_search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="phone" class="">輸入會員手機號碼</label>	
		<input type="text" name='phone_search' class="" style="width:300px;" id="phone" value="{{ isset($short_messages) ? $short_messages->first()?->mobile : '' }}" required>
        <input type="hidden" name="del_all_short_message"  id="del_all_short_message" value="0" />
    </div>
	<button type="button" class="btn btn-primary" onclick="$('#phone_search_rs').remove();$('#phone_search_operator').remove();$('.short_message_search_form').submit()">送出</button>
    <button type="button" class="btn btn-success" onclick="$('#phone').val('');$('#phone_search_rs').remove();$('#phone_search_operator').remove();" >Reset</button>
</form><br><br>

@if(($short_messages??null))
    <table id="phone_search_rs">
	@forelse ($short_messages->unique('member_id') as $key=>$s_message)
	
    <tr>
        <td>{{!$key?'此手機號碼在':''}}
		<td><a href="{{route('users/advInfo',['id'=> $s_message->user?->id])}}" target="_blank">{{ $s_message->user?->email }}</a></td>
		<td class="center">使用過</td>
		<td class="center"> 
            @if(var_carrier('backend_modify_mobile_num',$s_message->user?->short_message()->withTrashed()->select('mobile')->whereNotNull('mobile')->where('mobile','!=',$s_message->mobile)->whereIn('deleted_from',($forbidden_deleted_from_arr??[]))->distinct()->count())) ( 該會員被從後台修改過 {{var_carrier('backend_modify_mobile_num')}} 次"不同"的手機號碼)
            @elseif(var_carrier('sms_num',$s_message->user?->short_message()->withTrashed()->select('mobile')->whereNotNull('mobile')->distinct()->count('mobile'))>1) ( 該會員總共用過 {{var_carrier('sms_num')}} 個不同的手機號碼進行驗證) @endif
        </td>
	</tr>  
	@empty
	<tr>
        <td colspan="4">
        找不到資料
        </td>
	</tr>
	@endforelse
</table>            
    @if($short_messages && $short_messages->count())
    <div id="phone_search_operator">
        <br/><br/>
        <div style="color:red;">
        @if($short_messages->unique('member_id')->count()>1 || var_carrier('backend_modify_mobile_num'))
            提醒：此{{$s_message->mobile}}手機號碼
            @if($short_messages->unique('member_id')->count()>1)   
            有被2個以上的會員用於驗證
            @elseif(var_carrier('backend_modify_mobile_num'))
            的使用者，曾經從後台修改成"不同"的手機號碼{{var_carrier('backend_modify_mobile_num')}}次
            @endif
            ，為避免會員濫用驗證，因此非常不適合刪除{{$s_message->mobile}}此手機號碼的驗證紀錄 
        @elseif(var_carrier('sms_num')>1)
            此{{$s_message->mobile}}手機號碼的使用者，總共用過 {{var_carrier('sms_num')}} 個不同的手機號碼進行驗證，若刪除此手機號碼，之後將無法得到完整的使用者驗證紀錄。
        
        @endif
        </div> 
          
        
        <br/><br/>
        <button type="button" class="btn btn-danger" onclick="if(confirm('即將刪除{{$short_messages->first()->mobile}}的所有手機驗證紀錄，\n\n\n您確定真的要全部刪除{{$short_messages->first()->mobile}}的驗證紀錄嗎 (無法復原) ?')){$('#del_all_short_message').val('{{$short_messages->first()->mobile}}');$('.short_message_search_form').submit();}">刪除驗證紀錄</button>
    </div>
    @endif

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