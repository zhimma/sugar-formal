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
                    $isAdminWarned=$user->isAdminWarned();
                    $isBanned= \App\Models\User::isBanned($user->id);
                @endphp
                <tr @if($isBanned) style="background: yellow;" @elseif($isAdminWarned) style="background: palegreen;" @endif>
                    <td>{{$row->ip}}</td>
                    <td><a href="../advInfo/{{ $row->user_id }}" target="_blank">{{$row->email}}</a></td>
                    <td>{{$row->country}}</td>
                    <td>{{$row->cfp_id}}</td>
                    <td>@if($row->engroup==1)男@else 女@endif</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->title}}</td>
                    <td>{{$row->created_at}}
                        @if($row->groupCount>1 && request()->type!=='detail')
                            <a href="/admin/users/ip/{{$row->ip}}?type=detail&user_id={{ $row->user_id }}&cfp_id={{ $row->cfp_id }}&date={{ substr($row->created_at,0,10) }}" target="_blank">{{ '(' .$row->groupCount .')' }}</a>
                        @endif
                    </td>
                    <td>{{$row->last_login}}</td>
                    <td>{{ str_replace("Mozilla/5.0","", $row->userAgent) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{--    {!! $ipUsersData->appends(request()->input())->links('pagination::sg-pages') !!}--}}
</div>
@endif

</body>

<script>

</script>
@stop