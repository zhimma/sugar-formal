@extends('new.layouts.website')
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
@section('app-content')
    <style>
        .table>tbody>tr>td{
            border-top: unset;
            border-bottom: 1px solid #ddd;
        }
    </style>
    <div class="container matop80">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <!--基本资料-->
                <div class="mintop">
                    <div class="">
                        <div class="ziliao">
                            <div class="ztitle"><span>會員專屬資訊</span>Personal Information</div>
                            <div class="xiliao_input">
                                <div class="xl_input">
{{--                                    <dt>--}}
{{--                                        <span>LINE 通知</span>--}}
{{--                                        <span><div class="select_xx03">@if($user->line_notify_token==null) 尚未綁定<button class="btn btn-success line_notify">立即綁定</button> @else 已綁定 <button class="btn btn-secondary line_notify_cancel">取消綁定</button>@endif</div></span>--}}
{{--                                    </dt>--}}
                                    <dt>
                                        <span>VIP狀態</span>
                                        <span>
                                            <div class="select_xx03">{!! $vipStatus !!}</div>
                                        </span>
                                    </dt>

                                    <dt>
                                        <span>收件夾通訊人數</span>
                                        <span>
                                            <div class="select_xx03">{{$msgMemberCount}}</div>
                                        </span>
                                    </dt>

                                    <dt>
                                        <span>收件夾總訊息數</span>
                                        <span>
                                            <div class="select_xx03">{{ $allMessage }}</div>
                                        </span>
                                    </dt>
                                    @if($isBannedStatus != '')
                                    <dt>
                                        <span>站方封鎖</span>
                                        <span>
                                            <div class="select_xx03">
                                                {!! $isBannedStatus !!}
                                            </div>
                                        </span>
                                    </dt>
                                    @endif
                                    @if($adminWarnedStatus != '' || $isWarnedStatus != '')
                                    <dt>
                                        <span>警示紀錄</span>
                                        <span>
                                            @if($adminWarnedStatus=='' && $isWarnedStatus=='')
                                                <div class="select_xx03">無</div>
                                            @else
                                                @if($adminWarnedStatus!='')
                                                <div class="select_xx03">
                                                    {!! $adminWarnedStatus !!}
                                                </div>
                                                @endif
                                                @if($isWarnedStatus!='')
                                                <div class="select_xx03">
                                                    {!! $isWarnedStatus !!}
                                                </div>
                                                @endif
                                            @endif
                                        </span>
                                    </dt>
                                    @endif

                                    <dt>
                                        <span>當月網站管理狀況</span>
                                        <span>
                                            <div class="select_xx03">
                                                <table class="table">
                                                    <tbody>
{{--                                                    <tr><td>被檢舉人數 {{$reportedCount}} 人</td></tr>--}}
                                                    <tr><td>封鎖人數 {{$bannedCount}} 人</td></tr>
                                                    <tr><td>警示人數 {{$warnedCount}} 人</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </span>
                                    </dt>

                                    <dt>
                                        <span>檢舉處理狀況</span>
                                        <span>
                                            <div class="select_xx03">
                                                @if($reportedStatus)
                                                <table class="table">
                                                    <thead>
                                                        <tr data-toggle="collapse" data-target="#collapseExample" aria-expanded="false">
                                                            <th width="5%"></th>
                                                            <th width="60%">檢舉紀錄</th>
                                                            <th width="30%">處理情形</th>
                                                            <th width="5%"><div class="collapse_word">+</div></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="collapseExample" class="collapse">
                                                        @foreach($reportedStatus as $row)
                                                            @if(isset($row['table']))
                                                                <tr>
                                                                    <td><a href="javascript:void(0)" class="reportDelete" data-rid="{{$row['rid']}}" data-table="{{$row['table']}}"><img src="/new/images/del_03.png" style="height: 14px;" alt="刪除" title="刪除"></a></td>
                                                                    <td>{!! $row['content'] !!}</td>
                                                                    <td>{!! $row['status'] !!}</td>
                                                                    <td></td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td><a href="javascript:void(0)" class="reportDelete" data-rid="{{$row['rid']}}" data-table=""><img src="/new/images/del_03.png" style="height: 14px;" alt="刪除" title="刪除"></a></td>
                                                                    <td>{!! $row['content'] !!}</td>
                                                                    <td>{!! $row['status'] !!}</td>
                                                                    <td></td>
                                                                </tr>
                                                                {{ logger('index table not available, row:' . implode("|", $row)) }}
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @else
                                                    無
                                                @endif
                                            </div>
                                        </span>
                                    </dt>


                                    <dt>
                                        <span>你收藏的會員上線</span>
                                        <span>
                                            @if($user->isVip())
                                            <div class="select_xx03">
                                                @if(!empty($myFav))
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th width="30%">會員暱稱</th>
                                                            <th width="30%">會員標題</th>
                                                            <th width="20%">最後上線時間</th>
                                                            <th>是否來看過我</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($myFav as $row)
                                                        <tr>
                                                            <td><a href="{{url('/dashboard/viewuser/' . $row->member_fav_id . '?time=' . \Carbon\Carbon::now()->timestamp)}}">{{$row->name}}</a></td>
                                                            <td>{{$row->title}}</td>
                                                            <td>{{ substr($row->last_login,0,16)}}</td>
                                                            <td>@if($row->vid !='')是，{{substr($row->visited_created_at,0,16)}}@endif</td>
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                                @else
                                                    暫無收藏的會員
                                                @endif
                                            </div>
                                                @else
                                                <div class="select_xx03">此功能僅開方給VIP，<a class="red" href="{{url('/dashboard/new_vip')}}">立即升級</a></div>
                                                @endif
                                        </span>
                                    </dt>

                                    <dt>
                                        <span>收藏你的會員上線</span>
                                        <span>
                                            @if($user->isVip())
                                            <div class="select_xx03">
                                                @if(!empty($otherFav))
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th width="30%">會員暱稱</th>
                                                            <th width="30%">會員標題</th>
                                                            <th>最後上線時間</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($otherFav as $row)

                                                        <tr>
                                                            <td><a href="{{url('/dashboard/viewuser/' . $row->member_id . '?time=' . \Carbon\Carbon::now()->timestamp)}}">{{$row->name}}</a></td>
                                                            <td>{{$row->title}}</td>
                                                            <td>{{ substr($row->last_login,0,16)}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @else
                                                    暫無收藏的會員
                                                @endif
                                            </div>
                                                @else
                                                <div class="select_xx03">此功能僅開方給VIP，<a class="red" href="{{url('/dashboard/new_vip')}}">立即升級</a></div>
                                            @endif
                                        </span>
                                    </dt>

                                </div>
                            </div>
                        </div>
{{--                        <div class="line"></div>--}}
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('javascript')

<script>

    $(".reportDelete").on('click', function() {
        var table=$(this).data("table");
        var id=$(this).data("rid");
        if($(this).data("table").length > 0){
            c4('確定要刪除嗎?');
            $(".n_left").on('click', function() {
                $.post('{{ route('report_delete') }}', {
                    // table: table,
                    id: id,
                    uid: '{{$user->id}}',
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    var obj = JSON.parse(data);
                    // alert(obj.save);
                    $("#tab04").hide();
                    if(obj.save == 'ok') {
                        ccc('紀錄已刪除');
                        $(".n_bllbut_tab_other").on('click', function() {
                            $(".blbg").hide();
                            $(".bl").hide();
                            $(".gg_tab").hide();
                            window.location.reload();
                        });
                    }
                });
            });
        }
    });

    {{--$(".line_notify").on('click', function() {--}}
    {{--    var lineClientId = '{{config('line.line_notify.client_id')}}';--}}
    {{--    var callbackUrl = '{{config('line.line_notify.callback_url')}}';--}}
    {{--    var URL = '{{config('line.line_notify.authorize_url')}}?';--}}
    {{--    URL += 'response_type=code';--}}
    {{--    URL += '&client_id='+lineClientId;--}}
    {{--    URL += '&redirect_uri='+callbackUrl;--}}
    {{--    URL += '&scope=notify';--}}
    {{--    URL += '&state={{csrf_token()}}';--}}
    {{--    window.location.href = URL;--}}
    {{--});--}}

    {{--$(".line_notify_cancel").on('click', function() {--}}
    {{--    c4('確定要解除LINE綁定通知嗎?');--}}
    {{--    var URL = '{{route('lineNotifyCancel')}}';--}}
    {{--    $(".n_left").on('click', function() {--}}
    {{--        $("#tab04").hide();--}}
    {{--        $(".blbg").hide();--}}
    {{--        window.location.href = URL;--}}
    {{--    });--}}
    {{--});--}}

    $('#collapseExample').collapse('hide');

    $('#collapseExample').collapse('hide',{
        toggle: false

    });

    $('#collapseExample').on('hidden.bs.collapse', function () {
        // do something…
        $('.collapse_word').html('+');
    });
    $('#collapseExample').on('shown.bs.collapse', function () {
        // do something…
        $('.collapse_word').html('-');
    });

    $( document ).ready(function() {
        //
        $('#collapseExample').collapse('hide');
    });



    @if (isset($errors) && $errors->count() > 0)
        @foreach ($errors->all() as $error)
            c5('{{ $error }}');
        @endforeach
    @endif

    @if (Session::has('message') && Session::get('message')=="此用戶已關閉資料。")
        ccc('{{Session::get('message')}}');
    @elseif(Session::has('message'))
        c5('{{Session::get('message')}}');
    @endif

</script>

@stop
