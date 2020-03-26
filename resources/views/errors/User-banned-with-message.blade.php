@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy" style="min-height: 0!important;">
                    <div class="wxsy_title">
                        被封鎖了
                    </div>
                    <div class="wxsy_k">
                        <div class="wknr">
                            <p>
                                您因為 {{ $banned->message_time }} 發給 {{ $banned->recipient_name }} 的「{{ substr($banned->message_content, 0, 45) }}...」被封鎖 {{ $days }} 天，持續到 {{ date("Y-m-d H:i", strtotime($banned->expire_date)) }} 整，期滿後，重新登入將自動解除封鎖。
                            </p>
                            <p>
                                如有誤封，請點選網頁右下方的聯絡我們，聯繫站長，由站長幫你解鎖。
                            </p>
                            <a href="{{ url()->previous() }}" class="btn btn-danger">返回上一頁</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


