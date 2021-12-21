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
                <td>照片上傳</td>
                @endif
                <td>發送時間</td>
                <td width="5%" nowrap>狀態</td>
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
{{--                @if(isset($reported) && $reported == 1)--}}
{{--                <td>--}}
{{--                    <a class="btn btn-danger" href="{{ route('banUserWithDayAndMessage', [$result->from_id, $result->id, 'reported']) }}" target="_blank">封鎖</a>--}}
{{--                </td>--}}
{{--                @endif--}}
                @php
                    $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['from_id'])->get()->first();
                    $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $result['from_id'])->get()->first();
                    $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                    $data = \App\Models\SimpleTables\warned_users::where('member_id', $result['from_id'])->first();
                    if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                        $isAdminWarned = 1;
                    } else {
                        $isAdminWarned = 0;
                    }
                    $fromIDInfo = \App\Models\User::findById($result['from_id']);
                    $fromID_userMeta = \App\Models\UserMeta::where('user_id',$result['from_id'])->first();
                    $fromID_auth_status = 0;
                    if (!is_null($fromIDInfo) && $fromIDInfo->isPhoneAuth() == 1) {
                        $fromID_auth_status = 1;
                    }
                @endphp
                <td>
                    @if(!is_null($fromIDInfo))
                        @if($isBlocked)
                            <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{ $result['from_id'] }})" data-id="{{ $result['from_id'] }}">解除封鎖</button>
                        @else
                            <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $result['from_id'] }}">封鎖會員</a>
                            <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $result['from_id'] }}">隱性封鎖</a>
                        @endif
                        @if($isAdminWarned==1)
                            <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $result['from_id'] }})" data-id="{{ $result['from_id'] }}"> 解除站方警示 </button>
                        @else
                            <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $result['from_id'] }}">站方警示</a>
                        @endif

                        @if($fromID_userMeta->isWarned==0)
                            <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['from_id'] }},1)"
                                    @if($fromIDInfo->WarnedScore() >= 10 AND $fromID_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                警示用戶({{$fromIDInfo->WarnedScore()}})
                            </button>
                        @else
                            <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['from_id'] }},0)">
                                取消警示用戶({{$fromIDInfo->WarnedScore()}})
                            </button>
                        @endif
                    @else
                        會員資料不存在
                    @endif
                </td>
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
{{--                @if(isset($reported) && $reported == 1)--}}
{{--                <td>--}}
{{--                    <a class="btn btn-danger ban-user{{ $key }}" href="{{ route('banUserWithDayAndMessage', [$result->to_id, $result->id]), 'reported' }}" target="_blank">封鎖</a>--}}
{{--                </td>--}}
{{--                @endif--}}
                @php
                    $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['to_id'])->get()->first();
                    $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $result['to_id'])->get()->first();
                    $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                    $data = \App\Models\SimpleTables\warned_users::where('member_id', $result['to_id'])->first();
                    if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                        $isAdminWarned = 1;
                    } else {
                        $isAdminWarned = 0;
                    }
                    $toIDInfo = \App\Models\User::findById($result['to_id']);
                    $toID_userMeta = \App\Models\UserMeta::where('user_id',$result['to_id'])->first();
                    $toID_auth_status = 0;
                    if (!is_null($toIDInfo) && $toIDInfo->isPhoneAuth() == 1) {
                        $toID_auth_status = 1;
                    }
                @endphp
                <td>
                    @if(!is_null($toIDInfo))
                        @if($isBlocked)
                            <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{ $result['to_id'] }})" data-id="{{ $result['to_id'] }}">解除封鎖</button>
                        @else
                            <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $result['to_id'] }}">封鎖會員</a>
                            <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $result['to_id'] }}">隱性封鎖</a>
                        @endif
                        @if($isAdminWarned==1)
                            <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $result['to_id'] }})" data-id="{{ $result['to_id'] }}"> 解除站方警示 </button>
                        @else
                            <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $result['to_id'] }}">站方警示</a>
                        @endif

                        @if($toID_userMeta->isWarned==0)
                            <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['to_id'] }},1)"
                                    @if($toIDInfo->WarnedScore() >= 10 AND $toID_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                警示用戶({{$toIDInfo->WarnedScore()}})
                            </button>
                        @else
                            <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['to_id'] }},0)">
                                取消警示用戶({{$toIDInfo->WarnedScore()}})
                            </button>
                        @endif
                    @else
                        會員資料不存在
                    @endif
                </td>
                <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>
                @if(isset($reported) && $reported == 1)
                <td>{{ $result['reportContent'] }}</td>
                <td class="zoomInPic">
                    @php
                        $reportedMsgPics=is_null( $result['reportContentPic']) ? [] : json_decode($result['reportContentPic'],true);
                    @endphp
                    @if(isset($reportedMsgPics))
                        @foreach( $reportedMsgPics as $reportedMsgPic)
                            <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                <img src="{{ $reportedMsgPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                            </li>
                        @endforeach
                    @endif
                </td>
                @endif
                <td>{{ $result['created_at'] }}</td>
                <td nowrap>{{ $result['unsend']?'已收回':'' }}</td>
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
                <td>上傳照片</td>
                <td>發送時間</td>
                <td width="5%" nowrap>狀態</td>
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
{{--                    <td>--}}
{{--                        <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$sender['id'], $sender['messages'][0]['id'] ]) }}" target="_blank">封鎖</a>--}}
{{--                    </td>--}}
                    @php
                        $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $sender['id'])->get()->first();
                        $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $sender['id'])->get()->first();
                        $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                        $data = \App\Models\SimpleTables\warned_users::where('member_id', $sender['id'])->first();
                        if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                            $isAdminWarned = 1;
                        } else {
                            $isAdminWarned = 0;
                        }
                        $senderInfo = \App\Models\User::findById($sender['id']);
                        $sender_userMeta = \App\Models\UserMeta::where('user_id',$sender['id'])->first();
                        $sender_auth_status = 0;
                        if (!is_null($senderInfo) && $senderInfo->isPhoneAuth() == 1) {
                            $sender_auth_status = 1;
                        }
                    @endphp
                    <td>
                        @if(!is_null($senderInfo))
                            @if($isBlocked)
                                <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{$sender['id'] }})" data-id="{{ $sender['id'] }}">解除封鎖</button>
                            @else
                                <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $sender['id'] }}">封鎖會員</a>
                                <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{$sender['id'] }}">隱性封鎖</a>
                            @endif
                            @if($isAdminWarned==1)
                                <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $sender['id'] }})" data-id="{{ $sender['id'] }}"> 解除站方警示 </button>
                            @else
                                <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $sender['id'] }}">站方警示</a>
                            @endif

                            @if($sender_userMeta->isWarned==0)
                                <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $sender['id'] }},1)"
                                        @if($senderInfo->WarnedScore() >= 10 AND $sender_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                    警示用戶({{$senderInfo->WarnedScore()}})
                                </button>
                            @else
                                <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $sender['id'] }},0)">
                                    取消警示用戶({{$senderInfo->WarnedScore()}})
                                </button>
                            @endif
                        @else
                            會員資料不存在
                        @endif
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
                    <td class="zoomInPic">
                        @php
                            $reportedMsgPics=is_null( $sender['messages'][0]['reportContentPic']) ? [] : json_decode($sender['messages'][0]['reportContentPic'],true);
                        @endphp
                        @if(isset($reportedMsgPics))
                            @foreach( $reportedMsgPics as $reportedMsgPic)
                                <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                    <img src="{{ $reportedMsgPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                                </li>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $sender['messages'][0]['created_at'] }}</td>
                    <td nowrap>{{ $sender['messages'][0]['unsend']?'已收回':'' }}</td>
                    <td style="text-align: center; vertical-align: middle">
                        <input type="checkbox" name="msg_id[]" value="{{ $sender['messages'][0]['id'] }}" class="form-control boxes">
                    </td>
                </tr>
                @if(count($sender['messages']) > 1)
                    @for( $i = 1; $i < count($sender['messages']); $i++) <tr>
                        <td><a href="{{ route('AdminMessengerWithMessageId', [$sender['id'], $sender['messages'][$i]['id']]) }}" target="_blank" class='btn btn-dark'>撰寫</a></td>
{{--                        <td>--}}
{{--                            <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$sender['id'], $sender['messages'][0]['id'] ]) }} " target="_blank">封鎖</a>--}}
{{--                        </td>--}}
                        @php
                            $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $sender['id'])->get()->first();
                            $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $sender['id'])->get()->first();
                            $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                            $data = \App\Models\SimpleTables\warned_users::where('member_id', $sender['id'])->first();
                            if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                                $isAdminWarned = 1;
                            } else {
                                $isAdminWarned = 0;
                            }
                            $senderInfo = \App\Models\User::findById($sender['id']);
                            $sender_userMeta = \App\Models\UserMeta::where('user_id',$sender['id'])->first();
                            $sender_auth_status = 0;
                            if (!is_null($senderInfo) && $senderInfo->isPhoneAuth() == 1) {
                                $sender_auth_status = 1;
                            }
                        @endphp
                        <td>
                            @if(!is_null($senderInfo))
                                @if($isBlocked)
                                    <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{$sender['id'] }})" data-id="{{ $sender['id'] }}">解除封鎖</button>
                                @else
                                    <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $sender['id'] }}">封鎖會員</a>
                                    <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{$sender['id'] }}">隱性封鎖</a>
                                @endif
                                @if($isAdminWarned==1)
                                    <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $sender['id'] }})" data-id="{{ $sender['id'] }}"> 解除站方警示 </button>
                                @else
                                    <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $sender['id'] }}">站方警示</a>
                                @endif

                                @if($sender_userMeta->isWarned==0)
                                    <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $sender['id'] }},1)"
                                            @if($senderInfo->WarnedScore() >= 10 AND $sender_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                        警示用戶({{$senderInfo->WarnedScore()}})
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $sender['id'] }},0)">
                                        取消警示用戶({{$senderInfo->WarnedScore()}})
                                    </button>
                                @endif
                            @else
                                會員資料不存在
                            @endif
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
                        <td class="zoomInPic">
                            @php
                                $reportedMsgPics=is_null($sender['messages'][$i]['reportContentPic']) ? [] : json_decode($sender['messages'][$i]['reportContentPic'],true);
                            @endphp
                            @if(isset($reportedMsgPics))
                                @foreach( $reportedMsgPics as $reportedMsgPic)
                                    <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                        <img src="{{ $reportedMsgPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                                    </li>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $sender['messages'][$i]['created_at'] }}</td>
                        <td nowrap>{{ $sender['messages'][$i]['unsend']?'已收回':'' }}</td>
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
@php
    $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
    $implicitly_banReason = DB::table('reason_list')->select('content')->where('type', 'implicitly')->get();
    $warned_banReason = DB::table('reason_list')->select('content')->where('type', 'warned')->get();
