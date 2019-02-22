@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        @if(isset($exception))
            <h3 class="m-portlet__head-text">
            發生錯誤 <small></small>
            </h3>
        @else
            <h3 class="m-portlet__head-text">
            發生不明錯誤 <small></small>
            </h3>
        @endif
    </div>
</div>
</div>
<div class="m-portlet__body">
    @if(isset($exception))
        <h4>Sorry，網站發生錯誤，請聯絡網站管理員，並提供以下資訊：</h4>
        <h5>時間：{{ \Carbon\Carbon::now()->toDateTimeString() }}</h5>
        <h5>錯誤類型：{{ substr($exception, 0, strpos($exception, ':')) }}</h5>
        <h5>Line ID : AAABBBCC</h5>
    @else
        <h4>Sorry，網站發生不明錯誤，請聯絡網站管理員：</h4>
        <h5>Line ID : AAABBBCC</h5>
    @endif
</div>

@stop
