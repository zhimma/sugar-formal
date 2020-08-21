@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
        被封鎖了 <small></small>
        </h3>
    </div>			
</div>
</div>
<div class="m-portlet__body">
<p>您已在被封鎖的會員列表中，詳請請洽站長，謝謝</p>
<a class="btn btn-success" href="{!! url('contact') !!}" role="button">聯繫站長</a>
</div>

@stop

