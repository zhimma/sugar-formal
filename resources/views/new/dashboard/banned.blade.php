@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="fs_name">
                    <div class="fs_title">本月封鎖名單<h2>共{{$count}}筆資料</h2></div>
                    <div class="fs_table">
                        <table>
                            <tr class="fs_tb">
                                <th width="25%" style=" border-radius:5px 0 0 5px;">名稱</th>
                                <th width="25%">封鎖原因</th>
                                <th width="25%">開始日期</th>
                                <th width="25%" style=" border-radius:0 5px 5px 0;">解除時間</th>
                            </tr>
                            @foreach($banned_user as $row)
                            <tr>
                                <td>{{$row->member_id}}</td>
                                <td>{{$row->reason}}</td>
                                <td>{{ date('Y/m/d', strtotime($row->created_at))}}</td>
                                <td>@if($row->expire_date<>''){{ date('Y/m/d', strtotime($row->expire_date))}}@else - @endif</td>
                            </tr>
                            @endforeach
                        </table>
                        @if(count($banned_user)>15)
                        <div class="fenye">
                            <a id="prePage" href="{{ $banned_user->previousPageUrl() }}">上一頁</a>
                            <a id="nextPage" href="{{ $banned_user->nextPageUrl() }}">下一頁</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
