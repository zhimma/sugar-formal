@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<style>
    .message_block
    {
        display:inline;
    }
</style>
<body style="padding: 15px;">
@include('partials.errors')
@include('partials.message')
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        @if(!isset($msgs))
            @if(isset($msglib))
                <table class="table table-bordered table-hover 11">
                    <h1 class="message_block">訊息範本列表</h1><a href="/admin/users/message/msglib/create/editPic_sendMsg" target="_blank"><div class="btn btn-success message_block">新增</div></a>
                    <br>
                    <tr>
                        <td>範本選項標題</td>
                        <td></td>
                        <td>範本內容</td>
                    </tr>
                    @foreach($msglib as $msglib_report)
                        <tr>
                            <td>{{$msglib_report->title}}</td>
                            <td class="btn btn_edit btn-success" id="{{$msglib_report->id}}"><a href="/admin/users/message/msglib/create/editPic_sendMsg/{{$msglib_report->id}}" style="color:white" target="_blank">編輯</a></td>
                            <td class="btn btn_del btn-danger" id="{{$msglib_report->id}}">刪除</td>
                            <td>{{$msglib_report->msg}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if (Auth::user()->can('admin'))
                <form action="{{ route('admin/send', (!isset($isReported))? $user->id : $isReportedId ) }}" id='message' method='POST'>
                    @elseif (Auth::user()->can('readonly'))

                    @endif
                        <h1>發送站長訊息給 {{$user->name}}(收件者)</h1>
                        <table class="table table-bordered table-hover 33">
                            <tr>
                                <td nowrap>範本選項</td>
                                <td>
                                    <form id="idForm">
                                        @foreach($msglib as $msglib)
                                            <div class="btn btn-success com_tpl tpl2" id="{{$msglib->id}}">{{$msglib->title}}</div>
                                        @endforeach
                                    </form>
                                </td>
                            </tr>
                        </table>

                        <form action="{{ route('admin/send', (!isset($isReported))? $user->id : $isReportedId ) }}" id='message' method='POST'>
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                            @if(isset($isPic) && ($isPic))
                                @if(isset($isReported))
                                    <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{ $reportedName }}您好，您被檢舉圖片/大頭照，站長認為並無問題，若有疑慮請來訊。</textarea>
                                @else
                                    <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{ $user->name }}您好，您先前所檢舉{{ $reportedName }}的圖片/大頭照，站長已檢視，認為並無問題，若有疑慮請來訊。</textarea>
                                @endif
                            @elseif(isset($isReported))
                                <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">{{ $reportedName }}您好，您被檢舉，站長認為並無問題，若有疑慮請來訊。</textarea>
                            @else
                                <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5">@if(isset($message) && !isset($report)){{ $user->name }}您好，您先前所檢舉，由{{ $senderName }}於{{ $message->created_at }}發送的訊息，站長已檢視，認為並無問題，若有疑慮請來訊。@elseif(isset($message) && isset($report)) {{ $user->name }}您好，您先前在{{ $report->created_at }}檢舉了會員「{{ $reportedName }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊。 @endif</textarea>
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
                            @if (Auth::user()->can('readonly'))
                                <form action="{{ route('admin/send/multiple/readOnly') }}" id='message' method='POST'>
                                    <input type="hidden" value="back" name="back">
                                    @else
                                        <form action="{{ route('admin/send/multiple') }}" id='message' method='POST'>
                                            @endif
                                            {!! csrf_field() !!}
                                            <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                                            @if($msgs != 0)
                                                @foreach( $msgs as $msg )
                                                    <h3 style="text-align: left">發送給{{ $msg['user_name'] }}</h3>
                                                    <input class="name3" type="hidden" value="{{ $msg['user_name'] }}">
                                                    <input class="time3" type="hidden" value="{{ $msg['post_time'] }}">
                                                    <input type="hidden" value="{{ $msg['user_id'] }}" name="to[]">
                                                    <textarea name="msg[]" id="msg3" class="form-control msg3" cols="80" rows="5">{{ $msg['user_name'] }}{{ $template['pic']['head'] }}{{ $msg['post_time'] }}{{ $template['pic']['body'] }}</textarea><br>
                                                @endforeach
                                            @endif
                                            @if($msgs2 != 0)
                                                @foreach( $msgs2 as $msg )
                                                    <h3 style="text-align: left">發送給{{ $msg['user_name'] }}</h3>
                                                    <input class="name3" type="hidden" value="{{ $msg['user_name'] }}">
                                                    <input type="hidden" value="{{ $msg['user_id'] }}" name="to[]">
                                                    <textarea name="msg[]" id="msg3" class="form-control msg3" cols="80" rows="5">{{ $msg['user_name'] }}{{ $template['avatar']['head'] }}{{ $template['avatar']['body'] }}</textarea><br>
                                                @endforeach
                                            @endif
                                            <button type='submit' class='text-white btn btn-primary'>送出</button>
                                        </form>
                                        @else
                                            @if (Auth::user()->can('readonly'))
                                                <form action="{{ route('admin/send/multiple/readOnly') }}" id='message' method='POST'>
                                                    <input type="hidden" value="back" name="back">
                                                    @else
                                                        <form action="{{ route('admin/send/multiple') }}" id='message' method='POST'>
                                                            @endif
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                                                            @foreach( $msgs as $msg )
                                                                <h3 style="text-align: left">發送給{{ $msg['name'] }}</h3>
                                                                <input class="name3" type="hidden" value="{{ $msg['name'] }}">
                                                                <input class="time3" type="hidden" value="{{ $msg['post_time'] }}">
                                                                <input type="hidden" value="{{ $msg['from_id'] }}" name="to[]">
                                                                <textarea name="msg[]" id="msg3" class="form-control msg3" cols="80" rows="5">{{ $msg['name'] }}{{ $template['head'] }}{{ $msg['post_time'] }}{{ $template['body'] }}</textarea><br>
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

        // console.log("{{$msglib}}");
        // var msglib = <?php echo json_encode($msglib); ?>;
        // var msglib = JSON.parse(<?php echo json_encode($msglib); ?>);
        // console.log(msglib);
        // msglib.forEach(function(element) {
        //   console.log(element);
        // });
        let template = {!! json_encode($msglib_msg) !!};

        // console.log(template);

        let edit = '修改。', del = '刪除。', view='檢視', create='新增',
            report_user = ['{{$from_user->name}}', '{{$user->name}}']
        now_time = new Date();

        $(document).ready(
            $(".tpl").click(
                function () {
                    let i = $(".tpl").index(this);
                    template[i].replace("$user",'111222333');
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

        $(".savebtn").click(function(){
            $.ajax({
                type: 'POST',
                url: "/admin/users/updatemsglib",
                data:{
                    _token: '{{csrf_token()}}',
                    formdata: $("#idForm").serialize(),
                },
                dataType:"json",
                success: function(res){
                    alert('更新成功');
                    location.reload();

                }});
        });
    </script>
    <script>
        $(".str_type").on('click', function(){
            var text = $(this).text();
            var str_type = $(this).parent().find('.msg').append(text);
            // var selector = '.'.str_type;
            // $(selector).text(text);
        });
        $(".btn_del").on('click', function(){
            var id = $(this).attr('id');
            var r=confirm("刪除訊息？")
            if (r==true)
            {
                $.ajax({
                    type: 'POST',
                    url: "/admin/users/delmsglib",
                    data:{
                        _token: '{{csrf_token()}}',
                        id    : $(this).attr('id'),
                    },
                    dataType:"json",
                    success: function(res){
                        alert('刪除成功');
                        location.reload();

                    }});
            }else
            {
                alert('刪除失敗');
            }

        });
    </script>

@else
    <script>
        let template3 = {!! json_encode($msglib_delpic) !!};
        $(document).ready(
            $(".tpl3").click(
                function () {
                    let i = $(".tpl3").index(this);
                    msg = template3[i].msg;
                    $('.msg3').each(function (k, v) {
                        name = $('.name3').eq(k).val();
                        time = $('.time3').eq(k).val();
                        time = time?time:'';
                        msg2 = msg;
                        msg2 = msg2.replace(/NAME/g, name);
                        msg2 = msg2.replace(/\|$report\|/g, name);
                        msg2 = msg2.replace(/TIME/g, time);
                        msg2 = msg2.replace(/\|$responseTime\|/g, time);
                        msg2 = msg2.replace(/\|$reportTime\|/g, time);
                        msg2 = msg2.replace(/LINE_ICON/g, '<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a>');
                        msg2 = msg2.replace(/\|$lineIcon\|/g, '<a href="https://lin.ee/rLqcCns"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png" alt="加入好友" height="36" border="0" style="height: 36px; float: unset;"></a>');
                        $('.msg3').eq(k).val(msg2);
                    });                
                }
            )
        );
        $(".btn_del").on('click', function(){
            var id = $(this).attr('id');
            var r=confirm("刪除訊息？")
            if (r==true){
                $.ajax({
                    type: 'POST',
                    url: "/admin/users/delmsglib",
                    data:{
                        _token: '{{csrf_token()}}',
                        id    : $(this).attr('id'),
                    },
                    dataType:"json",
                    success: function(res){
                        alert('刪除成功');
                        location.reload();

                    }});
            }else{
                alert('刪除失敗');
            }
        });
    </script>
@endif
