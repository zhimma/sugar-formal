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
    <body style="padding: 15px;">
    <h1>列管追蹤名單列表</h1>
    <div>查詢結果：共 <span style="color: red;">{{ $trackUserList->total() }}</span> 筆記錄。</div>
    <table class='table table-bordered table-hover'>
        <tr>
            <td width="15%">會員帳號</td>
            <td width="15%">暱稱</td>
            <td width="40%">原因</td>
            <td width="15%">時間</td>
            <td width="15%">站長</td>
            <td></td>
        </tr>
        @forelse($trackUserList as $list)
            @php
                $admin=\App\Models\User::find($list->admin_id);
                $result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $list->user_id)->get()->first();
                if(!isset($result['isBlocked'])){
                    $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $list->user_id)->get()->first();
                }
                $userInfo=\App\Models\User::findById($list->user_id);
                $user['name'] = $userInfo->name;
                $user['engroup'] = $userInfo->engroup;
                $user['last_login'] = $userInfo->last_login;
                $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
                $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
                $user['exchange_period'] = $userInfo->exchange_period;
                $user['warnedicon'] = \App\Models\User::warned_icondata($list->user_id);
            @endphp
            <tr>
                <td><a href="/admin/users/advInfo/{{ $list->user_id }}" target="_blank">{{ $list->user_email }}</a></td>
                <td>
                    <a href="/admin/users/advInfo/{{ $list->user_id }}" target="_blank">
                        <p @if($user['engroup'] == '2') style="margin-bottom:0;color: #F00;" @else  style="margin-bottom:0;color: #5867DD;"  @endif>
                            {{ $list->user_name }}
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
                <td>{{ $list->reason }}</td>
                <td>{{ $list->created_at }}</td>
                @if($admin)
                    <td><a href="/admin/users/advInfo/{{ $admin->id }}" target="_blank">{{ substr($admin->email, 0, strpos($admin->email,"@")) }}</a></td>
                @else
                    <td></td>
                @endif
                <td>
                    <form method="POST" action="{{ route('track_user_remove') }}" style="display: inline-flex;max-width: 250px;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                        <input type="hidden" name='user_id' value="{{ $list->user_id }}">
                        <button type="submit" class="btn btn-success">移除</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                找不到資料
            </tr>
        @endforelse
    </table>
    {!! $trackUserList->appends(request()->input())->links('pagination::sg-pages') !!}
    </body>
    <script>
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        let today = new Date();
        let minus_date = new Date(today);
        jQuery(document).ready(function() {
            jQuery("#datepicker_1").datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }).val();
            jQuery("#datepicker_2").datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }).val();

            $('.today').click(
                function() {
                    $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    set_end_date();
                });
            $('.last3days').click(
                function() {
                    var days = 3; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth()  + 1 ) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last10days').click(
                function() {
                    var days = 10; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1 ) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last30days').click(
                function() {
                    var start_date = new Date(new Date().setDate(date.getDate() - 30));
                    $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                    $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                    set_end_date();
                });
        });

        function set_end_date() {
            $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
            $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        }

        function str_pad(n) {
            return String("00" + n).slice(-2);
        }
    </script>
@stop