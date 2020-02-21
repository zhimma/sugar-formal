@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
        Email 驗證 <small></small>
        </h3>
    </div>			
</div>
</div>
<div class="m-portlet__body">
    <p><h3>站長的話</h3></p>
    <p>...</p>
    <hr>
    <a>驗證碼已經寄到你的email. <a style="color: red; font-weight: bold;">【{{ $user->email }}】</a></p>
    <a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">如果沒收到認證信/認證失敗，請點此聯繫站長。</a>
</div>

@stop
