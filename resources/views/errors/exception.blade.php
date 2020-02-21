@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        @if(isset($exception))
            <h3 class="m-portlet__head-text">
            發生錯誤<small></small>
            </h3>
        @else
            <h3 class="m-portlet__head-text">
            發生不明錯誤<small></small>
            </h3>
        @endif
    </div>
</div>
</div>
<div class="m-portlet__body">
    @if(isset($exception))
        <h4>很抱歉，網站發生錯誤，請與站長聯繫(右下角聯絡我們)，並提供以下資訊：</h4>
        <h5>時間：{{ \Carbon\Carbon::now()->toDateTimeString() }}</h5>
        <h5>錯誤類型：{{ substr($exception, 0, strpos($exception, ':')) }}</h5>
    @else
        <h4>近期系統轉換升級~~如果認證失敗。請兩分鐘後重試。其他錯誤請與站長聯繫(右下角聯絡我們)</h4>
    @endif
</div>

@stop
