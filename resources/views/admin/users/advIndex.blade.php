@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>進階會員搜尋</h1>
<form method="get" action="{{ route('users/advSearch') }}" class="search_form">
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
                    <label for="keyword" class="">關鍵字</label><!--(關於我、約會模式)-->
                </th>
                <td>
                    <input type="text" name='keyword' class="" style="width:300px;" id="keyword" value="@if(isset($keyword)){{ $keyword }}@endif" autocomplete="off">
                    <div id="assgin_login_time" @if(!isset($keyword)) style="display: none;" @endif >
                        <input type="radio" name="login_time" value="3days" @if($login_time=='3days') checked="true" @endif/>三天
                        <input type="radio" name="login_time" value="1week" @if($login_time=='1week') checked="true" @endif/>一週
                        <input type="radio" name="login_time" value="2weeks" @if($login_time=='2weeks') checked="true" @endif/>兩週
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="phone" class="">註冊手機</label>
                </th>
                <td>
                    <input type="text" name='phone' class="" style="width:300px;" id="phone" value="@if(isset($phone)){{ $phone }}@endif">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="title" class="">一句話</label>
                </th>
                <td>
                    <input type="text" name='title' class="" style="width:300px;" id="title" value="@if(isset($title)){{ $title }}@endif">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="order_no" class="">帳單查詢</label>
                </th>
                <td>
                    <input type="text" name='order_no' class="" style="width:300px;" id="title" value="@if(isset($order_no)){{ $order_no }}@endif">
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
        <th>ID</th>
        <th>Email</th>
        <th>名稱</th>
        <th>男/女</th>
        <th>是否為VIP</th>
        <th>是否為免費方案</th>
        <th>升級時的帳單編號</th>
        <th>付費方式</th>
        <th>VIP資料建立時間</th>
        <th>VIP資料更新時間</th>
        <th>變更男/女</th>
        <th>提供/取消VIP權限</th>
    </tr>
    @forelse ($users as $user)
        @php
            $result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $user->id)->get()->first();
            if(!isset($result['isBlocked'])){
                $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $user->id)->get()->first();
            }
            $userInfo=\App\Models\User::findById($user->id);
            $user['name'] = $userInfo->name;
            $user['engroup'] = $userInfo->engroup;
            $user['last_login'] = $userInfo->last_login;
            $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
            $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
            $user['exchange_period'] = $userInfo->exchange_period;
            $user['warnedicon'] = \App\Models\User::warned_icondata($user->id);

        @endphp
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->email }}</td>
            <td @if($result['isBlocked']) style="background-color:#FFFF00" @endif>
                <a href="{{ route('users/advInfo', $user->id) }}" target='_blank'>
                    <p @if($user['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                        {{ $user->name }}
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
            <td>{{ $user->engroup ==1 ? '男':'女' }}</td>
            @if($userInfo->isVip())
                <td>是 @if($user->vip_data->expiry!="0000-00-00 00:00:00") (到期日: {{ substr($user->vip_data->expiry, 0, 10) }}) @endif</td>
                <td>@if($user->vip_data->free == 1) 是 @else 否 @endif</td>
                <td>{{ $user->vip_order_id }}</td>
                <td>
                    @if ($user->vip_data->payment_method == 'CREDIT')
                        信用卡
                    @elseif ($user->vip_data->payment_method == 'ATM')
                        ATM
                    @elseif ($user->vip_data->payment_method == 'CVS')
                        超商代碼
                    @elseif ($user->vip_data->payment_method == 'BARCODE')
                        超商條碼
                    @endif
                </td>
            @else
                <td>否</td>
                <td>@if(isset($user->vip_data))@if($user->vip_data->free == 1) 是 @else 否 @endif @else 無資料 @endif</td>
                <td>@if(isset($user->vip_order_id)){{ $user->vip_order_id }}@else 無資料 @endif</td>
                <td>@if(isset( $user->vip_data->payment_method))
                        @if ($user->vip_data->payment_method == 'CREDIT')
                            信用卡
                        @elseif ($user->vip_data->payment_method == 'ATM')
                            ATM
                        @elseif ($user->vip_data->payment_method == 'CVS')
                            超商代碼
                        @elseif ($user->vip_data->payment_method == 'BARCODE')
                            超商條碼
                        @endif
                    @else 無資料
                    @endif
                </td>
            @endif
            <td>@if(isset($user->vip_data->created_at))
                    <a href="{{ route('stats/vip_log', $user->id) }}" target="_blank">
                        				<a href="{{ url('admin/order#'.$user->email) }}" target="_blank">
                        {{ $user->vip_data->created_at }}</a>@else 無資料 @endif</td>
            <td>@if(isset($user['updated_at'])){{ $user['updated_at'] }}@else 無資料 @endif</td>
            <td>
                <form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
                    <input type="hidden" name='page' value="back">
                    <input type="hidden" name='user_id' value="{{ $user->id }}">
                    <input type="hidden" name='gender_now' value="{{ $user->engroup }}">
                    <button type="submit" class="btn btn-warning">變更</button></form>
            </td>
            <td>
                <form method="POST" action="VIPToggler" class="vip">{!! csrf_field() !!}
                    <input type="hidden" name='page' value="back">
                    <input type="hidden" name='user_id' value="{{ $user->id }}">
                    <input type="hidden" name='isVip' value="@if($userInfo->isVip()) 1 @else 0 @endif">
                    <button type="submit" class="btn btn-info">@if($userInfo->isVip()) 取消權限 @else 提供權限 @endif</button></form>
            </td>
        </tr>
    @empty
        <tr>
            找不到資料
        </tr>
    @endforelse
</table>
    {!! $users->appends(request()->input())->links('pagination::sg-pages') !!}

{{--<div align="center">--}}
{{--    <input type="hidden" value="2" id="morePage">--}}
{{--    @if(!isset($email) && !isset($name) && !isset($keyword))--}}
{{--    <button class="btn btn-info" onclick="getUserInfo()">載入更多</button>--}}
{{--    @endif--}}
{{--</div>--}}
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
            url:"advSearchInfo?{{csrf_token()}}={{now()->timestamp}}",
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

    $("input[name='keyword']").keyup(function(){

        if($(this).val()){
            $('#assgin_login_time').show();
        }else{
            $('#assgin_login_time').hide();
        }

    });
</script>
@stop