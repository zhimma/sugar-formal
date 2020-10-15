@include('partials.header')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
h3{
    text-align: left;
}
</style>
<body style="padding: 15px;">
    <h3>公告資料</h3>
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th class="text-center">內容</th>
            <th class="text-center">性別</th>
            <th class="text-center">排序</th>
            <th class="text-center">建立時間</th>
            <th class="text-center">更新時間</th>
        </tr>
        <tr class="template">
            <td style="word-break: break-all; width: 50%;">{!! nl2br($announce->content) !!}</td>
            <td>@if($announce->en_group == 1) 男 @else 女 @endif</td>
            <td>{{ $announce->sequence }}</td>
            <td class="created_at">{{ $announce->created_at }}</td>
            <td class="updated_at">{{ $announce->updated_at }}</td>
        </tr>
    </table>
    <h3>不再顯示的會員</h3>
    <h5>共{{ $results->count() }}筆</h5>
    <table class="table-bordered table-hover center-block text-center" id="table">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">會員名稱</th>
            <th class="text-center">記錄時間</th>
        </tr>
        @forelse($results as $r)
            <tr class="template">
                <td>{{ $r->user_id }}</td>
                <td><a href="{{ route('users/advInfo', $r->user_id) }}" target='_blank'>{{ $r->name }}</a></td>
                <td class="created_at">{{ $r->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">沒有資料</td>
            </tr>
        @endforelse
    </table>
</body>