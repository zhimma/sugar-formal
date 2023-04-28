@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>會員封鎖清單</h1>
共 {{ $list->total() }} 筆記錄
<table class='table table-bordered table-hover'>
	<tr>
        <td>會員ID</td>
		<td>Email</td>
		<td>名稱</td>
        <td>封鎖時間</td>
        <td>傳訊給誰</td>
        <td>封鎖原因</td>
        {{--<td>訊息內容</td>--}}
        {{--<td>到期日(自動解除)</td>--}}
        <td>封鎖設定</td>
        <td>自動封鎖來源email</td>
        <td>建立日期</td>
		<td>解除封鎖</td>
	</tr>
	@forelse($list as $user)
    <tr>
        <td>{{ $user->member_id }}</td>
        <td><a href="{!!route('users/advInfo',$user->member_id)!!}"  target="_blank">{{ $user->email }}</a></td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->created_at }}</td>
        <td>{{ $user->recipient_name }}</td>
        <td>{{ $user->reason }}</td>
        {{--<td>{{ $user->message_content }}</td>--}}
        {{--<td>{{ $user->expire_date }}</td>--}}
        <td>
            @if($user->set_auto_ban_id != 0 && isset($set_auto_ban_list[$user->set_auto_ban_id]))
                @if($set_auto_ban_list[$user->set_auto_ban_id]['type']=='name')
                    暱稱
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='email')
                    email
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='title')
                    一句話形容自己 
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='about')
                    關於我 
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='style')
                    期待的約會模式 
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='msg')
                    發送訊息內容 
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='allcheck')
                    全欄位封鎖
                @elseif($set_auto_ban_list[$user->set_auto_ban_id]['type']=='picname')
                    圖片檔名
                @else 
                    {{$set_auto_ban_list[$user->set_auto_ban_id]['type']}}
                @endif
                :
                @if($set_auto_ban_list[$user->set_auto_ban_id]['type']=='pic')
                    <div>
                        <img src="{{ asset($set_auto_ban_list[$user->set_auto_ban_id]['content']) }}" onerror="this.src='{{asset('img/filenotexist.png')}}'" class="autoban_pic_show"  /> 
                    </div>
                @endif
                {{ $set_auto_ban_list[$user->set_auto_ban_id]['content'] }}
                @if(($set_auto_ban_list[$user->set_auto_ban_id]['type'] ?? null)=='ip')
                    (到{{$set_auto_ban_list[$user->set_auto_ban_id]['expiry']}}止)
                @endif
            @endif
        </td>
        <td>
            @if($user->set_auto_ban_id != 0)
                @if(isset($set_auto_ban_list[$user->set_auto_ban_id]['cuz_user_set']) && ($set_auto_ban_list[$user->set_auto_ban_id]['host'] == '' || $set_auto_ban_list[$user->set_auto_ban_id]['host'] == request()->getHttpHost() ))
                    <a href="{{ route('users/advInfo', $set_auto_ban_list[$user->set_auto_ban_id]['cuz_user_set']) }}" target='_blank'>
                        @php
                            $user = \App\Models\User::findById($set_auto_ban_list[$user->set_auto_ban_id]['cuz_user_set']);
                        @endphp
                        @if($user)
                            {{ $user->email }}
                        @else
                            會員資料已刪除
                        @endif
                    </a>
                @endif
            @endif
        </td>
        <td>{{ $user->created_at }}</td>
        <td>
            <form action="userUnblock" method="POST">{!! csrf_field() !!}
                <input type="hidden" value="{{ $user->member_id }}" name="user_id">
                <button type="submit" class='text-white btn btn-success'>解除</button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
{{ $list->links() }}
</body>
</html>
@stop