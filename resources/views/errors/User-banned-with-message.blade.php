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
    <p>
        您因為 {{ $banned->message_time }} 發給 {{ $banned->recipient_name }} 的「{{ substr($banned->message_content, 0, 45) }}...」被封鎖 {{ $days }} 天，持續到 {{ date("Y-m-d H:i", strtotime($banned->expire_date)) }} 整，期滿後，重新登入將自動解除封鎖。
    </p>
    <p>
        如有誤封，請點選網頁右下方的聯絡我們，聯繫站長，由站長幫你解鎖。
    </p>
    <a href="{{ url()->previous() }}" class="btn btn-danger">返回上一頁</a>
</div>
@stop

