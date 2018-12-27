@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
        錯誤：沒有資料 <small></small>
        </h3>
    </div>			
</div>
</div>
<div class="m-portlet__body">
    <p>系統中沒有符合的資料，若您確定問題出在本站，敬請聯擊站長，並協助我們解決問題，謝謝。</p>
    <a class="btn btn-success" href="{!! url('contact') !!}" role="button">聯繫站長</a>
</div>

@stop

