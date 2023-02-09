@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
<table id="m_log" class="table table-hover table-bordered">
    <tr>
        <th width="10%">評價者</th>
        <th width="10%">被評價者</th>
        <th>最新內容</th>
        <th>上傳照片</th>
        <th width="15%">發送時間</th>
        <th width="8%">發送數 <br>評價者/被評</th>
    </tr>
        @if(!$message_1st)
        <tr>
            <td colspan="7" align="center">沒有訊息</td>
        </tr>
        @else
        <tr id="message_room_{{$message_1st->room_id}}">
            <td>@if($evaluator)<a href="{{ route('admin/showMessagesBetween', [$evaluate_from, $ref_user_id]) }}" target="_blank">{{ $evaluator->name }}</a>@else 評價者會員資料已刪除@endif</td>
            <td>@if($ref_user)<a href="{{ route('admin/showMessagesBetween', [$ref_user_id, $evaluate_from]) }}" target="_blank">{{ $ref_user->name }}</a>@else 被評價會員資料已刪除@endif</td>
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
            <td> {{$messages->where('from_id',$evaluator->id)->count()}} / {{$messages->where('from_id',$ref_user_id)->count()}} </td>
        </tr>
    <tr>
        <td colspan="7" align="center">
    <h4>{{ $ref_user->name }} 與 {{ $evaluator->name }} 的所有訊息</h1>
    <table class="table table-hover table-bordered" id="table-message">
        <tr>
            <th width="12%">發訊</th>
            <th width="12%">收訊</th>
            <th width="45%">內容</th>
            <th>上傳照片</th>
            <th width="5%">狀態</th>
            <th width="12%">發訊時間</th>
        </tr>
        @forelse ($messages as $message)
            <tr>
                <td>
                    @if($message->from_id == $ref_user->id) 
                        <a href="{{ route('users/advInfo', [$ref_user->id]) }} ">
                            <p @if($ref_user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $ref_user->name }}
                                @if($ref_user->vip->count())
                                    @if($ref_user->vip->first()->vip_diamond($evaluator->id)=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $ref_user->vip->count(); $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $ref_user->tipcount(); $i++)
                                    👍
                                @endfor
                                @if($ref_user->is_banned())
                                    @if(!is_null($ref_user->is_banned()->expire_date))
                                        @if(round((strtotime($ref_user->is_banned()->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($ref_user->is_banned()->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($ref_user->is_banned()->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif

                                @if($message->is_row_delete_1 == $ref_user->id || $message->is_row_delete_2 == $ref_user->id || $message->is_single_delete_1 == $ref_user->id || $message->is_single_delete_2 == $ref_user->id)
                                    (刪)
                                @endif
                            </p>
                        </a> 
                    @else
                        <a href="{{ route('users/advInfo', [$evaluator->id]) }} ">
                            <p @if($evaluator->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $evaluator->name }}
                                @if($evaluator->vip->count())
                                    @if($evaluator->vip->first()->vip_diamond($evaluator->id)=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $evaluator->vip->count(); $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $evaluator->tipcount(); $i++)
                                    👍
                                @endfor
                                @if($evaluator->is_banned())
                                    @if(!is_null($evaluator->is_banned()->expire_date))
                                        @if(round((strtotime($evaluator->is_banned()->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($evaluator->is_banned()->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($evaluator->is_banned()->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif

                                @if($message->is_row_delete_1 == $evaluator->id || $message->is_row_delete_2 == $evaluator->id || $message->is_single_delete_1 == $evaluator->id || $message->is_single_delete_2 == $evaluator->id)
                                    (刪)
                                @endif
                            </p>
                        </a>  
                    @endif
                </td>
                <td>
                    @if($message->from_id != $ref_user->id) 
                        <a href="{{ route('users/advInfo', [$ref_user->id]) }} ">
                            <p @if($ref_user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $ref_user->name }}
                                @if($ref_user->vip->count())
                                    @if($ref_user->vip->first()->vip_diamond($evaluator->id)=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $ref_user->vip->count(); $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $ref_user->tipcount(); $i++)
                                    👍
                                @endfor
                                @if($ref_user->is_banned())
                                    @if(!is_null($ref_user->is_banned()->expire_date))
                                        @if(round((strtotime($ref_user->is_banned()->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($ref_user->is_banned()->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($ref_user->is_banned()->type))
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                            </p>
                        </a> 
                    @else 
                        <a href="{{ route('users/advInfo', [$evaluator->id]) }} ">
                            <p @if($evaluator->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{ $evaluator->name }}
                                @if($evaluator->vip->count())
                                    @if($evaluator->vip->first()->vip_diamond($evaluator->id)=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $evaluator->vip->count(); $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $evaluator->tipcount(); $i++)
                                    👍
                                @endfor
                                @if($evaluator->is_banned())
                                    @if(!is_null($evaluator->is_banned()->expire_date))
                                        @if(round((strtotime($evaluator->is_banned()->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($evaluator->is_banned()->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif(isset($evaluator->is_banned()->type))
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
                <td nowrap>{{ $message->created_at }}</td>
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
            </td>
        </tr>
    @endif
    </table>  
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


</script>
</body>
@stop
