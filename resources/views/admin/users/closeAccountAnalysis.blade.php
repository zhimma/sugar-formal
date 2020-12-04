@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>會員帳號關閉原因查詢</h1>
<div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('closeAccountReasonList') }}" method='get'>
        <table class="table-hover table table-bordered">
            <tr>
                <th>帳號</th>
                <td>
                    <input type="text" name="account" class="form-control" placeholder="name@example.com" value="@if(isset($_GET['account'])){{ $_GET['account'] }}@endif">
                </td>
            </tr>
            <tr>
                <th>上次更新時間 (開始)</th>
                <td>
                    <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_start'])){{ $_GET['date_start'] }}@endif" class="form-control">
                </td>
            <tr>
                <th>上次更新時間 (結束)</th>
                <td>
                    <input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_end'])){{ $_GET['date_end'] }}@endif" class="form-control">
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
                <th>帳號狀態</th>
                <td>
                    <select name="status" class="form-control">
                        <option value="" @if(isset($_GET['status']) && $_GET['status']=='') selected @endif>請選擇</option>
                        <option value="0" @if(isset($_GET['status']) && $_GET['status']=='0') selected @endif>目前關閉</option>
                        <option value="more3" @if(isset($_GET['status']) && $_GET['status']=='more3') selected @endif>關閉已超過3個月</option>
                        <option value="more6" @if(isset($_GET['status']) && $_GET['status']=='more6') selected @endif>關閉已超過6個月</option>
                        <option value="more12" @if(isset($_GET['status']) && $_GET['status']=='more12') selected @endif>關閉已超過12個月</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>會員身份</th>
                <td>
                    <select name="accountType" class="form-control">
                        <option value=""@if(isset($_GET['accountType']) && $_GET['accountType']=='') selected @endif>請選擇</option>
                        <option value="vip_1"@if(isset($_GET['accountType']) && $_GET['accountType']=='vip_1') selected @endif>VIP男</option>
                        <option value="vip_2"@if(isset($_GET['accountType']) && $_GET['accountType']=='vip_2') selected @endif>VIP女</option>
                        <option value="notvip_1"@if(isset($_GET['accountType']) && $_GET['accountType']=='notvip_1') selected @endif>普通男</option>
                        <option value="notvip_2"@if(isset($_GET['accountType']) && $_GET['accountType']=='notvip_2') selected @endif>普通女</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>關閉原因</th>
                <td>
                    <select name="closeReason" class="form-control">
                        <option value=""@if(isset($_GET['closeReason']) && $_GET['closeReason']=='') selected @endif>請選擇</option>
                        <option value="1"@if(isset($_GET['closeReason']) && $_GET['closeReason']=='1') selected @endif>遇到騷擾/八大</option>
                        <option value="2"@if(isset($_GET['closeReason']) && $_GET['closeReason']=='2') selected @endif>網站介面操作不滿意</option>
                        <option value="3"@if(isset($_GET['closeReason']) && $_GET['closeReason']=='3') selected @endif>已找到長期穩定對象</option>
                        <option value="4"@if(isset($_GET['closeReason']) && $_GET['closeReason']=='4') selected @endif>其他原因</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="/admin/users/closeAccountReason" class='text-white btn btn-dark submit'>清除條件</a>
                    <input type="submit" class='text-white btn btn-primary submit' value="查詢">
                </td>
            </tr>
        </table>
    </form>
</div>
<span>共 {{ $listAccount->count() }} 筆記錄</span>
<br>
<span>狀態 (N):  N指此user關閉帳號統計次數。</span>
<table class='table table-bordered table-hover'>
	<tr>
        <td>會員ID</td>
		<td>帳號</td>
		<td>名稱</td>
        <td>身份</td>
        <td>帳號狀態(N)</td>
        <td>關閉時間</td>
        <td>關閉原因</td>
        @if(isset($_GET['closeReason']) && $_GET['closeReason']=='1')
            <td>檢舉此帳號</td>
            <td>檢舉證據</td>
            <td>說明</td>
        @elseif(isset($_GET['closeReason']) && $_GET['closeReason']=='2')
            <td>介面設計不優</td>
            <td>載入速度太慢</td>
            <td>說明</td>
        @elseif(isset($_GET['closeReason']) && $_GET['closeReason']=='4')
            <td>說明</td>
        @endif
	</tr>
	@forelse($listAccount as $account)
    <tr>
        <td>{{ $account->user_id }}</td>
        <td>{{ $account->email }}</td>
        <td>{{ $account->name }}</td>
        <td>{{ \App\Models\User::findById($account->id)->isVip() ? 'VIP' : '普通' }}{{ $account->engroup == 1 ? '男':'女' }}</td>
        <td>
            @if($account->created_at >=  date("Y-m-d",strtotime("-3 months", strtotime(Now()))))
                關閉已超過3個月
            @elseif($account->created_at >=  date("Y-m-d",strtotime("-3 months", strtotime(Now()))))
                關閉已超過6個月
            @elseif($account->created_at >=  date("Y-m-d",strtotime("-3 months", strtotime(Now()))))
                關閉已超過12個月
            @else
                目前關閉
            @endif
            {{ '(' .\App\Models\AccountStatusLog::where('user_id',$account->user_id)->get()->count() . ')' }}
        </td>
        <td>{{ $account->created_at }}</td>
        <td>
            @if($account->reasonType == '1')
                遇到騷擾/八大
            @elseif ($account->reasonType == '2')
                網站介面操作不滿意
            @elseif ($account->reasonType == '3')
                已找到長期穩定對象
            @elseif ($account->reasonType == '4')
                其他原因
            @endif
        </td>

        @if(isset($_GET['closeReason']) && $_GET['closeReason']=='1')
            <td>
                @foreach(explode(',',$account->reported_id) as $reportId)
                    <a href="/admin/users/advInfo/{{$reportId}}">{{$reportId}}</a><br>
                @endforeach
            </td>
            <td><img src="{{$account->image }}" style="width: 150px; height: 100px"></td>
            <td>{{ $account->content }}</td>
        @elseif (isset($_GET['closeReason']) && $_GET['closeReason']=='2')
            @php
                $reasonContent = json_decode($account->content);

                $design = [];
                $slow = [];
                foreach ($reasonContent as $key =>$vale){
                    $test1 = explode('-',$vale);
                    if($test1[0] == '介面設計不美觀'){
                        $design[] =$test1[1];
                    }else if ($test1[0] == '載入速度太慢'){
                        $slow[] =$test1[1];
                    }
                }
                $design = implode(' ,',$design);
                $slow = implode(' ,',$slow);
            @endphp
            <td>{{ $design }}</td>
            <td>{{ $slow }}</td>
            <td>{{ $account->remark1 }}</td>
        @elseif (isset($_GET['closeReason']) && $_GET['closeReason']=='4')
            <td>{{ $account->content }}</td>
        @endif
    </tr>
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
{{ $listAccount->links() }}
</body>
<script>
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
                minus_date.setDate(minus_date.getDate() - 29);
                $('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                $('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
                set_end_date();
                minus_date.setDate(minus_date.getDate() + 29);
            });
    });

    function set_end_date() {
        $('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
        $('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
    }

    function str_pad(n) {
        return String("00" + n).slice(-2);
    }
</script>
@stop