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

                    @if(in_array($user->user_id, $banned_user_list))
                        style="background:yellow;"
                    @elseif($user->user->account_status_admin)
                        style="background:darkgray;"
                    @elseif($user->user->accountStatus) 
                        style="background:lightgrey;"
                    @endif
                >
                    <td>
                        <div class="check_log">
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
                    <td>{{$user->user->email}}</td>
                    <td>{{$user->user->name}}</td>
                    <td>{{$user->is_warned_log->first()->created_at ?? '未要求'}}</td>
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
            $(this).next('.admin_check_log').toggle();
        });

        $("#hide_advance_auth_finish").on("click", function(){
            $('.advance_auth_finish').toggle();
        });
    </script>
@stop