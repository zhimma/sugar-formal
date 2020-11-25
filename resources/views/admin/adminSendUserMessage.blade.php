@extends('admin.main')
@section('app-content')
<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th {
        vertical-align: middle;
    }

    .table>tbody>tr>th {
        text-align: center;
    }

    .range-wrap {
        position: relative;
        margin: 0 auto 3rem;
    }
    .range {
        width: 100%;
    }
    .bubble {
        background: black;
        color: white;
        padding: 4px 12px;
        position: absolute;
        border-radius: 4px;
        left: 50%;
        transform: translate(-50%, 20px);
    }
    .bubble::after {
        content: "";
        position: absolute;
        width: 2px;
        height: 2px;
        background: black;
        top: -1px;
        left: 50%;
    }
</style>

<body style="padding: 15px;">

    <h1>指定會員發送訊息</h1>

    <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('admin/sendUserMessage') }}" id='message' method='post'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered">

            <tr>
                <th width="20%">發信者email</th>
                <td><input type="email" class="form-control" name="from-email" id="from-email" required></td>
            </tr>
            <tr>
                <th>發信者資訊</th>
                <td>

                        <table class="table-hover table table-bordered">
                            <div class="from-info">

                            </div>
                        </table>

                </td>
            </tr>

            <tr>
                <th width="20%">收信者email</th>
                <td><input type="email" class="form-control" name="to-email" id="to-email" required></td>
            </tr>
            <tr>
                <th width="20%">收信者資訊</th>
                <td>
                    <table class="table-hover table table-bordered">
                        <div class="to-info">

                        </div>
                    </table></td>
            </tr>
            <tr>
                <th width="20%">發送內容</th>
                <td><textarea type="text" class="form-control" name="sendContent" required></textarea></td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="submit" class='text-white btn btn-primary submit' value="發送">
                </td>
            </tr>
        </table>
    </form>
    </div>
    @php
    //print_r($test);
    @endphp
    @if(isset($log_data) && count($log_data)>0)
        <table class="table-hover table table-bordered" style="word-break: break-word;">
            <tr>
                <th width="">from</th>
                <th width="">to</th>
                <th width="">content</th>
                <th width="">建立時間</th>
            </tr>
            @foreach($log_data as $row)
                <tr>
                    <td>{{$row->from_id}}</td>
                    <td>{{$row->to_id}}</td>
                    <td>{{$row->content}}</td>
                    <td>{{$row->created_at}}</td>
                </tr>
            @endforeach
        </table>
    @else
        尚無紀錄
    @endif
</body>
<script>
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    $("#from-email").keyup(function (e) {
        if (isEmail($("#from-email").val())) {

            //alert('Enter key pressed!');
            ////TODO: call Ajax here
            $(".from-info").html("");

            $.post('{{ route('sendUserMessageFindUserInfo') }}', {
                email: $("#from-email").val(),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                var obj = JSON.parse(data);
                if(obj.status=='error'){
                    $(".from-info").html("");
                    $(".from-info").append("查無使用者資料");
                }else if(obj.status=='ok') {
                    var gender = '男';
                    //alert(obj.pic);
                    if(obj.gender==2){
                        gender = '女';
                    }
                    $(".from-info").html("");
                    $(".from-info").append("<tr><th>大頭照</th><th>暱稱</th><th>TITLE</th><th>性別</th></tr><tr><td><img height='60' src='" + obj.pic + "'></td><td>" + obj.name + "</td><td>" + obj.title + "</td><td>" + gender + "</td></tr>");
                }
            });
        }
    });

    $("#to-email").keyup(function (e) {
        if (isEmail($("#to-email").val())) {

            //alert('Enter key pressed!');
            ////TODO: call Ajax here
            $(".to-info").html("");
            $.post('{{ route('sendUserMessageFindUserInfo') }}', {
                email: $("#to-email").val(),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                var obj = JSON.parse(data);

                if(obj.status=='error'){
                    $(".to-info").html("");
                    $(".to-info").append("查無使用者資料");
                }else if(obj.status=='ok') {
                    var gender = '男';
                    //alert(obj.pic);
                    if(obj.gender==2){
                        gender = '女';
                    }
                    $(".to-info").html("");
                    $(".to-info").append("<tr><th>大頭照</th><th>暱稱</th><th>TITLE</th><th>性別</th></tr><tr><td><img height='60' src='" + obj.pic + "'></td><td>" + obj.name + "</td><td>" + obj.title + "</td><td>" + gender + "</td></tr>");
                }
            });
        }
    });
</script>
@stop