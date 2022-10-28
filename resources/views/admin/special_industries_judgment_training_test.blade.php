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
            <thead>
                <tr>
                    <th>email</th>
                    <td>暱稱</td>
                    <td>一句話</td>
                    <td>判斷</td>
                    <td>實際結果</td>
                </tr>
            </thead>
            <tbody>
                @foreach($topic_user as $user)
                <tr>
                    <th>
                        <a href="/admin/users/advInfo/{{$user->id}}?is_test=1" target="_blank">{{$user->email}}</a>
                    </th>
                    <td>
                        {{$user->name}}
                    </td>
                    <td>
                        {{$user->title}}
                    </td>
                    <td>
                        <input class="answer_user_id" type='hidden' value={{$user->id}}>
                        <input class="answer_choose" name="question{{$user->id}}" type="radio" value="banned" />封鎖
                        <br>
                        <input class="answer_choose" name="question{{$user->id}}" type="radio" value="warned" />警示
                        <br>
                        <input class="answer_choose" name="question{{$user->id}}" type="radio" value="pass" />pass
                    </td>
                    <td class='correct_answer' style="display:none;">
                        @if($correct_answer[$user->id][0] == 'pass')
                            PASS
                        @elseif($correct_answer[$user->id][0] == 'warned')
                            警示
                            <br>
                            {{$correct_answer[$user->id][1]}}
                        @elseif($correct_answer[$user->id][0] == 'banned')
                            封鎖
                            <br>
                            {{$correct_answer[$user->id][1]}}
                        @endif
                    </td>
                <tr>
                @endforeach
            </tbody>
        </table>
        <button id="submit">送出答案</button>
    </body>
    <script>
        $('#submit').click(function(e){
            let answer_user_id = [];
            let answer_choose = [];
            let unfilled = false;
            $('.answer_user_id').each(function(){
                qname = 'question' + $(this).val();
                answer_user_id.push($(this).val());
                answer_choose.push($('input[name='+ qname +']:checked').val());
                if ($('input[name='+ qname +']:checked').val() == undefined)
                {
                    unfilled = true;
                }
            });
            if(unfilled)
            {
                alert('有題目尚未填寫');
                return false;
            }
            $.ajax({
                type: "POST",
                url: '{{route('admin/special_industries_judgment_answer_send')}}',
                data: {
                    "_token": "{{csrf_token()}}",
                    topic_id: {{$test_topic->id}},
                    answer_user_id: JSON.stringify(answer_user_id),
                    answer_choose: JSON.stringify(answer_choose),
                },
                success: function(data)
                {
                    $('.correct_answer').show();
                    $('#submit').attr('disabled', true);
                }
            });
        });
    </script>
@stop