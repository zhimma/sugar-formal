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
    <h1>付款方式管理</h1>
    <br>
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">前台付款方式顯示</th>
            <th class="text-center">金流</th>
            <th class="text-center">狀態</th>
            <th class="text-center">建立時間</th>
            <th class="text-center">更新時間</th>
            <th class="text-center">操作</th>
        </tr>
        @foreach($paymentList as $key => $list)
            <tr class="template">
                <td>{{ $key+1 }}</td>
                <td>{{ $list->payment_text }}</td>
                <td>{{ $list->payment }}</td>
                <td>{{ $list->status == 1 ? '開啟':'未開啟' }}</td>
                <td class="created_at">{{ $list->created_at }}</td>
                <td class="updated_at">{{ $list->updated_at }}</td>
                <td>
                    <div style="display: inline-flex;">
                        <a class='text-white btn btn-primary' href="{{ route('showPaymentFlowChoose').'?id='.$list->id }}">修改</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    <br>
    </body>
@stop
