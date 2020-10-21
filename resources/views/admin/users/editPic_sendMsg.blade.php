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
    <table class="table table-bordered table-hover">
        <h1 class="message_block">訊息列表</h1><a href="/admin/users/message/msglib/create/editPic_sendMsg" target="_blank"><div class="btn btn-success message_block">新增</div></a>
        <br>
        <tr>
            <td>訊息標題</td>
            <td></td>
            <td>訊息內容</td>
        </tr>
        @forelse($msglib_report as $msglib_report)
            <tr>
                <td>{{$msglib_report->title}}</td>
                <td class="btn btn_edit btn-success" id="{{$msglib_report->id}}"><a href="/admin/users/message/msglib/create/editPic_sendMsg/{{$msglib_report->id}}" style="color:white" target="_blank">編輯</a></td>
                <td class="btn btn_del btn-danger" id="{{$msglib_report->id}}">刪除</td>
                <td>{{$msglib_report->msg}}</td>
            </tr>
        @empty
            <tr><td>目前沒有預設選項</td></tr>
        @endforelse
    </table>
    <h1>發送站長訊息給 -> {{ $from_user->name}}</h1>
    <table class="table table-bordered table-hover">
        <tr>
            <td>預設選項</td>
            <td>
                <form id="idForm">
                    @forelse($msglib as $msglib)
                        <div class="btn btn-success com_tpl tpl" id="{{$msglib->id}}">{{$msglib->title}}</div>
                    @empty
                        目前沒有預設選項
                    @endforelse
                </form>
                
            </td>
        </tr>
    </table>
    @if (Auth::user()->can('readonly'))
        <form action="{{ route('admin/send/readOnly', (!isset($isReported))? $user->id : $isReportedId ) }}" id='message' method='POST'>
    @else
        <form action="{{ route('admin/send', (!isset($isReported))? $user->id : $isReportedId ) }}" id='message' method='POST'>
    @endif
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
            <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">@if(isset($message) && !isset($report)){{ $from_user->name }}您好，您先前所檢舉，由{{ $senderName }}於{{ $message->created_at }}發送的訊息，站長已檢視，認為並無問題，若有疑慮請來訊。@elseif(isset($message) && isset($report)) {{ $from_user->name }}您好，您先前在{{ $report->created_at }}檢舉了會員「{{ $reportedName }}」，經站長檢視理由，認為此會員並無問題，若有疑慮請來訊。 @endif</textarea>
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

    <hr>
    
    <h1>{{ $user->name }}的所有資料</h1>
    <h4>基本資料</h4>
    <table class='table table-hover table-bordered'>
        <tr>
            <th>會員ID</th>
            <th>暱稱</th>
            <th>頭像照</th>
            <th>標題</th>
            <th>男/女</th>
            <th>Email</th>
            <th>建立時間</th>
            <th>更新時間</th>
            <th>上次登入</th>
        </tr>
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>@if($userMeta->pic) <img src="{{$userMeta->pic}}" width='100px'> @else 無 @endif</td>
            <td>{{ $user->title }}</td>
            <td>@if($user->engroup==1) 男 @else 女 @endif</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
            <td>{{ $user->last_login }}</td>
        </tr>
    </table>

    {{-- <form method="POST" action="/dashboard/header" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="userId" value="{{$user->id}}">
        <table class="table table-hover table-bordered">
            <tr>
                <td>
                    <label class="col-form-label twzip" for="image">變更頭像照</label>
                </td>
                <td>
                    <label class="custom-file">
                        <input required type="file" id="image" class="custom-file-input" name="image" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                        <span class="custom-file-control"></span>
                    </label>
                </td>
                <td>
                    <button type="submit" class="btn btn-success">上傳</button>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </form> --}}

    <table class="table table-hover table-bordered">
        <tr>
            <td>
                <label class="col-form-label twzip" for="image">變更頭像照</label>
            </td>
            <form method="POST" action="/dashboard/header/1" enctype="multipart/form-data">
		        {!! csrf_field() !!}
		        <input type="hidden" name="userId" value="{{$user->id}}">
                <td>
                    <label class="custom-file">
                        <input required type="file" id="image" class="custom-file-input" name="image" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                        <span class="custom-file-control"></span>
                    </label>
                </td>
                <td>
                    <button type="submit" class="btn btn-success">上傳</button>&nbsp;&nbsp;
                </td>
    		</form>
    		<td>
                @if (Auth::user()->can('readonly'))
                    <form action="{{ route('users/pictures/modify/readOnly') }}" method="POST" target="_blank">
                @else
                    <form action="/admin/users/pictures/modify" method="POST" target="_blank">
                @endif
    			    {!! csrf_field() !!}
    			    <input class="btn btn-danger btn-delete" type="submit" value="刪除大頭照"><br>
    			    <input type="hidden" name="delete" value="true">
    			    <input type="hidden" name="avatar_id" value="{{$user->id}}">
    			</form>
    		</td>
        </tr>
    </table>


    <form method="POST" action="/dashboard/image/1" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="userId" value="{{$user->id}}">
        <table class="table table-hover table-bordered">
            <tr>
                <td><label class="col-form-label twzip" for="images">新增生活照</label></td>
                <td class="input_field_weap">
                    <label class="custom-file">
                        <input type="file" id="images" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                        <span class="custom-file-control"></span>
                    </label>
                    <button type="button" id="add_image" class="" name="button">+</button>
                </td>
                <td>
                    <button id="image-submit" type="submit" class="btn btn-success upload-submit">上傳</button>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </form>
    <h4>現有生活照</h4>
    <?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
    <table class="table table-hover table-bordered" style="width: 50%;">
        @forelse ($pics as $pic)
            <tr>
                <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/imagedel/1">
                    <td>
                        {!! csrf_field() !!}
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <input type="hidden" name="imgId" value="{{$pic->id}}">
                        <div style="width:400px">
                            <img src="{{$pic->pic}}" />
                        </div>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-metal">刪除</button>
                    </td>
                </form>
            </tr>
        @empty
            此會員目前沒有生活照
        @endforelse
    </table>

</body>
    <script>
        let wrapper         = $(".input_field_weap");
        let add_button      = $("#add_image"); //Add button ID
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
                $(wrapper).append('<div><label class="custom-file"><input type="file" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val())"><span class="custom-file-control"></span></label><a href="#" class="remove_field">&nbsp;Remove</a></div>'); //add input box
        });
        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
        });

        let template = {!! json_encode($msglib_msg) !!};
        @if(isset($msglib_msg2))
                let template2 = {!! json_encode($msglib_msg2) !!};
        @endif

        let edit = '修改。', del = '刪除。', view='檢視', create='新增',
        report_user = ['{{$from_user->name}}', '{{$to_user->name}}']
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
    </script>
    <script>
        $(".str_type").on('click', function(){
            var text = $(this).text();
            var str_type = $(this).parent().find('.msg').append(text);
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

