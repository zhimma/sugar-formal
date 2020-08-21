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
        <h5 class="modal-title" id="exampleModalLabel">站方警示 {{$warnedUser['name']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="/admin/users/toggleUserWarned" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="user_id" value="{{$warnedUser['id']}}">
        <div class="modal-body">
            警示時間
            <select name="days" class="days">
                <option value="3">三天</option>
                <option value="7">七天</option>
                <option value="15">十五天</option>
                <option value="30">三十天</option>
                <option value="X" selected>永久</option>
            </select>
            <hr>
            警示原因
            @foreach($banReason as $a)
                <a class="text-white btn btn-success">{{ $a->content }}</a>
            @endforeach
            <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
            <label style="margin:10px 0px;">
                <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                <sapn style="vertical-align:middle;">加入常用原因</sapn>
            </label>
        </div>
        <div class="modal-footer" style="text-align: left!important; display: block!important;">
            <input type="submit" id="send_blockade" class="btn btn-outline-success ban-user" value="送出">
            <button type="button" class="btn btn-outline-danger cancel" data-dismiss="modal">取消</button>
        </div>  
    </form>
        
</div>
<script>
    jQuery(document).ready(function() {

        $("a").each( function(){
            $(this).bind("click" , function(){
                var id = $("a").index(this);
                var clickval = $("a").eq(id).text();
                $('.m-reason').val(clickval);
            });
        });

        $('.cancel').on('click', function() {
            window.close();
        });
    });


</script>