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
            <h1>發送站長訊息給{{ $user->name}}(被檢舉者)</h1>
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>預設選項</td>
                        <td>
                            <button class="btn btn-success tpl">檢舉沒問題</button>
                            <button class="btn btn-success tpl">檢舉沒問題2</button>
                            <button class="btn btn-success tpl">被檢舉沒問題</button>
                            <button class="btn btn-success tpl">圖片檢舉沒問題</button>
                            <button class="btn btn-success tpl">圖片檢舉沒問題2</button>
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
                            <!-- <button class="btn btn-success tpl">婚姻</button>
                            <button class="btn btn-success tpl">喝酒</button>
                            <button class="btn btn-success tpl">抽菸</button> -->
                            <button class="btn btn-success tpl">職業</button>
                            <button class="btn btn-success tpl">資產</button>
                            <button class="btn btn-success tpl">年收</button>
                        </td>
                    </tr>
                    <tr>
                        <td>檢舉者/被檢舉者</td>
                        <td>
                            檢舉者<button class="btn btn-primary report_user">{{$user->name}}</button>
                            被檢舉者<button class="btn btn-primary report_user">{{$to_user->name}}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>修改/刪除</td>
                        <td>
                            <button class="btn btn-info edit">修改</button>
                            <button class="btn btn-info del">刪除</button>
                            <button class="btn btn-info view">檢視</button>
                            <button class="btn btn-info create">新增</button>
                        </td>
                    </tr>
                    <tr>
                        <td>插入現在時間</td>
                        <td>
                            <button class="btn btn-danger now_time">現在時間</button>
                        </td>
                    </tr>
                </table>
            <form action="{{ route('admin/send', (!isset($isReported))? $user->id : $isReportedId ) }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                @if(isset($isPic) && ($isPic))
                    @if(isset($isReported))
                    <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">{{ $reportedName }}您好，您被檢舉圖片/大頭照，站長認為並無問題，若有疑慮請來訊。</textarea>
                    @else
                        <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">{{ $user->name }}您好，您先前所檢舉{{ $reportedName }}的圖片/大頭照，站長已檢視，認為並無問題，若有疑慮請來訊。</textarea>
                    @endif
                @elseif(isset($isReported))
                    <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">{{ $reportedName }}您好，您被檢舉，站長認為並無問題，若有疑慮請來訊。</textarea>
                @else
                    <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">@if(isset($message) && !isset($report)){{ $user->name }}您好，您先前所檢舉，由{{ $senderName }}於{{ $message->created_at }}發送的訊息，站長已檢視，認為並無問題，若有疑慮請來訊。@elseif(isset($message) && isset($report)) {{ $user->name }}您好，您先前在{{ $report->created_at }}檢舉了會員「{{ $reportedName }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊。 @endif</textarea>
                @endif
                <br>
                @if(isset($isPic) && ($isPic))
                    <input type="hidden" name="rollback" value="1">
                    @if(isset($isReported))
                        <input type="hidden" name="pic_id" value="avatar{{$pic_id }}">
                    @else
                        <input type="hidden" name="pic_id" value="{{$pic_id }}">
                    @endif
                @elseif(isset($message) && !isset($report) && !isset($isReported))
                    <input type="hidden" name="rollback" value="1">
                    <input type="hidden" name="msg_id" value="{{ $message->id }}">
                @elseif(isset($message) && isset($report))
                    <input type="hidden" name="rollback" value="1">
                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                @endif
                <button type='submit' class='text-white btn btn-primary'>送出</button>
            </form>

            <div>===================================================================================================</div>
            


            <h1>發送站長訊息給{{$to_user->name}}(檢舉者)</h1>
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>預設選項</td>
                        <td>
                            <button class="btn btn-success tpl2">檢舉沒問題</button>
                            <button class="btn btn-success tpl2">檢舉沒問題2</button>
                            <button class="btn btn-success tpl2">被檢舉沒問題</button>
                            <button class="btn btn-success tpl2">圖片檢舉沒問題</button>
                            <button class="btn btn-success tpl2">圖片檢舉沒問題2</button>
                            <button class="btn btn-success tpl2">暱稱</button>
                            <button class="btn btn-success tpl2">標題</button>
                            <button class="btn btn-success tpl2">身高</button>
                            <button class="btn btn-success tpl2">職業</button>
                            <button class="btn btn-success tpl2">體重</button>
                            <button class="btn btn-success tpl2">罩杯</button>
                            <button class="btn btn-success tpl2">體型</button>
                            <button class="btn btn-success tpl2">現況</button>
                            <button class="btn btn-success tpl2">關於我</button>
                            <button class="btn btn-success tpl2">期待的約會模式</button>
                            <button class="btn btn-success tpl2">教育</button>
                            <!-- <button class="btn btn-success tpl2">婚姻</button>
                            <button class="btn btn-success tpl2">喝酒</button>
                            <button class="btn btn-success tpl2">抽菸</button> -->
                            <button class="btn btn-success tpl2">職業</button>
                            <button class="btn btn-success tpl2">資產</button>
                            <button class="btn btn-success tpl2">年收</button>
                        </td>
                    </tr>
                    <tr>
                        <td>檢舉者/被檢舉者</td>
                        <td>
                            檢舉者<button class="btn btn-primary report_user2">{{$user->name}}</button>
                            被檢舉者<button class="btn btn-primary report_user2">{{$to_user->name}}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>修改/刪除/檢視</td>
                        <td>
                            <button class="btn btn-info edit2">修改</button>
                            <button class="btn btn-info del2">刪除</button>
                            <button class="btn btn-info view2">檢視</button>
                            <button class="btn btn-info create2">新增</button>
                        </td>
                    </tr>
                    <tr>
                        <td>插入現在時間</td>
                        <td>
                            <button class="btn btn-danger now_time2">現在時間</button>
                        </td>
                    </tr>
                </table>
            
            <form action="{{ route('admin/send', (!isset($isReported))? $to_user->id : $isReportedId ) }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                @if(isset($isPic) && ($isPic))
                    @if(isset($isReported))
                    <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{ $reportedName }}您好，您被檢舉圖片/大頭照，站長認為並無問題，若有疑慮請來訊。</textarea>
                    @else
                        <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{ $to_user->name }}您好，您先前所檢舉{{ $reportedName }}的圖片/大頭照，站長已檢視，認為並無問題，若有疑慮請來訊。</textarea>
                    @endif
                @elseif(isset($isReported))
                    <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{ $reportedName }}您好，您被檢舉，站長認為並無問題，若有疑慮請來訊。</textarea>
                @else
                    <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">@if(isset($message) && !isset($report)){{ $to_user->name }}您好，您先前所檢舉，由{{ $senderName }}於{{ $message->created_at }}發送的訊息，站長已檢視，認為並無問題，若有疑慮請來訊。@elseif(isset($message) && isset($report)) {{ $to_user->name }}您好，您先前在{{ $report->created_at }}檢舉了會員「{{ $reportedName }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊。 @endif</textarea>
                @endif
                <br>
                @if(isset($isPic) && ($isPic))
                    <input type="hidden" name="rollback" value="1">
                    @if(isset($isReported))
                        <input type="hidden" name="pic_id" value="avatar{{$pic_id }}">
                    @else
                        <input type="hidden" name="pic_id" value="{{$pic_id }}">
                    @endif
                @elseif(isset($message) && !isset($report) && !isset($isReported))
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
            @if(is_object($message))
                '{{$user->name }}您好，您先前所檢舉，由{{ $to_user->name }}於{{ $message->created_at }}發送的訊息，站長已檢視，認為並無問題，若有疑慮請來訊。',
                '{{ $user->name }}您好，您先前在{{ $message->created_at }}檢舉了會員「{{ $to_user->name }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊',
            @endif
            '{{$to_user->name}}您好，您被檢舉，站長認為並無問題，若有疑慮請來訊。',
            '{{$to_user->name}}您好，您被檢舉圖片/大頭照，站長認為並無問題，若有疑慮請來訊。',
            '{{ $user->name }}您好，您先前所檢舉{{ $to_user->name }}的圖片/大頭照，站長已檢視，認為並無問題，若有疑慮請來訊。',

            '{{$user->name}}你好，由於您的暱稱不符站方規定，故已',
            '{{$user->name}}你好，由於您的標題不符站方規定，故已',
            '{{$user->name}}你好，由於您的身高不符站方規定，故已',
            '{{$user->name}}你好，由於您的職業不符站方規定，故已',
            '{{$user->name}}你好，由於您的體重不符站方規定，故已',
            '{{$user->name}}你好，由於您的罩杯不符站方規定，故已',
            '{{$user->name}}你好，由於您的體型不符站方規定，故已',
            '{{$user->name}}你好，由於您的現況不符站方規定，故已',
            '{{$user->name}}你好，由於您的關於我不符站方規定，故已',
            '{{$user->name}}你好，由於您的期待的約會模式不符站方規定，故已',
            '{{$user->name}}你好，由於您的教育不符站方規定，故已',
            // '{{$user->name}}你好，由於您的婚姻不符站方規定，故已',
            // '{{$user->name}}你好，由於您的喝酒不符站方規定，故已',
            // '{{$user->name}}你好，由於您的抽菸不符站方規定，故已',
            '{{$user->name}}你好，由於您的職業不符站方規定，故已',
            '{{$user->name}}你好，由於您的資產不符站方規定，故已',
            '{{$user->name}}你好，由於您的年收不符站方規定，故已'
        ],
        edit = '修改。', del = '刪除。', view='檢視', create='新增',
        report_user = ['{{$user->name}}', '{{$to_user->name}}']
        now_time = new Date();

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
            ),
            $(".report_user").click(
                function () {
                    let i = $(".report_user").index(this);
                    $('#msg').val(report_user[i]);
                }
            ),
            $(".now_time").click(
                function () {
                    $('#msg').val($('#msg').val() + now_time);
                }
            ),
        );



        $(document).ready(
            $(".tpl2").click(
                function () {
                    let i = $(".tpl2").index(this);
                    $('#msg2').val(template[i]);
                }
            ),
            $(".edit2").click(
                function () {
                    $('#msg2').val($('#msg2').val() + edit);
                }
            ),
            $(".del2").click(
                function () {
                    $('#msg2').val($('#msg2').val() + del);
                }
            ),
            $(".report_user2").click(
                function () {
                    let i = $(".report_user2").index(this);
                    $('#msg2').val(report_user[i]);
                }
            ),
            $(".now_time2").click(
                function () {
                    $('#msg2').val($('#msg2').val() + now_time);
                }
            )
        );

        // function formatDate(date) {
        //   var hours = date.getHours();
        //   var minutes = date.getMinutes();
        //   var ampm = hours >= 12 ? 'pm' : 'am';
        //   hours = hours % 12;
        //   hours = hours ? hours : 12; // the hour '0' should be '12'
        //   minutes = minutes < 10 ? '0'+minutes : minutes;
        //   var strTime = hours + ':' + minutes + ' ' + ampm;
        //   return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;
        // }
        // var d = new Date().formatDate();
    </script>
    <script>
        $(".str_type").on('click', function(){
            var text = $(this).text();
            var str_type = $(this).parent().find('.msg').append(text);
            // var selector = '.'.str_type;
            // $(selector).text(text);
        });
    </script>
@endif