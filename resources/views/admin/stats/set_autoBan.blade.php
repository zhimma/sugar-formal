@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<style>
    .autoban_pic_show {width:50px;min-width:50px;}
</style>
<h1>自動封鎖警示設定</h1>
<span></span>
<form action="{{ route('stats/set_autoBan') }}" method="get">
	{!! csrf_field() !!}
	<span>
		輸入關鍵字 <input type ="text" name="key_word" value="{{ request()->get('key_word') }}">
	</span>
	<span>
		<input class="new text-white btn btn-success" type ="submit" value="查詢">
	</span>
</form>
<table class='table table-bordered table-hover'>
    <th>設定封鎖項目</th>
    <form action="{{ route('stats/set_autoBan_add') }}" method="post">
    	{!! csrf_field() !!}
	    <td>
	        <select name="type" id="type">
	    		<option value="allcheck">全欄位封鎖</option>
	    		<option value="email">email</option>
	    		<option value="name">暱稱</option>
	    		<option value="title">一句話形容自己</option>
	            <option value="about">關於我</option>
	    		<option value="style">期待的約會模式</option>
	    		<option value="msg">發送訊息內容</option>
				<option value="cfp_id">cfp_id</option>
				<option value="ip">ip</option>
				<option value="userAgent">userAgent</option>
				<option value="picname">圖片檔名</option>
	        </select>
	    </td>
	    <td>
	    	輸入關鍵字 <input type ="text" name="content" value="">
	    </td>
	    <td>
	    	輸入來源email <input type ="text" name="cuz_email_set" value="">
	    </td>
		<td>來源主機</td>
		<td>備註 <input type ="text" name="remark" value=""></td>
		<td>建立時間</td>
	    <td>
	    	<input type="radio" name="set_ban" value="1" checked>封鎖
	    	<input type="radio" name="set_ban" value="2">隱形封鎖
	    	<input type="radio" name="set_ban" value="3">警示
	    </td>
	    <td>
	    	<input class="new text-white btn btn-success" type ="submit" value="新增">
	    </td>
	</form>

	@forelse ($data as $result)
	<tr>
		<td>{{ $result->id }}</td>
		<td>
			@if($result->type=='name')暱稱
			@elseif($result->type=='email')email
			@elseif($result->type=='title')一句話形容自己 
			@elseif($result->type=='about')關於我 
			@elseif($result->type=='style')期待的約會模式 
			@elseif($result->type=='msg')發送訊息內容 
			@elseif($result->type=='allcheck')全欄位封鎖
			@elseif($result->type=='picname')圖片檔名
				@else {{$result->type}}
			@endif
		</td>
		<td>
            
            @if($result->type=='pic')
            <div>
                <img src="{{ asset($result->content) }}" onerror="this.src='{{asset('img/filenotexist.png')}}'" class="autoban_pic_show"  /> 
            </div>
            @endif
            {{ $result->content }}
            @if(($result->type ?? null)=='ip') (到{{$result->expiry}}止)@endif</td>
		<td>
			@if(isset($result->cuz_user_set) && ($result->host =='' || $result->host == request()->getHttpHost() ))
			<a href="{{ route('users/advInfo', $result->cuz_user_set) }}" target='_blank'>
				@php
					$user = \App\Models\User::findById($result->cuz_user_set);
				@endphp
				@if($user)
					{{ $user->email }}
				@else
					會員資料已刪除
				@endif
			</a>
			@endif
		</td>
		<td>{{$result->host}}</td>
		<td>{{$result->remark}}</td>
		<td>{{$result->created_at}}</td>
		@if($result->set_ban==1)
			<td style="color:red">@if(isset($result->cuz_user_set) && ($result->host =='' || $result->host == request()->getHttpHost() )) @if(($user??null) && $user->engroup==2)驗證封鎖@else 封鎖 @endif @else 封鎖 @endif</div></td>
		@elseif($result->set_ban==2)
			<td style="color:blue">隱性封鎖</td>
		@elseif($result->set_ban==3)
			<td style="color:blue">警示</td>
		@endif
		<td>
			<a class='text-white btn btn-danger' href="{{ route('stats/set_autoBan_del', $result->id) }}">刪除</a>
		</td>
	</tr>
	@empty
	<tr>
	找不到資料
	</tr>
	@endforelse
</table>
@if(count($data))
	{!! $data->appends(request()->input())->links('pagination::sg-pages') !!}
@endif
</body>
</html>
@stop