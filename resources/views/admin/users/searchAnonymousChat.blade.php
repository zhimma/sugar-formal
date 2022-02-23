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
</style>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
/>
<body style="padding: 15px;">
    <h1>匿名聊天室</h1>
    @if (isset($errors))
    @if ($errors->count() > 0)
    @else
    <h3 style="text-align: left;">搜尋</h3>
    <form action="{{ route('users/searchAnonymousChatPage') }}" id='message' method='get'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered" style="width: 50%;">
            <tr>
                <th width="15%">
                    <label for="msg">訊息內容</label>
                </th>
                <td>
                    <input type="text" name="msg" value="@if(isset($_REQUEST['msg'])) {{ $_REQUEST['msg'] }}@endif" class="form-control" id="msg">
                </td>
            </tr>
            <tr>
                <th>開始時間</th>
                <td>
                    <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_REQUEST['date_start'])){{ $_REQUEST['date_start'] }}@endif" class="form-control">
                </td>
            <tr>
                <th>結束時間</th>
                <td>
                    <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_REQUEST['date_end'])){{ $_REQUEST['date_end'] }}@endif" class="form-control">
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
                <td colspan="2"><button class='text-white btn btn-primary submit'>搜尋</button> 或 <a id="reported_meg" href="{{ route('users/searchAnonymousChatReport') }}" class="btn btn-info">檢視被檢舉訊息</a></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="button" class='btn btn-default' onclick="location.href='anonymousChat';" value="重設" />
                </td>
            </tr>
        </table>
    </form>
    @if(isset($results))
        <h3 style="text-align: left;">搜尋結果</h3>
        <table class="table-hover table table-bordered">

            <thead>
                <tr>
                    <td>發送者</td>
                    <td>匿名</td>
                    <td>性別</td>
                    <td>訊息內容</td>
                    <td>圖片</td>
                    <td>發訊時間</td>
                    <td>狀態</td>
                    <td>管理</td>
                </tr>
            </thead>

            <tbody>
            @if(count($results)>0)
            @foreach ($results as $row)
                <tr>
                    <td>
                        <a href="{{ route('users/advInfo', $row->user_id) }}" target='_blank' >{{$row->name}}</a>
                    </td>
                    <td>{{$row->anonymous}}</td>
                    <td>{{($row->engroup==1)?'男':'女'}}</td>
                    <td>{{$row->content}}</td>
                    <td>
                        @if(!is_null(json_decode($row->pic,true)))
                            @foreach(json_decode($row->pic,true) as $key => $pic)
                                @if(isset($pic['file_path']))
                                    <a href="{{$pic['file_path'] }}" data-fancybox="gallery_{{$row->id}}" target="_blank">
                                        <img src="{{ $pic['file_path'] }}" style="object-fit: cover; weight:50px; height: 50px;">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{$row->created_at}}</td>
                    <td>@if($row->deleted_at)<span class="badge badge-danger">刪：{{$row->deleted_at}}</span>@endif</td>
                    <td>
                        @if(!$row->deleted_at)<input type="button" class='btn btn-danger' onclick="deleteRow({{$row->id}})" value="刪除" />@endif
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="7">沒有資料</td>
                </tr>
            @endif
            </tbody>

        </table>
{{--        {{ $results->appends(request()->input())->links('pagination::tailwind') }}--}}
        {{ $results->links() }}
    @endif

    @if(isset($resultsReport))
        <h3 style="text-align: left;">搜尋結果</h3>
        <table class="table-hover table table-bordered">

            <thead>
            <tr>
                <td>發送者</td>
                <td>匿名</td>
                <td>性別</td>
                <td>訊息內容</td>
                <td>圖片</td>
                <td>發訊時間</td>
                <td>檢舉人</td>
                <td>檢舉內容</td>
                <td>檢舉時間</td>
                <td>狀態</td>
                <td>管理</td>
            </tr>
            </thead>

            <tbody>
            @if(count($resultsReport)>0)
            @foreach ($resultsReport as $row)
                <tr>
                    <td>
                        <a href="{{ route('users/advInfo', $row->user_id) }}" target='_blank' >{{$row->name}}</a>
                    </td>
                    <td>{{$row->anonymous}}</td>
                    <td>{{($row->engroup==1)?'男':'女'}}</td>
                    <td>{{$row->content}}</td>
                    <td>
                        @if(!is_null(json_decode($row->pic,true)))
                            @foreach(json_decode($row->pic,true) as $key => $pic)
                                @if(isset($pic['file_path']))
                                    <a href="{{$pic['file_path'] }}" data-fancybox="gallery_{{$row->id}}" target="_blank">
                                        <img src="{{ $pic['file_path'] }}" style="object-fit: cover; weight:50px; height: 50px;">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{$row->created_at}}</td>
                    <td>
                        <a href="{{ route('users/advInfo', $row->report_user) }}" target='_blank' >{{$row->report_name}}</a>
                    </td>
                    <td>{{$row->report_content}}</td>
                    <td>{{$row->report_time}}</td>
                    <td>@if($row->deleted_at)<span class="badge badge-danger">刪：{{$row->deleted_at}}</span>@endif</td>
                    <td>
                        @if(!$row->deleted_at)<input type="button" class='btn btn-danger' onclick="deleteRow({{$row->id}})" value="刪除" />@endif
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                <td colspan="10">沒有資料</td>
                </tr>
            @endif
            </tbody>

        </table>
{{--        {{ $resultsReport->appends(request()->input())->links('pagination::tailwind') }}--}}
        {{ $resultsReport->links() }}
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
    jQuery(document).ready(function() {
        jQuery("#datepicker_1").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }).val();
        jQuery("#datepicker_2").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }).val();

        $('.today').click(
            function() {
                $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                set_end_date();
            });
        $('.last3days').click(
            function() {
                var days = 3; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth()  + 1 ) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last10days').click(
            function() {
                var days = 10; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1 ) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last30days').click(
            function() {
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                set_end_date();
            });
        $('.submit').click(
            function() {
                $('#msg2').val($('#msg').val());
                $('#message').submit();
            });
        $('.delete-btn').on('click', function(e) {
            if (!confirm('確定要刪除選取的訊息?')) {
                e.preventDefault();
            }
        });


    });

    function deleteRow(id) {
        if (!confirm('確定要刪除選取的訊息?')) {
            e.preventDefault();
        }
        // alert('pass');
        $.post('{{ route('users/deleteAnonymousChatRow') }}', {
            id: id,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            location.reload();
        });
    }

    function selectAll() {
        $('.boxes').each(
            function() {
                if ($(this).is(':checked')) {
                    $(this).prop("checked", false);
                } else {
                    $(this).selected();
                }
            });

    }

    function set_end_date() {
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

    function Release(id) {
        $("#blockUserID").val(id);
    }
    function ReleaseWarnedUser(id) {
        $("#warnedUserID").val(id);
    }
    function WarnedToggler(user_id,isWarned){
        $.ajax({
            type: 'POST',
            url: "/admin/users/isWarned_user",
            data:{
                _token: '{{csrf_token()}}',
                id: user_id,
                status: isWarned
            },
            dataType:"json",
            success: function(res){
                if(isWarned ==1)
                    alert('警示用戶成功');
                else
                    alert('取消警示用戶成功');
                location.reload();
            }
        });
    }
    // let count = 0;
    // function setDays(a, key) {
    //     if (count === 0) {
    //         let href = a.href;
    //         if(key === '') {
    //             let reason = $('.m-reason').val();
    //             $('.ban-user' + key).attr("href", href + '/' + $('.days' + key).val() + '&' + reason);
    //         }else{
    //             $('.ban-user' + key).attr("href", href + '/' + $('.days' + key).val());
    //         }
    //     }
    //     count++;
    // }
</script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@stop
</html>