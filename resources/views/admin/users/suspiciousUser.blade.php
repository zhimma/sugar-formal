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
<h1>可疑名單列表</h1>
{{--@php--}}
{{--print_r($suspiciousUser);--}}
{{--@endphp--}}
@if(isset($suspiciousUser))
<div>
    <table class="table-hover table table-bordered">
        <tr>
            <td width="12%">列入可疑名單的時間</td>
            <td width="12%">原因及查看紀錄</td>
            <td width="12%">標題(一句話形容自己）</td>
            <td width="12%">email</td>
            <td width="12%">暱稱</td>
            <td width="14%">關於我</td>
            <td width="12%">期待的約會模式</td>
            <td width="12%">大頭照</td>
            <td width="5%">移除</td>
        </tr>
            @foreach ($suspiciousUser as $row)
                @php
                    $result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $row->id)->get()->first();
                    if(!isset($result['isBlocked'])){
                        $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $row->id)->get()->first();
                    }
                    $userInfo=\App\Models\User::findById($row->id);
                    $user['name'] = $userInfo->name;
                    $user['engroup'] = $userInfo->engroup;
                    $user['last_login'] = $userInfo->last_login;
                    $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
                    $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
                    $user['exchange_period'] = $userInfo->exchange_period;
                    $user['warnedicon'] = \App\Models\User::warned_icondata($row->id);
                @endphp
                <tr>
                    <td>{{$row->suspicious_created_time }}@if($adminInfo[$row->suspicious_admin_id] ?? false)<br><span>提報人員：<a href="{{ route('users/advInfo', $row->suspicious_admin_id) }}" target='_blank'>{{ $adminInfo[$row->suspicious_admin_id]->email }}</a></span>@endif</td>
                    <td>
                        {{$row->suspicious_reason ? $row->suspicious_reason : '無' }}
                        @foreach($userInfo->advInfo_check_log as $key => $log)
                            @if($key < 3)
                                <div class="log_line"><hr>{{$log->created_at}} {{strstr($adminInfo[$log->operator]->email, '@', true)}}</div>
                            @else
                                <div class="log_line" style="display:none"><hr>{{$log->created_at}} {{strstr($adminInfo[$log->operator]->email, '@', true)}}</div>
                            @endif
                        @endforeach
                        @if(count($userInfo->advInfo_check_log) > 3)
                            <a class="look_more_log">. . .</a>
                        @endif
                    </td>
                    <td>{{$row->title }}</td>
                    <td><a href="/admin/users/advInfo/{{ $row->id }}" target="_blank">{{ $row->email }}</a></td>
                    <td 
                        @if(!$row->account_status_admin)
                            bgcolor="#969696"
                        @elseif(!$row->accountStatus)
                            bgcolor="#C9C9C9"
                        @elseif($row->is_banned())
                            bgcolor="#FDFF8C"
                        @elseif($row->is_warned())
                            bgcolor="#B0FFB1"
                        @elseif($row->is_waiting_for_more_data())
                            bgcolor="#DBA5F2"
                        @elseif($row->is_waiting_for_more_data_with_login_time())
                            bgcolor="#A9D4F5"
                        @endif
                    >
                        <a href="{{ route('users/advInfo', $row->id) }}" target='_blank'>
                            <p @if($user['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $row->name }}
                                @if($user['vip'])
                                    @if($user['vip']=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $user['vip']; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @if(isset($user['tipcount']))
                                    @for($i = 0; $i < $user['tipcount']; $i++)
                                        👍
                                    @endfor
                                @else
                                    {{ logger('reportedUsers, line 80 tipcount does not exists, user id: ' . $result['reported_id']) }}
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
                                @if($user['warnedicon']['isAdminWarned']==1 OR $user['warnedicon']['isWarned']==1)
                                    <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                @endif
                                @if($user['warnedicon']['isWarned']==0 AND $user['warnedicon']['WarnedScore']>10 AND $user['warnedicon']['auth_status']==1)
                                    <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                @endif
                            </p>
                        </a>
                    </td>
                    <td style="word-break: break-word;">{{$row->about}}</td>
                    <td style="word-break: break-word;">{{$row->style }}</td>
                    <td><img src="{{$row->pic}}" style="width: 100px;"></td>
                    <td>
                        <button class="btn_sid btn btn-danger" data-sid="{{$row->id}}" data-uid="{{$row->id}}">移除</button>
                    </td>
                </tr>
            @endforeach
    </table>
    {!! $suspiciousUser->appends(request()->input())->links('pagination::sg-pages') !!}
</div>
@endif
<form id="sid_toggle" action="{{ route('users/suspicious_user_toggle') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="sid" id="sid" value="">
    <input type="hidden" name="uid" id="uid" value="">
</form>

<script>

    $('.btn_sid').on('click', function(){

        $('#sid').val($(this).data('sid'));
        $('#uid').val($(this).data('uid'));

        let sid = $(this).data('sid'),
            r = false;

        if(sid==''){
            r = confirm('是否確定加入可疑名單?');
        }else{
            r = confirm('是否確定移除可疑名單?');
        }

        if(r==true){
            $('#sid_toggle').submit();
        }

    });

    $('.look_more_log').on('click', function(){
        $(this).text('');
        $(this).parent('td').children('.log_line').each(function(){
            if($(this).css("display") == "none"){
                $(this).show();
            }
        });
    });

</script>


@stop