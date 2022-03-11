@extends('admin.main')
@section('app-content')
<script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
</style>
<body style="padding: 15px;">
<h1>會員檢查 step 1</h1>
<form action="@if(Auth::user()->can('readonly')){{ route('users/pictures/readOnly') }}@else{{ route('users/picturesSimpleSearch') }}@endif"
      @if(Auth::user()->can('readonly')) method="POST" @else method="get" @endif>
    {!! csrf_field() !!}
    <table class="table-hover table table-bordered" style="width: 50%;">
        <tr>
            <th>開始時間</th>
            <td>
                <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_start'])){{$_GET['date_start']}}@else{{ old('date_start') }}@endif" class="form-control">
            </td>
        <tr>
            <th>結束時間</th>
            <td>
                <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_end'])){{$_GET['date_end']}}@else{{ old('date_end') }}@endif" class="form-control">
            </td>
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
                <input type="radio" name="en_group" value="1" @if(isset($_GET['en_group']) && $_GET['en_group']==1) checked @endif>男
                <input type="radio" name="en_group" value="2" @if(isset($_GET['en_group']) && $_GET['en_group']==2) checked @endif>女
            </td>
        </tr>
        <tr>
            <th>地區</th>
            <td class="twzipcode">
                <div class="twzip" id="city" data-role="county" data-name="city" data-value="@if(isset($_GET['city'])){{$_GET['city']}}@endif"></div>
                <div class="twzip" id="area" data-role="district" data-name="area" data-value="@if(isset($_GET['area'])){{$_GET['area']}}@endif"></div>

            </td>
        </tr>
        <tr>
            <th>排序方式</th>
            <td>
                {{--
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="order_by" value="updated_at" @if((isset($_GET['order_by']) && $_GET['order_by']=='updated_at')) checked @endif style="margin-left: unset;">
                    <label class="form-check-label" for="inlineRadio4">更新時間</label>
                </div>
                --}}
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="order_by" value="last_login" @if((isset($_GET['order_by']) && $_GET['order_by']=='last_login') ) checked @endif style="margin-left: unset;">
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

@if(isset($data))
    <div>
        <table class="table-hover table table-bordered">
            <tr>
                <td width="12%">會員名稱</td>
                <td width="12%">照片</td>
                <td width="12%">email 前半段</td>
                <td width="12%">標題(一句話形容自己）</td>
                <td width="14%">關於我</td>
                <td width="12%">期待的約會模式</td>
                <td width="12%">上線時間</td>
                <td width="5%">操作</td>
            </tr>
            @foreach ($data as $key =>$d)
                <tr>
                    <td>
                        @if (Auth::user()->can('readonly'))
                        <a href="{{ route('users/pictures/editPic_sendMsg/readOnly', $d->id) }}">
                        @else
                        <a href="advInfo/editPic_sendMsg/{{ $d->id }}">
                        @endif
                            <p @if($d->engroup== '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $d->name }}
                                @if($account[$key]['vip'])
                                    @if($account[$key]['vip']=='diamond_black')
                                        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
                                    @else
                                        @for($z = 0; $z < $account[$key]['vip']; $z++)
                                            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
                                        @endfor
                                    @endif
                                @endif
                                @for($i = 0; $i < $account[$key]['tipcount']; $i++)
                                    👍
                                @endfor
                            </p>
                        </a>
                    </td>
                    <td>
                        <img src="{{ url($d->user_meta->pic) }}" width="150px">
                        @foreach($d->pic_orderByDecs as $pic)
                            <img src="{{ url($pic->pic) }}" width="150px">
                        @endforeach
                    </td>
                    <td>{{ strstr($d->email, '@', true) }}</td>
                    <td>{{ $d->title }}</td>
                    <td>{{ $d->user_meta->about }}</td>
                    <td>{{ $d->user_meta->style }}</td>
                    <td>{{ $d->last_login }}</td>
                    <td>
                        <input class="reason" placeholder="請輸入可疑原因">
                        <button class="btn_sid btn btn-primary" data-sid='' data-uid="{{$d->id}}">列為可疑</button>
                    </td>
                </tr>
            @endforeach
        </table>
        {!! $data->appends(request()->input())->links('pagination::sg-pages') !!}
    </div>
@endif
<form id="sid_toggle" action="{{ route('users/suspicious_user_toggle') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="sid" id="sid" value="">
    <input type="hidden" name="uid" id="uid" value="">
    <input type="hidden" name="reason" id="reason" value="">
</form>
</body>

<script>
    $('.twzipcode').twzipcode({
        'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode']
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
            function () {
                $('#datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                $('.datepicker_1').val(year + '-' + str_pad(month) + '-' + str_pad(day));
                set_end_date();
            });
        $('.last3days').click(
            function () {
                var days = 3; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last10days').click(
            function () {
                var days = 10; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last15days').click(
            function () {
                var days = 15; // Days you want to subtract
                var date = new Date();
                var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                $('#datepicker_1').val(last.getFullYear() + '-' + str_pad(last.getMonth() + 1) + '-' + str_pad(last.getDate()));
                $('.datepicker_1').val(date.getFullYear() + '-' + str_pad(date.getMonth()) + '-' + str_pad(date.getDate()));
                set_end_date();
            });
        $('.last30days').click(
            function () {
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + str_pad(start_date.getMonth()+1) + '-' + str_pad(start_date.getDate()));
                $('.datepicker_1').val(start_date.getFullYear() + '-' + str_pad(start_date.getMonth()+1) + '-' + str_pad(start_date.getDate()));
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

    $('.reset_btn').on('click', function(){
        $('input:radio').removeAttr('checked');
        $('#datepicker_1, #datepicker_2').removeAttr('value');
    });

    $('.btn_sid').on('click', function(){

        $('#sid').val($(this).data('sid'));
        $('#uid').val($(this).data('uid'));
        var reason = $(this).siblings('.reason').val();
        $('#reason').val(reason);

       let sid = $(this).data('sid'),
           r = false;

       if(sid==''){
           r = confirm('是否確定加入可疑名單?');
       }else{
           r = confirm('是否確定移除可疑名單?');
       }

        if(r==true){
            $('#sid_toggle').submit();
        }

    });
</script>
@stop