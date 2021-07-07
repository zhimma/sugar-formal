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
                <tr>
                    <td>{{$row->ip}}</td>
                    <td><a href="../advInfo/{{ $row->user_id }}" target="_blank">{{$row->email}}</a></td>
                    <td>{{$row->country}}</td>
                    <td>{{$row->cfp_id}}</td>
                    <td>@if($row->engroup==1)男@else 女@endif</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->title}}</td>
                    <td>{{$row->created_at}}</td>
                    <td>{{$row->last_login}}</td>
                    <td>{{ str_replace("Mozilla/5.0","", $row->userAgent) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $ipUsersData->links('pagination::sg-pages') !!}
</div>
@endif

</body>

<script>

</script>
@stop