@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
</style>
<body style="padding: 15px;">
<h1>站內訊息管理</h1>
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        <h3 style="text-align: left;">搜尋</h3>
        <form action="{{ route('users/message/search') }}" id='message' method='POST'>
            {!! csrf_field() !!}
            <table class="table-hover table table-bordered" style="width: 50%;">
                <tr>
                    <th width="15%">
                        <label for="msg">訊息內容</label>
                    </th>
                    <td>
                        <input type="text" name="msg" value="@if(isset($msg)) {{ $msg }}@endif" class="form-control" id="msg">
                    </td>
                </tr>
                <tr>
                    <th>開始時間</th>
                    <td>
                        <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
                    </td>
                <tr>
                    <th>結束時間</th>
                    <td>
                        <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>預設時間選項</th>
                    <td>
                        <a class="text-white btn btn-success today">今天</a>
                        <a class="text-white btn btn-success last3days">最近3天</a>
                        <a class="text-white btn btn-success last10days">最近10天</a>
                        <a class="text-white btn btn-success last30days">最近30天</a>
                    </td>
                </tr>
                <tr>
                    <th>排序方式1</th>
                    <td>
                        <input type="radio" name="time" value="created_at" @if(isset($time) && $time=='created_at') checked="true" @endif/>註冊時間
                        <input type="radio" name="time" value="login_time" @if(isset($time) && $time=='login_time') checked="true" @endif/>上線時間
                        <input type="radio" name="time" value="send_time" @if(isset($time) && $time=='send_time') checked="true" @endif/>發訊時間
                    </td>
                </tr>
                <tr>
                    <th>排序方式2</th>
                    <td>
                        <input type="radio" name="member_type" value="vip" @if(isset($member_type) && $member_type=='vip') checked="true" @endif/>VIP會員
                        <input type="radio" name="member_type" value="banned" @if(isset($member_type) && $member_type=='banned') checked="true" @endif/>Banned List會員
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button class='text-white btn btn-primary submit'>送出</button> 或 <a href="{{ route('users/message/search/reported') }}" class="btn btn-info">檢視被檢舉的訊息</a></td>
                </tr>
            </table>
        </form>
        @if(isset($results))
        <form action="{{ route('users/message/modify') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="msg" value="@if(isset($msg)) {{ $msg }}@endif" class="form-control" id="msg2">
            <input type='hidden' class="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
            <input type='hidden' class="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
            <h3 style="text-align: left;">搜尋結果</h3>
            <table class="table-hover table table-bordered">
                <tr>
                    <td>發送者</td>
                    <td>VIP</td>
                    <td>收訊者</td>
                    <td>內容</td>
                    <td>已讀</td>
                    <td>發送時間</td>
                    <td>
                        <button id="select_all" class="btn btn-primary" onclick="selectAll();return false;">全選</button>
                        <br>
                        <button type="submit" class="btn btn-danger delete-btn" onclick="$('#delete').val(1);">刪除選取</button>
                        <input type="hidden" name="delete" id="delete" value="0">
                        <button type="submit" class="btn btn-warning" onclick="$('#edit').val(1);">修改選取</button>
                        <input type="hidden" name="edit" id="edit" value="0">
                        <input type="hidden" name="msg" value="{{ $msg }}">
                    </td>
                </tr>
                @forelse ($results as $result)
                    <tr @if($result['isBlocked']) style="color: #F00;" @endif>
                        <td>
                            <a href="{{ route('users/advInfo', $result['from_id']) }}" target='_blank'>{{ $users[$result['from_id']] }}</a>
                            <button type="button" onclick="toggleBanned({{ $result['from_id'] }});" target="_blank" class='text-white btn @if($result['isBlocked']) btn-success @else btn-danger @endif'>@if($result['isBlocked']) ◯ @else 🞫 @endif</button>
                        </td>
                        <td>{{ $result['vip'] }}</td>
                        <td>{{ $users[$result['to_id']] }}</td>
                        <td width="45%">{{ $result['content'] }}</td>
                        <td>{{ $result['read'] }}</td>
                        <td>{{ $result['created_at'] }}</td>
                        <td style="text-align: center; vertical-align: middle">
                            <input type="checkbox" name="msg_id[]" value="{{ $result['id'] }}" class="form-control boxes">
                        </td>
                    </tr>
                @empty
                    沒有資料
                @endforelse
            </table>
        </form>
        @else
            @if(isset($senders))
                <form action="{{ route('users/message/modify') }}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="msg" value="@if(isset($msg)) {{ $msg }}@endif" class="form-control" id="msg2">
                    <input type='hidden' class="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
                    <input type='hidden' class="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
                    <h3 style="text-align: left;">搜尋結果</h3>
                    <table class="table-hover table table-bordered">
                        <tr>
                            <td>發送者</td>
                            <td>註冊時間</td>
                            <td>上線時間</td>
                            <td>VIP</td>
                            <td>收訊者</td>
                            <td>內容</td>
                            <td>已讀</td>
                            <td>發送時間</td>
                            <td>
                                <button id="select_all" class="btn btn-primary" onclick="selectAll();return false;">全選</button>
                                <br>
                                <button type="submit" class="btn btn-danger delete-btn" onclick="$('#delete').val(1);">刪除選取</button>
                                <input type="hidden" name="delete" id="delete" value="0">
                                <button type="submit" class="btn btn-warning" onclick="$('#edit').val(1);">修改選取</button>
                                <input type="hidden" name="edit" id="edit" value="0">
                                <input type="hidden" name="msg" value="{{ $msg }}">
                            </td>
                        </tr>
                        @forelse ($senders as $sender)
                        <tr @if($sender['isBlocked']) style="color: #F00;" @endif>
                            <td rowspan="{{ count($sender['messages']) }}">
                                <a href="{{ route('users/advInfo', $sender['id']) }}" target='_blank'>{{ $sender['name'] }}</a>
                                <button type="button" onclick="toggleBanned({{ $sender['id'] }});" target="_blank" class='text-white btn @if($sender['isBlocked']) btn-success @else btn-danger @endif'>@if($sender['isBlocked']) ◯ @else 🞫 @endif</button>
                            </td>
                            <td rowspan="{{ count($sender['messages']) }}">{{ $sender['created_at'] }}</td>
                            <td rowspan="{{ count($sender['messages']) }}">{{ $sender['last_login'] }}</td>
                            <td rowspan="{{ count($sender['messages']) }}">{{ $sender['vip'] }}</td>
                            <td>{{ $receivers[$sender['messages'][0]['to_id']] }}</td>
                            <td width="45%">{{ $sender['messages'][0]['content'] }}</td>
                            <td>{{ $sender['messages'][0]['read'] }}</td>
                            <td>{{ $sender['messages'][0]['created_at'] }}</td>
                            <td style="text-align: center; vertical-align: middle">
                                <input type="checkbox" name="msg_id[]" value="{{ $sender['messages'][0]['id'] }}" class="form-control boxes">
                            </td>
                        </tr>
                        @if(count($sender['messages']) > 1)
                            @for( $i = 1; $i < count($sender['messages']); $i++)
                                <tr @if($sender['isBlocked']) style="color: #F00;" @endif>
                                    <td>{{ $receivers[$sender['messages'][$i]['to_id']] }}</td>
                                    <td width="45%">{{ $sender['messages'][$i]['content'] }}</td>
                                    <td>{{ $sender['messages'][$i]['read'] }}</td>
                                    <td>{{ $sender['messages'][$i]['created_at'] }}</td>
                                    <td style="text-align: center; vertical-align: middle">
                                        <input type="checkbox" name="msg_id[]" value="{{ $sender['messages'][$i]['id'] }}" class="form-control boxes">
                                    </td>
                                </tr>
                            @endfor
                        @endif
                        @empty
                            沒有資料
                        @endforelse
                    </table>
                </form>
            @endif
        @endif
    @endif
