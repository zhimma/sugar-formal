@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
            驗證成功 <small></small>
            </h3>
        </div>
    </div>
</div>
<div class="m-portlet__body">
    <p><h3>{{ $user->name }}註冊完成</h3></p>
    <p>請再次確認您的帳號為：<a style="font-weight: bold">{{ $user->email }}</a></p>
    <p>現在您可以正常使用您的帳號了，按<a href="{!! url('login') !!}">這裡登入</a>，以開始您的第一步。</p>
</div>

@stop
