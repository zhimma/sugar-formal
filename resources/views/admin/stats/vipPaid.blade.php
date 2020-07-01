@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>付費 VIP 會員訂單資料</h1>
<h4>範本：您的 VIP 付費(卡號後四碼XXXX)已於 0000 年 O 月扣款失敗，故停止您的 VIP 權限。優選會員資格一併取消，若有疑問請點右下聯絡我們連絡站長。</h4>
<h2><a href="https://dollar.ezpay.com.tw/NewebPayment/Admin.jsp" target="_blank">藍新</a></h2>
共{{ $ezpay->count() }}筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員ID</th>
		<th>名稱</th>
		<th>男/女</th>
        <th>訂單編號</th>
		<th>上次登入</th>
{{--		<th>取消權限</th>--}}
    </tr>
	@forelse ($ezpay as $e)
	<tr>
		<td>{{ $e->member_id }}</td>
		<td>
            <a href="{{ route('users/advInfo/readOnly', $e->member_id) }}" target="_blank">{{ $e->name }}</a>
        </td>
        <td>{{ $e->engroup }}</td>
        <td>{{ $e->order_id }}</td>
		<td>{{ $e->last_login }}</td>
{{--		<td>--}}
{{--			<form method="POST" action="{{ route('VIPToggler') }}" class="vip">--}}
{{--				{!! csrf_field() !!}--}}
{{--				<input type="hidden" name='user_id' value="{{ $e->id }}">--}}
{{--				<input type="hidden" name='isVip' value="1">--}}
{{--				<input type="hidden" value="back" name="page">--}}
{{--				<button type="submit" class="btn btn-info">取消權限</button>--}}
{{--			</form>--}}
{{--		</td>--}}
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
<h2><a href="https://vendor.ecpay.com.tw/Frame/Index" target="_blank">綠界</a></h2>
共{{ $ecpay->count() }}筆資料
<table class='table table-bordered table-hover'>
	<tr>
		<th>會員ID</th>
		<th>名稱</th>
		<th>男/女</th>
		<th>訂單編號</th>
		<th>上次登入</th>
	</tr>
	@forelse ($ecpay as $e)
		<tr>
			<td>{{ $e->member_id }}</td>
			<td>
				<a href="{{ route('users/advInfo/readOnly', $e->member_id) }}" target="_blank">{{ $e->name }}</a>
			</td>
			<td>{{ $e->engroup }}</td>
			<td>{{ $e->order_id }}</td>
			<td>{{ $e->last_login }}</td>
		</tr>
	@empty
		<tr>
			找不到資料
		</tr>
	@endforelse
</table>
</body>
</html>
@stop