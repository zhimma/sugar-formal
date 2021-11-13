@extends('admin.main')
@section('app-content')
    <script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            vertical-align: middle;
        }

        .table>tbody>tr>th {
            text-align: center;
        }

    </style>

    <body style="padding: 15px;">
        <h1>會員照片管理-以圖搜圖</h1>

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

            @if (isset($users))

                <table class="table-hover table table-bordered">
                    <tr>
                        <td class="col-3">會員資料</td>
                        <td class="col-3">照片</td>
                        <td class="col-6">以圖找圖</td>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td rowspan="{{ $user->pic->count() + 1 }}" style="{{ $user->isBanned($user->id) ? 'background-color: #FFFF00;' : '' }}">
                                <p>
                                    <span>會員名稱: <a href="/admin/users/advInfo/editPic_sendMsg/{{ $user->id }}" target="_blank"><span class="{{ $user->engroup == 2 ? 'text-danger' : 'text-primary' }}">{{ $user->name }}</span></a></span><br>
                                    <span>電子郵件: {{ str_replace(strchr($user->email,'@'), '', $user->email) }}</span><br>
                                    <span>會員標題: {{ $user->title }}</span><br>
                                    <span>關於我: {{ $user->meta_()->about }}</span><br>
                                    <span>期待的約會模式: {{ $user->meta_()->style }}</span><br>
                                    <span>上線時間: {{ $user->last_login }}</span><br>
                                    <span>更新時間: {{ $user->last_update }}</span><br>
                                </p>
                                <p>
                                    @if ($user->isBanned($user->id))
                                        <button class="btn btn-sm btn-success" type="button" onclick="unblockModal(this)" data-toggle="modal" data-target="#unblockModal" data-uid="{{ $user->id }}" data-reason="{{ $user->banned->reason }}" data-expire="{{ $user->banned->expire_date }}">解除封鎖</button>
                                    @else
                                        <button class="btn btn-sm btn-danger" type="button" onclick="blockModal(this)" data-toggle="modal" data-target="#blockModal" data-uid="{{ $user->id }}">封鎖</button>
                                    @endif
                                    <form method="POST" action="genderToggler" class="user_profile">{!! csrf_field() !!}
                                        <input type="hidden" name='user_id' value="{{ $user->id }}">
                                        <input type="hidden" name='gender_now' value="{{ $user->engroup }}">
                                        <input type="hidden" name='page' value="userPicturesSimilar">
                                        <button type="submit" class="btn btn-sm btn-warning">變更性別</button>
                                    </form>
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
                                        <p><img src="{{ url($user->meta->pic) }}" width="120px"><br><span>新增時間: {{ $user->meta->created_at }}</span></p>
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
                                        <p>無紀錄</p>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @if ($user->pic->count() != 0)
                            @foreach ($user->pic as $pic)
                                <tr>
                                    <td>
                                        <p><img src="{{ url($pic->pic) }}" width="120px"><br><span>新增時間: {{ $pic->created_at }}</span>
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
                                            <p>無紀錄</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </table>

                {!! $users->appends(request()->input())->links('pagination::sg-pages') !!}

                <!-- blockModal -->
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

                <!-- unblockModal -->
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

            @endif

        @else

            <p>沒有本頁面權限</p>
        @endif
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
@stop
