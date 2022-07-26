@extends('admin.main')
@section('app-content')
    <h1>站長審核 - 女會員認證</h1>
    <table class="table-bordered table-hover center-block table" id="table">
        <thead>
            <tr>
                <th scope="col">email</th>
                <th scope="col">申請時間</th>
                <th scope="col">審核時間</th>
                <th scope="col">申請類型</th>
                <th scope="col">狀態</th>
                <th scope="col">異動類型</th>
            </tr>
        </thead>
        <tbody>
        @foreach($row_list as $row)
            <tr>
                <td scope="row"  {!!$service->getRowspanAttr($row->real_auth_modify_item_group_modify_with_trashed->count())!!}>
                    <a href="{{route('users/advInfo',['id'=>$row->id])}}" target="_blank">
                        {{$row->email}}
                    </a>
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
                    {{--@if($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->item_id>=4 && !$row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->apply_status_shot && $row->real_auth_modify_item_group_modify_with_trashed->first()->check_first==$row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->id)--}}
                    {{--
                    @if($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->is_formal_first)
                    新申請
                    @else                
                    {{$row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->patch_id_shot?'新申請補件-':''}}  
                    {{$row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed->real_auth_modify_item->name??null}}  
                    @endif
                    --}}
                    {!!$service->convertModifyItemIdToCompleteWord($row->real_auth_modify_item_group_modify_with_trashed->first()->real_auth_user_modify_with_trashed)!!}
                </td>               
            </tr>
            @foreach($row->real_auth_modify_item_group_modify_with_trashed->slice(1) as $modify)
            <tr>    
                <td>{{$modify->real_auth_user_modify_with_trashed->created_at}}</td>
                <td>
                    {{$service->handleNullWord($modify->real_auth_user_modify_with_trashed->status_at,'尚未審核')}}
                </td>
                <td>
                    {!!$service->getAuthTypeLayoutInAdminCheckByModifyEntry($modify->real_auth_user_modify_with_trashed)!!}
                </td>                
                <td>{{$service->convertModifyStatusToCompleteWord($modify->real_auth_user_modify_with_trashed)}}</td>
                <td>
                    {{--@if($modify->real_auth_user_modify_with_trashed->item_id>=4 && !$modify->real_auth_user_modify_with_trashed->apply_status_shot && $modify->check_first==$modify->real_auth_user_modify_with_trashed->id)--}}
                    {{--
                    @if($modify->real_auth_user_modify_with_trashed->is_formal_first)
                    新申請
                    @else
                    {{$modify->real_auth_user_modify_with_trashed->patch_id_shot?'新申請補件-':''}}  
                    {{$modify->real_auth_user_modify_with_trashed->real_auth_modify_item->name??null}}                    
                    @endif
                    --}}
                    {!!$service->convertModifyItemIdToCompleteWord($modify->real_auth_user_modify_with_trashed)!!}
                </td>                
            </tr> 
            @endforeach

        @endforeach
            
        </tbody>
    </table>
@stop
