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
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" defer=""></script>
<script>
    $(function() {
        $('#datatable').DataTable({
            pageLength: 200,
            bLengthChange: false,
        });
    })
</script>
<body>
@if(Request()->get('cfp_id'))
    <h1 @if($isSetAutoBan_cfp_id->count()) style="background: yellow;width: fit-content;" @endif>CFP_ID: {{Request()->get('cfp_id')}}</h1>
@else
    <h1 @if($isSetAutoBan_ip->count()) style="background: yellow;width: fit-content;" @endif>IP: {{$ip}}</h1>
@endif
<div style="margin: 20px 0px;">
    <form action="{{ route('logUserLoginHide') }}" method='POST'>
        {!! csrf_field() !!}
        <input type="hidden" name="user_id_list" value="{{ implode(',', $male_user_list) }}">
        @php
            $log_hide_count=\App\Models\LogUserLogin::whereIn('user_id', $male_user_list)->where('log_hide', 1)->get()->count();
        @endphp
        @if(count($male_user_list)>0)
            @if($log_hide_count>0)
                <input type="hidden" name="log_hide" value="0">
                <button type="submit" class="btn btn-primary">顯示男會員</button>
            @else
                <input type="hidden" name="log_hide" value="1">
                <button type="submit" class="btn btn-primary">隱藏男會員</button>
            @endif
        @endif
    </form>
</div>
@if(isset($ipUsersData))
<div>
    <table id="datatable" class="table-hover table table-bordered display">
        <thead>
        <tr>
            <th width="%">IP</th>
            <th width="%">Email</th>
            <th width="%">Country</th>
            <th width="%">CFP_ID</th>
            <th width="%">性別</th>
            <th width="%">暱稱</th>
            <th width="%">標題</th>
            <th>Log時間</th>
            <th width="%">最後登入時間</th>
            <th width="%">UserAgent</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($ipUsersData as $row)
                @php
                    $user= \App\Models\User::find($row->user_id);
                    $loginLogs=\App\Models\LogUserLogin::where('user_id', $row->user_id)->where('cfp_id', $row->cfp_id)->where('created_date', $row->created_date);
                    if($recordType == 'detail'){
                        $loginLogs = $loginLogs;
                    }else{
                        $loginLogs = $loginLogs->take(1);
                    }
                    if($ip !== '不指定'){
                        $loginLogs = $loginLogs->where('ip', $ip);
                    }

                    $loginLogs = $loginLogs->orderBy('created_at', 'desc')->get();

                    if($user){
                        $isAdminWarned = $user->isAdminWarned();
                        if(Request()->get('cfp_id')){
                            $isSetAutoBan = \App\Models\SetAutoBan::whereRaw('(content="'. Request()->get('cfp_id').'" AND expiry >="'. now().'")')->orWhereRaw('(content="'. Request()->get('cfp_id').'" AND expiry="0000-00-00 00:00:00")');
                        }else{
                            $isSetAutoBan = \App\Models\SetAutoBan::whereRaw('(content="'. $ip.'" AND expiry >="'. now().'")')->orWhereRaw('(content="'. $ip.'" AND expiry="0000-00-00 00:00:00")');
                        }
                        $isSetAutoBan = $isSetAutoBan->get()->count();
                        $isBanned = \App\Models\User::isBanned($user->id);
                    }else{
                        logger('not find user=>' . $row->user_id);  
                        continue;
                    }
                @endphp
                @foreach ($loginLogs as $loginLog)
                    @if(!($is_test && ($isBanned || $isAdminWarned))
                        <tr @if($isBanned) style="background: yellow;" @elseif($isAdminWarned) style="background: palegreen;" @endif>
                            <td>{{$loginLog->ip}}</td>
                            <td><a href="../advInfo/{{ $row->user_id }}" target="_blank">{{$user->email}}</a></td>
                            <td>{{$loginLog->country}}</td>
                            <td>{{$row->cfp_id}}</td>
                            @if($user->engroup==1)
                                <td style="color: blue;">男</td>
                            @else
                                <td style="color: red;">女</td>
                            @endif
                            <td>{{$user->name}}</td>
                            <td>{{$user->title}}</td>
                            <td>{{$loginLog->created_at}}
                                @if($row->groupCount>1 && request()->type!=='detail')
                                    <a href="/admin/users/ip/{{$loginLog->ip}}?type=detail&user_id={{ $row->user_id }}&cfp_id={{ $row->cfp_id }}&date={{ substr($loginLog->created_at,0,10) }}" target="_blank">{{ '(' .$row->groupCount .')' }}</a>
                                @endif
                            </td>
                            <td>{{$user->last_login}}</td>
                            <td>{{ str_replace("Mozilla/5.0","", $loginLog->userAgent) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
    {!! $ipUsersData->appends(request()->input())->links('pagination::sg-pages') !!}
</div>
@endif

</body>

<script>

</script>
@stop