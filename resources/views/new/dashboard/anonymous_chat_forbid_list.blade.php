@extends('new.layouts.website')
@section('style')
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
@endsection


@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="ddt_list">
                    <div class="nn_dontt">
                        <ul>
                            <li id="fs_a" class="nn_dontt_hover" style="color:#ee5472; width: 100%;">本週懲處名單</li>
                        </ul>
                    </div>
                </div>
                <div class="fs_name" id="fs">
                    <div class="fs_title"><h2>本週懲處名單，共{{ $forbid_count }}筆資料</h2></div>
                    <div class="fs_table">
                        <table>
                            <tr class="fs_tb">
                                <th width="25%" style=" border-radius:5px 0 0 5px;">匿名編號</th>
                                <th width="25%">懲處原因</th>
                                <th width="25%">開始日期</th>
                                <th width="25%" style=" border-radius:0 5px 5px 0;">解除時間</th>
                            </tr>
                            @foreach($forbid_users as $row)
                            
                                <tr>
                                    <td><font size="1">{{$row->anonymous}}</font></td>
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
                                {!! $forbid_users->appends(request()->input())->links('pagination::sg-pages2') !!}
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