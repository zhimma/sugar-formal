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

<body style="padding: 15px;">
    <h1>會員訊息管理</h1>
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
                    <input type="radio" name="time" value="created_at" @if(isset($time) && $time=='created_at' ) checked="true" @endif />註冊時間
                    <input type="radio" name="time" value="login_time" @if(isset($time) && $time=='login_time' ) checked="true" @endif />上線時間
                    <input type="radio" name="time" value="send_time" @if(isset($time) && $time=='send_time' ) checked="true" @endif />發訊時間
                </td>
            </tr>
            <tr>
                <th>排序方式2</th>
                <td>
                    <input type="radio" name="member_type" value="vip" @if(isset($member_type) && $member_type=='vip' ) checked="true" @endif />VIP會員
                    <input type="radio" name="member_type" value="banned" @if(isset($member_type) && $member_type=='banned' ) checked="true" @endif />Banned List會員
                </td>
            </tr>
            <tr>
                <td colspan="2"><button class='text-white btn btn-primary submit'>搜尋符合條件的訊息</button> 或 <a id="reported_meg" href="{{ route('users/message/search/reported') }}" class="btn btn-info">檢視所有被檢舉訊息</a></td>
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
                <th>曾被檢舉</th>
                <td>回覆發送者</td>
                @if(isset($reported) && $reported == 1)
                <td>封鎖發送者</td>
                @endif
                <td>收訊者</td>
                <td>回覆收訊者</td>
                @if(isset($reported) && $reported == 1)
                <td>封鎖收訊者</td>
                @endif
                <td>內容</td>
                @if(isset($reported) && $reported == 1)
                <td>檢舉理由</td>
                @endif
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
            @forelse ($results as $key => $result)
            @if(isset($reported_id))
                @if ($result['from_id'] != $reported_id)
                    @continue
                @endif
            @endif
            <tr>
                <td @if($result['isBlocked']) style="background-color:#FFFF00" @endif>
                    <a href="{{ route('users/advInfo', $result['from_id']) }}" target='_blank' >
                        <p  @if($users[$result['from_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                            {{ $users[$result['from_id']]['name'] }}
                            @if($users[$result['from_id']]['vip'])
                                @if($users[$result['from_id']]['vip']=='diamond_black')
                                    <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                @else
                                    @for($z = 0; $z < $users[$result['from_id']]['vip']; $z++)
                                        <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                    @endfor
                                @endif
                            @endif
                            @if(isset($users[$result['from_id']]['tipcount']))
                                @for($z = 0; $z < $users[$result['from_id']]['tipcount']; $z++)
                                    👍
                                @endfor
                            @else
                                {{ logger('searchMessage, line 112 tipcount does not exists.') }}
                            @endif
                            @if(!is_null($result['isBlocked']))
                                @if(!is_null($result['isBlocked']['expire_date']))
                                    @if(round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24)>0)
                                        {{ round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                    @else
                                        此會員登入後將自動解除封鎖
                                    @endif
                                @elseif(isset($result['isBlocked']['type']))
                                    (隱性)
                                @else
                                    (永久)
                                @endif
                            @endif
                        </p>
                    </a>
                </td>
                <td style="white-space:nowrap;font-size:17px;">
                    <a target='_blank' 
                    	href="/admin/users/message/search/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['from_id']}}">
                    	{{ $result['messagesResult'] }}
                    </a> /
                    <a target='_blank' 
                    	href="/admin/users/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['from_id']}}">
                    	{{ $result['reportsResult'] }}
                    </a> /
                    <a target='_blank' 
                    	href="/admin/users/pics/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['from_id']}}">
                    	{{ $result['picsResult'] }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('AdminMessengerWithMessageId', [$result->from_id, $result->id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                </td>
                @if(isset($reported) && $reported == 1)
                <td>
                    <a class="btn btn-danger" href="{{ route('banUserWithDayAndMessage', [$result->from_id, $result->id, 'reported']) }}" target="_blank">封鎖</a>
                </td>
                @endif
                <td @if($result['isBlockedReceiver']) style="background-color:#FFFF00" @endif>
                    <a href="{{ route('users/advInfo', $result['to_id']) }}" target='_blank'>
                        <p @if($users[$result['to_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                            {{ $users[$result['to_id']]['name'] }}
                            @if($users[$result['to_id']]['vip'])
                                @if($users[$result['to_id']]['vip']=='diamond_black')
                                    <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                @else
                                    @for($z = 0; $z < $users[$result['to_id']]['vip']; $z++)
                                        <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                    @endfor
                                @endif
                            @endif
                            @if(isset($users[$result['to_id']]['tipcount']))
                                @for($z = 0; $z < $users[$result['to_id']]['tipcount']; $z++)
                                    👍
                                @endfor
                            @else
                                {{ logger('searchMessage, line 162 tipcount does not exists.') }}
                            @endif
                            @if(!is_null($result['isBlockedReceiver']))
                                @if(!is_null($result['isBlockedReceiver']['expire_date']))
                                    @if(round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24)>0)
                                        {{ round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                    @else
                                        此會員登入後將自動解除封鎖
                                    @endif
                                @elseif(isset($result['isBlockedReceiver']['type']))
                                    (隱性)
                                @else
                                    (永久)
                                @endif
                            @endif
                        </p>
                    </a>
                </td>
                <td>
                    <a href="{{ route('AdminMessengerWithMessageId', [$result->to_id, $result->id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                </td>
                @if(isset($reported) && $reported == 1)
                <td>
                    <a class="btn btn-danger ban-user{{ $key }}" href="{{ route('banUserWithDayAndMessage', [$result->to_id, $result->id]), 'reported' }}" target="_blank">封鎖</a>
                </td>
                @endif
                <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>
                @if(isset($reported) && $reported == 1)
                <td>{{ $result['reportContent'] }}</td>
                @endif
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
                <th>曾被檢舉</th>
                <td>回覆發送者</td>
                <td>封鎖發送者</td>
                <td>註冊時間</td>
                <td>上線時間</td>
                <td>收訊者</td>
                <td>內容</td>
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
                <tr>
                    <td rowspan="{{ count($sender['messages']) }}" @if($sender['isBlocked']) style="background-color:#FFFF00" @endif>
                        <a href="{{ route('users/advInfo', $sender['id']) }}" target='_blank' >
                            <p @if($sender['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $sender['name'] }}
                                @if($sender['vip'])
                                    @if($sender['vip']=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $sender['vip']; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($z = 0; $z < $sender['tipcount']; $z++)
                                    👍
                                @endfor
                                @if(!is_null($sender['isBlocked']))
                                    @if(!is_null($sender['isBlocked']['expire_date']))
                                        @if(round((strtotime($sender['isBlocked']['expire_date']) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($sender['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($sender['isBlocked']['type']))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a>
                    </td>
                    <td rowspan="{{ count($sender['messages']) }}" style="white-space:nowrap;font-size:17px;">
                        <a target='_blank' 
                        	href="/admin/users/message/search/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$sender['id']}}">
                        	{{ $sender['messagesResult'] }}
                        </a> /
                        <a target='_blank' 
                        href="/admin/users/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$sender['id']}}">
                    		{{ $sender['reportsResult'] }}
                    	</a> /
                        <a target='_blank' 
                        	href="/admin/users/pics/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$sender['id']}}">
                        	{{ $sender['picsResult'] }}
                        </a>
                    </td>
                    <td><a href="{{ route('AdminMessengerWithMessageId', [$sender['id'], $sender['messages'][0]['id'] ]) }}" target="_blank" class='btn btn-dark'>撰寫</a></td>
                    <td>
                        <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$sender['id'], $sender['messages'][0]['id'] ]) }}" target="_blank">封鎖</a>
                    </td>
                    <td rowspan="{{ count($sender['messages']) }}">{{ $sender['created_at'] }}</td>
                    <td rowspan="{{ count($sender['messages']) }}">{{ $sender['last_login'] }}</td>
                    <td @if($receivers[$sender['messages'][0]['to_id']]['isBlockedReceiver']) style="background-color:#FFFF00" @endif>
                        <a href="{{ route('users/advInfo', $sender['messages'][0]['to_id']) }}" target='_blank' >
                            @if(isset($receivers[$sender['messages'][0]['to_id']]['engroup']))
                                <p @if($receivers[$sender['messages'][0]['to_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                            @else
                                <p>此會員資料已佚失
                            @endif
                                {{ $receivers[$sender['messages'][0]['to_id']]['name'] }}
                                @if($receivers[$sender['messages'][0]['to_id']]['vip'])
                                    @if($receivers[$sender['messages'][0]['to_id']]['vip']=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $receivers[$sender['messages'][0]['to_id']]['vip']; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($z = 0; $z < $receivers[$sender['messages'][0]['to_id']]['tipcount']; $z++)
                                    👍
                                @endfor
                                @if(!is_null($receivers[$sender['messages'][0]['to_id']]['isBlockedReceiver']))
                                    @if(!is_null($receivers[$sender['messages'][0]['to_id']]['isBlockedReceiver']['expire_date']))
                                        @if(round((strtotime($receivers[$sender['messages'][0]['to_id']]['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($receivers[$sender['messages'][0]['to_id']]['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($receivers[$sender['messages'][0]['to_id']]['isBlockedReceiver']['type']))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a>
                    </td>
                    <td width="45%">{{ $sender['messages'][0]['content'] }}</td>
                    <td>{{ $sender['messages'][0]['created_at'] }}</td>
                    <td style="text-align: center; vertical-align: middle">
                        <input type="checkbox" name="msg_id[]" value="{{ $sender['messages'][0]['id'] }}" class="form-control boxes">
                    </td>
                </tr>
                @if(count($sender['messages']) > 1)
                    @for( $i = 1; $i < count($sender['messages']); $i++) <tr>
                        <td><a href="{{ route('AdminMessengerWithMessageId', [$sender['id'], $sender['messages'][$i]['id']]) }}" target="_blank" class='btn btn-dark'>撰寫</a></td>
                        <td>
                            <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$sender['id'], $sender['messages'][0]['id'] ]) }} " target="_blank">封鎖</a>
                        </td>
                        <td @if($receivers[$sender['messages'][$i]['to_id']]['isBlockedReceiver']) style="background-color:#FFFF00" @endif>
                            <a href="{{ route('users/advInfo', $sender['messages'][$i]['to_id']) }}" target='_blank' >
                                <p @if($receivers[$sender['messages'][$i]['to_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                    {{ $receivers[$sender['messages'][$i]['to_id']]['name'] }}
                                    @if($receivers[$sender['messages'][$i]['to_id']]['vip'])
                                        @if($receivers[$sender['messages'][$i]['to_id']]['vip']=='diamond_black')
                                            <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                        @else
                                            @for($z = 0; $z < $receivers[$sender['messages'][$i]['to_id']]['vip']; $z++)
                                                <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                            @endfor
                                        @endif
                                    @endif
                                    @for($z = 0; $z < $receivers[$sender['messages'][$i]['to_id']]['tipcount']; $z++)
                                        👍
                                    @endfor
                                    @if(!is_null($receivers[$sender['messages'][$i]['to_id']]['isBlockedReceiver']))
                                        @if(!is_null($receivers[$sender['messages'][$i]['to_id']]['isBlockedReceiver']['expire_date']))
                                            @if(round((strtotime($receivers[$sender['messages'][$i]['to_id']]['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24)>0)
                                                {{ round((strtotime($receivers[$sender['messages'][$i]['to_id']]['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                            @else
                                                此會員登入後將自動解除封鎖
                                            @endif
                                        @elseif(isset($receivers[$sender['messages'][$i]['to_id']]['isBlockedReceiver']))
                                            (隱性)
                                        @else
                                            (永久)
                                        @endif
                                    @endif
                                <p>
                            </a>
                        </td>
                        <td width="45%">{{ $sender['messages'][$i]['content'] }}</td>
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
                        <option value="15">十五天</option>
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
                <a class="btn btn-outline-success ban-user" id="send_blockade" href="" onclick="setDays(this, '')">送出</a>
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