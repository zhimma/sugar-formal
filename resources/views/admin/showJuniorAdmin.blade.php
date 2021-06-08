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
    <h1>新增初階站長帳號</h1>
    @php
    if(!is_null(Request()->get('userid'))){
        $adminUser=\App\Models\User::findById(Request()->get('userid'));
        $item_permission=\Illuminate\Support\Facades\DB::table('role_user')->where('user_id',$adminUser->id)->first();
    }
    @endphp
    <form action="{{ is_null(Request()->get('userid')) ? route('juniorAdminCreate') : route('juniorAdminEdit').'?userid='.$adminUser->id }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" style="width: 70%;" id="table">
            <tr>
                <th class="text-center">帳號</th>
                <td style="text-align: left;">
                    @if(is_null(Request()->get('userid')))
                        <input name="account" placeholder="請輸入帳號" style="width: 100%;">
                    @else
                        <input name="account" value="{{ $adminUser->email }}"  readonly style="width: 100%;">
                    @endif
                </td>
            </tr>
            <tr>
                <th class="text-center">後台權限</th>
                <td style="text-align: left;">
                    <div style="display:grid;">
                        @if(isset($item_permission) && !is_null($item_permission))
                            @php
                                $getMenuList=explode(',',$item_permission->item_permission);
                            @endphp
                            @foreach($permissionItems as $item)
                                <label><input type="checkbox" name="items[]" value="{{$item->id}}" @if(in_array($item->id,$getMenuList)) checked @endif>{{$item->title}}</label>
                            @endforeach
                        @else
                            @foreach($permissionItems as $item)
                                <label><input type="checkbox" name="items[]" value="{{$item->id}}">{{$item->title}}</label>
                            @endforeach
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-center">操作</th>
                <td>
                    <a href="{{ route('accessPermission')}}" class="text-white btn btn-primary">返回</a>
                    <input type="submit" class='text-white btn btn-success' value="送出">
                </td>
            </tr>
        </table>
    </form>
    </body>
@stop
