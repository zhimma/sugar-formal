@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>未啟動會員</h1>
<!--<form method="POST" action="{{ route('users/manager') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="email" class="">Email</label>	
		<input type="text" name='search' class="" style="width:300px;" id="email" required>
	</div>
	<button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
</form><br>-->
@if(isset($users))
共 {{ $users->count() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>Email</th>
		<th>名稱</th>
		<th>男/女</th>
		<th>註冊時間</th>
		<th>Token</th>
		<th>啟動帳號</th>
	</tr>
	@forelse ($users as $user)
	<tr>
		<td>{{ $user->email }}</td>
		<td>
            <a href="advInfo/{{ $user->id }}" target="_blank">{{ $user->name }}</a>
        </td>
		<td>@if($user->engroup == '1') 男 @else 女 @endif</td>
		<td>{{ $user->created_at }}</td>
		<td>{{ $user->activation_token }}</td>
		<td>
			<a href="{{ route('activateUser', $user->activation_token)  }}" class="btn btn-success">啟動</a>
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
</html>
@stop