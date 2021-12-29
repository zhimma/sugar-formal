<style>
    div.block_line {float:left;margin-bottom:10px;}
    div.block_line > div {overflow:auto;}
    div.block_line:last-child {float:left;margin-bottom:1rem;}
    .images_comparation_cell {white-space:nowrap;}
    .images_comparation_cell b{clear:both;display:block;}
    #user_all_mem_pic_list,#user_all_avatar_list {width:100% !important;}    
    #user_all_mem_pic_list th,#user_all_avatar_list th {white-space:nowrap;}
    td.images_comparation_cell div img {border:1px solid #ccc;} 
    td.images_comparation_cell  div.block_line,td.images_comparation_cell  div.block_line img {max-width:120px; margin-right:10px;}
    .rs_user_banned {background-color:#FDFF8C;}
    .rs_user_warned {background-color:#B0FFB1;} 
    .rs_user_closed {background-color:#C9C9C9;}
    .rs_user_aclosed {background-color:#969696;}
    .lli_holder {text-align:right;margin-left:2px;float:right;}
    .block_line div > a {
        width: calc(100% - 35px);
        line-height: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        word-wrap: break-word;
        word-break: break-all;
        float:left;
    }
    
    .gender1 a,.gender1 a:visited,.gender1 a:active,.gender1 a:hover {color:blue;}
    .gender2 a,.gender2 a:visited,.gender2 a:active,.gender2 a:hover {color:red;}   
</style>
<h4>現有證件照</h4>
<?php $pics = \App\Models\MemberPic::getSelfIDPhoto($user->id); ?>
<table class="table table-hover table-bordered" style="width: 50%;">
	@forelse ($pics as $pic)
		<tr>
			<td>
				<input type="hidden" name="userId" value="{{$user->id}}">
				<input type="hidden" name="imgId" value="{{$pic->id}}">
				<div style="width:400px">
					<img src="{{$pic->pic}}" />
				</div>
			</td>
		</tr>
	@empty
		此會員目前沒有證件照
	@endforelse
</table>

<div>
    <a class="btn btn-primary text-white mb-2" href="/admin/users/picturesSimilar/job:create?type=userAll&targetUser={{ $user->id }}" target="_blank">照片全部送檢</a>
</div>
<table id="user_all_avatar_list" class="table table-bordered" style="width: 70%;">
    <thead>
        <th style="width: 120px;">頭像照</th>
        <th style="width: 170px;">上傳時間</th>
        <th style="width: 170px;">刪除時間</th>
        <th style="width: 15%;">刪除者</th>
        <th style="width: 35%;">以圖找圖</th>
        <th style="width: 35%;">站內搜圖</th>
    </thead>
    <tbody>
        {{-- 當前頭像 --}}
        @if($user->meta->pic)
        <tr>
            <td class="user_recorded_img"><img src="{{ url($user->meta->pic) }}" width="120px"></td>
            <td>{{ $user->meta->updated_at }}</td>
            <td>未刪除</td>
            <td>未刪除</td>
            <td>
                @php                   
                    $ImgResult = \App\Models\SimilarImages::where('pic', $user->meta->pic)->first();
                @endphp
                @if ($ImgResult)

                    @if ($ImgResult->status == 'success')

                        {{-- 完全匹配 Start --}}
                        <b>完全匹配(含調整過寬高)</b>
                        @if ($ImgResult->fullMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->fullMatchingImages) as $fullMatchingImage)
                                    <a href="{{ $fullMatchingImage->url }}" target="_blank"><img src="{{ $fullMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有完全匹配的內容</p>
                        @endif
                        {{-- 完全匹配 End --}}

                        {{-- 足夠相似 Start --}}
                        <b>足夠相似</b>
                        @if ($ImgResult->partialMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->partialMatchingImages) as $partialMatchingImage)
                                    <a href="{{ $partialMatchingImage->url }}" target="_blank"><img src="{{ $partialMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有足夠相似的內容</p>
                        @endif
                        {{-- 足夠相似 End --}}

                        {{-- 匹配的網頁 Start --}}
                        <b>匹配的網頁</b>
                        @if ($ImgResult->pagesWithMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->pagesWithMatchingImages) as $pagesWithMatchingImage)
                                    <a href="{{ $pagesWithMatchingImage->url }}" target="_blank">{{ $pagesWithMatchingImage->pageTitle ?? "無標題" }}</a><br>
                                @endforeach
                            </p>
                        @else
                            <p>沒有匹配的網頁</p>
                        @endif
                        {{-- 匹配的網頁 End --}}

                        {{-- 看起來像的圖片 Start --}}
                        <b>看起來像的圖片</b>
                        @if ($ImgResult->visuallySimilarImages)
                            <p>
                                @foreach (json_decode($ImgResult->visuallySimilarImages) as $visuallySimilarImage)
                                    <a href="{{ $visuallySimilarImage->url }}" target="_blank"><img src="{{ $visuallySimilarImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有看起來像的圖片</p>
                        @endif
                        {{-- 看起來像的圖片 End --}}

                    @elseif ($ImgResult->status == 'failed')
                        <p>搜尋失敗</p>
                    @endif
                @else
                    <p>無紀錄</p>
                @endif
            </td>
            <td class="images_comparation_cell">
                @if(!$user->meta->isPicNeedCompare())
                <b>不需比對的照片</b>      
                @else
                    @php 
                        $user->meta->compareImages('advInfo');
                        $compareStatus = $user->meta->getCompareStatus();
                        $compareRsImgs = $user->meta->getCompareRsImg(); 
                    @endphp
                    @if(!$user->meta->getCompareEncode() && !$user->meta->isPicFileExists())
                    <b>照片的檔案不存在，無法比對</b>  
                    @elseif(!$user->meta->getCompareEncode() && ($compareStatus??null) && (!($compareRsImgs??null) || $compareRsImgs->count()==0) && $compareStatus->queue??null)
                    <b>{{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已{{$compareStatus->queue==2?'重新':''}}申請排隊執行比對，等待系統回應中</b>   
                    @elseif(!$user->meta->getCompareEncode() )
                        @if($compareStatus??null)
                        <b>資料異常</b>
                        @else
                        <b>尚未建立比對資訊</b>
                        @endif  
                    @else
                        <b>完全相同(不含調整過寬高)</b>
                        @php $sameImages = $user->meta->getSameImg() @endphp
                        @if($sameImages->count())
                        <div>
                            @foreach ($sameImages as $sameImg)
                            
                            @if($sameImg->user??null)
                            <div class="block_line">
                                <a href="{{ $sameImg->pic }}" target="_blank"><img src="{{ $sameImg->pic }}"  onerror="this.src='/img/linktosource.png'"></a>
                                <div class="gender{{$sameImg->user->engroup}} {{$sameImg->userStateStr?'rs_user_':''}}{{$sameImg->userStateStr??''}}"><a href="{{route('users/advInfo',$sameImg->user->id)}}" target="_blank">{{$sameImg->user->name}}</a><span class="lli_holder">({{$sameImg->userLliDiffDays}})</span></div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <p>沒有完全相同的圖片</p>
                        @endif 
                        <b>看起來像的圖片</b>
                        
                        @if($compareStatus??null)
                           <div>
                                @if($compareStatus->isHoldTooLong())
                                    未完成比對
                                @elseif($compareStatus->queue==1)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已申請排隊執行比對，等待系統回應中 
                                @elseif($compareStatus->queue==2)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已重新申請排隊執行比對，等待系統回應中                                 
                                @elseif($compareStatus->status==1)
                                    比對中
                                @endif
                                @if($compareStatus->status)
                                <span>已比對
                                    <span class="percent_number">
                                    {{abs(intval(100*$compareStatus->encode_break_id/$last_images_compare_encode->id)-5)}}
                                    </span>
                                    %
                                </span>
                                @endif
                            </div>                                                
                            <div>
                                @forelse($compareRsImgs as $rsIdx => $rsImg)                          
                                    @if($rsImg->user??null)
                                    <div class="block_line">
                                        <a href="{{ $rsImg->pic }}" target="_blank"><img class="cp_rs_img"  src="{{ $rsImg->pic }}"  onerror="this.src='/img/linktosource.png'"></a>
                                        <div class="gender{{$rsImg->user->engroup}} {{$rsImg->userStateStr?'rs_user_':''}}{{$rsImg->userStateStr??''}}"><a href="{{route('users/advInfo',$rsImg->user->id)}}" target="_blank">{{$rsImg->user->name}}</a><span class="lli_holder">({{$rsImg->userLliDiffDays}})</span></div>
                                    </div>
                                    @endif
                                @empty
                                    @if(!$compareStatus->queue && !$compareStatus->status)
                                    <p>沒有看起來像的圖片</p>  
                                    @endif
                                @endforelse
                            </div>
                        @else
                            <p>尚未開始比對</p>
                        @endif                    
                    @endif
                @endif
            </td>                
        </tr>
        @endif

        {{-- 已刪除的頭像 --}}
        @forelse ($user->avatar_deleted as $pic)
        <tr>
            <td class="user_recorded_img"><img src="{{ url($pic->pic) }}" width="120px"></td>
            <td class="text-nowrap">{{ $pic->uploaded_at }}</td>
            <td class="text-nowrap">{{ $pic->created_at }}</td>
            <td>
                @if ($pic->user_id == $pic->operator)
                    本人
                @else
                    {{ \App\Models\User::find($pic->operator)->email }}
                @endif
            </td>
            <td>
                @php
                    $ImgResult = \App\Models\SimilarImages::where('pic', $pic->pic)->first();
                @endphp
                @if ($ImgResult)
                    @if ($ImgResult->status == 'success')
                        {{-- 完全匹配 Start --}}
                        <b>完全匹配(含調整過寬高)</b>
                        @if ($ImgResult->fullMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->fullMatchingImages) as $fullMatchingImage)
                                    <a href="{{ $fullMatchingImage->url }}" target="_blank"><img src="{{ $fullMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有完全匹配的內容</p>
                        @endif
                        {{-- 完全匹配 End --}}

                        {{-- 足夠相似 Start --}}
                        <b>足夠相似</b>
                        @if ($ImgResult->partialMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->partialMatchingImages) as $partialMatchingImage)
                                    <a href="{{ $partialMatchingImage->url }}" target="_blank"><img src="{{ $partialMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有足夠相似的內容</p>
                        @endif
                        {{-- 足夠相似 End --}}

                        {{-- 匹配的網頁 Start --}}
                        <b>匹配的網頁</b>
                        @if ($ImgResult->pagesWithMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->pagesWithMatchingImages) as $pagesWithMatchingImage)
                                    <a href="{{ $pagesWithMatchingImage->url }}" target="_blank">{{ $pagesWithMatchingImage->pageTitle ?? "無標題" }}</a><br>
                                @endforeach
                            </p>
                        @else
                            <p>沒有匹配的網頁</p>
                        @endif
                        {{-- 匹配的網頁 End --}}

                        {{-- 看起來像的圖片 Start --}}
                        <b>看起來像的圖片</b>
                        @if ($ImgResult->visuallySimilarImages)
                            <p>
                                @foreach (json_decode($ImgResult->visuallySimilarImages) as $visuallySimilarImage)
                                    <a href="{{ $visuallySimilarImage->url }}" target="_blank"><img src="{{ $visuallySimilarImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有看起來像的圖片</p>
                        @endif
                        {{-- 看起來像的圖片 End --}}

                    @elseif ($ImgResult->status == 'failed')
                        <p>搜尋失敗</p>
                    @endif
                @else
                    <p>無紀錄</p>
                @endif
            </td>
            <td class="images_comparation_cell">
                @if(!$pic->isPicNeedCompare())
                    <b>不需比對的照片</b>      
                @else              
                    @php 
                        $pic->compareImages('advInfo');  
                        $compareStatus = $pic->getCompareStatus();
                        $compareRsImgs = $pic->getCompareRsImg();
                    @endphp                            
                    @if(!$pic->getCompareEncode() && !$pic->isPicFileExists())
                    <b>照片的檔案不存在，無法比對</b>  
                    @elseif(!$pic->getCompareEncode() && ($compareStatus??null) && !($compareRsImgs??null) && $compareRsImgs->count()==0 && $compareStatus->queue??null)
                    <b>{{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已{{$compareStatus->queue==2?'重新':''}}申請排隊執行比對，等待系統回應中</b>   
                    @elseif(!$pic->getCompareEncode() )
                        @if($compareStatus??null)
                        <b>資料異常</b>
                        @else
                        <b>尚未建立比對資訊</b>
                        @endif    
                    @else
                        <b>完全相同(不含調整過寬高)</b>
                        @php $sameImages = $pic->getSameImg() @endphp
                        @if($sameImages->count())
                        <div>
                            @foreach ($sameImages as $sameImg)
                            
                            @if($sameImg->user??null)
                            <div class="block_line">
                                <a href="{{ $sameImg->pic }}" target="_blank"><img src="{{ $sameImg->pic }}" onerror="this.src='/img/linktosource.png'"></a>
                                <div class="gender{{$sameImg->user->engroup}} {{$sameImg->userStateStr?'rs_user_':''}}{{$sameImg->userStateStr??''}}"><a href="{{route('users/advInfo',$sameImg->user->id)}}" target="_blank">{{$sameImg->user->name}}</a><span class="lli_holder">({{$sameImg->userLliDiffDays}})</span></div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <p>沒有完全相同的圖片</p>
                        @endif 
                        <b>看起來像的圖片</b>
                        @php $compareStatus = $pic->getCompareStatus() @endphp
                        @if($compareStatus??null)
                           <div>
                                @if($compareStatus->isHoldTooLong())
                                    未完成比對
                                @elseif($compareStatus->queue==1)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已申請排隊執行比對，等待系統回應中
                                @elseif($compareStatus->queue==2)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已重新申請排隊執行比對，等待系統回應中                                  
                                @elseif($compareStatus->status==1)
                                    比對中
                                @endif
                                @if($compareStatus->status)
                                <span>已比對
                                    <span class="percent_number">
                                    {{abs(intval(100*$compareStatus->encode_break_id/$last_images_compare_encode->id)-5)}}
                                    </span>
                                    %
                                </span>
                                @endif
                            </div>                        
                            @php $compareRsImgs = $pic->getCompareRsImg() @endphp
                            <div>
                                @forelse($compareRsImgs as $rsIdx => $rsImg)                           
                                    @if($rsImg->user??null)
                                    <div class="block_line">
                                        <a href="{{ $rsImg->pic }}" target="_blank"><img class="cp_rs_img"  src="{{ $rsImg->pic }}"  onerror="this.src='/img/linktosource.png'"></a>
                                        <div class="gender{{$rsImg->user->engroup}} {{$rsImg->userStateStr?'rs_user_':''}}{{$rsImg->userStateStr??''}}"><a href="{{route('users/advInfo',$rsImg->user->id)}}" target="_blank">{{$rsImg->user->name}}</a><span class="lli_holder">({{$rsImg->userLliDiffDays}})</span></div>
                                    </div>
                                    @endif
                                @empty
                                    @if(!$compareStatus->queue && !$compareStatus->status)
                                    <p>沒有看起來像的圖片</p>    
                                    @endif
                                @endforelse
                            </div>
                        @else
                            <p>尚未開始比對</p>
                        @endif                    
                    @endif
                @endif
            </td>                
        </tr>
        @empty
        @endforelse
    </tbody>
</table>

<table  id="user_all_mem_pic_list" class="table table-bordered" style="width: 70%;">
    <thead>
        <th style="width: 120px;">生活照</th>
        <th style="width: 170px;">上傳時間</th>
        <th style="width: 170px;">刪除時間</th>
        <th style="width: 15%;">刪除者</th>
        <th style="width: 35%;">以圖找圖</th>
        <th style="width: 35%;">站內搜圖</th>
    </thead>
    <tbody>
        {{-- 當前生活照 --}}
        @forelse ($user->pic_orderByDecs as $pic)
        <tr>
            <td class="user_recorded_img"><img src="{{ url($pic->pic) }}" width="120px"></td>
            <td>{{ $pic->created_at }}</td>
            <td>未刪除</td>
            <td>未刪除</td>
            <td>
                @php
                    $ImgResult = \App\Models\SimilarImages::where('pic', $pic->pic)->first();
                @endphp
                @if ($ImgResult)

                    @if ($ImgResult->status == 'success')

                        {{-- 完全匹配 Start --}}
                        <b>完全匹配(含調整過寬高)</b>
                        @if ($ImgResult->fullMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->fullMatchingImages) as $fullMatchingImage)
                                    <a href="{{ $fullMatchingImage->url }}" target="_blank"><img src="{{ $fullMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有完全匹配的內容</p>
                        @endif
                        {{-- 完全匹配 End --}}

                        {{-- 足夠相似 Start --}}
                        <b>足夠相似</b>
                        @if ($ImgResult->partialMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->partialMatchingImages) as $partialMatchingImage)
                                    <a href="{{ $partialMatchingImage->url }}" target="_blank"><img src="{{ $partialMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有足夠相似的內容</p>
                        @endif
                        {{-- 足夠相似 End --}}

                        {{-- 匹配的網頁 Start --}}
                        <b>匹配的網頁</b>
                        @if ($ImgResult->pagesWithMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->pagesWithMatchingImages) as $pagesWithMatchingImage)
                                    <a href="{{ $pagesWithMatchingImage->url }}" target="_blank">{{ $pagesWithMatchingImage->pageTitle ?? "無標題" }}</a><br>
                                @endforeach
                            </p>
                        @else
                            <p>沒有匹配的網頁</p>
                        @endif
                        {{-- 匹配的網頁 End --}}

                        {{-- 看起來像的圖片 Start --}}
                        <b>看起來像的圖片</b>
                        @if ($ImgResult->visuallySimilarImages)
                            <p>
                                @foreach (json_decode($ImgResult->visuallySimilarImages) as $visuallySimilarImage)
                                    <a href="{{ $visuallySimilarImage->url }}" target="_blank"><img src="{{ $visuallySimilarImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有看起來像的圖片</p>
                        @endif
                        {{-- 看起來像的圖片 End --}}

                    @elseif ($ImgResult->status == 'failed')
                        <p>搜尋失敗</p>
                    @endif
                @else
                    <p>無紀錄</p>
                @endif
            </td>
            <td class="images_comparation_cell">
                @if(!$pic->isPicNeedCompare())
                    <b>不需比對的照片</b>      
                @else              
                    @php 
                        $pic->compareImages('advInfo');  
                        $compareStatus = $pic->getCompareStatus();
                        $compareRsImgs = $pic->getCompareRsImg();
                    @endphp                
                    @if(!$pic->getCompareEncode() && !$pic->isPicFileExists())
                    <b>照片的檔案不存在，無法比對</b>  
                    @elseif(!$pic->getCompareEncode() && ($compareStatus??null) && !($compareRsImgs??null) && $compareRsImgs->count()==0 && $compareStatus->queue??null)
                    <b>{{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已{{$compareStatus->queue==2?'重新':''}}申請排隊執行比對，等待系統回應中</b>   
                    @elseif(!$pic->getCompareEncode() )
                        @if($compareStatus??null)
                        <b>資料異常</b>
                        @else
                        <b>尚未建立比對資訊</b>
                        @endif   
                    @else
                    <b>完全相同(不含調整過寬高)</b>
                        @php $sameImages = $pic->getSameImg() @endphp
                        @if($sameImages->count())
                        <div>
                            @foreach ($sameImages as $sameImg)
                            
                            @if($sameImg->user??null)
                            <div class="block_line">
                                <a href="{{ $sameImg->pic }}" target="_blank"><img src="{{ $sameImg->pic }}" onerror="this.src='/img/linktosource.png'"></a>
                                <div class="gender{{$sameImg->user->engroup}} {{$sameImg->userStateStr?'rs_user_':''}}{{$sameImg->userStateStr??''}}"><a href="{{route('users/advInfo',$sameImg->user->id)}}" target="_blank">{{$sameImg->user->name}}</a><span class="lli_holder">({{$sameImg->userLliDiffDays}})</span></div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <p>沒有完全相同的圖片</p>
                        @endif 
                        <b>看起來像的圖片</b>
                        @if($compareStatus??null)
                           <div>
                                @if($compareStatus->isHoldTooLong())
                                    未完成比對
                                @elseif($compareStatus->queue==1)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已申請排隊執行比對，等待系統回應中    
                                @elseif($compareStatus->queue==2)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已重新申請排隊執行比對，等待系統回應中                                  
                                @elseif($compareStatus->status==1)
                                    比對中
                                @endif
                                @if($compareStatus->status)
                                <span>已比對
                                    <span class="percent_number">
                                    {{abs(intval(100*$compareStatus->encode_break_id/$last_images_compare_encode->id)-5)}}
                                    </span>
                                    %
                                </span>
                                @endif
                            </div>                        
                            <div>
                                @forelse($compareRsImgs as $rsIdx => $rsImg)                           
                                    @if($rsImg->user??null)
                                    <div class="block_line">
                                        <a href="{{ $rsImg->pic }}" target="_blank"><img class="cp_rs_img"  src="{{ $rsImg->pic }}"  onerror="this.src='/img/linktosource.png'"></a>
                                        <div class="gender{{$rsImg->user->engroup}} {{$rsImg->userStateStr?'rs_user_':''}}{{$rsImg->userStateStr??''}}"><a href="{{route('users/advInfo',$rsImg->user->id)}}" target="_blank">{{$rsImg->user->name}}</a><span class="lli_holder">({{$rsImg->userLliDiffDays}})</span></div>
                                    </div>
                                    @endif
                                @empty
                                    @if(!$compareStatus->queue && !$compareStatus->status)
                                    <p>沒有看起來像的圖片</p> 
                                    @endif
                                @endforelse
                            </div>
                        @else
                            <p>尚未開始比對</p>
                        @endif                    
                    @endif
                @endif
            </td>
        </tr>
        @empty
        @endforelse

        {{-- 已刪除的生活照 --}}
        @forelse ($user->pic_onlyTrashed as $pic)
        <tr>
            <td class="user_recorded_img"><img src="{{ url($pic->pic) }}" width="120px"></td>
            <td class="text-nowrap">{{ $pic->created_at }}</td>
            <td class="text-nowrap">{{ $pic->deleted_at }}</td>
            <td>
                @if ($pic->deleted_at)
                    @php
                        $checkAdminDeleted = \App\Models\AdminPicturesSimilarActionLog::where('pic', $pic->pic)->first();
                    @endphp
                    @if ($checkAdminDeleted)
                        {{ $checkAdminDeleted->operator_user->email }}
                    @else
                        本人
                    @endif
                @endif
            </td>
            <td>
                @php
                    $ImgResult = \App\Models\SimilarImages::where('pic', $pic->pic)->first();
                @endphp
                @if ($ImgResult)

                    @if ($ImgResult->status == 'success')

                        {{-- 完全匹配 Start --}}
                        <b>完全匹配(含調整過寬高)</b>
                        @if ($ImgResult->fullMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->fullMatchingImages) as $fullMatchingImage)
                                    <a href="{{ $fullMatchingImage->url }}" target="_blank"><img src="{{ $fullMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有完全匹配的內容</p>
                        @endif
                        {{-- 完全匹配 End --}}

                        {{-- 足夠相似 Start --}}
                        <b>足夠相似</b>
                        @if ($ImgResult->partialMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->partialMatchingImages) as $partialMatchingImage)
                                    <a href="{{ $partialMatchingImage->url }}" target="_blank"><img src="{{ $partialMatchingImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有足夠相似的內容</p>
                        @endif
                        {{-- 足夠相似 End --}}

                        {{-- 匹配的網頁 Start --}}
                        <b>匹配的網頁</b>
                        @if ($ImgResult->pagesWithMatchingImages)
                            <p>
                                @foreach (json_decode($ImgResult->pagesWithMatchingImages) as $pagesWithMatchingImage)
                                    <a href="{{ $pagesWithMatchingImage->url }}" target="_blank">{{ $pagesWithMatchingImage->pageTitle ?? "無標題" }}</a><br>
                                @endforeach
                            </p>
                        @else
                            <p>沒有匹配的網頁</p>
                        @endif
                        {{-- 匹配的網頁 End --}}

                        {{-- 看起來像的圖片 Start --}}
                        <b>看起來像的圖片</b>
                        @if ($ImgResult->visuallySimilarImages)
                            <p>
                                @foreach (json_decode($ImgResult->visuallySimilarImages) as $visuallySimilarImage)
                                    <a href="{{ $visuallySimilarImage->url }}" target="_blank"><img src="{{ $visuallySimilarImage->url }}" style="max-width:120px; margin-right:10px;" onerror="this.src='/img/linktosource.png'"></a>
                                @endforeach
                            </p>
                        @else
                            <p>沒有看起來像的圖片</p>
                        @endif
                        {{-- 看起來像的圖片 End --}}

                    @elseif ($ImgResult->status == 'failed')
                        <p>搜尋失敗</p>
                    @endif
                @else
                    <p>無紀錄</p>
                @endif
            </td>
            <td class="images_comparation_cell">
                @if(!$pic->isPicNeedCompare())
                    <b>不需比對的照片</b>      
                @else            
                    @php
                        $pic->compareImages('advInfo');                
                        $compareStatus = $pic->getCompareStatus();
                        $compareRsImgs = $pic->getCompareRsImg();
                    @endphp
                    @if(!$pic->getCompareEncode() && !$pic->isPicFileExists())
                        <b>照片的檔案不存在，無法比對</b>  
                    @elseif(!$pic->getCompareEncode() && ($compareStatus??null) && !($compareRsImgs??null) && $compareRsImgs->count()==0 && ($compareStatus->queue??null))
                        <b>{{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已{{$compareStatus->queue==2?'重新':''}}申請排隊執行比對，等待系統回應中</b>   
                    @elseif(!$pic->getCompareEncode() )
                        @if($compareStatus??null)
                        <b>資料異常</b>
                        @else
                        <b>尚未建立比對資訊</b>
                        @endif    
                    @else
                        <b>完全相同(不含調整過寬高)</b>
                        @php $sameImages = $pic->getSameImg() @endphp
                        @if($sameImages->count())
                        <div>
                            @foreach ($sameImages as $sameImg)
                            
                            @if($sameImg->user??null)
                            <div class="block_line">
                                <a href="{{ $sameImg->pic }}" target="_blank"><img src="{{ $sameImg->pic }}" onerror="this.src='/img/linktosource.png'"></a>
                                <div class="gender{{$sameImg->user->engroup}} {{$sameImg->userStateStr?'rs_user_':''}}{{$sameImg->userStateStr??''}}"><a href="{{route('users/advInfo',$sameImg->user->id)}}" target="_blank">{{$sameImg->user->name}}</a><span class="lli_holder">({{$sameImg->userLliDiffDays}})</span></div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <p>沒有完全相同的圖片</p>
                        @endif 
                        <b>看起來像的圖片</b>
                        @if($compareStatus??null)
                           <div>
                                @if($compareStatus->isHoldTooLong())
                                    未完成比對
                                @elseif($compareStatus->queue==1)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已申請排隊執行比對，等待系統回應中
                                @elseif($compareStatus->queue==2)
                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已重新申請排隊執行比對，等待系統回應中                                 
                                @elseif($compareStatus->status==1)
                                    比對中
                                @endif
                                @if($compareStatus->status)
                                <span>已比對
                                    <span class="percent_number">
                                    {{abs(intval(100*$compareStatus->encode_break_id/$last_images_compare_encode->id)-5)}}
                                    </span>
                                    %
                                </span>
                                @endif
                            </div>                        
                            <div>                    
                                @forelse($compareRsImgs as $rsIdx => $rsImg)                           
                                    @if($rsImg->user??null)
                                    <div class="block_line">
                                        <a href="{{ $rsImg->pic }}" target="_blank"><img class="cp_rs_img " src="{{ $rsImg->pic }}"  onerror="this.src='/img/linktosource.png'"></a>
                                        <div class="gender{{$rsImg->user->engroup}} {{$rsImg->userStateStr?'rs_user_':''}}{{$rsImg->userStateStr??''}}"><a href="{{route('users/advInfo',$rsImg->user->id)}}" target="_blank">{{$rsImg->user->name}}</a><span class="lli_holder">({{$rsImg->userLliDiffDays}})</span></div>
                                    </div>
                                    @endif
                                @empty
                                    @if(!$compareStatus->queue && !$compareStatus->status)
                                    <p>沒有看起來像的圖片</p>    
                                    @endif
                                @endforelse
                            </div>
                        @else
                            <p>尚未開始比對</p>
                        @endif                    
                    @endif
                @endif
            </td>            
        </tr>
        @empty
        @endforelse
    </tbody>
</table>

