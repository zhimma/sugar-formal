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
    <h1>{{$page_title}}</h1>
    @if(str_contains(url()->current(), 'delpic'))
        <p>變數設定說明： 被刪除會員的名字 NAME ，照片創建時間 TIME (刪大頭照無照片時間)</p>
        <p>範例:NAME您好，由於您在TIME上傳的照片不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡。</p>
        <p>範例:NAME您好，由於您的大頭照不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡。</p>
    @elseif(str_contains(url()->current(), 'editPic_sendMsg'))
        <p>變數設定說明： 被會員的名字 NAME ，現在時間 NOW_TIME</p>
        <p>範例:NAME您好，由於您上傳的照片不適合網站主旨，故已在NOW_TIME刪除。請重新上傳。如有疑慮請與站長聯絡。</p>
    @else
        <p>檢舉者變數|$report|，被檢舉者變數|$reported|   ，範例:|$report|在|$reportTime|檢舉|$reported|，經站長在|$responseTime|判別沒有問題。</p>
    @endif
    <button class="savemsgbtn btn btn-primary">儲存</button>
    <form action="" id='msglibform' method='POST'>
        {!! csrf_field() !!}
        <input type="hidden" name="msg_id" id="msg_id" value="{{$msg_id??''}}">
        種類
        <select name="kind" id="kind">
            @if(str_contains(url()->current(), 'delpic'))
                <option value="delpic" selected>照片刪除</option>
            @elseif(str_contains(url()->current(), 'editPic_sendMsg'))
                <option value="smsg" selected>站長訊息</option>
            @else
                <option value="report" @if(str_contains(url()->current(), 'reporter')) selected @endif>檢舉者</option>
                <option value="reported" @if(str_contains(url()->current(), 'reported')) selected @endif>被檢舉者</option>
            @endif
            
        </select>
        標題<input type="text" name="title_msglib" id="msglib_title" value="{{$title??''}}"></br>
        訊息<textarea name="textarea_msglib" id="msglib_content" class="form-control" cols="80" rows="5">{{$msg??''}}</textarea>
    </form>
    </table>
<script>
    $(".savemsgbtn").click(function(){
    $.ajax({
        type: 'POST',
        url: "/admin/users/addmsglib",
        data:{
            _token: '{{csrf_token()}}',
            'msg_id'   : $('#msg_id').val(),
            'kind'   : $('#kind').val(),
            'title':$('#msglib_title').val(),
            'content':$('#msglib_content').val(),
        },
        dataType:"json",
        success: function(res){
            alert('更新成功');
            location.reload();
      }});
    // $("#msglibform").submit();
    });
    var isEdit = "{{$isEdit??false}}"
    if(isEdit){
        $("#kind").attr('disabled', 'disabled');
    }
</script>