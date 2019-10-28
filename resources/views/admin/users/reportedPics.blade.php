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
    <h1>被檢舉照片清單</h1>
    @if (isset($errors))
        @if ($errors->count() > 0)
        @else
            <h3 style="text-align: left;">搜尋</h3>
            <form action="{{ route('users/pics/reported') }}" id='pics' method='POST'>
                {!! csrf_field() !!}
                <table class="table-hover table table-bordered" style="width: 50%;">
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
                        <td colspan="2">
                            <button class='text-white btn btn-primary submit'>送出</button>
                        </td>
                    </tr>
                </table>
            </form>
            @if(isset($results))
                <form action="{{ route('users/message/modify') }}" method="post">
                    {!! csrf_field() !!}
                    <input type='hidden' class="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
                    <input type='hidden' class="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
                    <h3 style="text-align: left;">搜尋結果</h3>
                    <table class="table-hover table table-bordered">
                        <tr>
                            <td>檢舉者</td>
                            <td>回覆檢舉者</td>
                            <td>封鎖檢舉者</td>
                            <td>被檢舉者</td>
                            <td>回覆被檢舉者</td>
                            <td>封鎖被檢舉者</td>
                            <td>圖片</td>
                            <td>檢舉理由</td>
                            <td>檢舉時間</td>
                        </tr>
                        @if(isset($results))
                            @foreach ($results as $result)
                            <tr >
                                <td>
                                    <a href="{{ route('users/advInfo', $result['reporter_id']) }}" target='_blank' @if($result['isBlocked']) style="color: #F00;" @endif >{{ $users[$result['reporter_id']]['name'] }}
                                    @if($users[$result['reporter_id']]['vip'] )
                                        <i class="m-nav__link-icon fa fa-diamond"></i>
                                    @endif
                                    @if(isset($result['isBlocked']))
                                        @if(isset($result['isBlocked']['expire_date']))
                                            ({{ round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                        @else
                                            (永久)
                                        @endif
                                    @endif
                                    </a>
                                    <!-- <button type="button" onclick="toggleBanned({{ $result['reporter_id'] }});" target="_blank" class='text-white btn @if($result['isBlocked']) btn-success @else btn-danger @endif'>@if($result['isBlocked']) ◯ @else 🞫 @endif</button> -->
                                </td>
                                <td>
                                    <a href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                                </td>
                                <td>
                                    <a class="btn btn-danger ban-user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$result['reporter_id'], $result['id']]) }}" data-name='{{ $users[$result['reporter_id']]['name'] }}'>封鎖</a>
                                </td>
                                <td>
                                    <a href="{{ route('users/advInfo', $result['reported_user_id']) }}" target='_blank' @if($result['isBlockedReceiver']) style="color: #F00;" @endif>{{ $users[$result['reported_user_id']]['name'] }}
                                    @if($users[$result['reported_user_id']]['vip'] )
                                        <i class="m-nav__link-icon fa fa-diamond"></i>
                                    @endif
                                    @if(!is_null($result['isBlockedReceiver']))
                                        @if(!is_null($result['isBlockedReceiver']['expire_date']))
                                            ({{ round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                        @else
                                            (永久)
                                        @endif
                                    @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true, 'reported'] ) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                                </td>
                                <td>
                                    <a class="btn btn-danger ban-user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$result['reported_user_id'], $result['id']]) }}" data-name='{{ $users[$result['reported_user_id']]['name'] }}'>封鎖</a>
                                </td>
                                <td>
                                    <img src="{{ $result['pic'] }}" alt="" height="200px">
                                </td>
                                <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>
                                <td>{{ $result['created_at'] }}</td>
                            </tr>
                        @endforeach
                        @endif
                        @if(isset($Presults))
                            @foreach ($Presults as $result)
                            <tr >
                                <td>
                                    <?php
                                    // var_dump($result['isBlocked']);
                                    ?>
                                    
                                    <a href="{{ route('users/advInfo', $result['reporter_id']) }}" target='_blank' @if($result['isBlocked']) style="color: #F00;" @endif>
                                        {{ $Pusers[$result['reporter_id']]['name'] }}
                                    
                                    @if($Pusers[$result['reporter_id']]['vip'] )
                                        <i class="m-nav__link-icon fa fa-diamond"></i>
                                    @endif
                                    @if(isset($result['isBlocked']))
                                        @if(isset($result['isBlocked']['expire_date']))
                                            ({{ round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                        @else
                                            (永久)
                                        @endif
                                    @endif
                                    </a>
                                    
                                    <!-- <button type="button" onclick="toggleBanned({{ $result['reporter_id'] }});" target="_blank" class='text-white btn @if($result['isBlocked']) btn-success @else btn-danger @endif'>@if($result['isBlocked']) ◯ @else 🞫 @endif</button> -->
                                </td>
                                <td>
                                    <a href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                                </td>
                                <td>
                                    <a class="btn btn-danger ban-user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$result['reporter_id'], $result['id']]) }}" data-name='{{ $Pusers[$result['reporter_id']]['name'] }}'>封鎖</a>
                                </td>
                                <td>
                                    @if(isset($result['reported_user_id']))
                                        <a href="{{ route('users/advInfo', $result['reported_user_id']) }}" target='_blank' @if($result['isBlockedReceiver']) style="color: #F00;" @endif>
                                            {{ $Pusers[$result['reported_user_id']]['name'] }}
                                            @if($Pusers[$result['reported_user_id']]['vip'] )
                                                <i class="m-nav__link-icon fa fa-diamond"></i>
                                            @endif
                                            @if(!is_null($result['isBlockedReceiver']))
                                                @if(!is_null($result['isBlockedReceiver']['expire_date']))
                                                    ({{ round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天)
                                                @else
                                                    (永久)
                                                @endif
                                            @endif
                                        </a>
                                        
                                    @else
                                        照片已刪除或該筆資料不存在。
                                    @endif
                                </td>
                                <td>
                                    @if(isset($result->reported_user_id))
                                        <a href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true, 'reported'] ) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                                    @else
                                        照片已刪除或該筆資料不存在。
                                    @endif
                                </td>
                                <td>
                                    @if(isset($result['reported_user_id']))
                                    
                                        <a class="btn btn-danger ban-user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$result['reported_user_id'], $result['id']]) }}" data-name='{{ $Pusers[$result['reported_user_id']]['name'] }}'>封鎖</a>
                                    @else
                                        照片已刪除或該筆資料不存在。
                                    @endif
                                </td>
                                <td>
                                    <img src="{{ $result['pic'] }}" alt="" height="200px">
                                </td>
                                <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>
                                
                                <td>{{ $result['created_at'] }}</td>
                            </tr>
                        @endforeach
                        @endif
                    </table>
                </form>
            @endif
        @endif
    @endif
    </body>
    <div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">封鎖</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        封鎖時間
                        <select name="days" class="days">
                            <option value="3">三天</option>
                            <option value="7">七天</option>
                            <option value="30">三十天</option>
                            <option value="X" selected>永久</option>
                        </select>
                        <hr>
                        封鎖原因
                        <a class="text-white btn btn-success advertising">廣告</a>
                        <a class="text-white btn btn-success improper-behavior">非徵求包養行為</a>
                        <a class="text-white btn btn-success improper-words">用詞不當</a>
                        <a class="text-white btn btn-success improper-photo">照片不當</a>
                        <br><br>
                        <textarea class="form-control m-reason" name="msg" id="msg" rows="4" maxlength="200">廣告</textarea>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-outline-success ban-user" id="send_blockade" href="" onclick="setDays(this)">送出</a>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
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
            $('.delete-btn').on('click',function(e){
                if(!confirm('確定要刪除選取的訊息?')){
                    e.preventDefault();
                }
            });
            $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
                var data_id = '';
                if (typeof $(this).data('id') !== 'undefined') {
                    data_id = $(this).data('id');
                    $("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
                }
                $("#send_blockade").attr('href', data_id);
            })
            $('.advertising').on('click', function(e) {
                $('.m-reason').val('廣告');
            });
            $('.improper-behavior').on('click', function(e) {
                $('.m-reason').val('非徵求包養行為');
            });
            $('.improper-words').on('click', function(e) {
                $('.m-reason').val('用詞不當');
            });
            $('.improper-photo').on('click', function(e) {
                $('.m-reason').val('照片不當');
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
        function setDays(a) {
            href = a.href;
            let reason = $('.m-reason').val();
            if(reason != '') {
                $('.ban-user').attr("href", href + '/' + $('.days').val() + '&' + reason);
            }else{
                $('.ban-user').attr("href", href + '/' + $('.days').val());
            }
        }
    </script>
@stop