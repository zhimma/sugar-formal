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
                <input type='hidden' class="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($date_start)){{ $date_start }}@endif" class="form-control">
                <input type='hidden' class="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($date_end)){{ $date_end }}@endif" class="form-control">
                <h3 style="text-align: left;">搜尋結果</h3>
                <table class="table-hover table table-bordered">
                    <tr>
                        <th>被檢舉者</th>
                        <th>曾被檢舉</th>
                        <th>回覆被檢舉者</th>
                        <th>封鎖被檢舉者</th>
                        <th>檢舉者</th>
                        <th>回覆檢舉者</th>
                        <th>封鎖檢舉者</th>
                        <th>圖片</th>
                        <th>刪除照片</th>
                        <th>檢舉理由</th>
                        <th>檢舉時間</th>
                    </tr>
                    <?php $rowIndex = 0; ?>
                    @if(isset($results))
                        @foreach ($results as $rowIndex=>$result)
                            @if(isset($reported_id))
                                @if ($result['reported_user_id'] != $reported_id)
                                    @continue
                                @endif
                            @endif
                        <? $rowIndex += 1; ?>
                        <tr >
                            <td @if($result['isBlockedReceiver']) style="background-color:#FFFF00" @endif>
                                <a href="{{ route('users/advInfo', $result['reported_user_id']) }}" target='_blank'>
                                    <p @if($users[$result['reported_user_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                        {{ $users[$result['reported_user_id']]['name'] }}
                                        @if($users[$result['reported_user_id']]['vip'])
                                            @if($users[$result['reported_user_id']]['vip']=='diamond_black')
                                                <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                            @else
                                                @for($z = 0; $z < $users[$result['reported_user_id']]['vip']; $z++)
                                                    <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                                @endfor
                                            @endif
                                        @endif
                                        @if(isset($users[$result['reported_user_id']]['tipcount']))
                                            @for($i = 0; $i < $users[$result['reported_user_id']]['tipcount']; $i++)
                                                👍
                                            @endfor
                                        @else
                                            {{ logger('reportedPics, line 78 tipcount does not exists.') }}
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
                                        @if($result['warnedicon']['isWarned']==0 AND $result['warnedicon']['WarnedScore']>=10 AND $result['warnedicon']['auth_status']==1)
                                            <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                        @endif
                                    </p>
                                </a>
                            </td>
                            <td style="white-space:nowrap;font-size:17px;">
                                <a target='_blank' 
                                    href="/admin/users/message/search/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_user_id']}}">
                                    {{ $result['messagesResult'] }}
                                </a> /
                                <a target='_blank' 
                                    href="/admin/users/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_user_id']}}">
                                    {{ $result['reportsResult'] }}
                                </a> /
                                <a target='_blank' 
                                    href="/admin/users/pics/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_user_id']}}">
                                    {{ $result['picsResult'] }}
                                </a>
                            </td>
                            <td>
                                <a class='btn btn-dark' href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true]) }}" target="_blank" >撰寫</a>
                            </td>
                            <td>
{{--                                @if(isset($result['reporter_id']))--}}
{{--                                    <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$result['reported_user_id'], $result['id']]) }}" target="_blank">封鎖</a>--}}
{{--                                @else--}}
{{--                                    被檢舉者資料已不存在--}}
{{--                                @endif--}}
                                @php
                                    $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['reported_user_id'])->get()->first();
                                    $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $result['reported_user_id'])->get()->first();
                                    $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                                    $data = \App\Models\SimpleTables\warned_users::where('member_id', $result['reported_user_id'])->first();
                                    if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                                        $isAdminWarned = 1;
                                    } else {
                                        $isAdminWarned = 0;
                                    }
                                    $reportedInfo = \App\Models\User::findById($result['reported_user_id']);
                                    $reported_userMeta = \App\Models\UserMeta::where('user_id',$result['reported_user_id'])->first();
                                    $reported_auth_status = 0;
                                    if (!is_null($reportedInfo) && $reportedInfo->isPhoneAuth() == 1) {
                                        $reported_auth_status = 1;
                                    }
                                @endphp
                                @if(!is_null($reportedInfo))
                                    @if($isBlocked)
                                        <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{ $result['reported_user_id'] }})" data-id="{{ $result['reported_user_id'] }}">解除封鎖</button>
                                    @else
                                        <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $result['reported_user_id'] }}">封鎖會員</a>
                                        <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $result['reported_user_id'] }}">隱性封鎖</a>
                                    @endif
                                    @if($isAdminWarned==1)
                                        <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $result['reported_user_id'] }})" data-id="{{ $result['reported_user_id'] }}"> 解除站方警示 </button>
                                    @else
                                        <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $result['reported_user_id'] }}">站方警示</a>
                                    @endif

                                    @if($reported_userMeta->isWarned==0)
                                        <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['reported_user_id'] }},1)"
                                                @if($reportedInfo->WarnedScore() >= 10 AND $reported_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                            警示用戶({{$reportedInfo->WarnedScore()}})
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['reported_user_id'] }},0)">
                                            取消警示用戶({{$reportedInfo->WarnedScore()}})
                                        </button>
                                    @endif
                                @else
                                    會員資料不存在
                                @endif
                            </td>
                            <td @if($result['isBlocked']) style="background-color:#FFFF00" @endif>
                                <a href="{{ route('users/advInfo', $result['reporter_id']) }}" target='_blank'>
                                    @if(isset($users[$result['reporter_id']]['engroup']))
                                        <p @if($users[$result['reporter_id']]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif data-eng="{{$users[$result['reporter_id']]['engroup']}}">
                                    @else
                                        <p>
                                    @endif
                                        {{ $users[$result['reporter_id']]['name'] }}
                                        @if($users[$result['reporter_id']]['vip'])
                                            @if($users[$result['reporter_id']]['vip']=='diamond_black')
                                                <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                            @else
                                                @for($z = 0; $z < $users[$result['reporter_id']]['vip']; $z++)
                                                    <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                                @endfor
                                            @endif
                                        @endif
                                        @if(isset($users[$result['reporter_id']]['tipcount']))
                                            @for($i = 0; $i < $users[$result['reporter_id']]['tipcount']; $i++)
                                                👍
                                            @endfor
                                        @else
                                            {{ logger('reportedPics, line 134 tipcount does not exists, user id: ' . $result['reporter_id']) }}
                                        @endif
                                        @if(!is_null($result['isBlocked']))
					                        @if(isset($result['isBlockedReceiver']['expire_date']))
						                        @if(!is_null($result['isBlockedReceiver']['expire_date']))
                                                    @if(round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24)>0)
                                                        {{ round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                                    @else
                                                        此會員登入後將自動解除封鎖
                                                    @endif

                                            	@else
                                                    (永久)
                                                @endif
                                            @elseif(isset($result['isBlockedReceiver']['type']))
                                                (隱性)
					                        @else
                                                無資料
                                            @endif
                                        @endif
                                        @if($users[$result['reporter_id']]['warnedicon']['isAdminWarned']==1 OR $users[$result['reporter_id']]['warnedicon']['isWarned']==1)
                                            <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                        @endif
                                        @if($users[$result['reporter_id']]['warnedicon']['isWarned']==0 AND $users[$result['reporter_id']]['warnedicon']['WarnedScore']>10 AND $users[$result['reporter_id']]['warnedicon']['auth_status']==1)
                                            <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                        @endif
                                    </p>
                                </a>
                            </td>
                            <td>
                                <a class='btn btn-dark' href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true]) }}" target="_blank" >撰寫</a>
                            </td>
                            <td>
{{--                                @if(isset($result['reporter_id']))--}}
{{--                                    <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$result['reporter_id'], $result['id']]) }}" target="_blank">封鎖</a>--}}
{{--                                @else--}}
{{--                                    檢舉者資料已不存在--}}
{{--                                @endif--}}
                                @php
                                    $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['reporter_id'])->get()->first();
                                    $isBlocked = is_null($banned_users) ? 0 : 1;

                                    $banned_users =  \App\Models\SimpleTables\banned_users::where('member_id', 'like', $result['reporter_id'])->get()->first();
                                    $implicitly_users = \App\Models\BannedUsersImplicitly::where('target', $result['reporter_id'])->get()->first();
                                    $isBlocked = is_null($banned_users) && is_null($implicitly_users) ? 0 : 1;

                                    $data = \App\Models\SimpleTables\warned_users::where('member_id', $result['reporter_id'])->first();
                                    if (isset($data) && ($data->expire_date == null || $data->expire_date >= \Carbon\Carbon::now())) {
                                        $isAdminWarned = 1;
                                    } else {
                                        $isAdminWarned = 0;
                                    }
                                    $reporterInfo = \App\Models\User::findById($result['reporter_id']);
                                    $reporter_userMeta = \App\Models\UserMeta::where('user_id',$result['reporter_id'])->first();
                                    $reporter_auth_status = 0;
                                    if (!is_null($reporterInfo) && $reporterInfo->isPhoneAuth() == 1) {
                                        $reporter_auth_status = 1;
                                    }
                                @endphp
                                @if(!is_null($reporterInfo))
                                    @if($isBlocked)
                                        <button type="button" class='unblock_user text-white btn @if($isBlocked) btn-success @else btn-danger @endif' onclick="Release({{ $result['reporter_id'] }})" data-id="{{ $result['reporter_id'] }}">解除封鎖</button>
                                    @else
                                        <a class="btn btn-danger ban-user block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $result['reporter_id'] }}">封鎖會員</a>
                                        <a class="btn btn-danger ban-user implicitly_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $result['reporter_id'] }}">隱性封鎖</a>
                                    @endif
                                    @if($isAdminWarned==1)
                                        <button type="button" title="站方警示與自動封鎖的警示，只能經後台解除" class='unwarned_user text-white btn @if($isAdminWarned) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $result['reporter_id'] }})" data-id="{{ $result['reporter_id'] }}"> 解除站方警示 </button>
                                    @else
                                        <a class="btn btn-danger warned-user warned_user" title="站方警示與自動封鎖的警示，只能經後台解除" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $result['reporter_id'] }}">站方警示</a>
                                    @endif

                                    @if($reporter_userMeta->isWarned==0)
                                        <button type="button" class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['reporter_id'] }},1)"
                                                @if($reporterInfo->WarnedScore() >= 10 AND $reporter_auth_status==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
                                            警示用戶({{$reporterInfo->WarnedScore()}})
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{ $result['reporter_id'] }},0)">
                                            取消警示用戶({{$reporterInfo->WarnedScore()}})
                                        </button>
                                    @endif
                                @else
                                    會員資料不存在
                                @endif
                            </td>
                            @if(!is_null($result['pic']))
                                <td>
                                    <img src="{{ $result['pic'] }}" alt="此照片已刪除或不存在" height="200px">
                                </td>
                                <td>
                                    <form id="Form" action="/admin/users/pictures/modify" method="POST" target="_blank">
                                        {!! csrf_field() !!}
                                        <input class="btn btn-danger btn-delete" type="submit" value="刪除"><br>
                                        <input type="hidden" name="delete" value="true">
                                        <input type="hidden" name="avatar_id" value="{{$result['reported_user_id']}}">
                                        @foreach($picReason as $a)
                                            <input type="radio" name="reason[{{$rowIndex}}]" value="{{ $a->content }}">{{ $a->content }}<br>
                                        @endforeach
                                        其他: <input type="text" name="otherReason"><br>
                                        <input type="checkbox" name="addreason">加入常用原因
                                    </form>
                                </td>
                            @else
                                <td>
                                    此會員無上傳照片
                                </td>
                                <td>
                                </td>
                            @endif
                            <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>
                            <td>{{ $result['created_at'] }}</td>
                        </tr>
                        @endforeach
                    @endif
                    @if(isset($Presults))
                        @foreach ($Presults as $result)
                            @if(isset($reported_id))
                                @if ($result['reported_user_id'] != $reported_id)
                                    @continue
                                @endif
                            @endif
                        <? $rowIndex += 1; ?>
                        <tr >
                            <td>
                                @if(isset($result['reported_user_id']))
                                    <a href="{{ route('users/advInfo', $result['reported_user_id']) }}" target='_blank' @if($result['isBlockedReceiver']) style="color: #F00;" @endif>
                                        {{ $Pusers[$result['reported_user_id']]['name'] }}
                                        @if($Pusers[$result['reported_user_id']]['vip'])
                                            @if($Pusers[$result['reported_user_id']]['vip']=='diamond_black')
                                                <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                            @else
                                                @for($z = 0; $z < $Pusers[$result['reported_user_id']]['vip']; $z++)
                                                    <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                                @endfor
                                            @endif
                                        @endif
                                        @if(isset($Pusers[$result['reported_user_id']]['tipcount']))
                                            @for($i = 0; $i < $Pusers[$result['reported_user_id']]['tipcount']; $i++)
                                                👍
                                            @endfor
                                        @else
                                            {{ logger('reportedPics, line 218 tipcount does not exists.') }}
                                        @endif
                                        @if(!is_null($result['isBlockedReceiver']))
					                        @if(isset($result['isBlockedReceiver']['expire_date']))
                                                @if(!is_null($result['isBlockedReceiver']['expire_date']))
                                                    @if(round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24)>0)
                                                        {{ round((strtotime($result['isBlockedReceiver']['expire_date']) - getdate()[0])/3600/24 ) }}天
                                                    @else
                                                        此會員登入後將自動解除封鎖
                                                    @endif
                                                @else
                                                    此會員登入後將自動解除封鎖
                                                @endif
                                            @elseif(isset($result['isBlockedReceiver']['type']))
                                                (隱性)
                                            @else
                                                (永久)
                                            @endif
                                        @endif
                                        @if($Pusers[$result['reported_user_id']]['warnedicon']['isAdminWarned']==1 OR $Pusers[$result['reported_user_id']]['warnedicon']['isWarned']==1)
                                            <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                        @endif
                                        @if($Pusers[$result['reported_user_id']]['warnedicon']['isWarned']==0 AND $Pusers[$result['reported_user_id']]['warnedicon']['WarnedScore']>10 AND $Pusers[$result['reported_user_id']]['warnedicon']['auth_status']==1)
                                            <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                        @endif
                                    </a>

                                @else
                                    照片已刪除或該筆資料不存在。
                                @endif
                            </td>
                            <td style="white-space:nowrap;font-size:17px;">
                                <a target='_blank' 
                                    href="/admin/users/message/search/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_user_id']}}">
                                    {{ $result['messagesResult'] }}
                                </a> /
                                <a target='_blank' 
                                    href="/admin/users/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_user_id']}}">
                                    {{ $result['reportsResult'] }}
                                </a> /
                                <a target='_blank' 
                                    href="/admin/users/pics/reported/{{date('Y-m-d', strtotime('-1 month'))}}/{{date('Y-m-d',time())}}/{{$result['reported_user_id']}}">
                                    {{ $result['picsResult'] }}
                                </a>
                            </td>
                            <td>
                                <a target="_blank" class='btn btn-dark' href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true, 'reported'] ) }}"  >撰寫</a>
                            </td>
                            <td>
                                @if(isset($result['reported_user_id']))
                                    <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$result['reported_user_id'], $result['id']]) }}" target="_blank">封鎖</a>
                                @else
                                    被檢舉者資料已不存在
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users/advInfo', $result['reporter_id']) }}" target='_blank' @if($result['isBlocked']) style="color: #F00;" @endif>
                                    {{ $Pusers[$result['reporter_id']]['name'] }}
                                    @if($Pusers[$result['reporter_id']]['vip'])
                                        @if($Pusers[$result['reporter_id']]['vip']=='diamond_black')
                                            <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                        @else
                                            @for($z = 0; $z < $Pusers[$result['reporter_id']]['vip']; $z++)
                                                <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                            @endfor
                                        @endif
                                    @endif
                                    @if(isset($Pusers[$result['reporter_id']]['tipcount']))
                                        @for($i = 0; $i < $Pusers[$result['reporter_id']]['tipcount']; $i++)
                                            👍
                                        @endfor
                                    @else
                                        {{ logger('reportedPics, line 271 tipcount does not exists, user id: ' . $result['reporter_id']) }}
                                    @endif
                                    @if(!is_null($result['isBlocked']))
					                    @if(isset($result['isBlocked']['expire_date']))
                                            @if(!is_null($result['isBlocked']['expire_date']))
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
                                    @if($Pusers[$result['reporter_id']]['warnedicon']['isAdminWarned']==1 OR $Pusers[$result['reporter_id']]['warnedicon']['isWarned']==1)
                                        <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                    @endif
                                    @if($Pusers[$result['reporter_id']]['warnedicon']['isWarned']==0 AND $Pusers[$result['reporter_id']]['warnedicon']['WarnedScore']>10 AND $Pusers[$result['reporter_id']]['warnedicon']['auth_status']==1)
                                        <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class='btn btn-dark' href="{{ route('AdminMessengerWithReportedId', [$result->reporter_id, $result->reported_user_id, $result->id, true]) }}" target="_blank" >撰寫</a>
                            </td>
                            <td>
                                @if(isset($result['reported_user_id']))
                                    <a class="btn btn-danger ban-user" href="{{ route('banUserWithDayAndMessage', [$result['reporter_id'], $result['id']]) }}" target="_blank">封鎖</a>
                                @else
                                    檢舉者資料已不存在
                                @endif
                            </td>
                            @if(!is_null($result['pic']))
                            <td>
                                <img src="{{ $result['pic'] }}" alt="此照片已刪除或不存在" height="200px">
                            </td>
                            <td>
                                @if(isset($result['reported_user_id']))
                                <form id="Form" action="/admin/users/pictures/modify" method="POST" target="_blank">
                                    {!! csrf_field() !!}
                                    <input class="btn btn-danger" type="submit" value="刪除"><br>
                                    <input type="hidden" name="delete" value="true">
                                    <input type="hidden" name="pic_id" value="{{$result['reported_pic_id']}}">
                                    @foreach($picReason as $a)
                                        <input type="radio" name="reason[{{$rowIndex}}]" value="{{ $a->content }}">{{ $a->content }}<br>
                                    @endforeach
                                    其他: <input type="text" name="otherReason"><br>
                                    <input type="checkbox" name="addreason">加入常用原因
                                </form>
                                @endif
                            </td>
                            @else
                                <td>
                                    此會員無上傳照片
                                </td>
                                <td>
                                </td>
                            @endif
                            <td width="45%" style="word-wrap: break-word;">{{ $result['content'] }}</td>

                            <td>{{ $result['created_at'] }}</td>
                        </tr>
                    @endforeach
                    @endif
                </table>
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
                        {{--                    <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>--}}
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
                    var days = 3; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth()  + 1 ) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last10days').click(
                function () {
                    var days = 10; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1 ) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
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
        // function setDays(a) {
        //     href = a.href;
        //     let reason = $('.m-reason').val();
        //     if(reason != '') {
        //         $('.ban-user').attr("href", href + '/' + $('.days').val() + '&' + reason);
        //     }else{
        //         $('.ban-user').attr("href", href + '/' + $('.days').val());
        //     }
        // }
        /*function deletePicture(id){
            let reported_user_id = $("input[name='reported_user_id[" + id +"]']").val();
            let picType = $("input[name='picType[" + id +"]']").val();
            let postData = {};
            if (picType == 'avatar') {
                postData = {
                    delete: true,
                    avatar_id: reported_user_id
                }
            }
            else {
                postData = {
                    delete: true,
                    pic_id: reported_user_id
                }
            }
            $.ajax({
                url: '/admin/users/pictures/modify',
                type: 'POST',
                data: postData,
                success: function(response) {
                    window.localhost.href = response.redirect;
                },
                error: function() {
                    alert("刪除失敗");
                }
            });
        }*/
// $(".btn-delete").on('click', function(){
//     $("#Form").submit();
//     console.log('123');
// });
    </script>
@stop
