@extends('admin.main')
@section('app-content')
<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th {
        vertical-align: middle;
        overflow-wrap: break-word;
        word-wrap: break-word;
        -ms-word-break: break-all;
        word-break: break-all;
        word-break: break-word;
        -ms-hyphens: auto;
        -moz-hyphens: auto;
        -webkit-hyphens: auto;
        hyphens: auto;
    }

    .table>tbody>tr>th {
        text-align: center;
    }
</style>
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
/>
<body style="padding: 15px;">
    <h1>匿名聊天室</h1>
    @if (isset($errors))
    @if ($errors->count() > 0)
    @else
    <h3 style="text-align: left;">搜尋</h3>
    <form action="{{ route('users/searchAnonymousChatPage') }}" id='message' method='get'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered" style="width: 50%;">
            <tr>
                <th width="15%">
                    <label for="msg">訊息內容</label>
                </th>
                <td>
                    <input type="text" name="msg" value="@if(isset($_REQUEST['msg'])) {{ $_REQUEST['msg'] }}@endif" class="form-control" id="msg">
                </td>
            </tr>
            <tr>
                <th>開始時間</th>
                <td>
                    <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_REQUEST['date_start'])){{ $_REQUEST['date_start'] }}@endif" class="form-control">
                </td>
            <tr>
                <th>結束時間</th>
                <td>
                    <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_REQUEST['date_end'])){{ $_REQUEST['date_end'] }}@endif" class="form-control">
                </td>
            </tr>
            <tr>
                <th>預設時間選項</th>
                <td>
                    <a class="text-white btn btn-success today">今天</a>
                    <a class="text-white btn btn-success last3days">最近3天</a>
                    <a class="text-white btn btn-success last10days">最近10天</a>
                    <a class="text-white btn btn-success last30days">最近30天</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input class='text-white btn btn-primary submit' type="submit" name="searchAnonymousChatPage" value="搜尋">
                    或 <input id="reported_meg"  class="btn btn-info" type="submit" name="searchAnonymousChatReport" value="檢視被檢舉訊息">
                </td>
{{--                href="{{ route('users/searchAnonymousChatReport') }}"--}}
            </tr>
            <tr>
                <td colspan="2">
                    <input type="button" class='btn btn-default' onclick="location.href='anonymousChat';" value="重設" />
                </td>
            </tr>
        </table>
    </form>
    @if(isset($results))
        <h3 style="text-align: left;">搜尋結果</h3>
        <table class="table-hover table table-bordered">

            <thead>
                <tr>
                    <td width="8%">發送者</td>
                    <td width="5%">匿名</td>
{{--                    <td>性別</td>--}}
                    <td width="20%">訊息內容</td>
                    <td width="20%">圖片</td>
                    <td width="12%">發訊時間</td>
                    <td width="15%">狀態</td>
                    <td width="20%">管理</td>
                </tr>
            </thead>

            <tbody>
            @if(count($results)>0)
            @foreach ($results as $row)
                <tr>
                    @php
                        $isWarned = \App\Models\SimpleTables\warned_users::where('member_id', $row->user_id)
                                ->where('expire_date', null)->orWhere('expire_date','>',\Carbon\Carbon::now() )
                                ->where('member_id', $row->user_id)
                                ->orderBy('created_at','desc')->first();
                    @endphp
                    <td style="@if(\App\Models\User::isBanned($row->user_id))background-color:#FFFF00;@endif @if($isWarned)background-color:#B0FFB1;@endif">
                        @if($row->userID)
                        <a href="{{ route('users/advInfo', $row->user_id) }}" style="color:{{($row->engroup==1)?'blue':'red'}};" target='_blank' >{{$row->name}}</a>
                        @else
                            <font style="font-size: 10pt;">會員資料已刪除</font>
                        @endif
                    </td>
                    <td>{{$row->anonymous}}</td>
{{--                    <td>{{($row->engroup==1)?'男':'女'}}</td>--}}
                    <td>{{$row->content}}</td>
                    <td>
                        @if(!is_null(json_decode($row->pic,true)))
                            @foreach(json_decode($row->pic,true) as $key => $pic)
                                @if(isset($pic['file_path']))
                                    <a href="{{$pic['file_path'] }}" data-fancybox="gallery_{{$row->id}}" target="_blank">
                                        <img src="{{ $pic['file_path'] }}" style="object-fit: cover; weight:50px; height: 50px;">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{$row->created_at}}</td>
                    <td>@if($row->deleted_at)<span class="badge badge-danger">刪：{{$row->deleted_at}}</span>@endif</td>
                    <td>
                        @if(!$row->deleted_at)
                            <input type="button" class='btn btn-danger' onclick="deleteRow({{$row->id}})" value="刪除" />
                        @endif
                        <div class="btn-group">
                        @if($row->forbid_userID=='' || ($row->forbid_userExpireDate && \Carbon\Carbon::parse($row->forbid_userExpireDate)->lt(now()) ) )
                            <button type="button" class='btn btn-dark' data-toggle="modal" data-target="#forbid_modal" data-id="{{$row->user_id}}" data-name="{{$row->name}}" data-anonymous="{{$row->anonymous}}">禁止進入</button>
                        @else
                            <button type="button" class='btn btn-outline-dark userBlockRemove' data-id="{{$row->user_id}}" data-name="{{$row->name}}" data-block_type="anonymous_chat_forbid">解除禁止進入</button>
                        @endif
                        @if($row->warned_userID=='' || ($row->warned_userExpireDate && \Carbon\Carbon::parse($row->warned_userExpireDate)->lt(now()) ) )
                            <button type="button" class='btn btn-warning' data-toggle="modal" data-target="#warned_modal" data-id="{{$row->user_id}}" data-name="{{$row->name}}">警示</button>
                        @else
                            <button type="button" class='btn btn-outline-warning unwarned_user' data-id="{{$row->user_id}}" data-name="{{$row->name}}" data-block_type="anonymous_chat_warned">解除警示</button>
                        @endif

                        @if($row->banned_userID=='' || ($row->banned_userExpireDate && \Carbon\Carbon::parse($row->banned_userExpireDate)->lt(now()) ))
                            <button type="button" class='btn btn-danger' data-toggle="modal" data-target="#banned_modal" data-id="{{$row->user_id}}" data-name="{{$row->name}}">封鎖</button>
                        @else
                            <button type="button" class='btn btn-outline-danger unblock_user' data-id="{{$row->user_id}}" data-name="{{$row->name}}" data-block_type="anonymous_chat_ban">解除封鎖</button>
                        @endif

                        </div>
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="7">沒有資料</td>
                </tr>
            @endif
            </tbody>

        </table>
        {{ $results->appends(request()->input())->links() }}
{{--        {{ $results->links() }}--}}
    @endif

    @if(isset($resultsReport))
        <h3 style="text-align: left;">搜尋結果</h3>
        <table class="table-hover table table-bordered">

            <thead>
            <tr>
                <td width="8%">發送者</td>
                <td width="5%">匿名</td>
{{--                <td>性別</td>--}}
                <td width="12%">訊息內容</td>
                <td width="12%">圖片</td>
                <td width="10%">發訊時間</td>
                <td width="8%">檢舉人</td>
                <td width="12%">檢舉內容</td>
                <td width="10%">檢舉時間</td>
                <td width="11%">狀態</td>
                <td width="12%">管理</td>
            </tr>
            </thead>

            <tbody>
{{--            @php--}}
{{--                dd($resultsReport);--}}
{{--            @endphp--}}
            @if(count($resultsReport)>0)

            @foreach ($resultsReport as $row)
                <tr>
                    @php
                        $isWarned = \App\Models\SimpleTables\warned_users::where('member_id', $row->user_id)
                                ->where('expire_date', null)->orWhere('expire_date','>',\Carbon\Carbon::now() )
                                ->where('member_id', $row->user_id)
                                ->orderBy('created_at','desc')->first();
                        $user = \App\Models\User::findById($row->user_id);
                    @endphp
                    <td style="@if(\App\Models\User::isBanned($row->user_id))background-color:#FFFF00;@endif @if($isWarned)background-color:#B0FFB1;@endif">
                        @if($row->userID)
                        <a href="{{ route('users/advInfo', $row->user_id) }}" style="color:{{($row->engroup==1)?'blue':'red'}};" target='_blank' >{{$row->name}}</a>
                        @else
                            <font style="font-size: 10pt;">會員資料已刪除</font>
                        @endif
                    </td>
                    <td>{{$row->anonymous}}</td>
{{--                    <td>{{($row->engroup==1)?'男':'女'}}</td>--}}
                    <td>{{$row->content}}</td>
                    <td>
                        @if(!is_null(json_decode($row->pic,true)))
                            @foreach(json_decode($row->pic,true) as $key => $pic)
                                @if(isset($pic['file_path']))
                                    <a href="{{$pic['file_path'] }}" data-fancybox="gallery_{{$row->id}}" target="_blank">
                                        <img src="{{ $pic['file_path'] }}" style="object-fit: cover; weight:50px; height: 50px;">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{$row->created_at}}</td>
                    <td>
                        <a href="{{ route('users/advInfo', $row->report_user) }}" style="color:{{($row->report_engroup==1)?'blue':'red'}};" target='_blank' >{{$row->report_name}}</a>
                    </td>
                    <td>{{$row->report_content}}</td>
                    <td>{{$row->report_time}}</td>
                    <td>
                        @if($row->deleted_at)
                            <span class="badge badge-danger">刪：{{$row->deleted_at}}</span>
                        @endif
                        @if($row->report_deleted_at)
                            <span class="badge badge-warning">刪：{{$row->report_deleted_at}}</span>
                        @endif
                        @if( ($row->reported_num>=3 && !$user->isVVIP()) || ($row->reported_num >=5 && $user->isVVIP()) )
                            @php
                                $checkReport = \App\Models\AnonymousChatReport::select('user_id', 'created_at')->where('reported_user_id', $row->user_id)->groupBy('user_id')->orderBy('created_at', 'desc')->first();
                            @endphp
                            @if(isset($checkReport) && !empty($checkReport->created_at) && \Carbon\Carbon::parse($checkReport->created_at)->diffInDays(\Carbon\Carbon::now())<3)
                                <span class="badge badge-warning">禁言中</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!$row->deleted_at)
                            <input type="button" class='btn btn-danger' onclick="deleteRow({{$row->id}})" value="刪除" />
                        @endif
                        @if(!$row->report_deleted_at)
                            <input type="button" class='btn btn-warning' onclick="deleteReport({{$row->report_id}})" value="刪除檢舉" />
                        @endif
                        @if( ($row->reported_num>=3 && !$user->isVVIP()) || ($row->reported_num >=5 && $user->isVVIP()) )
                            @if(isset($checkReport) && !empty($checkReport->created_at) && \Carbon\Carbon::parse($checkReport->created_at)->diffInDays(\Carbon\Carbon::now())<3)
                                    <input type="button" class='btn btn-warning' onclick="deleteReportAll({{$row->user_id}})" value="解除禁言" />
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                <td colspan="10">沒有資料</td>
                </tr>
            @endif
            </tbody>

        </table>
        {{ $resultsReport->appends(request()->input())->links() }}
{{--        {{ $resultsReport->links() }}--}}
    @endif

    @endif
    <div class="modal fade" id="banned_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h5 class="modal-title" id="bannedModalLabel">封鎖</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users/userBlock') }}" method="POST" id="clickToggleUserBanned">
                    {!! csrf_field() !!}
                    <input type="hidden" value="" name="user_id" id="bannedUserID">
                    <input type="hidden" value="" name="name" id="bannedUserName">
                    <input type="hidden" name="block_type" value="anonymous_chat_ban">
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
                        @if(isset($results))
                            @foreach($anonymousChatBanReason as $a)
                                <a class="text-white btn btn-success blockReason" data-target="bannedReason">{{ $a->content }}</a>
                            @endforeach
                        @endif
                        <br><br>
                        <textarea class="form-control m-reason bannedReason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                        <label style="margin:10px 0px;">
                            <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                            <sapn style="vertical-align:middle;">加入常用封鎖原因</sapn>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="warned_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warnedModalLabel">站方警示</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users/userBlock') }}" method="POST" id="clickToggleUserWarned">
                    {!! csrf_field() !!}
                    <input type="hidden" value="" name="user_id" id="warnedUserID">
                    <input type="hidden" value="" name="name" id="warnedUserName">
                    <input type="hidden" name="block_type" value="anonymous_chat_warned">
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
                        @if(isset($results))
                            @foreach($anonymousChatWarnedReason as $a)
                                <a class="text-white btn btn-success blockReason" data-target="warnedReason">{{ $a->content }}</a>
                            @endforeach
                        @endif
                        <textarea class="form-control m-reason warnedReason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                        <label style="margin:10px 0px;">
                            <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                            <sapn style="vertical-align:middle;">加入常用原因</sapn>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="forbid_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forbidModalLabel">禁止進入</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users/userBlock') }}" method="POST" id="clickToggleUserForbid">
                    {!! csrf_field() !!}
                    <input type="hidden" value="" name="user_id" id="forbidUserID">
                    <input type="hidden" value="" name="name" id="forbidUserName">
                    <input type="hidden" value="" name="anonymous" id="forbidUserAnonymous">
                    <input type="hidden" name="block_type" value="anonymous_chat_forbid">
                    <div class="modal-body">
                        禁止進入時間
                        <select name="days" class="days">
                            <option value="3">三天</option>
                            <option value="7">七天</option>
                            <option value="15">十五天</option>
                            <option value="30">三十天</option>
                            <option value="X" selected>永久</option>
                        </select>
                        <hr>
                        禁止進入原因
                        @if(isset($results))
                            @foreach($anonymousChatForbidReason as $a)
                                <a class="text-white btn btn-success blockReason" data-target="forbidReason">{{ $a->content }}</a>
                            @endforeach
                        @endif
                        <textarea class="form-control m-reason forbidReason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                        <label style="margin:10px 0px;">
                            <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                            <sapn style="vertical-align:middle;">加入常用原因</sapn>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</body>

