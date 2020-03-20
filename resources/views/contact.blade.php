@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
        聯絡我們 <small></small>
        </h3>
    </div>
</div>
</div>
<div class="m-portlet__body">
    @if(Auth::check() && isset($user))
        <li><h4>站長line：@giv4956r (包含@哦)</h4></li>
        <li><h4>站長email：admin@sugar-garden.org<h4></li>
        <li><h4>網站問題回報：@giv4956r<h4></li>
        <li><h4>網站問題回報：admin@sugar-garden.org<h4></li>
    @else
        {{-- no login --}}
        <p>請註冊會員，或者參考<a href="http://blog-tw.net/Sugar/">站長的碎碎念</a></p>
    @endif
</div>

@stop
