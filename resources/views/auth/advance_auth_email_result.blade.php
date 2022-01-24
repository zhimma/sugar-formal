@extends('layouts.website')

@section('app-content')
<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
            {{$message}} <small></small>
            </h3>
        </div>
    </div>
</div> 
@if($user??null)
<div class="m-portlet__body">
    <p><h3>{{ $user->name }}進階驗證已通過</h3></p>
    <p>{{$message}} 。現在開始，您的帳號將被標註為本站的<img src="{{asset('new/images/b_7.png')}}" class="adv_auth_icon" />進階驗證會員<img src="{{asset('new/images/b_7.png')}}"  class="adv_auth_icon" />。
    </p><p>按<a href="{!! url('login') !!}">這裡登入</a>。</p>
</div>
@endif
@stop
