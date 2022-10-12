@extends('admin.main')
@section('app-content')
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
    .table > tbody > tr > th{
        text-align: center;
    }
    </style>
    <body style="padding: 15px;">
        <h1>八大判斷訓練測試頁</h1>
        <table class="table-hover table table-bordered" style="width: 50%;">
            @foreach($test_topic as $test)
                <tr>
                    <th></th>
                    <td align="center">
                        <button onclick="location.href='special_industries_judgment_training_test?topic_id={{$test->id}}'">測試</button>
                    </td>
                <tr>
            @endforeach
        </table>
    </body>
@stop