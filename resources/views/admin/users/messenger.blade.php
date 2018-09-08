@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
</style>
<body style="padding: 15px;">
@include('partials.errors')
@include('partials.message')
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        @if(!isset($msgs))
            <h1>發送站長訊息給{{ $user->name }}</h1>
            @if(!isset($message))
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>預設選項</td>
                        <td>
                            <button class="btn btn-success tpl">暱稱</button>
                            <button class="btn btn-success tpl">標題</button>
                            <button class="btn btn-success tpl">身高</button>
                            <button class="btn btn-success tpl">職業</button>
                            <button class="btn btn-success tpl">體重</button>
                            <button class="btn btn-success tpl">罩杯</button>
                            <button class="btn btn-success tpl">體型</button>
                            <button class="btn btn-success tpl">現況</button>
                            <button class="btn btn-success tpl">關於我</button>
                            <button class="btn btn-success tpl">期待的約會模式</button>
                            <button class="btn btn-success tpl">教育</button>
                            <button class="btn btn-success tpl">婚姻</button>
                            <button class="btn btn-success tpl">喝酒</button>
                            <button class="btn btn-success tpl">抽菸</button>
                            <button class="btn btn-success tpl">職業</button>
                            <button class="btn btn-success tpl">資產</button>
                            <button class="btn btn-success tpl">年收</button>
                        </td>
                    </tr>
                    <tr>
                        <td>修改/刪除</td>
                        <td>
                            <button class="btn btn-danger edit">修改</button>
                            <button class="btn btn-danger del">刪除</button>
                        </td>
                    </tr>
                </table>
            @endif
            <form action="{{ route('admin/send', $user->id) }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">@if(isset($message) && !isset($report)){{ $user->name }}您好，您先前所檢舉，由{{ $senderName }}於{{ $message->created_at }}發送的訊息，站長已檢視此訊息並無問題，若有疑慮請來訊。@elseif(isset($message) && isset($report)) {{ $user->name }}您好，您先前在{{ $report->created_at }}檢舉了會員「{{ $reportedName }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊。 @endif</textarea><br>
                @if(isset($message) && !isset($report))
                    <input type="hidden" name="rollback" value="1">
                    <input type="hidden" name="msg_id" value="{{ $message->id }}">
                @elseif(isset($message) && isset($report))
                    <input type="hidden" name="rollback" value="1">
                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                @endif
                <button type='submit' class='text-white btn btn-primary'>送出</button>
            </form>
        @else
            @if(isset($msgs2) || $msgs2 == 0)
            <form action="{{ route('admin/send/multiple') }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                @if($msgs != 0)
                    @foreach( $msgs as $msg )
                        <h3 style="text-align: left">發送給{{ $msg['user_name'] }}</h3>
                        <input type="hidden" value="{{ $msg['user_id'] }}" name="to[]">
                        <textarea name="msg[]" class="form-control" cols="80" rows="5">{{ $msg['user_name'] }}{{ $template['pic']['head'] }}{{ $msg['post_time'] }}{{ $template['pic']['body'] }}</textarea><br>
                    @endforeach
                @endif
                @if($msgs2 != 0)
                    @foreach( $msgs2 as $msg )
                        <h3 style="text-align: left">發送給{{ $msg['user_name'] }}</h3>
                        <input type="hidden" value="{{ $msg['user_id'] }}" name="to[]">
                        <textarea name="msg[]" class="form-control" cols="80" rows="5">{{ $msg['user_name'] }}{{ $template['avatar']['head'] }}{{ $template['avatar']['body'] }}</textarea><br>
                    @endforeach
                @endif
                <button type='submit' class='text-white btn btn-primary'>送出</button>
            </form>
            @else
                <form action="{{ route('admin/send/multiple') }}" id='message' method='POST'>
                    {!! csrf_field() !!}
                    <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                    @foreach( $msgs as $msg )
                        <h3 style="text-align: left">發送給{{ $msg['name'] }}</h3>
                        <input type="hidden" value="{{ $msg['from_id'] }}" name="to[]">
                        <textarea name="msg[]" class="form-control" cols="80" rows="5">{{ $msg['name'] }}{{ $template['head'] }}{{ $msg['post_time'] }}{{ $template['body'] }}</textarea><br>
                    @endforeach
                    <button type='submit' class='text-white btn btn-primary'>送出</button>
                </form>
            @endif
        @endif
    @endif
@endif
</body>
@if(!isset($msgs))
    <script>
        let template = [
            '{{ $user->name }} 你好，由於您的暱稱不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的標題不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的身高不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的職業不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的體重不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的罩杯不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的體型不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的現況不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的關於我不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的期待的約會模式不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的教育不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的婚姻不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的喝酒不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的抽菸不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的職業不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的資產不符站方規定，故已',
            '{{ $user->name }} 你好，由於您的年收不符站方規定，故已'
        ],
        edit = '修改。', del = '刪除。';

        $(document).ready(
            $(".tpl").click(
                function () {
                    let i = $(".tpl").index(this);
                    $('#msg').val(template[i]);
                }
            ),
            $(".edit").click(
                function () {
                    $('#msg').val($('#msg').val() + edit);
                }
            ),
            $(".del").click(
                function () {
                    $('#msg').val($('#msg').val() + del);
                }
            )
        );
    </script>
@endif