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
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">封鎖 {{$bannedUser['name']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="/admin/users/banUserWithDayAndMessage" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="user_id" value="{{$bannedUser['id']}}">
        <input type="hidden" name="msg_id" value="@if(isset($msg['id'])) {{ $msg['id'] }} @endif">
        <input type="hidden" name="isReported" value="{{$isReported}}">
        <div class="modal-body">
            封鎖時間
            <select name="days" class="days">
                <option value="3">三天</option>
                <option value="7">七天</option>
                <option value="15">十五天</option>
                <option value="30">三十天</option>
                <option value="X" selected>永久</option>
            </select>
            <hr>
            封鎖原因
            <a class="text-white btn btn-success advertising">廣告</a>
            <a class="text-white btn btn-success improper-behavior">非徵求包養行為</a>
            <a class="text-white btn btn-success improper-words">用詞不當</a>
            <a class="text-white btn btn-success improper-photo">照片不當</a>
            <br><br>
            <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
        </div>
        <div class="modal-footer">
            <input type="submit" id="send_blockade" class="btn btn-outline-success ban-user" value="送出">
            <button type="button" class="btn btn-outline-danger cancel" data-dismiss="modal">取消</button>
        </div>  
    </form>
        
</div>
<script>
    jQuery(document).ready(function() {
        $('.cancel').on('click', function() {
            window.close();
        });
        $('.advertising').on('click', function() {
            $('.m-reason').val('廣告');
        });
        $('.improper-behavior').on('click', function() {
            $('.m-reason').val('非徵求包養行為');
        });
        $('.improper-words').on('click', function() {
            $('.m-reason').val('用詞不當');
        });
        $('.improper-photo').on('click', function() {
            $('.m-reason').val('照片不當');
        });
    });


</script>