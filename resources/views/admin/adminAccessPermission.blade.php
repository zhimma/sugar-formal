@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
h3{
    text-align: left;
}
</style>
<body style="padding: 15px;">
    <h1>初階站長權限管理</h1>
    <br>
    <h3>初階站長帳號列表</h3>
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">帳號</th>
            <th class="text-center">暱稱</th>
            <th class="text-center">後台權限項目</th>
            <th class="text-center">建立時間</th>
            <th class="text-center">更新時間</th>
            <th class="text-center">操作</th>
        </tr>
        @foreach($adminList as $key =>$admin)
            @php
                $adminInfo=\App\Models\User::findById($admin->user_id);
                if(is_null($adminInfo)){
                    continue;
                }
                $menuList=\App\Models\AdminMenuItems::whereIn('id',explode(',',$admin->item_permission))->where('status',1)->orderBy('sort')->get();
            @endphp
            <tr class="template">
                <td>{{ $key+1 }}</td>
                <td>{{ $adminInfo->email }}</td>
                <td>{{ $adminInfo->name }}</td>
                <td>
                    @foreach($menuList as $key =>$list)
                        <li style="text-align: left; padding: 0px 10px;">{{ $key+1 .'.'. $list->title }}</li>
                    @endforeach
                </td>
                <td class="created_at">{{ $admin->created_at }}</td>
                <td class="updated_at">{{ $admin->updated_at }}</td>
                <td>
                    <div style="display: inline-flex;">
                        <a class='text-white btn btn-primary' href="{{ route('showJuniorAdmin').'?userid='.$adminInfo->id }}">修改</a>
                        <form action="{{ route('juniorAdminDelete',$adminInfo->id) }}" method="post">
                            {!! csrf_field() !!}
                            <input type="submit" class='ttext-white btn btn-danger' value="刪除">
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    <br>
    <a href="{{ route('showJuniorAdmin') }}" class='new text-white btn btn-success'>新增初階站長帳號</a>

    {{--<br>
    <br>
    <h3>後台權限列表</h3>
    <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
        <tr>
            <th class="text-center">編號</th>
            <th class="text-center">名稱</th>
            <th class="text-center">route路徑</th>
            <th class="text-center">建立時間</th>
        </tr>
        @php
            $adminMenuList=\App\Models\AdminMenuItems::get();
        @endphp
        @foreach($adminMenuList as $item)
            <tr class="template">
                <td style="word-break: break-all; width: 10%;">{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->route_path }}</td>
                <td class="created_at">{{ $item->created_at }}</td>
            </tr>
        @endforeach
    </table>--}}
</body>
<script>
    function deleteAdmin(id) {
        let c = confirm('確定要刪除這個初階站長？');
        if(c === true){
            window.location = "/admin/dashboard/accessPermission/delete/" + id;
        }
        else{
            return 0;
        }
    }
</script>
@stop
