@extends('admin.main')
@section('app-content')

<body style="padding: 15px;">
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            vertical-align: middle;
        }

        .table>tbody>tr>th {
            text-align: center;
        }

        .range-wrap {
            position: relative;
            margin: 0 auto 3rem;
        }
        .range {
            width: 100%;
        }
        .bubble {
            background: black;
            color: white;
            padding: 4px 12px;
            position: absolute;
            border-radius: 4px;
            left: 50%;
            transform: translate(-50%, 20px);
        }
        .bubble::after {
            content: "";
            position: absolute;
            width: 2px;
            height: 2px;
            background: black;
            top: -1px;
            left: 50%;
        }
        
        #pic_apply_modal .form-control {display:initial;}
        
        #apply_time_start { width:50%; }
        #apply_datepicker {width:65%;}
        #apply_datepicker {margin-bottom:2rem;}
        #pic_apply_modal .apply-dialog-option-block {overflow:auto;}
        #pic_apply_modal .apply-dialog-option-block span:last-child {float:right;width:75%;}
        #pic_apply_modal .apply-dialog-option-block span:first-child {float:left;white-space:nowrap;font-weight:bolder;}
        #pic_apply_modal .apply-dialog-option-block span label span {white-space: nowrap;}
        #pic_apply_modal .apply-dialog-option-block span label {margin-right:5%;}
        #pic_apply_modal .apply-dialog-block-height {height:3rem;}
        #pic_apply_modal .apply-dialog-option-block.apply-dialog-datepicker-block span:last-child {width:90%;}
    </style>
    <script>
        function pic_apply_toggle_act() {
            console.log($('#member_search input[type=hidden],#member_search input:checked,#member_search input[type=text]').attr('value'));
            if(!$('#apply_dialog_form input[name=_token]').length) {
                $('#member_search input').each(function(){
                    var nowElt = $(this);
                    var nowType = nowElt.attr('type');
                    if((nowType=='checkbox' || nowType=='radio') && nowElt.attr('checked')===undefined) return;
                    if(['submit','reset','button','image'].indexOf(nowType)>=0) return;
                    $('#apply_dialog_form').prepend('<input type="hidden" name="'+nowElt.attr('name')+'" value="'+nowElt.val()+'" />');
                });
            }
        }
    </script>
    <h1>照片會員列表查詢</h1>

    <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('searchPicMemberList') }}" id='member_search' method='post'>
        {!! csrf_field() !!}
        <table class="table-hover table table-bordered">

            <tr>
                <th>會員性別</th>
                <td>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" id="inlineRadio1" value="1" @if(($request->gender??null)==1 ) checked="checked" @endif style="margin-left: unset;">
                        <label class="form-check-label" for="inlineRadio1">男</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" id="inlineRadio2" value="2"  @if(($request->gender??null)===null || ($request->gender??null)==2 ) checked="checked" @endif style="margin-left: unset;">
                        <label class="form-check-label" for="inlineRadio2">女</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" id="inlineRadio3" value="0"  @if(($request->gender??null)==='0' ) checked="checked" @endif style="margin-left: unset;">
                        <label class="form-check-label" for="inlineRadio3">Both</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>開始日期</th>
                <td>
                    <input type='date' autocomplete="off" id="datepicker_1" name="date_start" min='2010-01-01' value="{{$request->date_start??''}}" class="form-control" required>
                </td>
            <tr>
                <th>結束日期</th>
                <td>
                    <input type='date' autocomplete="off"  id="datepicker_2" name="date_end"  min='2010-01-01' value="{{$request->date_end??''}}" class="form-control" required>
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
                <th>排序方式</th>
                <td>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="time" id="inlineRadio4" value="created_at" @if(($request->time??null)=='created_at' ) checked="checked" @endif style="margin-left: unset;" />
                        <label class="form-check-label" for="inlineRadio4">註冊時間</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="time" id="inlineRadio5" value="last_login" @if(($request->time??null)=='last_login' ) checked="checked" @endif style="margin-left: unset;" />
                        <label class="form-check-label" for="inlineRadio5">上線時間</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>查詢使用者數</th>
                <td>
                    <div class="range-wrap">
                        <output class="bubble"></output>
                        <input type="range" name="users_counts" min="30" max="1000" value="{{$request->users_counts??30}}" class="form-control-range range" id="myRange">
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" class='text-white btn btn-primary submit' value="查詢">
                    <input type="reset" class='text-white btn btn-primary reset' value="Reset" onclick="if(document.getElementById('rs_num_zone')!=null) location.href='{{route('users/picMemberList')}}?reset=1';">
                </td>
            </tr>
        </table>
    </form>
    </div>
    @if(isset($results))
        <span id="rs_num_zone">共{{count($results)}}個會員</span>
    @endif
    @if(isset($results) && count($results)>0)
        <a class="btn btn-primary" title="照片全部送檢" id="pic_apply_toggle" href="#" data-toggle="modal" data-target="#pic_apply_modal" data-vip_pass="0" onclick="pic_apply_toggle_act();">照片全部送檢</a>
        <table class="table-hover table table-bordered" style="word-break: break-word;">
            <tr>
                <th width="14%">email</th>
                <th width="5%">暱稱</th>
                <th width="12%">一句話形容自己</th>
                <th width="3%">性別</th>
                <th width="8%">註冊時間</th>
                <th width="8%">上線時間</th>
                <th width="5%">上傳<br>照片數</th>
                <th width="10%">已做<br>以圖搜圖</th>
                <th width="5%">已做<br>照片比對</th>               
            </tr>
            @foreach($results as $row)
                <tr>
                    <td><a href="{{ route('users/advInfo', $row['id']) }}" target='_blank' >{{$row['email']}}</a></td>
                    <td @if($row->banned) style="background-color:#FFFF00" @endif>
                        <a href="{{ route('users/advInfo', $row['id']) }}" target='_blank'>
                            <p @if($row['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
                                {{$row['name']}}
                                @if($row->isVip())
                                    @if($row->getVipDiamond()=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $row->getVipDiamond(); $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @if($row->getTipCountChangeGood())
                                    @for($i = 0; $i < $row->getTipCountChangeGood(); $i++)
                                        👍
                                    @endfor
                                @else
                                    {{ logger('reportedUsers, line 80 tipcount does not exists, user id: ' . $row['id']) }}
                                @endif
                                @if($row->banned)
                                    @if($row->banned->expire_date??null)
                                        @if(round((strtotime($row->banned->expire_date) - getdate()[0])/3600/24)>0)
                                            {{ round((strtotime($row->banned->expire_date) - getdate()[0])/3600/24 ) }}天
                                        @else
                                            此會員登入後將自動解除封鎖
                                        @endif
                                    @elseif($row->banned->type??null)
                                        (隱性)
                                    @else
                                        (永久)
                                    @endif
                                @endif
                                @if($row->getWarnedIconData()['isAdminWarned']==1 OR $row->getWarnedIconData()['isWarned']==1)
                                    <img src="/img/warned_red.png" style="height: 16px;width: 16px;">
                                @endif
                                @if($row->getWarnedIconData()['isWarned']==0 AND $row->getWarnedIconData()['WarnedScore']>10 AND $row->getWarnedIconData()['auth_status']==1)
                                    <img src="/img/warned_black.png" style="height: 16px;width: 16px;">
                                @endif
                            </p>
                        </a>
                    </td>
                    <td>{{$row['title']}}</td>
                    <td>@if($row['engroup']==2)女@else男@endif</td>
                    <td>{{substr($row['created_at'],0,10)}}</td>
                    <td>{{substr($row['last_login'],0,10)}}</td>
                    <td>
                        {{(!!$row->meta->pic)+count($row->pic_withTrashed??[])+count($row->avatar_deleted??[])}}
                    </td>
                    <td>
                        {{$similarity->countSearchedByEntrysArr([collect([$row->meta]),$row->pic_withTrashed,$row->avatar_deleted])}}
                    </td>
                    <td>
                        {{$comparison->countComparedByEntrysArr([collect([$row->meta]),$row->pic_withTrashed,$row->avatar_deleted])}}
                    </td>

                   
                </tr>
            @endforeach
        </table>
<div class="modal fade" id="pic_apply_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 60%;">
		<div class="modal-content" >
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">照片送檢 </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('applyPicMemberList')}}" method="POST" id="apply_dialog_form">  
                <div class="modal-body">
                        共{{count($results)}}個會員
                        <hr>
                        <div class="apply-dialog-option-block">
                            <span>照片送檢類型</span>
                            <span>
                                <label><input type="checkbox" name="apply_type[]" value="s" @if(!($request->apply_type??null) || in_array('s',$request->apply_type??[])) checked @endif /><span>以圖找圖</span></label>
                                <label><input type="checkbox" name="apply_type[]" value="c" @if(!($request->apply_type??null) || in_array('c',$request->apply_type??[])) checked @endif  /><span>站內搜圖</span></label>
                            </span>
                        </div>
                        <hr>
                        <div class="apply-dialog-block-height">
                        開始佇列時間
                        </div>
                        <div class="apply-dialog-datepicker-block apply-dialog-option-block">
                            <span>日期</span>
                            <span>
                                <input type='date' autocomplete="off" id="apply_datepicker" name="apply_date_start" data-date-format='yyyy-mm-dd' value="{{$request->apply_date_start??date('Y-m-d')}}" min="{{date('Y-m-d')}}" class="form-control">
                            </span>
                        </div>
                        <div class="apply-dialog-datepicker-block apply-dialog-option-block">
                            <span>時間</span>
                            <span>                        
                                <input type="time" name="apply_time_start" id="apply_time_start"  value="{{$request->apply_time_start??$default_apply_time}}" >
                            </span>
                        </div>                    
                </div>
                <div class="modal-footer">
                	<button type="submit" class='btn btn-outline-success' id="apply-dialog-submit"> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
        
    @else
        查無資料
    @endif
</body>
<script>

    const allRanges = document.querySelectorAll(".range-wrap");
    allRanges.forEach(wrap => {
        const range = wrap.querySelector(".range");
        const bubble = wrap.querySelector(".bubble");

        range.addEventListener("input", () => {
            setBubble(range, bubble);
        });
        setBubble(range, bubble);
    });

    function setBubble(range, bubble) {
        const val = range.value;
        const min = range.min ? range.min : 0;
        const max = range.max ? range.max : 100;
        const newVal = Number(((val - min) * 100) / (max - min));
        bubble.innerHTML = val;

        // Sorta magic numbers based on size of the native UI thumb
        bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px))`;
    }

    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let today = new Date(year, month, day);
    let minus_date = new Date(today);
    jQuery(document).ready(function() {    
        $('#apply-dialog-submit').click(function(){
            var now_elt = $(this);
            now_elt.removeClass('btn-outline-success')
                .addClass('btn-dark').addClass('disabled')
                ;
        });
        $('#apply_dialog_form').submit(function(){
            $('#apply-dialog-submit').attr('disabled','disabled');
        });
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
                $('#datepicker_1').val(start_date.getFullYear() + '-' + str_pad(parseInt(start_date.getMonth()+1)) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + str_pad(parseInt(start_date.getMonth()+1)) + '-' + start_date.getDate());
                set_end_date();
            });

        $('.delete-btn').on('click', function(e) {
            if (!confirm('確定要刪除選取的訊息?')) {
                e.preventDefault();
            }
        });
        $('#reported_meg').click(function(event) {
            let date_start = $('#datepicker_1').val() ? $('#datepicker_1').val() : '1970-01-01';
            let date_end = $('#datepicker_2').val() ? $('#datepicker_2').val() : $.datepicker.formatDate('yy-mm-dd', new Date());
            let href = $(this).attr('href');
            $(this).attr('href', href + "?date_start=" + date_start + "&date_end=" + date_end);
        });
    });

    

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

    function toggleBanned(id) {
        let url = "{{ url("") }}";
        window.open(url + '/admin/users/toggleUserBlock/' + id);
        history.go(0);
    }
    

</script>
@stop