@endif
</body>
<script>
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date(year, month, day);
    let minus_date = new Date(today);
    jQuery(document).ready(function(){
        jQuery("#datepicker_1").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        ).val();
        jQuery("#datepicker_2").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }
        ).val();

        $('.today').click(
            function(){
                $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                set_end_date();
            });
        $('.last3days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 2);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 2);
            });
        $('.last10days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 9);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 9);
            });
        $('.last30days').click(
            function () {
                minus_date.setDate(minus_date.getDate() - 29);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 29);
            });
        $('.submit').click(
            function () {
                $('#msg2').val($('#msg').val());
                $('#message').submit();
            });
        $('.delete-btn').on('click',function(e){
            if(!confirm('確定要刪除選取的訊息?')){
                e.preventDefault();
            }
        });
    });

    function selectAll () {
        $('.boxes').each(
            function () {
                if($(this).is(':checked')){
                    $(this).prop("checked", false);
                }
                else{
                    $(this).selected();
                }
            });

    }
    function set_end_date(){
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }
    function str_pad(n) {
        return String("00" + n).slice(-2);
    }
    function toggleBanned(id) {
        //  http://sugar.formal/5814
        let url = "{{ url("") }}";
        window.open(url + '/admin/users/toggleUserBlock/' + id);
        history.go(0);
    }
</script>
@stop