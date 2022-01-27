@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
.table td,
.table th {
    white-space: nowrap;
    /*width: 1%;*/
}
</style>
<body style="padding: 15px;">
<div class="col col-12 col-sm-12 col-md-8 col-lg-4">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="2">訂單狀態檢查</th>

        </tr>
        <tr>
            <th>會員ID</th><td><a href="{{ route('users/advInfo', $paymentData['CustomField1']) }}" target='_blank'>{{$paymentData['CustomField1']}}</a></td>
        </tr>
        @if(isset($userInfo))
        <tr>
            <th>暱稱</th><td>{{$userInfo->name}}</td>
        </tr>
        <tr>
            <th>性別</th><td>{{($userInfo->engroup==1)?'男':'女'}}</td>
        </tr>
        <tr>
            <th>手機</th><td>{{$userInfo->phone}}</td>
        </tr>
        <tr>
            <th>email</th><td>{{$userInfo->email}}</td>
        </tr>
        <tr>
            <th>VIP起始時間 / 現狀 / 付費方式 / 種類</th>
            <td><a href="{{ route('stats/vip_log', $paymentData['CustomField1']) }}" target="_blank">{{ $showVipInfo }}</a></td>
        </tr>
        @endif
        <tr>
            <th>服務項目</th><td>{{ ($paymentData['CustomField4']=='') ? 'VIP':$paymentData['CustomField4']}}</td>
        </tr>
        <tr>
            <th>購買週期</th><td>{{($paymentData['CustomField3']=='') ? '舊式定期定額月付':$paymentData['CustomField3']}}</td>
        </tr>
        <tr>
            <th>檢查結果</th><td>{!! $result !!}</td>
        </tr>
        </thead>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="2">訂單內容</th>
        </tr>
        @if(isset($paymentData))
            @foreach($paymentData as $key => $value)
                <tr>
                    <th width="35%">{{$key}}</th><td>{{$value}}</td>
                </tr>
            @endforeach
        @endif
        </thead>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="2">定期定額訂單內容</th>
        </tr>
        @if(isset($paymentPeriodInfo))
            @foreach($paymentPeriodInfo as $key => $value)
                <tr>
                    <th width="35%">{{$key}}</th>
                    <td>
                        @if(is_array($value))
                            @foreach($value as $row)
                                @foreach($row as $k => $v)
                                    {{ $k }} =>{{ $v }}
                                    <br>
                                @endforeach
                                    <hr>
                            @endforeach
                        @else
                            {{ $value }}
                        @endif
{{--                        {!! $value !!}--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </thead>
    </table>
</div>

@stop