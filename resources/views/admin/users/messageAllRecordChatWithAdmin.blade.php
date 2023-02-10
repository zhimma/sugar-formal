@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<h1>站長會員通訊紀錄</h1>
<table id="m_log" class="table table-hover table-bordered">
    <tr>
        <th width="5%"></th>
        <th width="10%">發送給</th>
        <th>最新內容</th>
        <th>上傳照片</th>
        <th width="15%">發送時間</th>
        <th width="8%">發送數 <br>本人/管理者</th>
    </tr>
    @foreach($chat_with_admin_users as $cwa_user)
        <tr id="message_room_{{(($messages=$cwa_user->message_sent->merge($cwa_user->message_accepted)->sortByDesc('created_at')) && $message_1st=$messages->first())?$message_1st->room_id:''}}" >
            <td style="text-align: center;">
                <button data-toggle="collapse" data-target="#msgLog{{($ref_user_id=($message_1st->from_id==$admin->id?$message_1st->to_id:$message_1st->from_id))?$ref_user_id:''}}" class="accordion-toggle btn btn-primary message_toggle" value="{{$message_1st->room_id}}">+</button>
            </td>
            <td>@if(($ref_user=($message_1st->sender?->id==$ref_user_id?$message_1st->sender:$message_1st->receiver)) && $ref_user->name)<a href="{{ route('AdminMessageRecord', [$ref_user_id]) }}" target="_blank">{{ $ref_user->name }}</a>@else 會員資料已刪除@endif</td>
            <td id="new{{$message_1st->to_id}}">
                {{($cwa_user->id==$message_1st->from_id ? '(發)' :'(回)') .$message_1st->content}}
            </td>
            <td class="evaluation_zoomIn">
            @if($message_1st->pic)
                @if($messagePics=json_decode($message_1st->pic,true))
                    @foreach( $messagePics as $messagePic)
                        @if(isset($messagePic['file_path']))
                            <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                <img src="{{ $messagePic['file_path'] }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                            </li>
                        @else
                            <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                無法找到圖片
                            </li>
                        @endif
                    @endforeach
                @endif
            @endif
            </td>
            <td id="new_time{{$ref_user_id}}"> {{  $message_1st->created_at ??''}}</td>
            <td> {{$cwa_user->message_sent->count()}} / {{$cwa_user->message_accepted->count()}} </td>
        </tr>
        <tr class="accordian-body collapse" id="msgLog{{$ref_user_id}}">
            <td class="hiddenRow" colspan="6">
            </td>
        </tr>
    @endforeach
