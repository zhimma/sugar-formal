@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <h1>站長審核</h1>
    <table class="table-bordered table-hover center-block table" id="table">
        <thead>
            <tr>
                <th scope="col">項目</th>
                <th scope="col">待審筆數</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="{{ route('admin/checkNameChange') }}">修改暱稱申請</a></td>
                <td><a href="{{ route('admin/checkNameChange') }}">{{$item_a}}</a></td>
            </tr>
            <tr>
                <td><a href="{{ route('admin/checkGenderChange') }}">變更帳號類型</a></td>
                <td><a href="{{ route('admin/checkGenderChange') }}">{{$item_b}}</a></td>
            </tr>
            <tr>
                <td><a href="{{ route('admin/checkExchangePeriod') }}">包養關係變更申請</a></td>
                <td><a href="{{ route('admin/checkExchangePeriod') }}">{{$item_c}}</a></td>
            </tr>
            <tr>
                <td><a href="{{ route('admin/checkRealAuth') }}">女會員認證</a></td>
                <td><a href="{{ route('admin/checkRealAuth') }}">{{$item_d}}</a></td>
            </tr>
            <tr>
                <td><a href="{{ route('admin/checkAnonymousContent') }}">匿名評價訊息</a></td>
                <td><a href="{{ route('admin/checkAnonymousContent') }}">{{$item_e}}</a></td>
            </tr>
        </tbody>
    </table>
</body>
@stop
