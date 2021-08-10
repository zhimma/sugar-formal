@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>進階會員搜尋</h1>
<form method="POST" action="{{ route('users/advSearch') }}" class="search_form">
	{!! csrf_field() !!}
	<div class="form-group">
		<table class="table table-bordered table-hover">
            <tr>
                <th>
                    <label for="email" class="">Email</label>
                </th>
                <td>
                    <input type="email" name='email' class="" style="width:300px;" id="email" value="@if(isset($email )){{ $email }}@endif">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="name" class="">暱稱</label>
                </th>
                <td>
                    <input type="text" name='name' class="" style="width:300px;" id="name" value="@if(isset($name)){{ $name }}@endif">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="keyword" class="">關鍵字</label>
                </th>
                <td>
                    <input type="text" name='keyword' class="" style="width:300px;" id="keyword" value="@if(isset($keyword)){{ $keyword }}@endif">
                </td>
            </tr>
            <tr>
                <th>排序方式1</th>
                <td>
                    <input type="radio" name="time" value="created_at" @if(isset($users) && $time=='created_at') checked="true" @endif/>註冊時間
                    <input type="radio" name="time" value="last_login" @if(isset($users) && $time=='login_time') checked="true" @endif/>上線時間
                </td>
            </tr>
            <tr>
                <th>排序方式2</th>
                <td>
                    <input type="radio" name="member_type" value="vip" @if(isset($users) && $member_type=='vip') checked="true" @endif/>VIP會員
                    <input type="radio" name="member_type" value="banned" @if(isset($users) && $member_type=='banned') checked="true" @endif/>Banned List會員
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
@if(isset($users))
<table class='table table-hover table-bordered' id="tableList">
	<tr>
		<td>會員ID</td>
		<td>暱稱</td>
		<td>標題</td>
        <td>關於我</td>
        <td>希望的約會模式</td>
		<td>男/女</td>
		<td>Email</td>
		<td>建立時間</td>
		<td>更新時間</td>
		<td>上次登入</td>
		<td>封鎖使用者</td>
        <td>站長訊息</td>
		<td>所有資料/管理</td>
	</tr>
	@forelse ($users as $user)
	<tr @if($user['isBlocked']==1) style="color: #FF0000;" @endif>
		<td class="align-middle">{{ $user['id'] }}</td>
		<td class="align-middle">{{ $user['name'] }} @if($user['vip'] == 1) <i class="m-nav__link-icon fa fa-diamond"></i>@endif @if(isset($user['vip_data']) && $user['vip_data']['expiry'] != "0000-00-00 00:00:00") (到期日: {{ substr($user['vip_data']['expiry'], 0, 10) }}) @endif</td>
		<td class="align-middle">{{ $user['title'] }}</td>
        <td class="align-middle">{{ $user['about'] }}</td>
        <td class="align-middle">{{ $user['style'] }}</td>
		<td class="align-middle">@if($user['engroup']==1) 男 @else 女 @endif</td>
		<td class="align-middle">{{ $user['email'] }}</td>
		<td class="align-middle">{{ $user['created_at'] }}</td>
		<td class="align-middle">{{ $user['updated_at'] }}</td>
		<td class="align-middle">{{ $user['last_login'] }}</td>
        <td class="align-middle">
            <form action="toggleUserBlock" method="POST">{!! csrf_field() !!}
                <input type="hidden" value="@if(isset($email )){{ $email }}@endif" name="email">
                <input type="hidden" value="@if(isset($name)){{ $name }}@endif" name="name">
                <input type="hidden" value="{{ $user['id'] }}" name="user_id">
                @if($user['isBlocked'] == 1)
                    <button type="submit" class='text-white btn @if($user['isBlocked'] == 1) btn-success @else btn-danger @endif'> 解除 </button>
                @else
                    <a class="btn btn-danger ban-user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $user['id'] }}" data-sname="@if(isset($name)){{ $name }}@endif" data-email="@if(isset($email )){{ $email }}@endif"  data-name="{{ $user['name'] }}" onclick="setBlockade(this)">封鎖</a>
                @endif
            </form>
        </td>
        <td class="align-middle">
            <a href="message/to/{{ $user['id'] }}" target="_blank" class='btn btn-dark'>撰寫</a>
        </td>
        <td class="align-middle"><a href="advInfo/{{ $user['id'] }}" target='_blank' class='text-white btn btn-primary'>前往</a></td>
	</tr>
	@empty
	<tr>找不到符合條件的資料</tr>
	@endforelse
