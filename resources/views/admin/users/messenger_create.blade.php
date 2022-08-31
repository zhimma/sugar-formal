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
.template-descriptions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    row-gap: 5px;
    max-width: 300px;
    margin: 20px 0;
}

.template-descriptions dt {
    color: #555;
}

.template-descriptions dt, .template-descriptions dd {
    display: block;
    margin-bottom: 0;
    font-weight: normal;
}

.template-descriptions dd {
    font-weight: bold;
}
</style>
@php
    use App\Models\Msglib;
    $kind = strtolower(request()->query('kind'));
    $isAnonymousContentMode = $kind === Msglib::KIND_ANONYMOUS;
@endphp
<body style="padding: 15px;">
@include('partials.errors')
@include('partials.message')
<table class="table table-bordered table-hover">
    <h1>{{$page_title}}</h1>
    @if(str_contains(url()->current(), 'delpic'))
        <p>變數設定說明： 被刪除會員的名字 NAME，現在時間 NOW_TIME，照片創建時間 TIME (刪大頭照無照片時間)，line加入好友圖示 LINE_ICON</p>
        <p>範例:NAME您好，由於您在TIME上傳的照片不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡：LINE_ICON。</p>
        <p>範例:NAME您好，由於您的大頭照不適合網站主旨，故已刪除。請重新上傳。如有疑慮請與站長聯絡：LINE_ICON。</p>
    @elseif(str_contains(url()->current(), 'editPic_sendMsg'))
        @if ($isAnonymousContentMode)
            <dl class="template-descriptions">
                <dt>檢舉時間</dt>
                <dd>TIME</dd>
                <dt>站長發訊時間</dt>
                <dd>NOW_TIME</dd>
                <dt>評價者暱稱</dt>
                <dd>NAME</dd>
                <dt>被評價者暱稱</dt>
                <dd>TO_NAME</dd>
            </dl>
            <p>範例: NAME您好，您於TIME提交給TO_NAME的評論，由於內容不適合網站主旨，故已在NOW_TIME刪除。如有疑慮請與站長聯絡：LINE_ICON。</p>
        @else
            <p>變數設定說明： 被會員的名字 NAME ，現在時間 NOW_TIME，line加入好友圖示 LINE_ICON。</p>
            <p>範例:NAME您好，由於您上傳的照片不適合網站主旨，故已在NOW_TIME刪除。請重新上傳。如有疑慮請與站長聯絡：LINE_ICON。</p>
        @endif
    @elseif(str_contains(url()->current(), 'editRealAuth_sendMsg'))
        <p>變數設定說明： 被會員的名字 NAME ，現在日期 NOW_DATE，line加入好友圖示 LINE_ICON，本人認證 SELF_AUTH，美顏推薦 BEAUTY_AUTH，名人認證 FAMOUS_AUTH，認證申請日期 APPLY_DATE，補交項目的「請點我」連結 PATCH_LINK。</p>
        <p>範例:NAME您好，您於APPLY_DATE的SELF_AUTH申請，經站長在NOW_DATE審核，需要您補充部分資料，[PATCH_LINK]，再麻煩您了。</p>    
    @else
        <p>檢舉者變數|$report|，被檢舉者變數|$reported|，line加入好友圖示|$lineIcon|   ，範例:|$report|在|$reportTime|檢舉|$reported|，經站長在|$responseTime|判別沒有問題。如有疑慮請與站長聯絡：|$lineIcon|。</p>
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
                @if ($isAnonymousContentMode)
                    <option value="{{ Msglib::KIND_ANONYMOUS }}" selected>匿名評價回復訊息</option>
                @else
                    <option value="{{ Msglib::KIND_SMSG }}" selected>站長訊息</option>
                @endif
            @elseif(str_contains(url()->current(), 'editRealAuth_sendMsg'))
                <option value="real_auth" selected>本人證認/美顏推薦/名人認證</option>
            @else
                <option value="report" @if(str_contains(url()->current(), 'reporter')) selected @endif>檢舉者</option>
                <option value="reported" @if(str_contains(url()->current(), 'reported')) selected @endif>被檢舉者</option>
            @endif
            
        </select>       
        範本選項標題<input type="text" name="title_msglib" id="msglib_title" value="{{$title??''}}"><br>
        範本內容<textarea name="textarea_msglib" id="msglib_content" class="form-control" cols="80" rows="5">{{$msg??''}}</textarea>
    </form>
    </table>
<script>
    $(".savemsgbtn").click(function(){
    $.ajax({
        type: 'POST',
        url: "/admin/users/addmsglib?{{csrf_token()}}={{now()->timestamp}}",
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