@endphp
<div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
                <input type="hidden" value="" name="user_id" id="blockUserID">
                <input type="hidden" value="noRedirect" name="page">
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
                    @foreach($banReason as $a)
                        <a class="text-white btn btn-success banReason">{{ $a->content }}</a>
                    @endforeach
                    <br><br>
                    <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                    <label style="margin:10px 0px;">
                        <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                        <sapn style="vertical-align:middle;">加入常用封鎖原因</sapn>
                    </label>
                    <hr>
                    新增自動封鎖關鍵字(永久封鎖)
                    <input placeholder="1.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="2.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="3.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-outline-success ban-user' id="block_user_submit"> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" id="block_user_cancel">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="implicitly_blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="implicitly_blockade">隱性封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('banningUserImplicitly') }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" value="" name="user_id" id="implicitlyUserID">
                <input type="hidden" value="BannedInUserInfo" name="fp">
                <input type="hidden" value="noRedirect" name="page">
                <div class="modal-body">
                    隱性封鎖原因
                    @foreach($implicitly_banReason as $a)
                        <a class="text-white btn btn-success banReason">{{ $a->content }}</a>
                    @endforeach
                    <br><br>
                    <textarea class="form-control m-reason" name="reason" id="implicitlyMsg" rows="4" maxlength="200">廣告</textarea>
                    <label style="margin:10px 0px;">
                        <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                        <sapn style="vertical-align:middle;">加入常用隱性封鎖原因</sapn>
                    </label>
                    <hr>
                    新增自動封鎖關鍵字(隱性封鎖)
                    <input placeholder="1.請輸入隱性封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入隱性封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="2.請輸入隱性封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入隱性封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="3.請輸入隱性封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入隱性封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-outline-success ban-user' id="implicitly_user_submit"> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" id="implicitly_user_cancel">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="warned_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warnedModalLabel">站方警示</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/admin/users/toggleUserWarned" method="POST" id="clickToggleUserWarned">
                {!! csrf_field() !!}
                <input type="hidden" value="" name="user_id" id="warnedUserID">
                <input type="hidden" value="noRedirect" name="page">
                <div class="modal-body">
                    警示時間
                    <select name="days" class="days">
                        <option value="3">三天</option>
                        <option value="7">七天</option>
                        <option value="15">十五天</option>
                        <option value="30">三十天</option>
                        <option value="X" selected>永久</option>
                    </select>
                    <hr>
                    警示原因
                    @foreach($warned_banReason as $a)
                        <a class="text-white btn btn-success banReason">{{ $a->content }}</a>
                    @endforeach
                    <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                    <label style="margin:10px 0px;">
                        <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                        <sapn style="vertical-align:middle;">加入常用原因</sapn>
                    </label>
                    <hr>
                    新增自動封鎖關鍵字(警示)
                    <input placeholder="1.請輸入警示關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入警示關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="2.請輸入警示關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入警示關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                    <input placeholder="3.請輸入警示關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入警示關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-outline-success ban-user' id="warned_user_submit"> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" id="warned_user_cancel">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--照片查看-->
