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
<table class='table table-bordered table-hover'>
	<tr>
		<th>Email</th>
		<th>名稱</th>
		<th>男/女</th>
		<th>Token</th>
		<th>啟動帳號</th>
	</tr>
	@forelse ($users as $user)
	<tr>
		<td>{{ $user->email }}</td>
		<td>
            <a href="advInfo/{{ $user->id }}" target="_blank">{{ $user->name }}</a>
        </td>
		<td>{{ $user->gender_ch }}</td>
		<td>{{ $user->token }}</td>
		<td>
			<form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
			<input type="hidden" name='user_id' value="{{ $user->id }}">
			<input type="hidden" name='gender_now' value="{{ $user->engroup }}">
			<button type="button" class="btn btn-warning" onclick="$('.user_profile').submit()">變更</button></form>
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