<script>
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date();
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
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth()  + 1 ) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last10days').click(
            function() {
                var days = 10; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1 ) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last30days').click(
            function() {
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                set_end_date();
            });
        // $('.submit').click(
        //     function() {
        //         $('#msg2').val($('#msg').val());
        //         $('#message').submit();
        //     });
        $('.delete-btn').on('click', function(e) {
            if (!confirm('確定要刪除選取的訊息?')) {
                e.preventDefault();
            }
        });


    });

    function deleteRow(id) {
        if (!confirm('確定要刪除選取的訊息?')) {
            e.preventDefault();
        }
        // alert('pass');
        $.post('{{ route('users/deleteAnonymousChatRow') }}', {
            id: id,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            location.reload();
        });
    }

    function deleteReport(report_id) {
        if (!confirm('確定要刪除該筆檢舉紀錄?')) {
            e.preventDefault();
        }
        // alert('pass');
        $.post('{{ route('users/deleteAnonymousChatReportRow') }}', {
            report_id: report_id,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            location.reload();
        });
    }

    function deleteReportAll(user_id) {
        if (!confirm('確定要刪除此人所有檢舉紀錄?')) {
            e.preventDefault();
        }
        // alert('pass');
        $.post('{{ route('users/deleteAnonymousChatReportAll') }}', {
            user_id: user_id,
            _token: '{{ csrf_token() }}'
        }, function (data) {
            location.reload();
        });
    }

    function selectAll() {
        $('.boxes').each(
            function() {
                if ($(this).is(':checked')) {
                    $(this).prop("checked", false);
                } else {
                    $(this).selected();
                }
            });

    }

    function set_end_date() {
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }

    function str_pad(n) {
        return String("00" + n).slice(-2);
    }

    $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
        if (typeof $(this).data('id') !== 'undefined') {
            $("#bannedModalLabel").html('封鎖 '+ $(this).data('name'));
            $("#bannedUserID").val($(this).data('id'));
            $("#bannedUserName").val($(this).data('name'));
            $("#warnedModalLabel").html('站方警示 '+ $(this).data('name'));
            $("#warnedUserID").val($(this).data('id'));
            $("#warnedUserName").val($(this).data('name'));
            $("#forbidModalLabel").html('禁止 '+ $(this).data('name') + ' 進入');
            $("#forbidUserID").val($(this).data('id'));
            $("#forbidUserName").val($(this).data('name'));
            $("#forbidUserAnonymous").val($(this).data('anonymous'));
        }
    });

    $(".blockReason").each( function(){
        $(this).bind("click" , function(){
            var id = $("a").index(this);
            var clickval = $("a").eq(id).text();
            var target_class = $(this).data('target');
            $('.' + target_class).val(clickval);
        });
    });

    $(".userBlockRemove").click(function(){
        var data = $(this).data();
        var block_type = $(this).data('block_type');
        let msg1, msg2, name;
        if(block_type=='anonymous_chat_forbid'){
            name = $(this).data('name');
            msg1 = '確定要解除 '+name+' 的禁止狀態?';
            msg2 = '已解除 '+name+' 禁止狀態';
        }

        if(confirm(msg1)){
            $.ajax({
                type: 'POST',
                url: "/admin/users/userBlockRemove?{{csrf_token()}}={{now()->timestamp}}",
                data:{
                    _token: '{{csrf_token()}}',
                    data: data,
                },
                dataType:"json",
                success: function(res){
                    alert(msg2);
                    location.reload();
                }});
        }
        else{
            return false;
        }
    });

    $(".unblock_user").click(function(){
        var data = $(this).data();
        if(confirm('確定解除封鎖此會員?')){
            $.ajax({
                type: 'POST',
                url: "/admin/users/unblock_user?{{csrf_token()}}={{now()->timestamp}}",
                data:{
                    _token: '{{csrf_token()}}',
                    data: data,
                },
                dataType:"json",
                success: function(res){
                    alert('解除封鎖成功');
                    location.reload();
                }});
        }
        else{
            return false;
        }
    });

    $(".unwarned_user").click(function(){
        var data = $(this).data();
        if(confirm('確定解除此會員站方警示?')){
            $.ajax({
                type: 'POST',
                url: "/admin/users/unwarned_user?{{csrf_token()}}={{now()->timestamp}}",
                data:{
                    _token: '{{csrf_token()}}',
                    data: data,
                },
                dataType:"json",
                success: function(res){
                    alert('已解除站方警示');
                    location.reload();
                }});
        }
        else{
            return false;
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
@stop
</html>