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
                <td> 完成註冊人數 </td>
                <td> {{$complete_regist_count}} 人 </td>
            <tr>
            <tr>
                <td> 登入人數 </td>
                <td> {{$login_count}} 人 </td>
            <tr>
        </table>
        <br>
        <div>
            <h5 style="text-align:left;">從連結進入是已登入的狀態時為登入</h5>
            <h5 style="text-align:left;">進入註冊頁時為註冊</h5>
            <h5 style="text-align:left;">進入登入頁面登入完成進到個人專屬頁為登入</h5>
            <h5 style="text-align:left;">其餘皆為瀏覽</h5>
        </div>
    </body>
@stop