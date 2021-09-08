@extends('new.layouts.website')

<style>
    .pagination > li > a:focus,
.pagination > li > a:hover,
.pagination > li > span:focus,
.pagination > li > span:hover{
    z-index: 3;
    /* color: #23527c !important; */
    background-color: #FF8888 !important;
    /* border-color: #ddd !important; */
    /* border-color:#ee5472 !important; */
    /* color:white !important; */
}
</style>
@section('app-content')
    <div class="container matop70">
        <input type="hidden" value="{{ \App\Models\User::isBanned($user->id) ?1:2 }}">
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
                                <td>{{$row->name}}</td>
                                <td>{{$row->reason}}</td>
                                <td>{{ date('Y/m/d', strtotime($row->created_at))}}</td>
                                <td>@if($row->expire_date<>''){{ date('Y/m/d', strtotime($row->expire_date))}}@else - @endif</td>
                            </tr>
                            @endforeach
                        </table>

                        <div style="text-align: center;">
                            {!! $banned_user->links('pagination::sg-pages2') !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