</table>
<div align="center">
    <input type="hidden" value="2" id="morePage">
    @if(!isset($email) && !isset($name) && !isset($keyword))
    <button class="btn btn-info" onclick="getUserInfo()">載入更多</button>
    @endif
</div>
@endif
</body>
</html>
<div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="toggleUserBlock" method="POST">{!! csrf_field() !!}
                <input type="hidden" id="blockName"   value="" name="name">
                <input type="hidden" id="blockEmail"   value="" name="email">
                <input type="hidden" id="blockUserID" value="" name="user_id">
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
                        <a class="text-white btn btn-success advertising banReason">廣告</a>
                        <a class="text-white btn btn-success improper-behavior banReason">非徵求包養行為</a>
                        <a class="text-white btn btn-success improper-words banReason">用詞不當</a>
                        <a class="text-white btn btn-success improper-photo banReason">照片不當</a>
                        <br><br>
                        <textarea class="form-control m-reason" name="msg" id="msg" rows="4" maxlength="200">廣告</textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(".banReason").each( function(){
        $(this).bind("click" , function(){
            var id = $("a").index(this);
            var clickval = $("a").eq(id).text();
            $('#msg').val(clickval);
        });
    });

    function setBlockade(value){
        if (typeof $(value).data('id') !== 'undefined') {
            $("#exampleModalLabel").html('封鎖 '+ $(value).data('name'))
            $("#blockName").val($(value).data('sname'))
            $("#blockEmail").val($(value).data('email'))
            $("#blockUserID").val($(value).data('id'))
        }
    }
    function getUserInfo(){
        let page = $("#morePage").val();
        $.ajax({
            type:"POST",
            url:"advSearchInfo",
            data:{
                _token: '{{csrf_token()}}',
                page : page,
            },
            success:function(msg){
                if(msg){
                    $("#morePage").val(msg.users.current_page + 1)
                    
                    $.each( msg.users.data, function( keys, value ) {
                        let tr = $("#tableList").find('tr:last').clone()
                        let trKey = ['id','name','title','about','style','engroup','email','created_at','updated_at','last_login']
                        let trValue = [];
                        $.each(trKey , function(values, worth){
                            if(worth == 'engroup'){
                                trValue.push((value[worth] == 1) ? '男' : '女')
                            }else{
                                trValue.push(value[worth])
                            }
                        })
                        $(tr.find('td').eq(9)).each(function(){
                            $($(this).find('input')).each(function(){
                                if($(this).attr("name") == 'user_id'){
                                    $(this).val(value.id)
                                }
                            })
                            $(this).find('a').attr('data-id',value.id)
                            $(this).find('a').attr('data-name',value.name)
                        })
                        $(tr.find('td').eq(10)).each(function(){
                            let urlArray = $(this).find('a').attr('href').split('/')
                            urlArray.pop()
                            let url =''
                            $(urlArray).each(function(k, v){
                                url = url + v +'/'
                            })
                            $(this).find('a').attr('href', url+value.id)
                        })
                        $(tr.find('td').eq(11)).each(function(){
                            let urlArray = $(this).find('a').attr('href').split('/')
                            urlArray.pop()
                            let url =''
                            $(urlArray).each(function(k, v){
                                url = url + v +'/'
                            })
                            $(this).find('a').attr('href', url+value.id)
                        })
                      
                        $.each(tr.find('td').slice(0,10), function(key,val){
                            
                                val.innerHTML = trValue[key]
                        })

                        if(value.isBlocked){
                            tr.css('color','#FF0000')
                        }
                        
                        $('#tableList').append(tr)
                    });
                }
            }
        })
    }
</script>
@stop