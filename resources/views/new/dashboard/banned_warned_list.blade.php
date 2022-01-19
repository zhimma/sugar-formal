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
<!-- Bootstrap -->
<link href={{asset("alert/css/bootstrap.min.css")}} rel="stylesheet">
<link href={{asset("alert/css/bootstrap-theme.min.css")}} rel="stylesheet">
<!-- owl-carousel-->
<!--    css-->
<link rel="stylesheet" href={{asset("alert/css/style.css")}}>
<link rel="stylesheet" href={{asset("alert/css/swiper.min.css")}}>
<script src={{asset('alert/js/bootstrap.min.js')}}></script>
<script src={{asset("alert/js/jquery-2.1.1.min.js")}} type="text/javascript"></script>
<script src={{asset("alert/js/main.js")}} type="text/javascript"></script>
<script>
    function changediv(id) {
        document.getElementById("fs").style.display = "none";
        document.getElementById("fs2").style.display = "none";
        document.getElementById("fs_a").className = "";
        document.getElementById("fs2_a").className = "";
        document.getElementById(id).style.display = "table";
        document.getElementById(id + "_a").className = "nn_dontt_hover";
        return false;
    }
</script>
@section('app-content')
    <div class="container matop70">
        <input type="hidden" value="{{ \App\Models\User::isBanned($user->id) ?1:2 }}">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="ddt_list">
                    <div class="nn_dontt">
                        <ul>
                            <li onclick='return changediv("fs")' id="fs_a" class="nn_dontt_hover" target=_parent style="color:#ee5472 ">封鎖名單</li>
                            <li onclick='return changediv("fs2")' id="fs2_a" target=_parent style="color:#ee5472 ">警示名單</li>
                        </ul>
                    </div>
                </div>
                <div class="fs_name" id="fs">
                    <div class="fs_title"><h2>本月封鎖名單，共{{ $banned_count }}筆資料</h2></div> 
                    <div class="fs_table">
                        <table>
                            <tr class="fs_tb">
                                <th width="25%" style=" border-radius:5px 0 0 5px;">名稱</th>
                                <th width="25%">封鎖原因</th>
                                <th width="25%">開始日期</th>
                                <th width="25%" style=" border-radius:0 5px 5px 0;">解除時間</th>
                            </tr>
                            @foreach($banned_users as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->reason}}</td>
                                    <td>{{ date('Y/m/d', strtotime($row->created_at))}}</td>
                                    <td>@if($row->expire_date<>''){{ date('Y/m/d', strtotime($row->expire_date))}}@else - @endif</td>
                                </tr>
                            @endforeach
                        </table>
                        <div style="text-align: center;">
                            {!! $banned_users->links('pagination::sg-pages2') !!}
                        </div>
                    </div>
                </div>
                <div class="fs_name" id="fs2" style="display: none;">
                    <div class="fs_title"><h2>本月警示名單，共{{ $warned_count }}筆資料</h2></div>
                    <div class="fs_table">
                        <table>
                            <tr class="fs_tb">
                                <th width="25%" style=" border-radius:5px 0 0 5px;">名稱</th>
                                <th width="25%">封鎖原因</th>
                                <th width="25%">開始日期</th>
                                <th width="25%" style=" border-radius:0 5px 5px 0;">解除時間</th>
                            </tr>
                            @foreach($warned_users as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->reason}}</td>
                                    <td>{{ date('Y/m/d', strtotime($row->created_at))}}</td>
                                    <td>@if($row->expire_date<>''){{ date('Y/m/d', strtotime($row->expire_date))}}@else - @endif</td>
                                </tr>
                            @endforeach
                        </table>
                        <div style="text-align: center;">
                            {!! $warned_users->links('pagination::sg-pages2') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop