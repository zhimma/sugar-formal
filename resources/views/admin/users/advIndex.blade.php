@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>進階會員搜尋</h1>
<form method="POST" action="{{ route('users/advSearch') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="email" class="">Email</label>
		<input type="email" name='email' class="" style="width:300px;" id="email" value="@if(isset($email )){{ $email }}@endif">
		<label for="name" class="">暱稱</label>
		<input type="text" name='name' class="" style="width:300px;" id="name" value="@if(isset($name)){{ $name }}@endif">
	</div>
	<button type="button" class="btn btn-success" onclick="$('.search_form').submit()">送出</button>
</form><br>
@if(isset($users))
<table class='table table-hover table-bordered'>
	<tr>
		<td>會員ID</td>
		<td>暱稱</td>
		<td>標題</td>
		<td>男/女</td>
		<td>Email</td>
		<td>VIP</td>
		<td>建立時間</td>
		<td>更新時間</td>
		<td>上次登入</td>
		<td>封鎖使用者</td>
        <td>站長訊息</td>
		<td>所有資料/管理</td>
	</tr>
	@forelse ($users as $user)
	<tr @if($user->isBlocked) style="color: #FF0000;" @endif>
		<td class="align-middle">{{ $user->id }}</td>
		<td class="align-middle">{{ $user->name }}</td>
		<td class="align-middle">{{ $user->title }}</td>
		<td class="align-middle">@if($user->engroup==1) 男 @else 女 @endif</td>
		<td class="align-middle">{{ $user->email }}</td>
		<td class="align-middle">{{ $user->vip }}</td>
		<td class="align-middle">{{ $user->created_at }}</td>
		<td class="align-middle">{{ $user->updated_at }}</td>
		<td class="align-middle">{{ $user->last_login }}</td>
        <td class="align-middle">
            <form action="toggleUserBlock" method="POST">{!! csrf_field() !!}
                <input type="hidden" value="@if(isset($email )){{ $email }}@endif" name="email">
                <input type="hidden" value="@if(isset($name)){{ $name }}@endif" name="name">
                <input type="hidden" value="{{ $user->id }}" name="user_id">
                <button type="submit" class='text-white btn @if($user->isBlocked) btn-success @else btn-danger @endif'>@if($user->isBlocked) 解除 @else 封鎖 @endif</button>
            </form>
        </td>
        <td class="align-middle">
            <a href="message/to/{{ $user->id }}" target="_blank" class='btn btn-dark'>撰寫</a>
        </td>
        <td class="align-middle"><a href="advInfo/{{ $user->id }}" target='_blank' class='text-white btn btn-primary'>前往</a></td>
	</tr>
	@empty
	<tr>找不到符合條件的資料</tr>
	@endforelse
</table>
@endif
</body>
</html>
@stop