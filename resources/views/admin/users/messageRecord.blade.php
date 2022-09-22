@include('partials.header')
@include('partials.message')


<body style="padding: 15px;">
    <h4>{{ $user->name }} 與 {{ $admin->name }} 的所有訊息</h1>
    <table class="table table-hover table-bordered" id="table-message">
        <tr>
            <th width="12%">發訊</th>
            <th width="12%">收訊</th>
            <th width="45%">內容</th>
            <th>上傳照片</th>
            <th width="5%">狀態</th>
        </tr>
        @forelse ($messages as $message)
            <tr>
                <td>
                    @if($message->from_id == $user->id) 
                        <a href="{{ route('users/advInfo', [$user->id]) }} ">
                            <p @if($user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $user->name }}
                                @if($user->vip)
                                    @if($user->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $user->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $user->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($user->isBlocked))
                                    @if(!is_null($user->isBlocked->expire_date))
                                        @if(round((strtotime($user->isBlocked->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($user->isBlocked->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($user->isBlocked->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif

                                @if($message->is_row_delete_1 == $user->id || $message->is_row_delete_2 == $user->id || $message->is_single_delete_1 == $user->id || $message->is_single_delete_2 == $user->id)
                                    (刪)
                                @endif
                            </p>
                        </a> 
                    @else
                        <a href="{{ route('users/advInfo', [$admin->id]) }} ">
                            <p @if($admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $admin->name }}
                                @if($admin->vip)
                                    @if($admin->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $admin->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $admin->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($admin->isBlocked))
                                    @if(!is_null($admin->isBlocked->expire_date))
                                        @if(round((strtotime($admin->isBlocked->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($admin->isBlocked->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($admin->isBlocked->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif

                                @if($message->is_row_delete_1 == $admin->id || $message->is_row_delete_2 == $admin->id || $message->is_single_delete_1 == $admin->id || $message->is_single_delete_2 == $admin->id)
                                    (刪)
                                @endif
                            </p>
                        </a>  
                    @endif
                </td>
                <td>
                    @if($message->from_id != $user->id) 
                        <a href="{{ route('users/advInfo', [$user->id]) }} ">
                            <p @if($user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $user->name }}
                                @if($user->vip)
                                    @if($user->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $user->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $user->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($user->isBlockedReceiver))
                                    @if(!is_null($user->isBlockedReceiver->expire_date))
                                        @if(round((strtotime($user->isBlockedReceiver->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($user->isBlockedReceiver->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($user->isBlockedReceiver->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a> 
                    @else 
                        <a href="{{ route('users/advInfo', [$admin->id]) }} ">
                            <p @if($admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $admin->name }}
                                @if($admin->vip)
                                    @if($admin->vip=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $admin->vip; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $admin->tipcount; $i++)
                                    👍
                                @endfor
                                @if(!is_null($admin->isBlockedReceiver))
                                    @if(!is_null($admin->isBlockedReceiver->expire_date))
                                        @if(round((strtotime($admin->isBlockedReceiver->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($admin->isBlockedReceiver->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($admin->isBlockedReceiver->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a>  
                    @endif
                </td>
                <td>
                    <p style="word-break:break-all;">{{ $message->content }}</p>
                </td>
                <td class="evaluation_zoomIn">
                    @php
                        $messagePics=is_null($message->pic) ? [] : json_decode($message->pic,true);
                    @endphp
                    @if(isset($messagePics))
                        @foreach($messagePics as $messagePic)
                            <li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
                                <img src="{{ $messagePic['file_path'] }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
                            </li>
                        @endforeach
                    @endif
                </td>
                <td nowrap>{{ $message->unsend?'已收回':'' }}</td>
            </tr>
        @empty
            沒有訊息
        @endforelse
    </table>
    <div class='pagination-container' >
        <nav>
            <ul class="pagination">
                <li data-page="prev" ><span> < <span class="sr-only">(current)</span></span></li>
                <li data-page="next" id="prev"><span> > <span class="sr-only">(current)</span></span></li>
            </ul>
        </nav>
    </div>
    <h4>發送站長訊息給 {{$user->name}}(收件者)</h4>
    <form action="{{ route('admin/send', $user->id) }}" id='message' method='POST'>
        {!! csrf_field() !!}
        <input type="hidden" value="{{ $admin->id }}" name="admin_id">
        <input type="hidden" value="1" name="chat_with_admin">
        <textarea name="msg" id="msg2" class="form-control" cols="80" rows="5"></textarea>
        <br>
        <button type='submit' class='text-white btn btn-primary'>送出</button>
    </form>
</body>

<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<script>
jQuery(document).ready(function(){
    getPagination('#table-message');
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

</script>
</html>
