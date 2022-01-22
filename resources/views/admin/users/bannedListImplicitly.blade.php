@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>指紋比對清單</h1>
共 {{ $users->total() }} 筆資料
<table class='table table-bordered table-hover'>
	<tr>
        <td>
            Hash 值
            @if(request()->orderBy == 'fp' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'fp', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'fp', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
		<td>Email
            @if(request()->orderBy == 'email' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'email', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'email', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
		<td>封鎖方式
            @if(request()->orderBy == 'type1, type2' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'type1, type2', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'type1, type2', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>封鎖日期
            @if(request()->orderBy == 'banned_at' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'banned_at', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'banned_at', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>帳號建立時間
            @if(request()->orderBy == 'created_at' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'created_at', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'created_at', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>最近上站時間
            @if(request()->orderBy == 'last_login' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'last_login', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'last_login', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>暱稱
            @if(request()->orderBy == 'name' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'name', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'name', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>標題
            @if(request()->orderBy == 'title' && request()->order == 'asc')
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'title', 'order' => 'desc']) }}">▲</a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['orderBy' => 'title', 'order' => 'asc']) }}">▼</a>
            @endif
        </td>
        <td>被檢舉次數</td>
        <td>操作</td>
	</tr>
	@forelse($users as $user)
        @if(isset($user['email']))
            @php
                $user['count'] = \App\Services\AdminService::countReported($user['user_id']);
                $user['fp'] = isset($user['fp']) ? ($user['fp'] != '' ? $user['fp'] : '無資料') : '無資料';
            @endphp
            <tr>
                <td>
                    @if($user['fp'] != '無資料')
                        <a href="{{ route("showFingerprint", $user['fp']) }}" target="_blank">{{ $user['fp'] }}</a>
                    @else
                        {{ $user['fp'] }}
                    @endif
                </td>
                <td><a @if($user['engroup'] == '2') style="color: #F00;" @else  style="color: #000fff;" @endif href="advInfo/{{ $user['user_id'] }}" target="_blank">{{ $user['email'] }}</a></td>
                <td>
                    @if($user['type1'] == '永久' || $user['type1'] > 0)
                        永久
                    @elseif($user['type2'] == '隱性' || $user['type2'] > 0)
                        隱性
                    @endif
                </td>
                <td>{{ $user['banned_at'] }}</td>
                <td>{{ $user['created_at'] }}</td>
                <td>{{ $user['last_login'] }}</td>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['title'] }}</td>
                <td>{{ $user['count'] }}</td>
                <td>
                    @if($user['type1'] == 0 && $user['type2'] == 0 && !($user['type1'] == '永久' || $user['type2'] == '隱性'))
                        <form action="{{ route('banningUserImplicitly') }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $user['user_id'] }}" name="user_id">
                            <input type="hidden" value="{{ $user['fp'] }}" name="fp">
                            <input type="hidden" value="{{ url()->full() }}" name="page">
                            <button type="submit" class='btn btn-info'>隱性封鎖</button>
                        </form>
                        <a class="btn btn-danger ban-user" id="block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $user['user_id'] }}" data-name="{{ $user['name']}}">永久封鎖</a>
                    @else
                        <form action="{{ route('unbanAll') }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $user['user_id'] }}" name="user_id">
                            <input type="hidden" value="{{ url()->full() }}" name="page">
                            <button type="submit" class='btn btn-success'>解除封鎖</button>
                        </form>
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <td colspan="10">無會員資料</td>
            </tr>
        @endif
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
{!! $users->links() !!}
</body>
<div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">永久封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
                {!! csrf_field() !!}
                <input type="hidden" value="" name="user_id" id="blockUserID">
                <input type="hidden" value="{{ url()->full() }}" name="page">
                <input type="hidden" name="days" value="X">
                <div class="modal-body">
                    封鎖原因
                    @foreach($banReason as $a)
                        <a class="text-white btn btn-success banReason">{{ $a->content }}</a>
                    @endforeach
                    <br><br>
                    <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                    <label style="margin:10px 0px;">
                        <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                        <sapn style="vertical-align:middle;">加入常用封鎖原因</sapn>
                    </label>
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
    jQuery(document).ready(function() {
        $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
            if (typeof $(this).data('id') !== 'undefined') {
                $("#exampleModalLabel").html('封鎖 ' + $(this).data('name'))
                $("#blockUserID").val($(this).data('id'))
            }
        });

        $(".banReason").each( function(){
            $(this).bind("click" , function(){
                var id = $("a").index(this);
                var clickval = $("a").eq(id).text();
                $('.m-reason').val(clickval);
            });
        });

        $('.advertising').on('click', function(e) {
            $('.m-reason').val('廣告');
        });
        $('.improper-behavior').on('click', function(e) {
            $('.m-reason').val('非徵求包養行為');
        });
        $('.improper-words').on('click', function(e) {
            $('.m-reason').val('用詞不當');
        });
        $('.improper-photo').on('click', function(e) {
            $('.m-reason').val('照片不當');
        });
    });
 </script>
@stop