@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>關閉會員帳號原因統計</h1>
<div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('users/closeAccountReasonList') }}" method='get'>
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
<div>查詢結果：共 <span style="color: red;">{{ $listAccount->count() }}</span> 筆關閉記錄。</div>
<br>
<span>備註：帳號目前狀態  (N)：N指此會員總關閉次數。</span>
<table class='table table-bordered table-hover'>
	<tr>
        <td>會員ID</td>
		<td>帳號</td>
		<td>名稱</td>
        <td>身份</td>
        <td>帳號目前狀態(N)</td>
        <td>關閉時間</td>
        <td>關閉原因</td>
        <td></td>
	</tr>
	@forelse($listAccount as $account)
    <tr>
        <td><a href="/admin/users/advInfo/{{ $account->user_id }}" target="_blank">{{ $account->user_id }}</a></td>
        <td>{{ $account->email }}</td>
        <td>{{ $account->name }}</td>
        <td>{{ \App\Models\User::findById($account->id)->isVip() ? 'VIP' : '普通' }}{{ $account->engroup == 1 ? '男':'女' }}</td>
        <td>
            @if($account->accountStatus == 1)
                目前開啟
            @elseif($account->created_at > date("Y-m-d",strtotime("-3 months", strtotime(Now()))))
                目前關閉
            @elseif($account->created_at <=  date("Y-m-d",strtotime("-12 months", strtotime(Now()))))
                關閉已超過12個月
            @elseif($account->created_at <=  date("Y-m-d",strtotime("-6 months", strtotime(Now()))))
                關閉已超過6個月
            @elseif($account->created_at <=  date("Y-m-d",strtotime("-3 months", strtotime(Now()))))
                關閉已超過3個月
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
        <td>
            <a href="/admin/users/closeAccountDetail?userID={{ $account->id }}" target="_blank" class='text-white btn btn-primary'>明細</a>
        </td>
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
                var start_date = new Date(new Date().setDate(date.getDate() - 30));
                $('#datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
                $('.datepicker_1').val(start_date.getFullYear() + '-' + parseInt(start_date.getMonth()+1) + '-' + start_date.getDate());
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
</script>
@stop