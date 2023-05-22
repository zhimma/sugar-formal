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
    <h1>觀察名單列表</h1>
    {{--<div class="col col-12 col-sm-12 col-md-8 col-lg-6">
        <form action="{{ route('observe_user_list') }}" method='get'>
            <table class="table-hover table table-bordered">
                <tr>
                    <th>帳號</th>
                    <td>
                        <input type="text" name="account" class="form-control" placeholder="name@example.com" value="@if(isset($_GET['account'])){{ $_GET['account'] }}@endif">
                    </td>
                </tr>
                <tr>
                    <th>原因</th>
                    <td>
                        <input type="text" name="reason" class="form-control" placeholder="" value="@if(isset($_GET['reason'])){{ $_GET['reason'] }}@endif">
                    </td>
                </tr>
                <tr>
                    <th>時間 (開始)</th>
                    <td>
                        <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_start'])){{ $_GET['date_start'] }}@endif" class="form-control">
                    </td>
                <tr>
                    <th>時間 (結束)</th>
                    <td>
                        <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_end'])){{ $_GET['date_end'] }}@endif" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>預設時間選項</th>
                    <td>
                        <a class="text-white btn btn-success today">今天</a>
                        <a class="text-white btn btn-success last3days">最近3天</a>
                        <a class="text-white btn btn-success last10days">最近10天</a>
                        <a class="text-white btn btn-success last30days">最近30天</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="/admin/users/observe_user_list" class='text-white btn btn-dark submit'>清除條件</a>
                        <input type="submit" class='btn btn-outline-primary submit' value="查詢">
                    </td>
                </tr>
            </table>
        </form>
    </div>--}}
    <div>查詢結果：共 <span style="color: red;">{{ $observeUserList->total() }}</span> 筆記錄。</div>
    <table class='table table-bordered table-hover'>
        <tr>
            <td width="15%">會員帳號</td>
            <td width="15%">暱稱</td>
            <td width="15%">進階驗證時間</td>
            <td width="25%">原因</td>
            <td width="15%">時間</td>
            <td width="15%">站長</td>
            <td></td>
        </tr>
        @forelse($observeUserList as $list)
            @php
                $admin=\App\Models\User::find($list->admin_id);
                $result['isBlocked'] = \App\Models\SimpleTables\banned_users::where('member_id', 'like', $list->user_id)->get()->first();
                if(!isset($result['isBlocked'])){
                    $result['isBlocked'] = \App\Models\BannedUsersImplicitly::select(\DB::raw('id, "隱性" as type'))->where('target', 'like', $list->user_id)->get()->first();
                }
                $userInfo=\App\Models\User::findById($list->user_id);
                $advance_auth_time='';
                if($userInfo->isAdvanceAuth()){
                    $advance_auth_time= $userInfo->advance_auth_email_at ? $userInfo->advance_auth_email_at : $list->advance_auth_time;
                    if($advance_auth_time=='0000-00-00 00:00:00'){
                        $advance_auth_time='';
                    }
                }
                $user['name'] = $userInfo->name;
                $user['engroup'] = $userInfo->engroup;
                $user['last_login'] = $userInfo->last_login;
                $user['vip'] = \App\Models\Vip::vip_diamond($userInfo->id);
                $user['tipcount'] = \App\Models\Tip::TipCount_ChangeGood($userInfo->id);
                $user['exchange_period'] = $userInfo->exchange_period;
                $user['warnedicon'] = \App\Models\User::warned_icondata($list->user_id);

                $background_color='';

                if(!$userInfo->account_status_admin)
                    $background_color="#969696";
                else if(!$userInfo->accountStatus)
                    $background_color="#C9C9C9";
                else if($userInfo->is_banned())
                    $background_color="#FDFF8C";
                else if($userInfo->is_warned())
                    $background_color="#B0FFB1";
                else if($userInfo->is_waiting_for_more_data())
                    $background_color="#DBA5F2";
                else if($userInfo->is_waiting_for_more_data_with_login_time())
                    $background_color="#A9D4F5";
            @endphp
            <tr style="background:{{$background_color}};">
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
                <td>{{ $advance_auth_time }}</td>
                <td>{{ $list->reason }}</td>
                <td>{{ $list->created_at }}</td>
                @if($admin)
                    <td><a href="/admin/users/advInfo/{{ $admin->id }}" target="_blank">{{ substr($admin->email, 0, strpos($admin->email,"@")) }}</a></td>
                @else
                    <td></td>
                @endif
                <td>
                    <form method="POST" action="{{ route('observe_user_remove') }}" style="display: inline-flex;max-width: 250px;">
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
    {!! $observeUserList->appends(request()->input())->links('pagination::sg-pages') !!}
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