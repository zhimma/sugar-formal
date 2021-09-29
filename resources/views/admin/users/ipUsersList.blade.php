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
<body>
<h1>IP: {{$ip}}</h1>

@if(isset($ipUsersData))
<div>
    <table class="table-hover table table-bordered">
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
                    $loginLogs=\App\Models\LogUserLogin::where('user_id',$row->user_id)->where('cfp_id',$row->cfp_id)->where('created_date', $row->created_date)->orderBy('created_at','desc');
                    if($recordType=='detail'){
                        $loginLogs=$loginLogs->get();
                    }else{
                        $loginLogs=$loginLogs->take(1)->get();
                    }

                    logger('not find user=>'.$row->user_id);
                    if($user){
                        $isAdminWarned=$user->isAdminWarned();
                        $isBanned= \App\Models\User::isBanned($user->id);
                    }else{
                        continue;
                    }
                @endphp
                @foreach ($loginLogs as $loginLog)
                    <tr @if($isBanned) style="background: yellow;" @elseif($isAdminWarned) style="background: palegreen;" @endif>
                        <td>{{$loginLog->ip}}</td>
                        <td><a href="../advInfo/{{ $row->user_id }}" target="_blank">{{$user->email}}</a></td>
                        <td>{{$loginLog->country}}</td>
                        <td>{{$row->cfp_id}}</td>
                        <td>@if($user->engroup==1)男@else 女@endif</td>
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