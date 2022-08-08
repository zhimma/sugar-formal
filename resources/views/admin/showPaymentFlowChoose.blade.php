@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
    </style>
    <body style="padding: 15px;">
    <h1>編輯付款方式</h1>
    <form action="{{route('paymentFlowChooseEdit').'?id='.$paymentInfo->id }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" style="width: 70%;" id="table">
            <tr>
                <th class="text-center">前台付款方式顯示</th>
                <td style="text-align: left;">{{ $paymentInfo->payment_text }}</td>
            </tr>
            <tr>
                <th class="text-center">金流</th>
                <td style="text-align: left;">
                    @foreach(\App\Models\PaymentFlowChoose::PAYMENT as $payment)
                        <input type="radio" name="payment" value="{{ $payment }}" @if($payment==$paymentInfo->payment) checked @endif>
                        <label>{{ $payment }}</label>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th class="text-center">操作</th>
                <td>
                    <a href="{{ route('paymentFlowChoose')}}" class="text-white btn btn-primary">返回</a>
                    <input type="submit" class='text-white btn btn-success' value="送出">
                </td>
            </tr>
        </table>
    </form>
    </body>
@stop
