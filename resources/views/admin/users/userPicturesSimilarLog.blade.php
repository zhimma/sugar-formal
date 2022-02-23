@extends('admin.main')
@section('app-content')
    <style>
        .images_comparation_cell img {width:120px;}
        
    div.block_line {float:left;}
    div.block_line > div {overflow:auto;}
    div.block_line:last-child {float:left;margin-bottom:1rem;}
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
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>

    <body style="padding: 15px;">
        <h1>會員照片管理結果列表</h1>

        @if (Auth::user()->can('admin') || Auth::user()->can('juniorAdmin'))

            <form action="/admin/users/picturesSimilarLog" method="get">
                <table class="table-hover table table-bordered" style="width: 50%;">
                    <tr>
                        <th>開始時間</th>
                        <td><input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if (isset($_GET['date_start'])){{ $_GET['date_start'] }}@else{{ old('date_start') }}@endif" class="form-control"></td>
                    <tr>
                        <th>結束時間</th>
                        <td><input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if (isset($_GET['date_end'])){{ $_GET['date_end'] }}@else{{ old('date_end') }}@endif" class="form-control"></td>
                    </tr>
                    <tr>
                        <th>預設時間選項</th>
                        <td>
                            <a class="text-white btn btn-success today">今天</a>
                            <a class="text-white btn btn-success last3days">最近3天</a>
                            <a class="text-white btn btn-success last10days">最近10天</a>
                            <a class="text-white btn btn-success last15days">最近15天</a>
                            <a class="text-white btn btn-success last30days">最近30天</a>
                        </td>
                    </tr>

                    <tr>
                        <th>性別</th>
                        <td>
                            <input type="radio" name="en_group" value="1" @if (isset($_GET['en_group']) && $_GET['en_group'] == 1) checked @endif>男
                            <input type="radio" name="en_group" value="2" @if (isset($_GET['en_group']) && $_GET['en_group'] == 2) checked @endif>女
                        </td>
                    </tr>
                    <tr>
                        <th>地區</th>
                        <td class="twzipcode">
                            <div class="twzip" id="city" data-role="county" data-name="city" data-value="@if (isset($_GET['city'])){{ $_GET['city'] }}@endif"></div>
                            <div class="twzip" id="area" data-role="district" data-name="area" data-value="@if (isset($_GET['area'])){{ $_GET['area'] }}@endif"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>排序方式</th>
                        <td>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="order_by" value="updated_at" @if (isset($_GET['order_by']) && $_GET['order_by'] == 'updated_at') checked @endif style="margin-left: unset;">
                                <label class="form-check-label" for="inlineRadio4">更新時間</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="order_by" value="last_login" @if (isset($_GET['order_by']) && $_GET['order_by'] == 'last_login') checked @endif style="margin-left: unset;">
                                <label class="form-check-label" for="inlineRadio5">上線時間</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary">查詢</button> 或
                            <button type="submit" class="btn btn-info" name="hidden" value="1">隱藏的照片</button>
                            <button type="reset" class="btn btn-default reset_btn" value="Reset">清除重選</button>
                        </td>
                    </tr>
                </table>
            </form>
            <p>
                <a class="btn btn-sm btn-primary" href="/admin/users/picturesSimilar">照片管理</a>
                <a class="btn btn-sm btn-primary" href="/admin/users/picturesSimilarLog">管理結果</a>
                {{-- <a class="btn btn-sm btn-primary" href="/admin/users/picturesSimilarJob">送檢照片</a> --}}
            </p>


            @isset($AdminPicturesSimilarActionLogs)

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-nowrap" style="width: 230px">會員資料</th>
                                <th class="text-nowrap" style="width: 200px">操作者</th>
                                <th class="text-nowrap" style="width: 120px">操作</th>
                                <th class="text-nowrap" style="width: 300px">原因</th>
                                <th class="text-nowrap" style="width: 150px">時間</th>
                                <th class="text-nowrap" style="width: 200px">照片</th>
                                <th class="text-nowrap">以圖找圖</th>
                                <th class="text-nowrap">站內搜圖</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($AdminPicturesSimilarActionLogs as $ActionLog)
                                @php
                                    $CurrentTargetLogs = \App\Models\AdminPicturesSimilarActionLog::where('target_id', $ActionLog->target_id)
                                        ->where('operator_role', 3)
                                        ->orderByDesc('created_at')
                                        ->limit(10)
                                        ->get();
                                    $count = $CurrentTargetLogs->count();
                                @endphp

                                @foreach ($CurrentTargetLogs as $Log)
                                    <tr>
                                        @if ($loop->first)
                                            <td class="align-middle" style="{{ $ActionLog->target_user->isBanned($ActionLog->target_user->id) ? 'background-color: #FFFF00;' : '' }}" rowspan="{{ $count }}">
                                                <p>
                                                    @if ($ActionLog->target_user->meta->pic)
                                                        <img class="w-100" src="{{ url($ActionLog->target_user->meta->pic) }}">
                                                    @else
                                                        <span class="bg-secondary">此用戶未設置頭像</span>
                                                    @endif
                                                </p>
                                                <p>
                                                    <span>會員名稱: <a href="{{ route('users/advInfo', $ActionLog->target_user->id) }}" target="_blank"><span class="{{ $ActionLog->target_user->engroup == 2 ? 'text-danger' : 'text-primary' }}">{{ $ActionLog->target_user->name }}</span></a></span><br>
                                                    <span>會員標題: {{ $ActionLog->target_user->title }}</span><br>
                                                    <span>上線時間: {{ $ActionLog->target_user->last_login }}</span>
                                                </p>
                                                <p>
                                                    @if ($ActionLog->target_user->isBanned($ActionLog->target_user->id))
                                                        <button class="btn btn-sm btn-success" type="button" onclick="unblockModal(this)" data-toggle="modal" data-target="#unblockModal" data-uid="{{ $ActionLog->target_user->id }}" data-reason="{{ $ActionLog->target_user->banned->reason }}" data-expire="{{ $ActionLog->target_user->banned->expire_date }}">解除封鎖</button>
                                                    @else
                                                        <button class="btn btn-sm btn-danger" type="button" onclick="blockModal(this)" data-toggle="modal" data-target="#blockModal" data-uid="{{ $ActionLog->target_user->id }}">封鎖</button>
                                                    @endif
                                                </p>
                                                <p>
                                                <form class="form-inline" action="/admin/users/picturesSimilar/suspicious:toggle" method="post">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="uid" value="{{ $ActionLog->target_user->id }}">
                                                    <label class="mr-sm-2">可疑原因:</label>

                                                    @if ($ActionLog->target_user->suspicious)

                                                        <input type="hidden" name="toggle" value="0">
                                                        <input class="form-control form-control-sm form-control-plaintext mr-sm-2" type="text" value="{{ $ActionLog->target_user->suspicious->reason }}" readonly>
                                                        <button class="btn btn-sm btn-primary btn_sid" type="button" data-confirm="0">解除</button>
                                                    @else

                                                        <input type="hidden" name="toggle" value="1">
                                                        <input class="form-control form-control-sm mr-sm-2" type="text" placeholder="請輸入可疑原因" name="reason" value="">
                                                        <button class="btn btn-sm btn-danger btn_sid" type="button" data-confirm="1">列入</button>
                                                    @endif
                                                </form>
                                                </p>
                                            </td>
                                        @endif
                                        <td class="align-middle">{{ $Log->operator_user->email }}</td>
                                        <td class="align-middle">
                                            <p class="text-center">
                                                @if ($Log->act == '刪除頭像' || $Log->act == '刪除生活照')
                                                    <span class="badge bg-danger">{{ $Log->act }}</span>
                                                @endif

                                                @if ($Log->act == '加入封鎖名單' || $Log->act == '加入可疑名單')
                                                    <span class="badge bg-warning">{{ $Log->act }}</span>
                                                @endif

                                                @if ($Log->act == '刪除封鎖名單' || $Log->act == '刪除可疑名單')
                                                    <span class="badge bg-success">{{ $Log->act }}</span>
                                                @endif

                                                @if (str_contains($Log->act, '變更性別'))
                                                    <span class="badge bg-warning">{{ $Log->act }}</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <p>{{ $Log->reason ? $Log->reason : '無'  }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <p>{{ $Log->created_at }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-center">
                                                @if($Log->pic)
                                                    <img src="{{ $Log->pic ? url($Log->pic) : '' }}" style="max-height: 180px;">
                                                @else
                                                    無
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            @if ($Log->act == '刪除生活照' || $Log->act == '刪除頭像')
                                                @php
                                                    $ImgResult = \App\Models\SimilarImages::where('pic', $Log->pic ?? $Log->pic)->first();
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
                                                    <p>尚未檢查</p>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="images_comparation_cell"> 
                                            @if ($Log->pic && ($Log->act == '刪除生活照' || $Log->act == '刪除頭像'))
                                                    @php 
                                                        if($Log->isPicNeedCompare()) $Log->compareImages('picturesSimilarLog');
                                                        $compareStatus = $Log->getCompareStatus();
                                                        $compareRsImgs = $Log->getCompareRsImg(); 
                                                    @endphp
                                                    @if(!$Log->getCompareEncode() && !$Log->isPicFileExists())
                                                    <b>照片的檔案不存在，無法比對</b>  
                                                    @elseif(!$Log->getCompareEncode() && ($compareStatus??null) && (!($compareRsImgs??null) || $compareRsImgs->count()==0) && $compareStatus->queue??null)
                                                    <b>{{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已{{$compareStatus->queue==2?'重新':''}}申請排隊執行比對，等待系統回應中</b>   
                                                    @elseif(!$Log->getCompareEncode())
                                                        @if($compareStatus??null)
                                                        <b>資料異常</b>
                                                        @else
                                                        <b>尚未建立比對資訊</b>
                                                        @endif   
                                                    @else
                                                        
                                                        <b>完全相同(不含調整過寬高)</b>
                                                        @php $sameImages = $Log->getSameImg() @endphp
                                                        @if($sameImages->count())
                                                        <div>
                                                            @foreach ($sameImages as $sameImg)
                                                            
                                                            @if($sameImg->user)
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
                                                                @forelse($compareRsImgs as $rsImg)                           
                                                                    @if($rsImg->user)
                                                                    <div class="block_line">
                                                                        <a href="{{ $rsImg->pic }}" target="_blank"><img src="{{ $rsImg->pic }}"  onerror="this.src='/img/linktosource.png'"></a>
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
                                                            <div>尚未開始比對
                                                            </div>
                                                        @endif                    
                                                    @endif
                                                @endif
                                            @endif
                                        </td>                                          
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {!! $AdminPicturesSimilarActionLogs->appends(request()->input())->links('pagination::sg-pages') !!}

            @endisset
        @else
            <p>沒有本頁面權限</p>
        @endif


        {{-- blockModal --}}
        <div class="modal fade" id="blockModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">會員封鎖</h5>
                        <button class="close" type="button" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="BlockUser" action="/admin/users/picturesSimilar/block:toggle" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="toggle" value="1">
                            <input type="hidden" name="user_id" value="">
                            <input type="hidden" name="page" value="{{ url()->full() }}">
                            <input type="hidden" name="vip_pass" value="0">
                            <div class="form-group">
                                <label class="form-label">封鎖時間</label>
                                <select class="form-control" name="days">
                                    <option value="X" selected>永久</option>
                                    <option value="3">三天</option>
                                    <option value="7">七天</option>
                                    <option value="15">十五天</option>
                                    <option value="30">三十天</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">封鎖原因</label>
                                <div>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">廣告</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">非徵求包養行為</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">用詞不當</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">照片不當</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">多重帳號</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">性別錯誤</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">要求裸照</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">罐頭訊息+主動給line</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">基本資料錯誤</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">站規8</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">未成年</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">拒往</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">多重帳號,情節嚴重</button>
                                    <button class="btn btn-sm btn-success text-white mb-1 mr-1 banReason" type="button">未依要求與站方聯絡，已按使用天數刷退VIP費用。並停止帳號使用。</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="reason" rows="4" maxlength="200">廣告</textarea>
                            </div>
                        </form>
                        <script>
                            $('#BlockUser .banReason').click(function(e) {
                                e.preventDefault();

                                $('#BlockUser textarea[name=reason]').val($(this).html());
                            });
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" type="submit" form="BlockUser">送出</button>
                        <button class="btn btn-outline-dark" type="button" data-dismiss="modal">取消</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- unblockModal --}}
        <div class="modal fade" id="unblockModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">會員封鎖</h5>
                        <button class="close" type="button" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="unBlockUser" action="/admin/users/picturesSimilar/block:toggle" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="toggle" value="0">
                            <input type="hidden" name="user_id" value="">
                            <input type="hidden" name="page" value="{{ url()->full() }}">
                            <input type="hidden" name="vip_pass" value="0">
                            <div class="form-group">
                                <label class="form-label">封鎖時間</label>
                                <input class="form-control" id="expire_date" type="text" value="" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">封鎖原因</label>
                                <textarea class="form-control" name="reason" rows="4" maxlength="200" readonly>廣告</textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" type="submit" form="unBlockUser">解除</button>
                        <button class="btn btn-outline-dark" type="button" data-dismiss="modal">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        function blockModal(e) {
            let user_id = $(e).data('uid');
            $('#BlockUser input[name=user_id]').val(user_id);
        }

        function unblockModal(e) {

            let user_id = $(e).data('uid');
            let expire = $(e).data('expire');
            let reason = $(e).data('reason');

            $('#unBlockUser input[name=user_id]').val(user_id);
            if (expire) {
                $('#expire_date').val(expire);
            } else {
                $('#expire_date').val('永久');
            }
            $('#unBlockUser textarea[name=reason]').val(reason);
        }
    </script>
    <script>
        $('.twzipcode').twzipcode({
            'detect': true,
            'css': ['form-control twzip', 'form-control twzip', 'zipcode']
        });
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        let today = new Date(year, month, day);
        let minus_date = new Date(today);
        jQuery(document).ready(function() {
            jQuery("#datepicker_1").datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }).val();
            jQuery("#datepicker_2").datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }).val();

            $('.today').click(
                function() {
                    $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                    set_end_date();
                });
            $('.last3days').click(
                function() {
                    var days = 3; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last10days').click(
                function() {
                    var days = 10; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last15days').click(
                function() {
                    var days = 15; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                    set_end_date();
                });
            $('.last30days').click(
                function() {
                    var start_date = new Date(new Date().setDate(date.getDate() - 30));
                    $('#datepicker_1').val(start_date.getFullYear() + '-' + str_pad(start_date.getMonth() + 1) + '-' + str_pad(start_date.getDate()));
                    $('.datepicker_1').val(start_date.getFullYear() + '-' + str_pad(start_date.getMonth() + 1) + '-' + str_pad(start_date.getDate()));
                    set_end_date();
                });
        });

        function set_end_date() {
            $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
            $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        }

        function str_pad(n) {
            return String("00" + n).slice(-2);
        }

        $('.reset_btn').on('click', function() {
            $('input:radio').removeAttr('checked');
            $('#datepicker_1, #datepicker_2').removeAttr('value');
        });

        $('.btn_sid').on('click', function() {

            let type = $(this).data('confirm');
            if (type == 1) {
                r = confirm('是否確定加入可疑名單?');
            } else {
                r = confirm('是否確定移除可疑名單?');
            }

            if (r == true) {
                $(this).closest('form').submit();
            }

        });
    </script>
@stop
