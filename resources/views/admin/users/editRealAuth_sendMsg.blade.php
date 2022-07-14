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
    <table class="table table-bordered table-hover" id="msglib_table">
        <h1 class="message_block">訊息範本列表</h1><a href="{{route('admin/addMessageLibRealAuth')}}" target="_blank"><div class="btn btn-success message_block">新增</div></a>
        <br>
        <tr>
            <td>範本選項標題</td>
            <td></td>
            <td>範本內容</td>
        </tr>
        @forelse($msglib_report as $msglib_report)
            <tr>
                <td>{{$msglib_report->title}}</td>
                <td class="btn btn_edit btn-success" id="{{$msglib_report->id}}"><a href="/admin/users/message/msglib/create/editPic_sendMsg/{{$msglib_report->id}}" style="color:white" target="_blank">編輯</a></td>
                <td class="btn btn_del btn-danger" id="{{$msglib_report->id}}">刪除</td>
                <td class="msglib_msg">{{$msglib_report->msg}}</td>
            </tr>
        @empty
            <tr><td>目前沒有範本選項</td></tr>
        @endforelse
    </table>
    <h1>發送站長訊息給 -> {{ $from_user->name}}</h1>
    <table class="table table-bordered table-hover">
        <tr>
            <td nowrap>補交項目</td>
            <td>
                <div>
                補交項目：
                    <input type="radio" class="patch_type" name="patch_type"  value=2 onclick="document.getElementById('item_id').value=this.value;">基本資料
                    <input type="radio" class="patch_type" name="patch_type" value=3 onclick="document.getElementById('item_id').value=this.value;">照片
                    <input type="radio" class="patch_type" name="patch_type" value=4 onclick="document.getElementById('item_id').value=this.value;">重錄視頻
                    <input type="radio" class="patch_type" name="patch_type" value=5 onclick="document.getElementById('item_id').value=this.value;">表單必填資料                    
                </div>               
            
            </td>
        </tr>
        <tr>
            <td nowrap>範本選項</td>
            <td>
                <form id="idForm">
                    @forelse($msglib as $msglib)
                        <div class="btn btn-success com_tpl tpl" id="{{$msglib->id}}">{{$msglib->title}}</div>
                    @empty
                        目前沒有範本選項
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
        <input type="hidden" name="auth_type_id" id="auth_type_id" value="" />
        <input type="hidden" name="item_id" id="item_id" value="" />
        <textarea name="msg" id="msg" class="form-control" cols="80" rows="5">
        </textarea>
        <br>
        <button type='submit' class='text-white btn btn-primary' onclick="set_auth_type_id(this);return false;">送出</button>
    </form>

    <hr>
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

        function set_auth_type_id(dom) {
            var form_dom = dom.form;
            var msg_dom = form_dom.msg;
            var auth_type_id_dom = form_dom.auth_type_id;
            var msg_str = msg_dom.value;
            
            if(msg_str.search('SELF_AUTH')>=0
                || msg_str.search('本人認證')>=0
            ){
                auth_type_id_dom.value=1;
            }
            else if(msg_str.search('BEAUTY_AUTH')>=0
                || msg_str.search('美顏推薦')>=0
            ) {
                auth_type_id_dom.value=2;
            }
            else if(msg_str.search('FAMOUS_AUTH')>=0
                || msg_str.search('名人認證')>=0
            ) {
                auth_type_id_dom.value=3;
            } 

            form_dom.submit();
        }

        $(document).ready(
            $(".tpl").click(
                function () {
                    let i = $(".tpl").index(this);
                    template[i].replace("$user",'111222333');
                    var patch_type_str = '';
                    var msglib_msg = $('.msglib_msg');
                    var profile_manage_url = '{!! url('dashboard') !!}';
                    var pic_manage_url = '{!! url('dashboard_img') !!}';
                    var template_str = template[i];
                    if(template[i].search('PATCH_LINK')>=0) {
                        $('.patch_type:checked').each(function(index){
                            var patch_type_val = $(this).val();
                            document.getElementById('item_id').value=patch_type_val;
                            switch(patch_type_val) {
                                case '2':
                                    template_str = template_str.replace('PATCH_LINK','<a href="'+profile_manage_url+'">請點我</a>');
                                break;
                                case '3':
                                    template_str = template_str.replace('PATCH_LINK','<a href="'+pic_manage_url+'">請點我</a>');
                                break;
                                case '5':
                                    if(msglib_msg.eq(i).html().search('BEAUTY_AUTH')>=0) 
                                    {
                                        template_str = template_str.replace('PATCH_LINK','<a href="{{route('beauty_auth')}}">請點我</a>');
                                    }
                                    else if(msglib_msg.eq(i).html().search('FAMOUS_AUTH')>=0) 
                                    {
                                        template_str = template_str.replace('PATCH_LINK','<a href="{{route('famous_auth')}}">請點我</a>');
                                    }
                                break;
                                case '4':
                                    template_str = template_str.replace('PATCH_LINK','<a href="{{url('user_video_chat_verify')}}">請點我</a>');
                                break;                            
                            }
                        
                        });
                    }

                    $('#msg').val(template_str);
                    error_msg = '';
                    if(template_str.search('APPLY_DATE')>=0) {
                            error_msg+='申請日期：APPLY_DATE變數轉換失敗，請確認此會員是否已提出認證申請，以免誤發訊息。';
                    }
                    
                    if(template_str.search('PATCH_LINK')>=0) {
                            if(error_msg!='') error_msg+='\n\n'
                            error_msg+='補交項目：PATCH_LINK變數轉換失敗，請勾選補交項目的選項或檢查認證方式是否包含所選取的補交項目，以免誤發訊息。';
                    }                    
                    
                    if(error_msg!='') alert(error_msg);
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
                url: "/admin/users/updatemsglib?{{csrf_token()}}={{now()->timestamp}}",
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
                url: "/admin/users/delmsglib?{{csrf_token()}}={{now()->timestamp}}",
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

