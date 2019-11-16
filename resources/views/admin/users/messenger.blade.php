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
            
            
            <table class="table table-bordered table-hover">
            <h1 class="message_block">訊息列表</h1><a href="/admin/users/message/msglib/create"><div class="btn btn-success message_block">新增</div></a>
                <tr>
                    <td>訊息標題</td>
                        <td></td>
                    <td>訊息內容</td>
                </tr>
                @foreach($msglib_report as $msglib_report)
                <tr>
                    <td>{{$msglib_report->title}}</td>
                        <td class="btn btn_edit btn-success" id="{{$msglib_report->id}}"><a href="/admin/users/message/msglib/create/{{$msglib_report->id}}" style="color:white">編輯</a></td>
                        <td class="btn btn_del btn-danger" id="{{$msglib_report->id}}">刪除</td>
                    <td>{{$msglib_report->msg}}</td>
                </tr>
                @endforeach
            </table>

            
            
            <h1>發送站長訊息給{{ $user->name}}(收件者)</h1>
            <!-- <button class="savebtn btn btn-primary">儲存</button> -->
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>預設選項</td>
                        <td></td>
                        <td></td>
                        <td>
                            <form id="idForm">
                                @foreach($msglib as $msglib)
                                    <div class="btn btn-success com_tpl tpl" id="{{$msglib->id}}">{{$msglib->title}}</div>
                                @endforeach
                            </form>
                            
                        </td>
                    </tr>
                
                    <!-- <tr>
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
                    </tr> -->
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
            <table class="table table-bordered table-hover">
            <h1 class="message_block">訊息列表</h1><a href="/admin/users/message/msglib/create"><div class="btn btn-success message_block">新增</div></a>
                <tr>
                    <td>訊息標題</td>
                        <td></td>
                    <td>訊息內容</td>
                </tr>
                @foreach($msglib_reported as $msglib_reported)
                <tr>
                    <td>{{$msglib_reported->title}}</td>
                        <td class="btn btn_edit btn-success" id="{{$msglib_reported->id}}"><a href="/admin/users/message/msglib/create/{{$msglib_reported->id}}" style="color:white">編輯</a></td>
                        <td class="btn btn_del btn-danger" id="{{$msglib_reported->id}}">刪除</td>
                    <td>{{$msglib_reported->msg}}</td>
                </tr>
            @endforeach
            </table>
            
            <h1>發送站長訊息給{{$to_user->name}}(發訊者)</h1>
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>預設選項</td>
                        <td>
                            <form id="idForm">
                            @foreach($msglib2 as $msglib2)
                                <div class="btn btn-success com_tpl tpl2" id="{{$msglib->id}}">{{$msglib2->title}}</div>
                            @endforeach
                            </form>
                            <!-- <button class="btn btn-success tpl2">檢舉沒問題2</button>
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
                            <button class="btn btn-success tpl2">教育</button> -->
                            <!-- <button class="btn btn-success tpl2">婚姻</button>
                            <button class="btn btn-success tpl2">喝酒</button>
                            <button class="btn btn-success tpl2">抽菸</button> -->
                            <!-- <button class="btn btn-success tpl2">職業</button>
                            <button class="btn btn-success tpl2">資產</button>
                            <button class="btn btn-success tpl2">年收</button> -->
                        </td>
                    </tr>
                    <!-- <tr>
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
                    </tr> -->
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

        // console.log("{{$msglib}}");
        // var msglib = <?php echo json_encode($msglib); ?>;
        // var msglib = JSON.parse(<?php echo json_encode($msglib); ?>);
        // console.log(msglib);
        // msglib.forEach(function(element) {
        //   console.log(element);
        // });
        let template = {!! json_encode($msglib_msg) !!};
        let template2 = {!! json_encode($msglib_msg2) !!};
        // console.log(template);
        // let template = [
            // '{{$user->name }}您好，您先前所檢舉，由{{ $to_user->name }}於{{ $message->created_at }}發送的訊息，站長已檢視，認為並無問題，若有疑慮請來訊。',
            // '{{ $user->name }}您好，您先前在{{ $message->created_at }}檢舉了會員「{{ $to_user->name }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊',
            // '{{$to_user->name}}您好，您被檢舉，站長認為並無問題，若有疑慮請來訊。',
            // '{{$to_user->name}}您好，您被檢舉圖片/大頭照，站長認為並無問題，若有疑慮請來訊。',
            // '{{ $user->name }}您好，您先前所檢舉{{ $to_user->name }}的圖片/大頭照，站長已檢視，認為並無問題，若有疑慮請來訊。',


            // '{{$user->name}}你好，由於您的暱稱不符站方規定，故已',
            // '{{$user->name}}你好，由於您的標題不符站方規定，故已',
            // '{{$user->name}}你好，由於您的身高不符站方規定，故已',
            // '{{$user->name}}你好，由於您的職業不符站方規定，故已',
            // '{{$user->name}}你好，由於您的體重不符站方規定，故已',
            // '{{$user->name}}你好，由於您的罩杯不符站方規定，故已',
            // '{{$user->name}}你好，由於您的體型不符站方規定，故已',
            // '{{$user->name}}你好，由於您的現況不符站方規定，故已',
            // '{{$user->name}}你好，由於您的關於我不符站方規定，故已',
            // '{{$user->name}}你好，由於您的期待的約會模式不符站方規定，故已',
            // '{{$user->name}}你好，由於您的教育不符站方規定，故已',
            // '{{$user->name}}你好，由於您的婚姻不符站方規定，故已',
            // '{{$user->name}}你好，由於您的喝酒不符站方規定，故已',
            // '{{$user->name}}你好，由於您的抽菸不符站方規定，故已',
            // '{{$user->name}}你好，由於您的職業不符站方規定，故已',
            // '{{$user->name}}你好，由於您的資產不符站方規定，故已',
            // '{{$user->name}}你好，由於您的年收不符站方規定，故已'
        // ];
        let edit = '修改。', del = '刪除。', view='檢視', create='新增',
        report_user = ['{{$user->name}}', '{{$to_user->name}}']
        now_time = new Date();

        $(document).ready(
            $(".tpl").click(
                function () {
                    let i = $(".tpl").index(this);
                    console.log(i);
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
            // $(".com_tpl").dblclick(
            //     function(){
            //         // console.log($(this).attr('id'));
            //         var id = $(this).attr('id');
            //         $.ajax({
            //             type: 'POST',
            //             url: "/admin/users/getmsglib",
            //             data:{
            //                 _token: '{{csrf_token()}}',
            //                 id : id,
            //             },
            //             dataType:"json",
            //             success: function(res){
            //                 console.log(res[0].msg);
            //                 var selector = ".com_tpl[id="+id+"]";
            //                 var str = '<input type="text" name="'+res[0].id+'" value="'+res[0].msg+'" />'
            //                 $(selector).html(str);
            //             // $(this).html(res);
            //           }});
            //     }),
        );



        $(document).ready(
            $(".tpl2").click(
                function () {
                    let i = $(".tpl2").index(this);
                    $('#msg2').val(template2[i]);
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
@endif