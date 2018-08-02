@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>會員封鎖清單</h1>
{{ dd($list) }}
<table class='table'>
	<tr>
		<td>Email</td>
		<td>名稱</td>
		<td>男/女</td>
		<td>是否為VIP</td>
		<td>是否為免費方案</td>
		<td>升級時的帳單編號</td>
		<td>升級時卡號的後四碼</td>
		<td>VIP資料建立時間</td>
		<td>VIP資料更新時間</td>
		<td>變更男/女</td>
		<td>提供/取消VIP權限</td>
	</tr>
	<tr>
	找不到資料
	</tr>
</table>
</body>
</html>
@stop