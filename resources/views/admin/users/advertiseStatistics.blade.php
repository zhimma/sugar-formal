@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>廣告紀錄統計</h1>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td> 瀏覽人數 </td>
                <td> {{$explore_count}} 人 </td>
            <tr>
            <tr>
                <td> 註冊人數 </td>
                <td> {{$regist_count}} 人 </td>
            <tr>
            <tr>
                <td> 登入人數 </td>
                <td> {{$login_count}} 人 </td>
            <tr>
        </table>
    </body>
@stop