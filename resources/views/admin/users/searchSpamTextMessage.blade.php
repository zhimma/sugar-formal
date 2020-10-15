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

    <h1>罐頭訊息查詢</h1>

    <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('searchSpamTextMessage') }}" id='message' method='post'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered">

            <tr>
                <th>相似度設定</th>
                <td>
                    <div class="range-wrap">
                    <output class="bubble"></output>
                    <input type="range" name="percent" min="50" max="100" value=@if(isset($_POST['percent']))"{{$_POST['percent']}}"@else"70"@endif class="form-control-range range" id="myRange">
                    </div>
                </td>
            </tr>
            <tr>
                <th>會員email</th>
                <td>
                    <textarea type="text" name="search_email" class="form-control" placeholder="多筆查詢請「,」隔開">@if(isset($_POST['search_email'])){{$_POST['search_email']}}@endif</textarea>
                </td>
            </tr>
            <tr>
                <th>開始時間</th>
                <td>
                    <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_POST['date_start'])){{ $_POST['date_start'] }}@endif" class="form-control" required>
                </td>
            <tr>
                <th>結束時間</th>
                <td>
                    <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_POST['date_end'])){{ $_POST['date_end'] }}@endif" class="form-control" required>
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
                <th>排序方式</th>
                <td>
                    <input type="radio" name="time" value="created_at" @if(isset($_POST['time']) && $_POST['time']=='created_at' ) checked="true" @endif />註冊時間
                    <input type="radio" name="time" value="last_login" @if(isset($_POST['time']) && $_POST['time']=='last_login' ) checked="true" @endif />上線時間
                </td>
            </tr>
            <tr>
                <th>查詢使用者數</th>
                <td>
                    <div class="range-wrap">
                        <output class="bubble"></output>
                        <input type="range" name="users_counts" min="30" max="200" value=@if(isset($_POST['users_counts']))"{{$_POST['users_counts']}}"@else"30"@endif class="form-control-range range" id="myRange">
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" class='text-white btn btn-primary submit' value="查詢">
                </td>
            </tr>
        </table>
    </form>
    </div>
    @php
    //print_r($test);
    @endphp
    @if(isset($data_all) && count($data_all)>0)
        <table class="table-hover table table-bordered" style="word-break: break-word;">
            <tr>
                <th width="9%">email</th>
                <th width="5%">暱稱</th>
                <th width="10%">一句話形容自己</th>
                <th width="2%">性別</th>
                <th width="10%">關於我</th>
                <th width="7%">註冊時間</th>
                <th width="7%">上線時間</th>
                <th width="50%">罐頭訊息比例</th>
            </tr>
            @foreach($data_all as $row)
                @php
                @endphp
                <tr>
                    <td><a href="{{ route('users/advInfo', $row['user_id']) }}" target='_blank' >{{$row['email']}}</a></td>
                    <td>{{$row['name']}}</td>
                    <td>{{$row['title']}}</td>
                    <td>@if($row['engroup']==2)女@else男@endif</td>
                    <td>{{$row['about']}}</td>
                    <td>{{substr($row['created_at'],0,10)}}</td>
                    <td><font size="1">{{$row['last_login']}}</font></td>
                    <td>
                        相似訊息數：{{count($row['similar_msg'])}} <br>
                        總訊息數：{{$row['all_msg_counts']}} <br>
                        比例：{{ round( (count($row['similar_msg']) / $row['all_msg_counts'])*100 ) }}%<br>
                        <table class="table" style="word-break: break-word;">
                            <tr>
                                <th width="70%">內容</th>
                                <th>時間</th>
                                <th width="10%">相似度</th>
                            </tr>
                            @foreach($row['similar_msg'] as $detail)
                            <tr>
                                <td>{{$detail[1]}}</td>
                                <td><font size="1">{{$detail[2]}}</font></td>
                                <td>{{round($detail[3])}}</td>
                            </tr>
                            @endforeach

                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        查詢資料
    @endif
</body>
<script>

    const allRanges = document.querySelectorAll(".range-wrap");
    allRanges.forEach(wrap => {
        const range = wrap.querySelector(".range");
        const bubble = wrap.querySelector(".bubble");

        range.addEventListener("input", () => {
            setBubble(range, bubble);
        });
        setBubble(range, bubble);
    });

    function setBubble(range, bubble) {
        const val = range.value;
        const min = range.min ? range.min : 0;
        const max = range.max ? range.max : 100;
        const newVal = Number(((val - min) * 100) / (max - min));
        bubble.innerHTML = val;

        // Sorta magic numbers based on size of the native UI thumb
        bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px))`;
    }

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
                minus_date.setDate(minus_date.getDate() - 29);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 29);
            });
        // $('.submit').click(
        //     function() {
        //         $('#msg2').val($('#msg').val());
        //         $('#message').submit();
        //     });
        $('.delete-btn').on('click', function(e) {
            if (!confirm('確定要刪除選取的訊息?')) {
                e.preventDefault();
            }
        });
        $('#reported_meg').click(function(event) {
            let date_start = $('#datepicker_1').val() ? $('#datepicker_1').val() : '1970-01-01';
            let date_end = $('#datepicker_2').val() ? $('#datepicker_2').val() : $.datepicker.formatDate('yy-mm-dd', new Date());
            let href = $(this).attr('href');
            $(this).attr('href', href + "?date_start=" + date_start + "&date_end=" + date_end);
        });
        // $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
        //     var data_id = '';
        //     if (typeof $(this).data('id') !== 'undefined') {
        //         data_id = $(this).data('id');
        //         $("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
        //     }
        //     $("#send_blockade").attr('href', data_id);
        // })
        // $('.advertising').on('click', function(e) {
        //     $('.m-reason').val('廣告');
        // });
        // $('.improper-behavior').on('click', function(e) {
        //     $('.m-reason').val('非徵求包養行為');
        // });
        // $('.improper-words').on('click', function(e) {
        //     $('.m-reason').val('用詞不當');
        // });
        // $('.improper-photo').on('click', function(e) {
        //     $('.m-reason').val('照片不當');
        // });
    });

    

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
@stop