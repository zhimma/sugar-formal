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
<p>您因為 2019/04/01 23:00 發給 ooo 的 "訊息內容前 15 個字,超過15個字就+more替代"
    被封鎖 N 天，封鎖時間到 2019/04/04 23:00 整。如有誤封，請點右下方聯絡我們
    請站長幫你解鎖。</p>
<a class="btn btn-success" href="{!! url('contact') !!}" role="button">聯繫站長</a>
</div>
@stop

