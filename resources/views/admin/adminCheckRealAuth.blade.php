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
                <th scope="col">異動時間</th>
                <th scope="col">異動類型</th>
            </tr>
        </thead>
        <tbody>
        @foreach($row_list as $row)
            @php
                //$Vip = \App\Models\Vip::vip_diamond($row->id);
            @endphp
            <tr>
                <td scope="row"  {!!$service->getRowspanAttr($service->riseByUserEntry($row)->getUserUncheckedApplyList()->count())!!}>
                    <a href="{{route('users/advInfo',['id'=>$service->user()->id])}}" target="_blank">
                        {{$row->email}}
                    </a>
                </td>
                <td {!!$service->getRowspanAttr($service->getApplyUncheckedModifyList()->count())!!}>
                    {{$service->apply_entry()->created_at}}
                </td>
                <td {!!$service->getRowspanAttr($service->modify_list()->count())!!}>
                    {{$service->handleNullWord($service->apply_entry()->passed_at,'尚未審核')}}
                </td>
                    {{--
                <td {!!$service->getRowspanAttr($service->modify_list()->count())!!}>{{$service->apply_entry()->real_auth_type->name}}</td>
                    --}}
                <td {!!$service->getRowspanAttr($service->modify_list()->count())!!}>
                    {!!$service->getAdminCheckAuthTypeLayoutByApplyEntry()!!}
                </td>
                <td>
                    {{$service->modify_list()->count()?$service->convertStatusToCompleteWord($service->modify_entry()->status):null}}
                </td>
                <td>
                    {{$service->modify_list()->count()?$service->modify_entry()->created_at:null}}
                </td>
                <td>
                    {{$service->modify_list()->count()?$service->modify_entry()->passed_at:null}}
                </td>               
            </tr>
            @foreach($service->modify_list()->slice(1) as $modify)
            <tr>    
                <td>{{$service->convertStatusToCompleteWord($modify->status)}}</td>
                <td>{{$service->getStatusLayout($modify->status)}}</td>
                <td>{{$modify->created_at}}</td>
                <td>{{$modify->real_auth_modify_item->name??null}}</td>                
            </tr> 
            @endforeach
            
            @foreach($service->apply_list()->slice(1) as $apply)
            <tr>                
                <td {!!$service->getRowspanAttr($service->slotByApplyEntry($apply)->getApplyUncheckedModifyList()->count())!!}>{{$apply->created_at}}</td>
                <td>{{$service->handleNullWord($apply->passed_at,'尚未審核')}}</td>
                <td>{!!$service->getAdminCheckAuthTypeLayoutByApplyEntry()!!}</td>
                <td>{{$service->modify_entry()->status??null}}</td>
                <td>{{$modify->created_at??null}}</td>
                <td>{{$modify->real_auth_modify_item->name??null}}</td>
            </tr>
            @endforeach
            @foreach($service->modify_list() as $modify)
            <tr>    
                <td>{{$modify->status?'已完成':'待確認'}}</td>
                <td>{{$modify->created_at}}</td>
                <td>{{$modify->real_auth_modify_item->name}}</td>                
            </tr>
            @endforeach


        @endforeach
        </tbody>
    </table>
@stop
