@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
.form-check-input{
    margin-left: unset !important;
}
</style>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
/>
<body style="padding: 15px;">
<h1>VVIP申請管理</h1>

@if(isset($applicationData))
<div>
    <table class="table-hover table table-bordered">
        <thead>
        <tr>
            <th width="5%">ID</th>
            <th width="8%">暱稱</th>
            <th width="8%">Email</th>
            <th width="8%">申請方案</th>
            <th width="8%">申請狀態</th>
            <th width="8%">申請日</th>
            <th width="8%">異動時間</th>
            <th width="12%">VVIP定期定額
            <th width="10%">使用者備註</th>
            <th width="10%">備註</th>
            <th width="10%">管理申請資料</th>
            <th width="8%">管理必填資料頁</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($applicationData as $row)
                <tr>
{{--                    <td><a href="javascript:void(0);" class="get_prove_img" data-user_id="{{ $row->user_id }}" data-name="{{$row->name}}" data-updated_at="{{$row->updated_at}}" data-deadline="{{$row->deadline}}" data-toggle="modal" data-target="#exampleModalLong">{{$row->name}}</a></td>--}}
                    <td>{{$row->user_id}}</td>
                    <td>{{$row->name}}</td>
                    <td><a href="advInfo/{{ $row->user_id }}" target="_blank">{{$row->email}}</a></td>
                    <td>@if($row->plan=='VVIP_A')老會員優惠方案@elseif($row->plan=='VVIP_B')隱私方案@endif
                        @if($row->plan=='VVIP_A')
                            <br><a href="javascript:void(0);" class="get_prove_img" data-user_id="{{ $row->user_id }}" data-name="{{$row->name}}" data-updated_at="{{$row->updated_at}}" data-deadline="{{$row->deadline}}" data-toggle="modal" data-target="#exampleModalLong">證明文件</a>
                        @endif
                    </td>
                    <td>@if($row->status==0)申請中@elseif($row->status==1)通過@elseif($row->status==2)不通過@elseif($row->status==3)待補件<br>補件期限日：<br>{{$row->deadline}}@elseif($row->status==4)取消申請@elseif($row->status==5)匯款待確認@endif</td>
                    <td>{{$row->created_at}}</td>
                    <td>{{$row->updated_at}}</td>
                    <td>
                        @if($row->service_status==1)
                            VVIP會員費訂單編號：{{$row->order_id}} <br>
                            到期日：{{$row->expiry}}
                        @else
                            尚未完成VVIP會員繳費
                        @endif
                    </td>
                    <td>{!! nl2br($row->user_note) !!}</td>
                    <td>{!! nl2br($row->note) !!}</td>
                    <td>
{{--                        @if($row->status==0||$row->status==3)--}}
                        <div class="form-group">
                            <form id="form_{{$row->id}}" action="{{ route('users/VVIP_edit') }}" method="post">
                                {!! csrf_field() !!}
                                @if($row->status != 0)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="0" @if($row->status==0) checked @endif>
                                        <label class="form-check-label" for="status">
                                            申請中
                                        </label>
                                    </div>
                                @endif
                                @if($row->status != 1)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="1" @if($row->status==1) checked @endif>
                                    <label class="form-check-label" for="status">
                                        通過
                                    </label>
                                </div>
                                @endif
                                @if($row->status != 2)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="2" @if($row->status==2) checked @endif>
                                    <label class="form-check-label" for="status">
                                        不通過
                                    </label>
                                </div>
                                @endif
                                @if($row->plan=='VVIP_A' && $row->status != 3)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="3" data-id="{{$row->id}}" @if($row->status==3) checked @endif>
                                    <label class="form-check-label" for="status">
                                        待補件
                                    </label>
                                </div>

                                <div class="form-group deadline_{{$row->id}}" style="display: none;">
                                    <label for="datepicker">補件期限：</label><br>
                                    <input id="datepicker" data-date-format='yyyy-mm-dd' type="text" name="deadline">
                                </div>
                                @endif
                                @if($row->status != 4)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="4" @if($row->status==4) checked @endif>
                                        <label class="form-check-label" for="status">
                                            取消申請
                                        </label>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">備註</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="note" rows="3">{!! $row->note !!}</textarea>
                                </div>
                                <input type="hidden" name="id" value="{{$row->id}}">
                                <input type="hidden" name="user_id" value="{{$row->user_id}}">
                                <button type="submit" class="btn btn-success">送出</button>
                            </form>
                        </div>
{{--                        @endif--}}

                        <div><a href="editPic_sendMsg/{{$row->user_id}}" class='text-white btn btn-primary'>照片&發訊息</a></div>
                    </td>
                    <td>
                        @if(empty($row->vvip_user_id))
                            <div class="red">尚未產生VVIP會員頁</div>
                        @else
                            <a href="/dashboard/viewuser_vvip/{{$row->user_id}}" class="btn btn-brand" target="_blank">預覽會員頁</a>
                        <form id="" action="{{ route('users/vvipInfo_admin_edit') }}" method="post">
                            {!! csrf_field() !!}
                            <input class="vvipInfo_status_toggle" type="checkbox" name="status" data-user_id="{{$row->user_id}}" data-toggle="toggle" data-on="會員頁開啟" data-off="會員頁關閉" data-onstyle="success" data-offstyle="danger" @if($row->vvip_info_status==1)checked="checked" @endif>
                            <br>
                            <div class="form-group">
                                <label for="about_textarea">關於我 (forVVIP)</label>
                                <textarea class="form-control" id="about_textarea" name="about" rows="3">{!! $row->about !!}</textarea>
                            </div>
                            <input type="hidden" name="user_id" value="{{$row->user_id}}">
                            <button type="submit" class="btn btn-success">送出</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $applicationData->appends(request()->input())->links('pagination::sg-pages') !!}
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><div class="modal-name"></div>證明文件</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js" integrity="sha512-x/vqovXY/Q4b+rNjgiheBsA/vbWA3IVvsS8lkQSX1gQ4ggSJx38oI2vREZXpTzhAv6tNUaX81E7QBBzkpDQayA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}
<script>
    jQuery(document).ready(function() {
        jQuery("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            startDate: "today"
        }).val();
    });

    $("input[name='status']").change(function(){
        if($(this).val()==3){
            $('.deadline_' + $(this).data('id')).show();
        }else{
            $('.deadline_' + $(this).data('id')).hide();
        }
    });

    $('.get_prove_img').on('click', function(){
       var user_id = $(this).data('user_id'),
           name = $(this).data('name'),
           updated_at = $(this).data('updated_at'),
           // updated_at = $(this).data('updated_at').toISOString(),
           deadline = $(this).data('deadline');
       // alert(updated_at.toISOString());
        $.post('{{ route('get_prove_img') }}', {
            user_id: user_id,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            $('.modal-name').html(name);
            $('.modal-body').html('');
            $.each(data.imgData,function(i,e) {
                if(deadline != '' && Date.parse(e.created_at) > Date.parse(updated_at)){
                    $('.modal-body').append('<a href="' + e.path + '" data-fancybox="gallery_'+ user_id +'" data-caption="' + e.created_at + '"><img src="' + e.path + '" alt="' + e.created_at + '" height="120" /></a>補件' + moment(e.created_at).format("YYYY-MM-DD HH:mm:ss") + '<br>');
                }else {
                    $('.modal-body').append('<a href="' + e.path + '" data-fancybox="gallery_'+ user_id +'" data-caption="' + e.created_at + '"><img src="' + e.path + '" alt="' + e.created_at + '" height="120" /></a>' + moment(e.created_at).format("YYYY-MM-DD HH:mm:ss") + '<br>');
                }
            });


        });
    });
    // const formatDate = (current_datetime)=>{
    //     let formatted_date = current_datetime.getFullYear() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getDate() + " " + current_datetime.getHours() + ":" + current_datetime.getMinutes() + ":" + current_datetime.getSeconds();
    //     return formatted_date;
    // }
    // $('[data-fancybox]').fancybox({
    //     protect: true,
    //     buttons : [
    //         'zoom',
    //         'thumbs',
    //         'close'
    //     ]
    // });
</script>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>

    $('.vvipInfo_status_toggle').on('change', function() {
        let status = 0;
        if($(this).prop('checked') == true){
            status = 1;
        }
        $.ajax({
            type: 'POST',
            url: "{{ route("users/vvipInfo_status_toggle") }}",
            data:{
                _token: '{{csrf_token()}}',
                user_id: $(this).data('user_id'),
                status: status
            },
            dataType:"json",
            success: function(res){
                alert(res.msg);
                location.reload();
            }
        });
    })

</script>
@stop
</html>