</table>     
<script>
jQuery(document).ready(function(){
    //getPagination('#table-message');
});
    function getPagination(table) {
        var lastPage = 1;
        var trnum = 0;
        var maxRows = 10;
        $('.pagination')
            .find('li')
            .slice(1, -1)
            .remove();

        var totalRows = $(table + ' tbody tr').length;

        if (totalRows <= maxRows) {
            $('.pagination').hide();
        } else {
            $('.pagination').show();
        }

        $(table + ' tr:gt(0)').each(function() {
            trnum++;
            if (trnum > maxRows) {
                $(this).hide();
            }
            if (trnum <= maxRows) {
                $(this).show();
            }
        });
        if (totalRows > maxRows) {
            var pagenum = Math.ceil(totalRows / maxRows);
            for (var i = 1; i <= pagenum; ) {
            $('.pagination #prev')
                .before('<li data-page="' + i + '">\ <span>' + i++ + '<span class="sr-only">(current)</span></span>\ </li>')
                .show();
            }
        }
        $('.pagination [data-page="1"]').addClass('active');
        $(document).on('click', '.pagination li', function(e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            var pageNum = $(this).attr('data-page'); 

            if (pageNum == 'prev') {
            if (lastPage == 1) {
                return;
            }
            pageNum = --lastPage;
            }
            if (pageNum == 'next') {
            if (lastPage == $('.pagination li').length - 2) {
                return;
            }
            pageNum = ++lastPage;
            }

            lastPage = pageNum;
            var trIndex = 0;
            $('.pagination li').removeClass('active');
            $('.pagination [data-page="' + lastPage + '"]').addClass('active');
            limitPagging();
            $(table + ' tr:gt(0)').each(function() {
                trIndex++;
                if (
                    trIndex > maxRows * pageNum ||
                    trIndex <= maxRows * pageNum - maxRows
                ) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
        limitPagging();
    }
    function limitPagging(){
        if($('.pagination li').length > 7 ){
                if( $('.pagination li.active').attr('data-page') <= 3 ){
                $('.pagination li:gt(5)').hide();
                $('.pagination li:lt(5)').show();
                $('.pagination [data-page="next"]').show();
            }if ($('.pagination li.active').attr('data-page') > 3){
                $('.pagination li:gt(0)').hide();
                $('.pagination [data-page="next"]').show();
                for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
                    $('.pagination [data-page="'+i+'"]').show();

                }
            }
        }
    }
    function closeChat(id) {
        $.ajax({
            type: 'POST',
            url: "/admin/users/isChatToggler",
            data:{
                _token: '{{csrf_token()}}',
                user_id: id,
                is_admin_chat_channel_open: 0,
            },
            dataType:"json",
            success: function(res){
                alert('對話已結束')
                location.reload();
        }});
    }
    $('.message_management_btn').on('click', function(){
        $('.main').toggle();
        $('.message').toggle();
        if($(this).text() == '開啟會員對話')
        {
            $(this).text('對話中');
            getPagination('#table-message');
        }
        else if($(this).text() == '對話中')
        {
            $(this).text('開啟會員對話');
        }

    });

    if (window.parent.location.href.match(/from_advInfo=/)){
        if (typeof (history.pushState) != "undefined") {
            var obj = { Title: document.title, Url: window.parent.location.pathname };
            history.pushState(obj, obj.Title, obj.Url);
        } else {
            window.parent.location = window.parent.location.pathname;
        }
    }
    
    $('.message_toggle').on('click', function(){
        let room_id = $(this).attr("value");
        let cur_room_block = $('#message_room_' + room_id).next().find('.hiddenRow');
        if($(this).text() == '+') {
            $(this).text('-');

            console.log('cur_room_block=');
            console.log(cur_room_block);
            $.ajax({
                type: 'GET',
                url: '{{route('users/getAdminMessageRecordDetailFromRoomId')}}',
                data: {
                    room_id: room_id,
                    csrf_{{csrf_token()}}: Date.now(),
                },
                success: function (data) {
                    console.log('cur_room_block=');
                    console.log(cur_room_block);
                    console.log('data=');

                    cur_room_block.html(data);
                    getPagination('#table-message-' + room_id);
                    return;
                    data.message_detail.forEach(function (value) {
                        messagePics = (value.pic === null) ? [] : JSON.parse(value.pic);
                        messagePicHTML = '';
                        messagePics.forEach(function(pic){
                            messagePicHTML =  messagePicHTML +
                            '<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">'+
                                '<img src="'+ pic.file_path +'" style="max-width:130px;max-height:130px;margin-right: 5px;">'+
                            '</li>';
                        });

                        name_color = '';
                        if(value.engroup == 2){
                            name_color = 'color: #F00;';
                        }
                        else{
                            name_color = 'color: #5867DD;';
                        }

                        user_icon = '';
                        if(data.users_data[value.u_id]['vip']){
                            if(data.users_data[value.u_id]['vip'] == 'diamond_black'){
                                user_icon += '<img src="/img/diamond_black.png" style="height: 16px;width: 16px;">';
                            }
                            else{
                                for(i = 0; i < data.users_data[value.u_id]['vip']; i++){
                                    user_icon += '<img src="/img/diamond.png" style="height: 16px;width: 16px;">';
                                }
                            }
                        }

                        for(i = 0; i < data.users_data[value.u_id]['tipcount']; i++){
                            user_icon += '👍';
                        }

                        if(value.banned_id){
                            if(value.banned_expire_date){
                                let exp_date = new Date(value.banned_expire_date);
                                let now_date = Date.now();
                                let idays = parseInt(Math.abs(exp_date - now_date) / 1000 / 60 / 60 / 24);
                                user_icon += '(' + idays + '天)';
                            }
                            else{
                                user_icon += '(永久)';
                            }
                        }

                        $('#message_room_detail_' + data.room_id).append(
                            '<tr>'+
                                '<td style="text-align: right;">'+
                                    '<a href="' + data.message_href + '" target="_blank">'+
                                        '<p style="margin-bottom:0px;'+ name_color +'">'+
                                            value.name + user_icon +
                                        '</p>'+
                                    '</a>'+
                                '</td>'+
                                '<td>'+
                                    '<p style="word-break:break-all;">'+
                                        value.content +
                                    '</p>'+
                                '</td>'+
                                '<td class="evaluation_zoomIn">'+
                                    messagePicHTML +
                                '</td>'+
                                '<td>'+
                                    value.m_time +
                                '</td>'+
                                '<td nowrap>'+
                                    (value.unsend ? '已收回' : '') +
                                '</td>'+
                            '</tr>'
                        );
                    });
                    
            }});
        }
        else if($(this).text() == '-')
        {
            $(this).text('+');
            cur_room_block.empty();
        }
    });
</script>
@stop
