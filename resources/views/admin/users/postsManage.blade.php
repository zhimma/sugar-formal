@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
    </style>
    <body style="padding: 15px;">
    <h1>討論區管理</h1>
    <div class="col col-12 col-sm-12 col-md-8 col-lg-6">
        <form action="{{ route('users/posts') }}" method='get'>
            <table class="table-hover table table-bordered">
                <tr>
                    <th>帳號</th>
                    <td>
                        <input type="text" name="account" class="form-control" placeholder="name@example.com" value="@if(isset($_GET['account'])){{ $_GET['account'] }}@endif">
                    </td>
                </tr>
                <tr>
                    <th>發表時間 (開始)</th>
                    <td>
                        <input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="@if(isset($_GET['date_start'])){{ $_GET['date_start'] }}@endif" class="form-control">
                    </td>
                <tr>
                    <th>發表時間 (結束)</th>
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
                    <th>類別</th>
                    <td>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="type" value="main" @if(isset($_GET['type']) && $_GET['type'] == 'main') checked @endif style="margin-left: unset;">
                            <label class="form-check-label" for="inlineRadio4">文章</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="type" value="sub" @if(isset($_GET['type']) && $_GET['type'] == 'sub') checked @endif style="margin-left: unset;">
                            <label class="form-check-label" for="inlineRadio5">留言</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="type" value="" @if(isset($_GET['type']) && $_GET['type'] == '') checked @endif style="margin-left: unset;">
                            <label class="form-check-label" for="inlineRadio5">全部</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="/admin/users/posts" class='text-white btn btn-dark submit'>清除條件</a>
                        <input type="submit" class='text-white btn btn-primary submit' value="查詢">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div>查詢結果：共 <span style="color: red;">{{ $postsList->total() }}</span> 筆記錄。</div>
    <table class='table table-bordered table-hover'>
        <tr>
            <td width="15%">會員帳號</td>
            <td width="15%">暱稱</td>
            <td width="5%">類別</td>
            <td width="30%">內容</td>
            <td width="15%">發表時間</td>
            <td width="25%">功能</td>
        </tr>
        @forelse($postsList as $list)
            <tr>
                <td><a href="/admin/users/advInfo/{{ $list->user_id }}" target="_blank">{{ $list->email }}</a></td>
                <td>{{ $list->name }}</td>
                <td>{{ $list->type=='sub' ? '留言' : '文章' }}</td>
                <td>{{ $list->contents }}</td>
                <td>{{ $list->created_at }}</td>
                <td style="display:flex;">
                    <a href="/admin/users/posts/delete/{{ $list->id }}" class='text-white btn btn-danger'>刪除</a>
                    <form method="POST" action="/admin/users/posts/prohibit">
                        {!! csrf_field() !!}
                        <input type="hidden" name='uid' value="{{ $list->user_id}}">
                        <input type="hidden" name='prohibit' value="{{ $list->prohibit_posts ?  0 : 1 }}">
                        <button class="text-white btn {{ $list->prohibit_posts ?  'btn-success' : 'btn-primary' }}" style="margin-left: 5px;">{{ $list->prohibit_posts ?  '解除' : '' }}禁止發言</button>
                    </form>
                    <form method="POST" action="/admin/users/posts/access">
                        {!! csrf_field() !!}
                        <input type="hidden" name='uid' value="{{ $list->user_id}}">
                        <input type="hidden" name='access' value="{{ $list->access_posts ?  0 : 1 }}">
                        <button class="text-white btn  {{ $list->access_posts ?  'btn-success' : 'btn-primary' }}" style="margin-left: 5px;">{{ $list->access_posts ?  '解除' : '' }}封鎖進入</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                找不到資料
            </tr>
        @endforelse
    </table>
    {{ $postsList->links() }}
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