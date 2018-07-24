@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
會員資料查詢：
<form method="POST" action="{{ route('users/advSearch') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="email" class="">Email</label>	
		<input type="email" name='email' class="" style="width:300px;" id="email">
		<br>
		<label for="name" class="">暱稱</label>	
		<input type="text" name='name' class="" style="width:300px;" id="name">
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
		<td>建立時間</td>
		<td>更新時間</td>
		<td>上次登入</td>
		<td>所有資料/管理</td>
	</tr>
	@forelse ($users as $user)
	<tr>
		<td class="align-middle">{{ $user->id }}</td>
		<td class="align-middle">{{ $user->name }}</td>
		<td class="align-middle">{{ $user->title }}</td>
		<td class="align-middle">@if($user->engroup==1) 男 @else 女 @endif</td>
		<td class="align-middle">{{ $user->email }}</td>
		<td class="align-middle">{{ $user->created_at }}</td>
		<td class="align-middle">{{ $user->updated_at }}</td>
		<td class="align-middle">{{ $user->last_login }}</td>
		<td class="align-middle"><button class='btn btn-primary'><a href="advInfo/{{ $user->id }}" target='blank' class='text-white '>前往</a></button></td>		
	</tr>
	@empty
	<tr>找不到符合條件的資料</tr>
	@endforelse
</table>
@endif
</body>
</html>
@stop