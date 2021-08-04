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