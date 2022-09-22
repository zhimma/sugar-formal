@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>VVIP 保證金修改 - {{ $deposit->user->name }}</h1>
<form method="POST" action="{{ route('users/VVIP_margin_deposit/save', $deposit->user->id) }}" class="the_form">
	{!! csrf_field() !!}
    <input type="hidden" name="balance_before" value="{{ $deposit->balance ?? 0 }}">
	<table class="table table-bordered table-hover" style="width: 50%">
        <tr>
            <th>
                <label for="email" class="">保證金</label>
            </th>
            <td>
                <input type="number" min="0" name='balance_after' class="form-control" style="width:300px;" value="{{ old('balance', $deposit->balance ?? 0) }}" id="">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="button" class="btn btn-primary" onclick="$('.the_form').submit()">送出</button>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
@stop