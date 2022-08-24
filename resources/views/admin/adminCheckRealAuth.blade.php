@extends('admin.main')
@section('app-content')
    <h1>站長審核 - 女會員認證</h1>
    <table class="table-bordered table-hover center-block table" id="table">
        <thead>
            <tr>
                <th scope="col">email</th>
                <th scope="col">暱稱</th>
                <th scope="col">申請時間</th>
                <th scope="col">審核時間</th>
                <th scope="col">申請類型</th>
                <th scope="col">狀態</th>
                <th scope="col">異動類型</th>
            </tr>
        </thead>
        <tbody>
        @foreach($row_list as $row)
            <tr style="border-top: solid;">
                <td scope="row"  {!!$service->getRowspanAttr($row->real_auth_modify_item_group_modify_with_trashed->count())!!}>
                    <a href="{{route('users/advInfo',['id'=>$row->id])}}" target="_blank">
                        {{$row->email}}
                    </a>
                    <a class="btn btn-success" href="/admin/users/message/to/{{ $row->id }}" target="_blank;">站長對話</a>
                </td>
                <td >
                    {{$row->name}}
                </td>
                <td >
                    {{$row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->created_at??null}}
                </td>
                <td >
                    {{$service->handleNullWord($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->status_at??null,'尚未審核')}}
                </td>

                <td >
                    {!!$service->getAuthTypeLayoutInAdminCheckByModifyEntry($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed)!!}
                </td>
                <td>
                    {{$service->convertModifyStatusToCompleteWord($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed)}}
                </td>
                <td>
                    {!!$service->convertModifyItemIdToCompleteWord($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed)!!}
                </td>               
            </tr>
            @foreach($row->real_auth_modify_item_group_modify_with_trashed->slice(1) as $modify)
            @php
                $temp_user_id='';
                $now_user_id=$row->id;
                if($temp_user_id!==$now_user_id){
                    $temp_user_id=$now_user_id;
                }
            @endphp
            <tr @if($temp_user_id==$row->id) style="border-bottom: solid;" @endif>
                <td >
                    {{$row->name}}
                </td>
                <td>{{$modify->real_auth_user_modify_with_trashed->created_at}}</td>
                <td>
                    {{$service->handleNullWord($modify->real_auth_user_modify_with_trashed->status_at,'尚未審核')}}
                </td>
                <td>
                    {!!$service->getAuthTypeLayoutInAdminCheckByModifyEntry($modify->real_auth_user_modify_with_trashed)!!}
                </td>                
                <td>{{$service->convertModifyStatusToCompleteWord($modify->real_auth_user_modify_with_trashed)}}</td>
                <td>

                    {!!$service->convertModifyItemIdToCompleteWord($modify->real_auth_user_modify_with_trashed)!!}
                </td>                
            </tr> 
            @endforeach

        @endforeach
            
        </tbody>
    </table>
@stop
