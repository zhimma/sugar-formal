@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
.form-check-input{
    margin-left: unset !important;
}
</style>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
/>
<body style="padding: 15px;">
<h1>{{$selectionRewardData->name}} 的 {{$selectionRewardData->title}} 活動應徵名單</h1>
<h4>預計核定人數：{{$selectionRewardData->limit}}</h4>

@if(isset($applicationData))
<div>
    <table class="table-hover table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>暱稱</th>
            <th>Email</th>
            <th>應徵狀態</th>
            <th>管理者備註</th>
            <th>應徵時間</th>
            <th>異動時間</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($applicationData as $row)
                <tr>
{{--                    <td><a href="javascript:void(0);" class="get_prove_img" data-user_id="{{ $row->user_id }}" data-name="{{$row->name}}" data-updated_at="{{$row->updated_at}}" data-deadline="{{$row->deadline}}" data-toggle="modal" data-target="#exampleModalLong">{{$row->name}}</a></td>--}}
                    <td>{{$row->user_id}}</td>
                    <td>{{$row->name}}</td>
                    <td><a href="advInfo/{{ $row->user_id }}" target="_blank">{{$row->email}}</a></td>
                    <td>
                        @php
                            switch($row->status) {
                                case 0:
                                    $text = '申請中';
                                    break;
                                case 1:
                                    $text = '通過';
                                    break;
                                case 2:
                                    $text = '不通過';
                                    break;
                            }
                        @endphp
                        <a href="javascript:void(0);" class="update_status" data-type="select" data-name="status" data-pk="{{$row->id}}" data-value="{{$row->status}}" data-title="輸入狀態">{{$text}}</a>
                    </td>
                    <td><a href="javascript:void(0);" class="update" data-type="textarea" data-name="note" data-pk="{{$row->id}}" data-value="{{$row->note}}" data-title="輸入">{{$row->note}}</a></td>
                    <td>{{$row->created_at}}</td>
                    <td>{{$row->updated_at}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{--    {!! $applicationData->appends(request()->input())->links('pagination::sg-pages') !!}--}}
</div>
@endif
</body>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.css" integrity="sha512-Fik9pU5hBUfoYn2t6ApwzFypxHnCXco3i5u+xgHcBw7WFm0LI8umZ4dcZ7XYj9b9AXCQbll9Xre4dpzKh4nvAQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.css" integrity="sha512-4p9BaBwuA5E3w3mOrlv7yFHn6upnXQ4QbjZebGFhqGnM/hUHAFuR1SpRymnLhqWrWv9sGwPI0B6S6CUfHUuSaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js" integrity="sha512-28e47INXBDaAH0F91T8tup57lcH+iIqq9Fefp6/p+6cgF7RKnqIMSmZqZKceq7WWo9upYMBLMYyMsFq7zHGlug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js" integrity="sha512-x/vqovXY/Q4b+rNjgiheBsA/vbWA3IVvsS8lkQSX1gQ4ggSJx38oI2vREZXpTzhAv6tNUaX81E7QBBzkpDQayA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}
<script>

    $.fn.editable.defaults.mode = 'inline';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('.update_status').editable({
        url: "{{ route('vvipSelectionRewardApplyListUpdate') }}",
        type: $(this).data('type'),
        pk: $(this).data('pk'),
        name: $(this).data('name'),
        title: $(this).data('title'),
        value: $(this).data('value'),
        source:[{value: 0, text: "申請中"}, {value: 1, text: "通過"}, {value: 2, text: "不通過"}],
        success: function(response, newValue) {
            if(response.success==false) {
                alert(response.msg);
                location.reload();
            }
        }
    });

    $('.update').editable({
        url: "{{ route('vvipSelectionRewardApplyListUpdate') }}",
        type: $(this).data('type'),
        pk: $(this).data('pk'),
        name: $(this).data('name'),
        title: $(this).data('title'),
        value: $(this).data('value')
    });

</script>

@stop
</html>