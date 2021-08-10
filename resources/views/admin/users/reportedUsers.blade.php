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
<h1>被檢舉會員清單</h1>
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        <h3 style="text-align: left;">搜尋</h3>
        <form action="{{ route('users/reported') }}" id='message' method='POST'>
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
            <input type="hidden" name="msg" value="@if(isset($msg)) {{ $msg }}@endif" class="form-control" id="msg2">
            <input type='hidden' class="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
            <input type='hidden' class="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
            <h3 style="text-align: left;">搜尋結果</h3>
            <table class="table-hover table table-bordered">
                <tr>
                    <th>被檢舉者</th>
                    <th title="近一月(訊息/會員/照片)">曾被檢舉</th>
                    <th>包養關係</th>
                    <th>回覆被檢舉者(回覆後將會自動移除檢舉記錄)</th>
                    <th>封鎖被檢舉者</th>
                    <th>檢舉者</th>
                    <th>回覆檢舉者(回覆後將會自動移除檢舉記錄)</th>
                    <th>封鎖檢舉者</th>
                    <th>檢舉理由</th>
                    <th>上傳圖片</th>
                    <th>檢舉時間</th>
                </tr>
                @forelse ($results as $result)
                    @if(isset($reported_id))
                        @if ($result['reported_id'] != $reported_id)
                            @continue
                        @endif
                    @endif
                    <tr >
                        <td @if($result['isBlockedReceiver']) style="background-color:#FFFF00" @endif>
                            <a href="{{ route('users/advInfo', $result['reported_id']) }}" target='_blank'>
                                <p @if($users[$result['reported_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                    {{ $users[$result['reported_id']]['name'] }}
                                    @if($users[$result['reported_id']]['vip'])
                                        @if($users[$result['reported_id']]['vip']=='diamond_black')
                                            <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                        @else
                                            @for($z = 0; $z < $users[$result['reported_id']]['vip']; $z++)
                                                <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                            @endfor
                                        @endif
                                    @endif
                                    @if(isset($users[$result['reported_id']]['tipcount']))
                                        @for($i = 0; $i < $users[$result['reported_id']]['tipcount']; $i++)
                                            👍
                                        @endfor
                                    @else
                                        {{ logger('reportedUsers, line 80 tipcount does not exists, user id: ' . $result['reported_id']) }}
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
                                    @if($result['warnedicon']['isAdminWarned']==1 OR $result['warnedicon']['isWarned']==1)
                                        <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                    @endif
                                    @if($result['warnedicon']['isWarned']==0 AND $result['warnedicon']['WarnedScore']>10 AND $result['warnedicon']['auth_status']==1)
                                        <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                    @endif
                                </p>
                            </a> 
                        </td>
                        @if(isset($result['messagesResult']))
                            <td style="white-space:nowrap;font-size:17px;">
                                <a target='_blank' href="/admin/users/message/search/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_id']}}">{{ $result['messagesResult'] }}</a> /
                                <a target='_blank' href="/admin/users/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_id']}}">{{ $result['reportsResult'] }}</a> /
                                <a target='_blank' href="/admin/users/pics/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_id']}}">{{ $result['picsResult'] }}</a>
                            </td>
                        @else
                            <td rowspan="1" style="white-space:nowrap;font-size:17px;">
                                無資料
                            </td>
                            {{ logger('reportedUsers, line 110 messagesResult does not exists, user id: ' . $result['reported_id']) }}
                        @endif
                        <td>
                            @php
                                $exchange_period_name = DB::table('exchange_period_name')->where('id',$users[$result['reported_id']]['exchange_period'])->first();
                            @endphp
                            @if($users[$result['reported_id']]['engroup']=='2')
                                {!! $exchange_period_name->name!!}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('AdminMessengerWithReportedId', [$result->member_id, $result->reported_id, $result->id, 0, 'reported']) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                        </td>
                        <td>
{{--                            <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$result['reported_id'], $result['id'], 'reported'])}}" target="_blank">封鎖</a>--}}
                            @php
                                $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['reported_id'])->get()->first();
                                $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $result['reported_id'])->get()->first();
                                $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                                $data = \App\Models\SimpleTables\warned_users::where('member_id', $result['reported_id'])->first();
                                if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                                    $isAdminWarned = 1;
                                } else {
                                    $isAdminWarned = 0;
                                }
                                $reportedInfo = \App\Models\User::findById($result['reported_id']);
                                $reported_userMeta = \App\Models\UserMeta::where('user_id',$result['reported_id'])->first();
                                $reported_auth_status = 0;
                                if (!is_null($reportedInfo) && $reportedInfo->isPhoneAuth() == 1) {
                                    $reported_auth_status = 1;
                                }
                            @endphp
                            @if(!is_null($reportedInfo))
                                @if($isBlocked)
                                    <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{ $result['reported_id'] }})" data-id="{{ $result['reported_id'] }}">解除封鎖</button>
                                @else
                                    <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $result['reported_id'] }}">封鎖會員</a>
                                    <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $result['reported_id'] }}">隱性封鎖</a>
                                @endif
    {{--                            <a class="btn btn-danger ban-user" href="{{ route('warnedUserWithDayAndMessage', [$result['reported_id'], $result['id']])}}" target="_blank">站方警示</a>--}}
                                @if($isAdminWarned==1)
                                    <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $result['reported_id'] }})" data-id="{{ $result['reported_id'] }}"> 解除站方警示 </button>
                                @else
                                    <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $result['reported_id'] }}">站方警示</a>
                                @endif

                                @if($reported_userMeta->isWarned==0)
                                    <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['reported_id'] }},1)"
                                            @if($reportedInfo->WarnedScore() >= 10 AND $reported_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                        警示用戶({{$reportedInfo->WarnedScore()}})
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['reported_id'] }},0)">
                                        取消警示用戶({{$reportedInfo->WarnedScore()}})
                                    </button>
                                @endif
                            @else
                                會員資料不存在
                            @endif
                        </td>

                        <td @if($result['isBlocked']) style="background-color:#FFFF00" @endif>
                            <a href="{{ route('users/advInfo', $result['member_id']) }}" target='_blank'>
                                @if(isset($users[$result['member_id']]['engroup']))
                                    <p @if($users[$result['member_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                @else
                                    <p>
                                @endif
                                    {{ $users[$result['member_id']]['name'] }}
                                    @if($users[$result['member_id']]['vip'])
                                        @if($users[$result['member_id']]['vip']=='diamond_black')
                                            <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                        @else
                                            @for($z = 0; $z < $users[$result['member_id']]['vip']; $z++)
                                                <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                            @endfor
                                        @endif
                                    @endif
                                    @if(isset($users[$result['member_id']]['tipcount']))
                                        @for($i = 0; $i < $users[$result['member_id']]['tipcount']; $i++)
                                            👍
                                        @endfor
                                    @else
                                        {{ logger('reportedUsers, line 137 tipcount does not exists, user id: ' . $result['member_id']) }}
                                    @endif
                                    @if(!is_null($result['isBlocked']))
                                        @if(!is_null($result['isBlocked']['expire_date']))
                                            @if(isset($result['isBlocked']['expire_date']))
                                                @if(round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24)>0)
                                                    {{ round((strtotime($result['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                                @else
                                                    此會員登入後將自動解除封鎖
                                                @endif
                                            @else
                                                此會員登入後將自動解除封鎖
                                            @endif
                                        @elseif(isset($result['isBlocked']['type']))
                                            (隱性)
                                        @else
                                            (永久)
                                        @endif
                                    @endif
                                    @if($users[$result['member_id']]['warnedicon']['isAdminWarned']==1 OR $users[$result['member_id']]['warnedicon']['isWarned']==1)
                                        <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                    @endif
                                    @if($users[$result['member_id']]['warnedicon']['isWarned']==0 AND $users[$result['member_id']]['warnedicon']['WarnedScore']>10 AND $users[$result['member_id']]['warnedicon']['auth_status']==1)
                                        <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                    @endif
                                </p>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('AdminMessengerWithReportedId', [$result->member_id, $result->reported_id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
                        </td>
                        <td>
{{--                            <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [ $result['member_id'], $result['id'] , 'reported' ] ) }}" target="_blank">封鎖</a>--}}
                            @php
                                $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['member_id'])->get()->first();
                                $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $result['member_id'])->get()->first();
                                $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                                $data = \App\Models\SimpleTables\warned_users::where('member_id', $result['member_id'])->first();
                                if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                                    $isAdminWarned = 1;
                                } else {
                                    $isAdminWarned = 0;
                                }
                                $memberIDInfo = \App\Models\User::findById($result['member_id']);
                                $memberID_userMeta = \App\Models\UserMeta::where('user_id', $result['member_id'])->first();
                                $memberID_auth_status = 0;
                                if (!is_null($memberIDInfo) && $memberIDInfo->isPhoneAuth() == 1) {
                                    $memberID_auth_status = 1;
                                }
                            @endphp

                            @if(!is_null($memberIDInfo))
                                @if($isBlocked)
                                    <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{ $result['member_id'] }})" data-id="{{ $result['member_id'] }}">解除封鎖</button>
                                @else
                                    <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $result['member_id'] }}">封鎖會員</a>
                                    <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $result['member_id'] }}">隱性封鎖</a>
                                @endif
    {{--                            <a class="btn btn-danger ban-user" href="{{ route('warnedUserWithDayAndMessage', [ $result['member_id'], $result['id'] ] ) }}" target="_blank">站方警示</a>--}}
                                @php
                                @endphp
                                @if($isAdminWarned==1)
                                    <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $result['member_id'] }})" data-id="{{ $result['member_id'] }}"> 解除站方警示 </button>
                                @else
                                    <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $result['member_id'] }}">站方警示</a>
                                @endif
                                @if($memberID_userMeta->isWarned==0)
                                    <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['member_id'] }},1)"
                                            @if($memberIDInfo->WarnedScore() >= 10 AND $memberID_auth_status) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                        警示用戶({{$memberIDInfo->WarnedScore()}})
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['member_id'] }},0)">
                                        取消警示用戶({{$memberIDInfo->WarnedScore()}})
                                    </button>
                                @endif
                            @else
                                會員資料不存在
                            @endif
                        </td>

                        <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>
                        <td class="zoomInPic">
                            @php
                                $reportedUserPics=is_null( $result['pic']) ? [] : json_decode($result['pic'],true);
                            @endphp
                            @if(isset($reportedUserPics))
                                @foreach( $reportedUserPics as $reportedUserPic)
                                    <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                        <img src="{{ $reportedUserPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                                    </li>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $result['created_at'] }}</td>
                    </tr>
                @empty
                    沒有資料
                @endforelse
            </table>
        </form>
        @endif
    @endif
