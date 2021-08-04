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
<style>
	body table.table-hover tr.isClosed {background-color:#C9C9C9}
	body table.table-hover tr.isClosedByAdmin {background-color:#969696}
	body table.table-hover tr.isWarned {background-color:#B0FFB1; !important;}
	body table.table-hover tr.banned,table tr.implicitlyBanned {background-color:#FDFF8C; !important;}

	body .engroup1, body .engroup1 a {color:blue;}
	body .engroup2,body .engroup2  a {color:red;}
	body h1 .fa-diamond:before {font-size:32px;}
</style>
<body>
<h1 class="engroup{{$curLogUser->engroup ?? ''}}">
@if(request()->ip) IP : {{request()->ip}} @endif
@if(request()->user_id) 
	User Id : {{request()->user_id}} 
	@if(isset($curLogUser) && $curLogUser->engroup == 1 && $curLogUser->isVip()) <i class="m-nav__link-icon fa fa-diamond"></i> @endif
@endif
@if(request()->cfp_id) Cfp Id : {{request()->cfp_id}} @endif

</h1>

@if(isset($getUsersLogData))
<div>
    <table class="table-hover table table-bordered">
        <thead>
        <tr>
			@if(!request()->user_id) <<th width="%" nowrap>User Id</th> @endif
            @if(!request()->ip) <th width="%" nowrap>IP</th> @endif
            <th width="%">Email</th>
            <th width="%" nowrap>Country</th>
            @if(!request()->cfp_id) <th width="%" nowrap>CFP_ID</th> @endif
            <th width="%" nowrap>性別</th>
            <th width="%">暱稱</th>
            <th width="%">標題</th>
            <th>Log時間</th>
            <th width="%">最後登入時間</th>
            <th width="%">UserAgent</th>
        </tr>
        </thead>
        <tbody class="engroup{{$curLogUser->engroup ?? ''}}">
            @foreach ($getUsersLogData as $row)
                <tr class="engroup{{$row->user->engroup ?? ''}} 
				@if ($row->user)
				@if ($row->user->banned) banned @endif
				@if ($row->user->implicitlyBanned) implicitlyBanned @endif
				@if ($row->user->aw_relation || $row->user->user_meta->isWarned) isWarned @endif
				@if ($row->user->accountStatus===0) isClosed @endif
				@if ($row->user->account_status_admin===0) isClosedByAdmin @endif 
				@endif ">
					@if(!request()->user_id) 
					<td nowrap>
						<a target="_blank" href="/{!!request()->path()!!}?user_id={{$row->user_id}}">
						{{$row->user_id}} 
						@if($row->user->engroup == 1 && $row->user->isVip()) 
						<i class="m-nav__link-icon fa fa-diamond"></i> 
						@endif 
						</a>
					</td> 
					@endif
                    @if(!request()->ip) 
					<td nowrap>
						<a target="_blank" href="/{!!request()->path()!!}?ip={{$row->ip}}">
						{{$row->ip}}
						</a>
					</td> 
					@endif
                    <td><a target="_blank"  href="{!!route('users/advInfo',$row->user_id)!!}">{{$row->user->email}}</a></td>
                    <td nowrap>@if ($row->user) {{$row->country}} @endif</td>
                    @if(!request()->cfp_id) <td><a target="_blank" href="/{!!request()->path()!!}?cfp_id={{$row->cfp_id}}">{{$row->cfp_id}}</a></td> @endif
					<td nowrap>@if ($row->user)  @if($row->user->engroup==1) 男 @else 女 @endif @endif</td>
                    <td>@if ($row->user) {{$row->user->name}} @endif</td>
                    <td>@if ($row->user) {{$row->user->title}} @endif</td>
                    <td>{{$row->created_at}}</td>
                    <td>@if ($row->user) {{$row->user->last_login}} @endif</td>
                    <td>{{ str_replace("Mozilla/5.0","", $row->userAgent) }}</td>					
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $getUsersLogData->links('pagination::sg-pages') !!}
</div>
@endif

</body>

<script>

</script>
@stop