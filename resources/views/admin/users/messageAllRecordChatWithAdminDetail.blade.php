@if(!$ref_user || !$messages->count())
    查無訊息
@else
<h4>{{ $ref_user->name }} 與 {{ $admin->name }} 的所有訊息</h1>
<table class="table table-hover table-bordered" id="table-message-{{$messages->first()->room_id}}">
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
                                @if($ref_user->vip->first()->vip_diamond($admin->id)=='diamond_black')
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
                    <a href="{{ route('users/advInfo', [$admin->id]) }} ">
                        <p @if($admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                            {{ $admin->name }}
                            @if($admin->vip->count())
                                @if($admin->vip->first()->vip_diamond($admin->id)=='diamond_black')
                                    <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                @else
                                    @for($z = 0; $z < $admin->vip->count(); $z++)
                                        <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                    @endfor
                                @endif
                            @endif
                            @for($i = 0; $i < $admin->tipcount(); $i++)
                                👍
                            @endfor
                            @if($admin->is_banned())
                                @if(!is_null($admin->is_banned()->expire_date))
                                    @if(round((strtotime($admin->is_banned()->expire_date) - getdate()[0])/3600/24)>0)
                                        {{ round((strtotime($admin->is_banned()->expire_date) - getdate()[0])/3600/24 ) }}天
                                    @else
                                        此會員登入後將自動解除封鎖
                                    @endif
                                @elseif(isset($admin->is_banned()->type))
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
                @if($message->from_id != $ref_user->id) 
                    <a href="{{ route('users/advInfo', [$ref_user->id]) }} ">
                        <p @if($ref_user->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                            {{ $ref_user->name }}
                            @if($ref_user->vip->count())
                                @if($ref_user->vip->first()->vip_diamond($admin->id)=='diamond_black')
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
                    <a href="{{ route('users/advInfo', [$admin->id]) }} ">
                        <p @if($admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                            {{ $admin->name }}
                            @if($admin->vip->count())
                                @if($admin->vip->first()->vip_diamond($admin->id)=='diamond_black')
                                    <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                @else
                                    @for($z = 0; $z < $admin->vip->count(); $z++)
                                        <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                    @endfor
                                @endif
                            @endif
                            @for($i = 0; $i < $admin->tipcount(); $i++)
                                👍
                            @endfor
                            @if($admin->is_banned())
                                @if(!is_null($admin->is_banned()->expire_date))
                                    @if(round((strtotime($admin->is_banned()->expire_date) - getdate()[0])/3600/24)>0)
                                        {{ round((strtotime($admin->is_banned()->expire_date) - getdate()[0])/3600/24 ) }}天
                                    @else
                                        此會員登入後將自動解除封鎖
                                    @endif
                                @elseif(isset($admin->is_banned()->type))
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
@endif                       
