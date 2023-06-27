@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>中長期會員管理</h1>
        <br>
        <a id="hide_advance_auth_finish" class="btn btn-success">隱藏完成進階驗證者</a>
        <form method="POST" action="{{ route('medium_long_term_without_adv_verification_communication_count_set_change') }}" style="display: inline-flex;max-width: 250px;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
            <input name='communication_count_set' value="{{ $communication_count }}">
            <button type="submit" class="btn btn-success">修改總通訊人數</button>
        </form>
        <br>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td>列入時間</td>
                <td>email</td>
                <td>暱稱</td>
                <td>發送要求進階驗證時間</td>
                <td>完成進階驗證時間</td>
                <td>移除</td>
            </tr>
            @foreach($user_list as $user)
                <tr
                    @if($user->user->advance_auth_status == 1)
                        class="advance_auth_finish"
                    @endif

                    @if(!$user->user->account_status_admin)
                        bgcolor="#969696"
                    @elseif(!$user->user->accountStatus)
                        bgcolor="#C9C9C9"
                    @elseif($user->user->is_banned())
                        bgcolor="yellow"
                    @elseif($user->user->is_warned())
                        bgcolor="#B0FFB1"
                    @elseif($user->user->is_waiting_for_more_data())
                        bgcolor="#DBA5F2"
                    @elseif($user->user->is_waiting_for_more_data_with_login_time())
                        bgcolor="#A9D4F5"
                    @endif
                >
                    <td>  
                        <div>
                            <span class="check_log btn btn-primary">+</span>
                            {{$user->medium_long_term_without_adv_verification_created_at}}
                        </div>
                        <div class="admin_check_log" style="display:none">
                            <table class='table table-bordered table-hover'>
                                <tr>
                                    <td>查看紀錄</td>
                                </tr>
                                @foreach($user->user->advInfo_check_log as $log)
                                    <tr>
                                        <td>
                                            {{$log->created_at}}
                                            <br>
                                            {{strstr($log->operator_user->email, '@', true)}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </td>
                    <td><a href="/admin/users/advInfo/{{ $user->user_id }}" target="_blank">{{$user->user->email}}</a></td>
                    <td>{{$user->user->name}}</td>
                    <td>{{$user->is_warned_log->where('adv_auth', 1)->sortByDesc('created_at')->first()->created_at ?? '未要求'}}</td>
                    <td>{{$user->user->advance_auth_time == '0000-00-00 00:00:00' ? '未完成' : $user->user->advance_auth_time}}</td>
                    <td>
                        <form method="POST" action="{{ route('medium_long_term_without_adv_verification_user_remove') }}" style="display: inline-flex;max-width: 250px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                            <input type="hidden" name='user_id' value="{{ $user->user_id }}">
                            <button type="submit" class="btn btn-success">移除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
    <script>
        $(".check_log").on("click", function(){
            $(this).parent().next('.admin_check_log').toggle();
        });

        $("#hide_advance_auth_finish").on("click", function(){
            $('.advance_auth_finish').toggle();
        });
    </script>
@stop