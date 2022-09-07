@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <style>
            th {text-align:center;}
            a, a:visited, a:hover, a:active {
                    /*text-decoration: none;*/
                    color: inherit;
            } 
            tr.female_row td {color:red !important;}
            tr.male_row td {color:blue !important;}
            
            body table.table-hover.table tr.isClosed {background-color:#C9C9C9 !important;}
            tr.isClosed th.col-most, tr.isClosed td.col-most {background-color:#C9C9C9 !important;}

            body table.table-hover tr.isWarned {background-color:#B0FFB1;}
            tr.isWarned th.col-most,tr.isWarned td.col-most {background-color:#B0FFB1 !important;}
            
            table.table-hover tr.banned {background-color:#FDFF8C;} 
            table.table-hover tr.implicitlyBanned {background-color:#FDFF8C;}
            tr.banned th.col-most,tr.implicitlyBanned th.col-most,tr.banned td.col-most,tr.implicitlyBanned td.col-most {background-color:#FDFF8C !important;}
            
            tr.isClosedByAdmin {background-color:#969696}
            tr.isClosedByAdmin th.col-1st,tr.isClosedByAdmin th.col-2nd,tr.isClosedByAdmin th.col-3rd,tr.isClosedByAdmin th.col-most,tr.isClosedByAdmin td.col-most,tr.isClosedByAdmin td.col-1st,tr.isClosedByAdmin td.col-2nd,tr.isClosedByAdmin td.col-3rd,tr.isClosedByAdmin td.col-most {background-color:#969696 !important;}	
            
            tr.banned th.col-1st,tr.implicitlyBanned th.col-1st,tr.banned th.col-2nd,tr.implicitlyBanned th.col-2nd,tr.banned th.col-3rd,tr.implicitlyBanned th.col-3rd,tr.banned td.col-1st,tr.implicitlyBanned td.col-1st,tr.banned td.col-2nd,tr.implicitlyBanned td.col-2nd,tr.banned td.col-3rd,tr.implicitlyBanned td.col-3rd {background-color:#FDFF8C !important;}
            
            tr.isClosedByAdmin:not(.banned):not(.implicitlyBanned) th.col-3rd,tr.isClosedByAdmin:not(.isClosed) th.col-3rd,tr.isClosedByAdmin:not(.isWarned) th.col-3rd   ,tr.isClosedByAdmin:not(.banned):not(.implicitlyBanned) td.col-3rd,tr.isClosedByAdmin:not(.isClosed) td.col-3rd,tr.isClosedByAdmin:not(.isWarned) td.col-3rd {background-color:#969696 !important;}
            tr.isWarned:not(.isClosedByAdmin):not(.banned):not(.implicitlyBanned) th.col-3rd,    tr.isWarned:not(.isClosedByAdmin):not(.banned):not(.implicitlyBanned) td.col-3rd {background-color:#B0FFB1 !important;}
            
            tr.isWarned th.col-1st ,tr.isWarned th.col-2nd,     tr.isWarned td.col-1st ,tr.isWarned td.col-2nd {background-color:#B0FFB1 !important;}
            
            tr.banned.isWarned:not(.isClosed) th.col-2nd,tr.implicitlyBanned.isWarned:not(.isClosed) th.col-2nd,     tr.banned.isWarned:not(.isClosed) td.col-2nd,tr.implicitlyBanned.isWarned:not(.isClosed) td.col-2nd{background-color:#FDFF8C !important;}
            tr.isClosedByAdmin.isWarned:not(.isClosed):not(.banned):not(.implicitlyBanned) th.col-2nd,      tr.isClosedByAdmin.isWarned:not(.isClosed):not(.banned):not(.implicitlyBanned) td.col-2nd {background-color:#969696 !important;}
            tr.isClosedByAdmin.banned:not(.isClosed):not(.isWarned) th.col-2nd,tr.isClosedByAdmin.implicitlyBanned:not(.isClosed):not(.isWarned) th.col-2nd,     tr.isClosedByAdmin.banned:not(.isClosed):not(.isWarned) td.col-2nd,tr.isClosedByAdmin.implicitlyBanned:not(.isClosed):not(.isWarned) td.col-2nd {background-color:#969696 !important;}
            
            tr.isClosed th.col-1st,     tr.isClosed td.col-1st  {background-color:#C9C9C9 !important;}	            
        
            .table-hover > tbody > tr:hover,.table-hover > tbody > tr:hover > td {background-color:rgba(0, 0, 0, .075) !important;}
            tr.banned.isWarned:not(.isClosed):hover th.col-2nd,tr.implicitlyBanned.isWarned:not(.isClosed):hover th.col-2nd,     tr.banned.isWarned:not(.isClosed):hover td.col-2nd,tr.implicitlyBanned.isWarned:not(.isClosed):hover td.col-2nd{background-color:rgba(0, 0, 0, .075) !important;}
            tr.isClosedByAdmin.isWarned:not(.isClosed):not(.banned):not(.implicitlyBanned):hover th.col-2nd,      tr.isClosedByAdmin.isWarned:not(.isClosed):not(.banned):not(.implicitlyBanned):hover td.col-2nd {background-color:rgba(0, 0, 0, .075) !important;}
            tr.isClosedByAdmin.banned:not(.isClosed):not(.isWarned):hover th.col-2nd,tr.isClosedByAdmin.implicitlyBanned:not(.isClosed):not(.isWarned):hover th.col-2nd,     tr.isClosedByAdmin.banned:not(.isClosed):not(.isWarned):hover td.col-2nd,tr.isClosedByAdmin.implicitlyBanned:not(.isClosed):not(.isWarned):hover td.col-2nd {background-color:rgba(0, 0, 0, .075) !important;}
        
            tr.isClosedByAdmin:not(.banned):not(.implicitlyBanned):hover th.col-3rd,tr.isClosedByAdmin:not(.isClosed):hover th.col-3rd,tr.isClosedByAdmin:not(.isWarned):hover th.col-3rd   ,tr.isClosedByAdmin:not(.banned):not(.implicitlyBanned):hover td.col-3rd,tr.isClosedByAdmin:not(.isClosed):hover td.col-3rd,tr.isClosedByAdmin:not(.isWarned):hover td.col-3rd {background-color:rgba(0, 0, 0, .075) !important;}
            tr.isWarned:not(.isClosedByAdmin):not(.banned):not(.implicitlyBanned):hover th.col-3rd,    tr.isWarned:not(.isClosedByAdmin):not(.banned):not(.implicitlyBanned):hover td.col-3rd {background-color:rgba(0, 0, 0, .075) !important;}
            
            tr.isWarned:hover th.col-1st ,tr.isWarned:hover th.col-2nd,     tr.isWarned:hover td.col-1st ,tr.isWarned:hover td.col-2nd {background-color:rgba(0, 0, 0, .075) !important;}        
        
            #setting_empty_page_name_container {margin-bottom:10px;text-align:right;}
        </style>
        <h1>頁面停留時間</h1>
        <br>
        <div id="setting_empty_page_name_container"><a href="{{ route('admin/stay_online_record_page_name_view') }}" class='new text-white btn btn-success'>空白頁面名稱設定</a></div>           
            
        <table id="table_userLogin_log" class="table table-hover table-bordered">
            @foreach($user_online_record as $key => $record)
                @if(!$record->user) 
                    @continue
                @endif    
                <tr>
                    <td>
                        <span id="btn_showDetail_{{ $record->user['id'] }}" class="btn_showLogUser btn btn-primary" data-sectionName="showDetail_{{ $record->user['id'] }}">+</span>
                        <a href="/admin/users/advInfo/{{ $record->user['id'] }}" target="_blank"><span>帳號：{{  $record->user['name'] }}</span></a>
                    <table>
                            <tr class="showLog" id="showDetail_{{ $record->user['id'] }}">
                                <td>
                                    <table class="table table-bordered  table-hover" style="display: block; max-height: 500px; overflow-x: scroll;">
                                        <thead>
                                        <tr class="info">
                                            <th >網址</th>
                                            <th width="10%">頁面名稱</th>
                                            <th width="5%">ip</th>
                                            <th width="45%">User Agent</th>
                                            <th width="5%">停留時間(秒)</th>
                                            <th width="10%">開始時間</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($record->user->stay_online_record_only_page->sortByDesc('created_at') as $record)
                                            <tr  class="{{$record->user->banned?'banned':''}} 
                                                        {{$record->user->implicitlyBanned?'implicitlyBanned':''}} 
                                                        {{(isset($record->user->user_meta->isWarned) && $record->user->user_meta->isWarned) || $record->user->aw_relation?'isWarned':''}}
                                                        {{$record->user->accountStatus===0?'isClosed':''}}
                                                        {{$record->user->account_status_admin===0?'isClosedByAdmin':''}}
                                                        {{$record->user->engroup == 1 ? 'male_row' : 'female_row'}}
                                            ">
                                                <td class="col-1st">{{ $record['url'] }}</td>
                                                <td class="col-2nd">{!!$record->page_name && $record->page_name->name? $record->page_name->name:($record->title?:'<div style="width:100%;text-align:center;"><a class="text-white btn btn-primary" href="'.route('admin/stay_online_record_page_name_switch',['url'=>$record->url,'rtn'=>'record']).'">設定<a></div>')!!}</td>
                                                <td class="col-3rd" nowrap>{{$record->ip}}</td>
                                                <td class="col-most">{{$record->userAgent}}</td>
                                                <td class="col-most" nowrap>{{$record->stay_online_time}} 秒</td>
                                                <td class="col-most">{{$record->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
            @if(count($user_online_record)==0)
                目前暫時無資料
            @endif
        </table>
        <script>
        $('.showLog').hide();
        $('.btn_showLogUser').click(function(){
            var sectionName =$(this).attr('data-sectionName');

            if( $('#'+sectionName).css('display')=='none'){
                $('#'+sectionName).show();
                $('#btn_'+sectionName).text('-');
            }else{
                $('#'+sectionName).hide();
                $('#btn_'+sectionName).text('+');
            }
        });        
        
        </script>
            
            
            
    </body>
@stop