<div class="big_img">
    <!-- 自定义分页器 -->
    <div class="swiper-num">
        <span class="active"></span>/
        <span class="total"></span>
    </div>
    <div class="swiper-container2">
        <div class="swiper-wrapper">
        </div>
    </div>
    <div class="swiper-pagination2"></div>
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

        $(".unblock_user").click(function(){
            var data = $(this).data();
            if(confirm('確定解除封鎖此會員?')){
                $.ajax({
                    type: 'POST',
                    url: "/admin/users/unblock_user",
                    data:{
                        _token: '{{csrf_token()}}',
                        data: data,
                    },
                    dataType:"json",
                    success: function(res){
                        if(res.code ==200){
                            alert('解除封鎖成功');
                        }else{
                            alert('解除封鎖失敗');
                        }
                        location.reload();
                    }});
            }
            else{
                return false;
            }
        });

        $("#block_user_submit").click(function(){
            $("#block_user_cancel").click();
            let data = $("#clickToggleUserBlock").serializeArray();
            var days='';
            var reason='';
            var addreason='';
            var addautoban= [];
            for(var i=0; i<data.length; i++) {
                if(data[i]['name'] =='days')
                    days= data[i]['value'];
                else if(data[i]['name'] =='reason')
                    reason= data[i]['value'];
                else if(data[i]['name'] =='addreason')
                    addreason= data[i]['value'];
                else if(data[i]['name'] =='addautoban[]')
                    addautoban.push(data[i]['value']);
            }
            $.ajax({
                type: 'POST',
                url: "/admin/users/toggleUserBlock",
                data:{
                    _token: '{{csrf_token()}}',
                    user_id: $("#blockUserID").val(),
                    page: 'noRedirect',
                    days: days,
                    reason: reason,
                    addreason: addreason,
                    addautoban: addautoban
                },
                dataType:"json",
                success: function(res){

                    if(res.code ==200){
                        alert('封鎖成功');
                    }else{
                        alert('封鎖失敗');
                    }
                    location.reload();
                }
            });
        });
        $(".unwarned_user").click(function(){
            var data = $(this).data();
            if(confirm('確定解除此會員站方警示?')){
                $.ajax({
                    type: 'POST',
                    url: "/admin/users/unwarned_user",
                    data:{
                        _token: '{{csrf_token()}}',
                        data: data,
                    },
                    dataType:"json",
                    success: function(res){
                        alert('已解除站方警示');
                        location.reload();
                    }});
            }
            else{
                return false;
            }
        });
        $("#warned_user_submit").click(function(){
            $("#warned_user_cancel").click();
            let data = $("#clickToggleUserWarned").serializeArray();
            var days='';
            var reason='';
            var addreason='';
            var addautoban= [];
            for(var i=0; i<data.length; i++) {
                if(data[i]['name'] =='days')
                    days= data[i]['value'];
                else if(data[i]['name'] =='reason')
                    reason= data[i]['value'];
                else if(data[i]['name'] =='addreason')
                    addreason= data[i]['value'];
                else if(data[i]['name'] =='addautoban[]')
                    addautoban.push(data[i]['value']);
            }
            $.ajax({
                type: 'POST',
                url: "/admin/users/toggleUserWarned",
                data:{
                    _token: '{{csrf_token()}}',
                    user_id: $("#warnedUserID").val(),
                    page: 'noRedirect',
                    days: days,
                    reason: reason,
                    addreason: addreason,
                    addautoban: addautoban
                },
                dataType:"json",
                success: function(res){

                    if(res.code ==200){
                        alert('站方警示成功');
                    }else{
                        alert('站方警示失敗');
                    }
                    location.reload();
                }
            });
        });
        $("#implicitly_user_submit").click(function(){
            $("#implicitly_user_cancel").click();
            let data = $("#implicitly_blockade").serializeArray();
            var reason='';
            var addreason='';
            var addautoban= [];
            for(var i=0; i<data.length; i++) {
                if(data[i]['name'] =='reason')
                    reason= data[i]['value'];
                else if(data[i]['name'] =='addreason')
                    addreason= data[i]['value'];
                else if(data[i]['name'] =='addautoban[]')
                    addautoban.push(data[i]['value']);
            }
            $.ajax({
                type: 'POST',
                url: "/admin/users/bans_implicitly",
                data:{
                    _token: '{{csrf_token()}}',
                    user_id: $("#implicitlyUserID").val(),
                    fp: 'BannedInUserInfo',
                    page: 'noRedirect',
                    reason: $("#implicitlyMsg").val(),
                    addreason: addreason,
                    addautoban: addautoban
                },
                dataType:"json",
                success: function(res){
                    if(res.code ==200){
                        alert('隱性封鎖成功');
                    }else{
                        alert('隱性封鎖失敗');
                    }
                    location.reload();
                }
            });
        });
        $(".block_user").click(function(){
            $("#blockUserID").val($(this).attr("data-id"));
        });
        $(".implicitly_user").click(function(){
            $("#implicitlyUserID").val($(this).attr("data-id"));
        });
        $(".warned_user").click(function(){
            $("#warnedUserID").val($(this).attr("data-id"));
        });

        $(".banReason").each( function(){
            $(this).bind("click" , function(){
                var id = $("a").index(this);
                var clickval = $("a").eq(id).text();
                $('.m-reason').val(clickval);
            });
        });
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
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script>
    $(document).ready(function () {
        /*调起大图 S*/
        var mySwiper = new Swiper('.swiper-container2',{
            pagination : '.swiper-pagination2',
            paginationClickable:true,
            onInit: function(swiper){//Swiper初始化了
                // var total = swiper.bullets.length;
                var active =swiper.activeIndex;
                $(".swiper-num .active").text(active);
                // $(".swiper-num .total").text(total);
            },
            onSlideChangeEnd: function(swiper){
                var active =swiper.realIndex +1;
                $(".swiper-num .active").text(active);
            }
        });

        $(".zoomInPic li").on("click",
            function () {
                var imgBox = $(this).parent(".zoomInPic").find("li");
                var i = $(imgBox).index(this);
                $(".big_img .swiper-wrapper").html("")

                for (var j = 0, c = imgBox.length; j < c ; j++) {
                    $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
                }
                mySwiper.updateSlidesSize();
                mySwiper.updatePagination();
                $(".big_img").css({
                    "z-index": 1001,
                    "opacity": "1"
                });
                //分页器
                var num = $(".swiper-pagination2 span").length;
                $(".swiper-num .total").text(num);
                // var active =$(".swiper-pagination2").index(".swiper-pagination-bullet-active");
                $(".swiper-num .active").text(i + 1);
                // console.log(active)

                mySwiper.slideTo(i, 0, false);
                return false;
            });
        $(".swiper-container2").click(function(){
            $(this).parent(".big_img").css({
                "z-index": "-1",
                "opacity": "0"
            });
        });

    });
    /*调起大图 E*/
</script>
@stop