@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>自動封鎖警示設定</h1>
<table class='table table-bordered table-hover'>
    <th>新增關鍵字</th>
    <form action="{{ route('stats/set_autoBan_add') }}" method="post">
    	{!! csrf_field() !!}
	    <td>
	        <select name="type" id="type">
	    		<option value="email">email</option>
	    		<option value="name">暱稱</option>
	    		<option value="title">一句話形容自己</option>
	            <option value="about">關於我</option>
	    		<option value="style">期待的約會模式</option>
	    		<option value="msg">發送訊息內容</option>
	    		{{-- <option value="allcheck">全檢查</option> --}}
	        </select>
	    </td>
	    <td>
	    	輸入關鍵字<input type ="text" name="content" value="">
	    </td>
	    <td>
	    	<input type="radio" name="set_ban" value="1">封鎖
	    	<input type="radio" name="set_ban" value="2" checked>隱形封鎖
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
			@endif
		</td>
		<td>{{ $result->content }}</td>
		@if($result->set_ban==1)
			<td style="color:red">永久封鎖</td>
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
</body>
</html>
@stop