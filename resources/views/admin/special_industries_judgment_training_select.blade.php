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
                    <th>
                        題目編號{{$test->topic_id}}-設定({{$test->title}})
                        ({{$test->topic_count}}人)
                        ({{Carbon\Carbon::parse($test->start_time)->toDateString()}}~{{Carbon\Carbon::parse($test->end_time)->toDateString()}})
                        (
                        @if($test->gender == 1)
                        男
                        @elseif($test->gender == 2)
                        女
                        @else
                        男女
                        @endif
                        )
                    </th>
                    <td align="center">
                        <button onclick="location.href='special_industries_judgment_training_test?topic_id={{$test->topic_id}}'">測試</button>
                    </td>
                <tr>
            @endforeach
        </table>
        <h1>已填寫測驗</h1>
        <table class="table-hover table table-bordered" style="width: 50%;">
            @foreach($already_test_topic as $test)
                <tr>
                    <th>
                        題目編號{{$test->topic_id}}-設定({{$test->title}})
                        ({{$test->topic_count}}人)
                        ({{Carbon\Carbon::parse($test->start_time)->toDateString()}}~{{Carbon\Carbon::parse($test->end_time)->toDateString()}})
                        (
                        @if($test->gender == 1)
                        男
                        @elseif($test->gender == 2)
                        女
                        @else
                        男女
                        @endif
                        )
                    </th>
                    <td align="center">
                        <button onclick="location.href='special_industries_judgment_training_test?topic_id={{$test->topic_id}}'">查看</button>
                    </td>
                <tr>
            @endforeach
        </table>
    </body>
@stop