@endif

</body>
@php
    $warned_banReason = DB::table('reason_list')->select('content')->where('type', 'warned')->get();
@endphp

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
{{-- <div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <a class="btn btn-outline-success ban-user" id="send_blockade" href="" onclick="setDays(this)">送出</a>
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div> --}}
@php
    $banReason = DB::table('reason_list')->select('content')->where('type', 'ban')->get();
    $implicitly_banReason = DB::table('reason_list')->select('content')->where('type', 'implicitly')->get();
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
{{--                {!! csrf_field() !!}--}}
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
{{--                    <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>--}}
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
{{--                    <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>--}}
                    <button type="button" class='btn btn-outline-success ban-user' id="implicitly_user_submit"> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" id="implicitly_user_cancel">取消</button>
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
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                set_end_date();
            });
        $('.delete-btn').on('click',function(e){
            if(!confirm('確定要刪除選取的訊息?')){
                e.preventDefault();
            }
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

        $(".warned_user").click(function(){
            $("#warnedUserID").val($(this).attr("data-id"));
        });

        $(".block_user").click(function(){
            $("#blockUserID").val($(this).attr("data-id"));
        });

        $(".implicitly_user").click(function(){
            $("#implicitlyUserID").val($(this).attr("data-id"));
        });

        $(".banReason").each( function(){
            $(this).bind("click" , function(){
                var id = $("a").index(this);
                var clickval = $("a").eq(id).text();
                $('.m-reason').val(clickval);
            });
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
                status: isWarned,
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
    // function setDays(a) {
    //     if(count === 0){
    //         let href = a.href;
    //         let reason = $('.m-reason').val();
    //         if(reason != '') {
    //             $('.ban-user').attr("href", href + '/' + $('.days').val() + '&' + reason);
    //         }else{
    //             $('.ban-user').attr("href", href + '/' + $('.days').val() );
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
