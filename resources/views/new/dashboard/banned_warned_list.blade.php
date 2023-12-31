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
    #reason_hidden{
        background-color: #faf0f0;
        border: 0px;
    }

</style>
<head>
    
    <!--<meta charset="utf-8">-->
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <!--<meta name="format-detection" content="telephone=no" />-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!--<title>懲處名單</title>-->
    <!-- Bootstrap -->
    <!--<link href={{asset("alert/css/bootstrap.min.css")}} rel="stylesheet">-->
    <!--<link href={{asset("alert/css/bootstrap-theme.min.css")}} rel="stylesheet">-->
    <!-- owl-carousel-->
    <!--    css-->
    <!--<link rel="stylesheet" href={{asset("alert/css/style.css")}}>-->
    <!--<link rel="stylesheet" href={{asset("alert/css/swiper.min.css")}}>-->
    <!--<script src={{asset('alert/js/bootstrap.min.js')}}></script>-->
    <!--<script src={{asset("alert/js/jquery-2.1.1.min.js")}} type="text/javascript"></script>-->
    <!--<script src={{asset("/js/main.js")}} type="text/javascript"></script>-->

</head>
<!--<script>
    function changediv(id) {
        document.getElementById("fs").style.display = "none";
        document.getElementById("fs2").style.display = "none";
        document.getElementById("fs_a").className = "";
        document.getElementById("fs2_a").className = "";
        document.getElementById(id).style.display = "table";
        document.getElementById(id + "_a").className = "nn_dontt_hover";
        return false;
    }
</script>-->
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
                            @if($type == 0)
                            <li id="fs_a" class="nn_dontt_hover" target=_parent style="color:#ee5472 ">封鎖名單</li>
                            <li onclick="location.href='/dashboard/banned_warned_list?type=1';" id="fs2_a" target=_parent style="color:#ee5472 ">警示名單</li>
                            @elseif($type == 1)
                            <li onclick="location.href='/dashboard/banned_warned_list';" id="fs_a" target=_parent style="color:#ee5472 ">封鎖名單</li>
                            <li id="fs2_a" class="nn_dontt_hover" target=_parent style="color:#ee5472 ">警示名單</li>
                            @endif
                        </ul>
                    </div>
                </div>
                @if($type == 0)
                <div class="fs_name" id="fs">
                @elseif($type == 1)
                <div class="fs_name" id="fs" style="display: none;">
                @endif
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
                                    <td><font size="1">{{$row->name}}</font></td>
                                    <td ><font size="1">
                                        @if ($row->reason <>'')                                       
                                            @if(mb_strlen($row->reason) >8) 
                                                <button style="background:transparent;" id="reason_hidden"  onclick="c5('{{$row->reason}}'),setTimeout(function(){window.location.reload();},3000)">{{mb_substr($row->reason,0,5,'utf-8')}}...</button>
                                            @else
                                                {{$row->reason}}
                                            @endif   
                                        @else
                                            -
                                        @endif
                                        </font>
                                    </td>
                                    <td><font size="1">{{ date('Y/m/d', strtotime($row->created_at))}}</font></td>
                                    <td><font size="1">@if($row->expire_date<>''){{ date('Y/m/d', strtotime($row->expire_date))}}@else - @endif</font></td>
                                </tr>
                            @endforeach
                            
                        </table>
                        <div style="text-align: center;">
                            <div class="fenye">
                                @if($banned_users->currentPage()==1)
                                    <a>上一頁</a>
                                    <span class="new_page">第 {{ $banned_users->currentPage() }} 頁</span>
                                    <a href="{{ $banned_users->nextPageUrl() }}" >下一頁</a>
                                @elseif($banned_users->currentPage() == $banned_users->lastPage())
                                    <a href="{{ $banned_users->previousPageUrl() }}" id="pPage">上一頁</a>
                                    <span class="new_page">第 {{ $banned_users->currentPage() }} 頁</span>
                                    <a>下一頁</a>
                                @else
                                    <a href="{{ $banned_users->previousPageUrl() }}" id="pPage">上一頁</a>
                                    <span class="new_page">第 {{ $banned_users->currentPage() }} 頁</span>
                                    <a href="{{ $banned_users->nextPageUrl() }}" >下一頁</a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                @if($type == 0)
                <div class="fs_name" id="fs2" style="display: none;">
                @elseif($type == 1)
                <div class="fs_name" id="fs2">
                @endif
                    <div class="fs_title"><h2>本月警示名單，共{{ $warned_count }}筆資料</h2></div>
                    <div class="fs_table">
                        <table>
                            <tr class="fs_tb">
                                <th width="25%" style=" border-radius:5px 0 0 5px;">名稱</th>
                                <th width="25%">警示原因</th>
                                <th width="25%">開始日期</th>
                                <th width="25%" style=" border-radius:0 5px 5px 0;">解除時間</th>
                            </tr>
                            @foreach($warned_users as $row)
                                <tr>
                                    <td><font size="1">{{$row->name}}</font></td>
                                    <td ><font size="1">
                                        @if ($row->reason <>'')                                       
                                            @if(mb_strlen($row->reason) >8) 
                                                <button style="background:transparent;" id="reason_hidden"  onclick="c5('{{$row->reason}}'),setTimeout(function(){window.location.reload();},3000)">{{mb_substr($row->reason,0,5,'utf-8')}}...</button>
                                            @else
                                                {{$row->reason}}
                                            @endif   
                                        @else
                                            -
                                        @endif
                                        </font>
                                    </td>
                                    <td><font size="1">{{ date('Y/m/d', strtotime($row->created_at))}}</font></td>
                                    <td><font size="1">@if($row->expire_date<>''){{ date('Y/m/d', strtotime($row->expire_date))}}@else - @endif</font></td>
                                </tr>
                            @endforeach
                        </table>
                        <div style="text-align: center;">
                            <div class="fenye">
                                @if($banned_users->currentPage()==1)
                                    <a>上一頁</a>
                                    <span class="new_page">第 {{ $banned_users->currentPage() }} 頁</span>
                                    <a href="{{ $banned_users->nextPageUrl()."&type=1" }}">下一頁</a>
                                @elseif($banned_users->currentPage() == $banned_users->lastPage())
                                    <a href="{{ $banned_users->previousPageUrl()."&type=1" }}" id="pPage">上一頁</a>
                                    <span class="new_page">第 {{ $banned_users->currentPage() }} 頁</span>
                                    <a>下一頁</a>
                                @else
                                    <a href="{{ $banned_users->previousPageUrl()."&type=1" }}" id="pPage">上一頁</a>
                                    <span class="new_page">第 {{ $banned_users->currentPage() }} 頁</span>
                                    <a href="{{ $banned_users->nextPageUrl()."&type=1" }}" >下一頁</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
<script>
    // 計算瀏覽時間
    var page_id = 'browse';
</script>
@stop