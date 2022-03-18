@extends('admin.main')
@section('app-content')
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>

    <!--照片查看-->
    <link type="text/css" rel="stylesheet" href="/new/css/app.css">
    <link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
    <script type="text/javascript" src="/new/js/swiper.min.js"></script>
    <!--照片查看-->

    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            vertical-align: middle;
        }

        .table>tbody>tr>th {
            text-align: center;
        }
        
        .recompare_intro {
            font-size:8px;
        }
        
        .images_comparation_cell img {width:120px;}
    
        .btn-gray {
            cursor: default;
            color: #fff;
            background-color: #5a5a5a;
            border-color: #5a5a5a;   
            opacity: .65;        
            
        }

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
        
        #blockade .form-group {clear:both;}
        #autoban_pic_gather .autoban_pic_unit {float:left;margin:10px;}
        #autoban_pic_gather .autoban_pic_unit img {width:80px;min-width:80px;}
        #autoban_pic_gather input {display:none;}
        #autoban_pic_gather .autoban_pic_unit label {padding:0 10px 10px 10px;} 
        #autoban_pic_gather .autoban_pic_unit label span {display:block;text-align:center;font-size:4px;}
        #autoban_pic_gather .autoban_pic_unit input:checked+label {background:#1E90FF;}
    
    </style>

    <body style="padding: 15px;">
        <h1>會員檢查 step 2</h1>

        @if (Auth::user()->can('admin') || Auth::user()->can('juniorAdmin'))

            <form action="{{ route('users/picturesSimilar') }}" method="get">
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
                            <button type="submit" class="btn btn-info" name="hidden" value="1">查詢並顯示隱藏的照片</button>
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

            @if (isset($users))

                <table class="table-hover table table-bordered">
                    <tr>
                        <td class="col-2">會員資料</td>
                        <td class="col-2">照片</td>
                        <td class="col-4">以圖找圖</td>
                        <td class="col-4">站內搜圖</td>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td rowspan="{{ $user->pic->count() + 1 }}" style="{{ $user->isBanned($user->id) ? 'background-color: #FFFF00;' : '' }}">
                                <p>
                                    <span>會員名稱: <a href="/admin/users/advInfo/editPic_sendMsg/{{ $user->id }}" target="_blank"><span class="{{ $user->engroup == 2 ? 'text-danger' : 'text-primary' }}">{{ $user->name }}</span></a></span><br>
                                    <span>電子郵件: {{ str_replace(strchr($user->email,'@'), '', $user->email) }}</span><br>
                                    <form method="POST" action='/admin/users/little_update_profile'>
                                        {!! csrf_field() !!}
                                        <input type="hidden" value={{ $user->id }} name="user_id">
                                        <span>
                                            會員標題: 
                                            <input type="text" class="form-control form-control-sm form-control-plaintext mr-sm-2" value={{ $user->title }} name="title">
                                        </span>
                                        <span>
                                            關於我: 
                                            <input type="text" class="form-control form-control-sm form-control-plaintext mr-sm-2" value={{ $user->meta_()->about }} name="about">
                                        </span>
                                        <span>
                                            期待的約會模式: 
                                            <input type="text" class="form-control form-control-sm form-control-plaintext mr-sm-2" value={{ $user->meta_()->style }} name="style">
                                        </span>
                                        <br>
                                        <button type="submit" class="btn btn-sm btn-primary">修改資料</button>
                                    </form>
                                    <br>
                                    <span>上線時間: {{ $user->last_login }}</span><br>
                                    <span>更新時間: {{ $user->last_update }}</span><br>
                                </p>
                                <p>
                                    @if($user->engroup== '1')
                                        <button type="button" class="btn btn-sm btn-danger ban_user" data-uid="{{$user->id}}" data-toggle="modal" data-target="#banModal">封鎖</button>
                                    @endif
                                    @if($user->engroup== '2')
                                        <button type="button" class="btn btn-sm @if($user->advance_auth_status==1) btn-gray @else btn-danger @endif advance_auth_ban_user" data-uid="{{$user->id}}" data-toggle="modal" data-target="#banModal" data-advance_auth_status="{{$user->advance_auth_status}}">驗證封鎖</button>
                                    @endif
                                    <form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
                                        <input type="hidden" name='user_id' value="{{ $user->id }}">
                                        <input type="hidden" name='gender_now' value="{{ $user->engroup }}">
                                        <input type="hidden" name='page' value="userPicturesSimilar">
                                        <button type="submit" class="btn btn-sm btn-warning">變更性別</button>
                                    </form>
                                    <br>
                                    <a href="/admin/users/advInfo/editPic_sendMsg/{{ $user->id }}" class='text-white btn btn-sm btn-primary'>照片&發訊息</a>
                                </p>
                                <p>
                                <form class="form-inline" action="/admin/users/picturesSimilar/suspicious:toggle" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="uid" value="{{ $user->id }}">
                                    <label class="mr-sm-2">可疑原因:</label>

                                    @if ($user->suspicious)

                                        <input type="hidden" name="toggle" value="0">
                                        <input class="form-control form-control-sm form-control-plaintext mr-sm-2" type="text" value="{{ $user->suspicious->reason }}" readonly>
                                        <button class="btn btn-sm btn-primary btn_sid" type="button" data-confirm="0">解除</button>
                                    @else

                                        <input type="hidden" name="toggle" value="1">
                                        <input class="form-control form-control-sm mr-sm-2" type="text" placeholder="請輸入可疑原因" name="reason" value="">
                                        <button class="btn btn-sm btn-danger btn_sid" type="button" data-confirm="1">列入</button>
                                    @endif
                                </form>
                                </p>
                            </td>
                            <td>
                                <p>
                                    @if ($user->meta->pic)
                                        <p class='evaluation_zoomIn'>
                                                <img class='img_select' src="{{ $user->meta->pic }}" width="120px">
                                            <br>
                                            <span>
                                                新增時間: {{ $user->meta->created_at }}
                                            </span>
                                        </p>
                                    @else
                                        無
                                    @endif
                                </p>
                            </td>
                            {{--<td style="width: 10%">
                                @if ($user->meta->pic)
                                    <form action="/admin/users/picturesSimilar/avatar:delete" method="post">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="userId" value="{{ $user->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">刪除</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-dark mb-2" type="button" disabled>刪除</button>
                                @endif
                            </td>--}}
                            <td>
                                @if ($user->meta->pic)
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
                                        <p>尚未檢查</p>
                                    @endif
                                @endif
                            </td>
                            <td class="images_comparation_cell">
                                @if(!($user->meta->pic??null))
                                <b>無</b>
                                @endif
                                @if($user->meta->pic??null)
                                    @php 
                                        if($user->meta->isPicNeedCompare()) $user->meta->compareImages('picturesSimilar');
                                        $compareStatus = $user->meta->getCompareStatus();
                                        $compareRsImgs = $user->meta->getCompareRsImg(); 
                                    @endphp
                                    @if(!$user->meta->getCompareEncode() && !$user->meta->isPicFileExists())
                                    <b>照片的檔案不存在，無法比對</b>  
                                    @elseif(!$user->meta->getCompareEncode() && ($compareStatus??null) && (!($compareRsImgs??null) || $compareRsImgs->count()==0) && $compareStatus->queue??null)
                                    <b>{{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已{{$compareStatus->queue==2?'重新':''}}申請排隊執行比對，等待系統回應中</b>   
                                    @elseif(!$user->meta->getCompareEncode())
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
                                                {{--<div><a href="/admin/users/advInfo/{{ $user->id }}" target="_blank">重新比對</a></div>--}}
                                                    未完成比對
                                                @elseif($compareStatus->queue==1)
                                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已申請排隊執行比對，等待系統回應中 
                                                @elseif($compareStatus->queue==2)
                                                    {{date('m/d H:i',strtotime($compareStatus->qstart_time))}}已重新申請排隊執行比對，等待系統回應中                                 
                                                @elseif($compareStatus->status==1)
                                                    比對中
                                                @else

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
                                        {{--<div><a href="/admin/users/advInfo/{{ $user->id }}" target="_blank">加入排隊等待比對</a></div>--}}
                                            <div>尚未開始比對
                                            </div>
                                        @endif                    
                                    @endif
                                @endif
                            </td>                          
                        </tr>
                        @if ($user->pic->count() != 0)
                            @foreach ($user->pic as $pic)
                                <tr>
                                    <td>
                                        <p class='evaluation_zoomIn'>
                                            <img class='img_select' src="{{ url($pic->pic) }}" width="120px">
                                            <br>
                                            <span>
                                                新增時間: {{ $pic->created_at }}
                                            </span>
                                        </p>
                                    </td>
                                    {{--<td>
                                        <form action="/admin/users/picturesSimilar/image:delete" method="post">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="userId" value="{{ $user->id }}">
                                            <input type="hidden" name="imgId" value="{{ $pic->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger">刪除</button>
                                        </form>
                                    </td>--}}
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
                                            <p>尚未檢查</p>
                                        @endif
                                    </td>
                                    <td class="images_comparation_cell">
                                    @if(!($pic->pic??null))
                                    <b>無</b>
                                    @endif
                                    @if($pic->pic??null)
                                        @php
                                            if($pic->isPicNeedCompare()) $pic->compareImages('picturesSimilar');                
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
                                                
                                                @if($sameImg->user)
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
                                                    @else
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
                                                            <a href="{{ $rsImg->pic }}" target="_blank"><img src="{{ $rsImg->pic }}" onerror="this.src='/img/linktosource.png'"></a>
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
                            @endforeach
                        @endif
                    @endforeach
                </table>

                {!! $users->appends(request()->input())->links('pagination::sg-pages') !!}
                <div style="text-align:center;">
                    <button class="check_and_next_page btn btn-primary">下一頁(檢查完畢)</button>
                </div>
                <br>

            @endif

        @else

            <p>沒有本頁面權限</p>
        @endif
    </body>

    @if(isset($user_id_of_page))
    <form id="check_and_next_page" action="{{ route('admin/member_profile_check_over') }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="users_id" id="users_id" value={{json_encode($user_id_of_page)}}>
        <input type="hidden" name="check_point_id" id="check_point_id" value=2>
    </form>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="banModal" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="banModal_title">封鎖</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
                    {!! csrf_field() !!}
                    <input type="hidden" value="" name="user_id" id="blockUserID">
                    <input type="hidden" value="member_check_step1" name="page">
                    <input type="hidden" name="vip_pass" value=0>
                    <input type="hidden" name="adv_auth" value=0>
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
                        <div id='banReason'>
                        </div>
                        <br><br>
                        <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                        <label style="margin:10px 0px;">
                            <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                            <sapn style="vertical-align:middle;">加入常用封鎖原因</sapn>
                        </label>
                        <hr>
                            <div id="auto_ban_title">
                                新增自動封鎖條件
                            </div>
                        <div class="form-group">
                            <label for="cfp_id" id='cfp_id_title'>CFP_ID</label>
                            <select multiple class="form-control" id="cfpid" name="cfp_id[]">
                            </select>
                        </div>
                        <div class="form-group">
                            <label id='pic_title'>照片</label>
                            <div id="autoban_pic_gather">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="ip">IP</label>
                            <table id="table_userLogin_log" class="table table-hover table-bordered">
                            </table>
                        </div>
                        <hr>
                            <div id="add_auto_ban_title">
                                新增自動封鎖關鍵字(永久封鎖)
                            </div>
                        <input placeholder="1.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                        <input placeholder="2.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                        <input placeholder="3.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">       
                    </div>
                    <div class="modal-footer" id="modal-footer">
                        <button type="submit" class="btn btn-outline-success ban-user">送出</button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!--照片查看-->
    <div class="big_img">
        <!-- 自定義分頁器 -->
        <div class="swiper-num">
            <span class="active"></span>
            /
            <span class="total"></span>
        </div>
        <div class="swiper-container2">
            <div class="swiper-wrapper">
            </div>
        </div>
        <div class="swiper-pagination2"></div>
    </div>
    <!--照片查看-->

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
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' +
                        str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(
                        date.getDate()));
                    set_end_date();
                });
            $('.last10days').click(
                function() {
                    var days = 10; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' +
                        str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(
                        date.getDate()));
                    set_end_date();
                });
            $('.last15days').click(
                function() {
                    var days = 15; // Days you want to subtract
                    var date = new Date();
                    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                    $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' +
                        str_pad(last.getDate()));
                    $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(
                        date.getDate()));
                    set_end_date();
                });
            $('.last30days').click(
                function() {
                    var start_date = new Date(new Date().setDate(date.getDate() - 30));
                    $('#datepicker_1').val(start_date.getFullYear() + '-' + str_pad(start_date.getMonth() + 1) +
                        '-' + str_pad(start_date.getDate()));
                    $('.datepicker_1').val(start_date.getFullYear() + '-' + str_pad(start_date.getMonth() + 1) +
                        '-' + str_pad(start_date.getDate()));
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
    <script>
        //照片查看
        $(function(){
            var mySwiper = new Swiper('.swiper-container2',{
                pagination : '.swiper-pagination2',
                paginationClickable:true,
                onInit: function(swiper){
                    var active =swiper.activeIndex;
                    $(".swiper-num .active").text(active);
                },
                onSlideChangeEnd: function(swiper){
                    var active =swiper.realIndex +1;
                    $(".swiper-num .active").text(active);
                }
            });

            $(".img_select").on("click",function () {
                var imgBox = $(this).closest(".evaluation_zoomIn").find(".img_select");
                var i = $(imgBox).index(this);
                $(".big_img .swiper-wrapper").html("")

                for (var j = 0, c = imgBox.length; j < c ; j++) {
                    $(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).attr("src") + '" / ></div></div>');
                }
                mySwiper.updateSlidesSize();
                mySwiper.updatePagination();
                $(".big_img").css({
                    "z-index": 1001,
                    "opacity": "1"
                });
                //分页器
                var num = $(".swiper-pagination2 span").length;
                $(".swiper-num .total").text(num);
                $(".swiper-num .active").text(i + 1);

                mySwiper.slideTo(i, 0, false);
                return false;
            });

            $(".swiper-container2").click(function(){
                $(this).parent(".big_img").css({
                    "z-index": "-1",
                    "opacity": "0"
                });
            });
        });
        //照片查看

        //封鎖相關
        $('.ban_user').on('click', function () {
            uid = $(this).attr('data-uid');
            init_ban_modal();
            $('#banModal_title').append('封鎖');
            $('#auto_ban_title').append('新增自動封鎖條件');
            $('#cfp_id_title').append('CFP_ID');
            $('#pic_title').append('照片');
            $('#add_auto_ban_title').append('新增自動封鎖關鍵字 ( 永久封鎖 )');

            ban_form(uid);
        });

        $('.advance_auth_ban_user').on('click', function () {
            uid = $(this).attr('data-uid');
            advance_auth_status = $(this).attr('data-advance_auth_status');
            if(advance_auth_status == 1)
            {
                return false;
            }
            else
            {
                init_ban_modal();
                $('#banModal_title').append('封鎖 ( 驗證封鎖 )');
                $('#auto_ban_title').append('新增自動封鎖條件 ( 驗證封鎖 )');
                $('#cfp_id_title').append('CFP_ID ( 驗證封鎖 )');
                $('#pic_title').append('照片 ( 驗證封鎖 )');
                $('#add_auto_ban_title').append('新增自動封鎖關鍵字 ( 驗證封鎖 )');

                $("#clickToggleUserBlock input[name='adv_auth']").val(1);
                ban_form(uid);
            }
        });

        function init_ban_modal()
        {
            $('#banModal_title').empty();
            $('#auto_ban_title').empty();
            $('#cfp_id_title').empty();
            $('#pic_title').empty();
            $('#add_auto_ban_title').empty();

            $('#banReason').empty();
            $('#cfpid').empty();
            $('#autoban_pic_gather').empty();
            $('#table_userLogin_log').empty();
        }

        function ban_form(id)
        {
            this.uid = id;
            console.log(this.uid);
            $.ajax({
                type : 'GET',
                url : '/admin/ban_information',
                data : {uid : this.uid},
                success : function(response){

                    $('#blockUserID').val(uid);
                    
                    response.banReason.forEach(function(value){
                        $('#banReason').append('<a class="text-white btn btn-success banReason">' + value.content + '</a>');
                    });

                    response.cfp_id.forEach(function(value){
                        $('#cfpid').append('<option value=' + value.cfp_id + '>' + value.cfp_id + '</option>');
                    });

                    hstr = pic_tpl(response.meta);
                    $('#autoban_pic_gather').append(hstr);

                    response.member_pic.forEach(function(value){
                        hstr = pic_tpl(value);
                        $('#autoban_pic_gather').append(hstr);
                    });
                    
                    response.userLogin_log.forEach(function(value){
                        htmlstr = '';
                        htmlstr = htmlstr + '<tr class="loginItem" id="showloginTimeIP' + value.loginMonth + '" data-sectionName="loginTimeIP' + value.loginMonth + '">';
                        htmlstr = htmlstr + '<td>';
                        htmlstr = htmlstr + '<span>' + value.loginMonth + ' [' + value.Ip.Ip_group.length + ']' + '</span>';
                        htmlstr = htmlstr + '</td>';
                        htmlstr = htmlstr + '</tr>';
                        htmlstr = htmlstr + '<tr class="showLog" id="loginTimeIP' + value.loginMonth + '">';
                        htmlstr = htmlstr + '<td>';
                        htmlstr = htmlstr + '<select multiple class="form-control" name="ip[]">';
                        
                        for(let i = 0; i < value.Ip.Ip_group.length; i++)
                        {
                            htmlstr = htmlstr + '<option value="' + value.Ip.Ip_group[i].ip + '">' + '[' + value.Ip.Ip_group[i].loginTime + ']  ' + value.Ip.Ip_group[i].ip  + '</option>';
                        }

                        htmlstr = htmlstr + '</select>';
                        htmlstr = htmlstr + '</td>';
                        htmlstr = htmlstr + '</tr>';
                        $('#table_userLogin_log').append(htmlstr);
                    });

                    $('.showLog').hide();
                    $('.loginItem').click(function(){
                        var sectionName = $(this).attr('data-sectionName');
                        $('.showLog').hide();
                        $('#'+sectionName).show();
                    });

                    $('.banReason').click(function(){
                        $('#msg').empty();
                        $('#msg').append($(this).text());
                    });
                    
                },
                error : function(response)
                {
                    alert('取得資料失敗');
                }
            });
        }

        function pic_tpl(picture){
            html_str = '';
            if(picture??false)
            {
                html_str = html_str + '<div class="autoban_pic_unit">';
                html_str = html_str + '<input type="checkbox" id="' + picture.pic.replace('/','',) + '" name="pic[]" value="' + picture.pic + '" />';
                html_str = html_str + '<label for="' + picture.pic.replace('/','',) + '">';
                html_str = html_str + '<span>';

                if((picture.operator??false) || ((picture.deleted_at??false) && picture.deleted_at != '0000-00-00 00:00:00'))
                {
                    html_str = html_str + '已刪';
                }
                else
                {
                    html_str = html_str + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }

                html_str = html_str + '</span>';
                html_str = html_str + '<img src="' + picture.pic + '" onerror="this.src=' + 'img/filenotexist.png' + '" />';
                html_str = html_str + '</label>';
                html_str = html_str + '</div>';
            }
            return html_str;
        }
        //封鎖相關

        $('.check_and_next_page').on('click', function(){
            r = confirm('是否確定本頁檢查完畢?');
            if(r==true){
                $('#check_and_next_page').submit();
            }
        });
    </script>
@stop
