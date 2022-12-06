<table class="table table-hover table-bordered">
    @foreach($junior_admin_log_list as $admin_log)
        <tr>
            <td>
                <span>操作人員：{{$admin_log['operator_data']->email}} ({{count($admin_log['action_log'])}})</span>
                @if(count($admin_log['action_log']) > 0)
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>暱稱</th>
                                <th>一句話</th>
                                <th>關於我</th>
                                <th>約會模式</th>
                                <th>處置</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admin_log['action_log'] as $action_detail)
                                <tr
                                    @if($action_detail->user->isbanned)
                                        bgcolor="yellow"
                                    @elseif($action_detail->user->warned)
                                        bgcolor="green"
                                    @elseif(!$action_detail->user->accountStatus)
                                        bgcolor="gray"
                                    @endif
                                >
                                    <td>
                                        @if($action_detail->user->isPhoneAuth()) (手機) @endif
                                        @if($action_detail->user->is_real) (本人) @endif
                                        @if($action_detail->user->isAdvAuthUsable) (進階) @endif
                                        <a href="/admin/users/advInfo/{{$action_detail->user->id}}" target="_blank">
                                            {{$action_detail->user->email}}
                                        </a>
                                    </td>
                                    <td>{{$action_detail->user->name}}</td>
                                    <td>{{$action_detail->user->title}}</td>
                                    <td>{{$action_detail->user_meta->about}}</td>
                                    <td>{{$action_detail->user_meta->style}}</td>
                                    <td>{{$action_detail->action_name()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </td>
        </tr>
    @endforeach
</table>