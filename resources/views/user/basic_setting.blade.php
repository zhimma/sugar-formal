@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>基本設定</h1>
<!-- <h2>VIP等級設定</h2>
<form method="POST" action="{{ route('users/basic_setting') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<table class="table table-bordered table-hover">
            <tr>
                <th>
                    <label for="keyword" class="">VIP</label>
                </th>
                <td>
                    <select name="vipLevel" id="vipLevel">
                    　<option value="2">Level2</option>
                    　<option value="1">Level1</option>
                    　<option value="0">否</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>性別</th>
                <td>
                    <select name="gender" id="gender">
                    　<option value="1">男</option>
                    　<option value="0">女</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="timeSet" class="">時間設定</label>
                </th>
                <td>
                
                    <input type="text" name='timeSet' class="" style="width:300px;" id="timeSet" value="@if(isset($basic_setting['timeSet'] )){{ $basic_setting['timeSet'] }}@endif">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="countSet" class="">次數設定</label>
                </th>
                <td>
                    <input type="text" name='countSet' class="" style="width:300px;" id="countSet" value="@if(isset($basic_setting['countSet'])){{ $basic_setting['countSet'] }}@endif">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
                </td>
            </tr>
        </table>
    </div>
</form> -->
<h2>複製/貼上 限制設定</h2>
<form method="POST" action="{{ route('users/basic_setting') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<table class="table table-bordered table-hover">
            <tr>
                <th>
                    <label for="keyword" class="">VIP</label>
                </th>
                <td>
                    <select name="vipLevel" id="vipLevel">
                    　<option value="2">Level2</option>
                    　<option value="1">Level1</option>
                    　<option value="0">否</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>性別</th>
                <td>
                    <select name="gender" id="gender">
                    　<option value="1">男</option>
                    　<option value="0">女</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="timeSet" class="">時間設定</label>
                </th>
                <td>
                
                    <input type="text" name='timeSet' class="" style="width:300px;" id="timeSet" value="@if(isset($basic_setting['timeSet'] )){{ $basic_setting['timeSet'] }}@endif">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="countSet" class="">次數設定</label>
                </th>
                <td>
                    <input type="text" name='countSet' class="" style="width:300px;" id="countSet" value="@if(isset($basic_setting['countSet'])){{ $basic_setting['countSet'] }}@endif">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="button" class="btn btn-primary" onclick="$('.search_form').submit()">送出</button>
                </td>
            </tr>
        </table>
    </div>
</form><br>
<script>
$("#vipLevel").children().each(function(){
    if ($(this).val()=="{{$basic_setting['vipLevel']}}"){
        //jQuery給法
        $(this).attr("selected", "true"); //或是給"selected"也可

        //javascript給法
        this.selected = true; 
    }
});

$("#gender").children().each(function(){
    if ($(this).val()=="{{$basic_setting['gender']}}"){
        //jQuery給法
        $(this).attr("selected", "true"); //或是給"selected"也可

        //javascript給法
        this.selected = true; 
    }
});
</script>
</body>
</html>


@stop