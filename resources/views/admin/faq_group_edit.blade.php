@extends('admin.main')
@section('app-content')
    <link rel="stylesheet" href="{{asset('/css/faq_admin_common.css')}}">
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
        
        .is_new_block {position:relative;top:20px;color:black;font-weight:normal;}
        .cvvs_box {margin-top:5px;}
        .ainput {width:80px;}
        #table {width:100%;margin:10px;}
        #table th {width:20%;}
        #table th,#table td {padding:10px;}
        .act_lbl span {margin-left:10px;}
    </style>
    <body style="padding: 15px;">
    <h1>FAQ修改組別</h1>
    <form action="{{ route('admin/faq_group/save', $entry->id) }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">內容</th>
                <td>
                    <input type="text" name="name" value="{{ $entry->name }}" /> 
               </td> 
            </tr>
            <tr>
                <th class="text-center">男/女</th>
                <td>
                   @foreach($service->getEngroupVipWord() as $code=>$value)
                    <label for="faq_group_engroupvip_{{$code}}">
                    <input type="radio" name="engroup_vip" id="faq_group_engroupvip_{{$code}}" value="{{$code}}" {{$service->getFormChkEditAssign($code,$service->getFormColByEntry($entry,'engroup_vip'),request()->engroup_vip)}} />
                        {{$value}}
                    </label>
                    @endforeach                  
                </td> 
            </tr>
            <tr>                
                <th class="text-center">在第幾次login時才跳出此公告</th>
                <td>
                    <input type="number" name="faq_login_times" min="0" step="1" value="{{$service->getFormValEditAssign($entry->faq_login_times,request()->faq_login_times)}}" />
                </td> 
            </tr>
            <tr>
                <th class="text-center">啟用</th>
                <td>
                    <label for="act" class="act_lbl">
                    <input type="checkbox" id="act" name="act" value="1" {{$entry->act?'checked':''}} {{($entry->act || $entry->isRealHasAnswer())?'':'disabled'}}/>
                    <span> @if(!$entry->isRealHasAnswer()) ( 啟用選項無效，請先至少設定一題的答案  ) @endif</span>
                    </label>
                </td>
            </tr>
            <tr>                
                <th class="text-center">啟用時間</th>
                <td class="created_at">{!! $service->getGroupActAtWordByEntry($entry) !!}</td>
            </tr>            
            <tr>                
                <th class="text-center">建立時間</th>
                <td class="created_at">{{ $entry->created_at }}</td>
            </tr>
            <tr>
                <th class="text-center">更新時間</th>
                <td class="updated_at">{{ $entry->updated_at }}</td>
            </tr>
            <tr>
                <th class="text-center">操作</th>
                <td>
                    <input type="hidden" name="id" value="{{ $entry->id }}">
                    <input type="submit" class='text-white btn btn-success' value="送出">
                    <input type="reset"  class='text-white btn btn-danger' value="復原">
                </td>
            </tr>
        </table>
    </form>
    <a href="{{ route('admin/faq_group') }}{{$service->getEngroupVipQueryString('?',$entry)}}" class="text-white btn btn-primary">返回組別</a>
    </body>
@stop
