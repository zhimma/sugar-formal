<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
<style>
    .page>li{
        display: none !important;
    }
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
    .sjright{
        right: 40px;
        position: absolute;
    }
    .shou_but{
         margin-top: 8px !important;
         right: 80px;
         position: absolute;
         z-index: 1;
     }
    @media (max-width: 767px){
        .sjright{
            right: 20px;
        }
        .shou_but{
            margin-top: 8px !important;
            right: 40px !important;
            position: absolute;
        }
    }

</style>
@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70 chat">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span>收件夾</span>
                    <font>Inbox</font>
{{--                    <a href="" class="shou_but">全部刪除</a>--}}
                    <a href="javascript:void(0);" onclick="showChatSet()"><img src="/new/images/ncion_03.png" class="whoicon02 marlr10"></a>
                </div>
                <div class="n_shtab">

{{--                    <h2><span>您目前為高級會員</span>訊息可保存天數：30，可通訊人數:無限</h2>--}}
                    @if($user->isVip())
                        <h2><span>{{$letter_vip}}</span>訊息可保存天數：180，可通訊人數:無限</h2>
                        @else
                        <h2><span>{{$letter_normal_member}}</span>訊息可保存天數：7，可通訊人數:10</h2>
                    @endif
                </div>
                <div class="sjlist_li">
                    <div class="leftsidebar_box">
                        <dl class="system_log">
                            @if($user->engroup==1)
                            @php
                                $exchange_period_name = DB::table('exchange_period_name')->get();
                            @endphp
                            <!--男性介面-->
                            @foreach($exchange_period_name as $row)
                            @if($user->isVip())
                                <span class="exchange_period_delete_{{$row->id}} shou_but">全部刪除</span>
                            @endif
                            <dt class="lebox{{$row->id}} lebox_exchange_period_{{$row->id}}">{{$row->name}}</dt>
                            <dd>
                                <div class="loading warning" id="sjlist_exchange_period_warning_{{$row->id}}"><span class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_exchange_period_{{$row->id}}">
                                </ul>
                                <div class="page page_exchange_period_{{$row->id}} fenye" style="text-align: center;"></div>
                            </dd>
                            @endforeach
                            <!--男性介面-->
                            @endif

                            @if($user->engroup==2)
                            <!--女性介面-->
                            @if(($user->isVip() && ($user->engroup==1 || $user->engroup==2)) || (!$user->isVip() && $user->engroup==2))
                            <span class="vip_delete shou_but">全部刪除</span>
                            @endif
                            <dt class="lebox1">VIP會員</dt>
                            <dd>
                                <div class="loading warning" id="sjlist_vip_warning"><span class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_vip">
                                </ul>
                                <div class="page page_vip fenye" style="text-align: center;"></div>
                            </dd>
                            @if(($user->isVip() && ($user->engroup==1 || $user->engroup==2)) || (!$user->isVip() && $user->engroup==2))
                            <span class="novip_delete shou_but">全部刪除</span>
                            @endif
                            <dt class="lebox2">普通會員</dt>
                            <dd>
                                <div class="loading warning" id="sjlist_novip_warning"><span class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_novip">
                                </ul>
                                <div class="page page_novip fenye" style="text-align: center;"></div>
                            </dd>
                            <!--女性介面 END -->
                            @endif


                            @if(($user->isVip() && ($user->engroup==1 || $user->engroup==2)) || (!$user->isVip() && $user->engroup==2))
                            <span class="alert_delete shou_but">全部刪除</span>
                            @endif
                            <dt class="lebox_alert">警示會員</dt>
                            <dd>
                                <div class="loading warning" id="sjlist_alert_warning"><span class="loading_text">loading</span></div>
                                <ul class="sjlist sjlist_alert">
                                </ul>
                            </dd>

                        </dl>
                    </div>
                </div>

                <input name="rows" type="hidden" id="rows" value="">

                <div class="zixun">
                    <span><input type="radio" name="RadioGroup1" value="7" id="RadioGroup1_0" checked>7天內訊息</span>
                    <span><input type="radio" name="RadioGroup1" value="30" id="RadioGroup1_1">30天內訊息</span>
                    <span><input type="radio" name="RadioGroup1" value="all" id="RadioGroup1_2">全部訊息</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bl bl_tab" id="tab03">
        <div class="bltitle">設定</div>
        <div class="blnr02 ">
            <h2>信息通知</h2>
            <select name="notifmessage" id="notifmessage" class="blinput">
                <option value="收到即通知" @if($user->meta_()->notifmessage=='收到即通知') selected @endif>收到即通知</option>
                <option value="每天通知一次" @if($user->meta_()->notifmessage=='每天通知一次') selected @endif>每天通知一次</option>
                <option value="不通知" @if($user->meta_()->notifmessage=='不通知') selected @endif>不通知</option>
            </select>
            <h2>收信設定</h2>
            <select name="notifhistory" id="notifhistory" class="blinput">
                <option value="顯示普通會員信件" @if($user->meta_()->notifhistory=='顯示普通會員信件') selected @endif>顯示普通會員信件</option>
                <option value="顯示VIP會員信件" @if($user->meta_()->notifhistory=='顯示VIP會員信件') selected @endif>顯示VIP會員信件</option>
                <option value="顯示全部會員信件" @if($user->meta_()->notifhistory=='顯示全部會員信件') selected @endif>顯示全部會員信件</option>
            </select>

            <a class="blbut" href="">更新資料</a>
        </div>
        <a id="" onclick="$('.blbg').click();" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <div class="bl bl_tab" id="show_banned">
        <div class="bltitle banned_name"><span></span></div>
        <div class="n_blnr01 ">
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPost') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="aid" value="{{$user->id}}">
                <input type="hidden" name="uid" value="">
                <textarea name="content" cols="" rows="" class="n_nutext" placeholder=""></textarea>
                <div class="n_bbutton">
                    <button type="submit" class="n_bllbut" style="border-style: none;">送出</button>
                </div>
            </form>
        </div>
        <a id="" onclick="gmBtnNoReload()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
    </div>

    <script>
        var total = 0;//總筆數
        var no_row_li='';
        no_row_li = '<li class="li_no_data"><div class="listicon02 nodata"><img src="/new/images/xj.png" class="list_img"><span>您目前尚無訊息</span></div></li>';
        var userIsVip = '{{ $isVip }}';
        var userGender = '{{ $user->engroup }}';

            //vip
            var Page = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page.row) == 0 ? 1 : Math.ceil(total/Page.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_vip').html(str);
                $('.warning').hide();

                $('.page_vip a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_vip').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page.page = parseInt(Page.page) - 1; break;
                        case 'last': Page.page = parseInt(Page.page) + 1; break;
                        default: Page.page = parseInt($(this).data('p'));
                    }
                    Page.DrawPage(total);

                    date= $('input[name=RadioGroup1]:checked').val();

                    if(date==7){
                        $('.sjlist_vip>.date7.vipMember').slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_vip>.common30.vipMember').slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    }else{
                        $('.sjlist_vip>.vipMember').slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    }

                    $('.sjlist_vip>.li_no_data').remove();

                    if($('.sjlist_vip>li:visible').length==0){
                        $('#sjlist_vip_warning').hide();
                        $('.sjlist_vip').append(no_row_li);
                    }
                });
            }
        };

        var Page_noVip = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_noVip.row) == 0 ? 1 : Math.ceil(total/Page_noVip.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page_noVip.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_noVip.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_noVip.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_noVip.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_noVip.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_novip').html(str);
                $('.warning').hide();

                $('.page_novip a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_novip').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_noVip.page = parseInt(Page_noVip.page) - 1; break;
                        case 'last': Page_noVip.page = parseInt(Page_noVip.page) + 1; break;
                        default: Page_noVip.page = parseInt($(this).data('p'));
                    }
                    Page_noVip.DrawPage(total);
                    date= $('input[name=RadioGroup1]:checked').val();

                    if(date==7) {
                        $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                    }else{
                        $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                    }

                    $('.sjlist_novip>.li_no_data').remove();

                    if($('.sjlist_novip>li:visible').length==0){
                        $('#sjlist_novip_warning').hide();
                        $('.sjlist_novip').append(no_row_li);
                    }
                });
            }
        };

        @php
            $exchange_period_name = DB::table('exchange_period_name')->get();
        @endphp
        @foreach($exchange_period_name as $row)
            var  Page_exchange_period_{{$row->id}}= {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_exchange_period_{{$row->id}}.row) == 0 ? 1 : Math.ceil(total/Page_exchange_period_{{$row->id}}.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                {{--var new_page = Page_exchange_period_{{$row->id}}.page / total_page;--}}
                var current_page = Page_exchange_period_{{$row->id}}.page;
                if(total_page==1){
                    str   = '';
                }else if(Page_exchange_period_{{$row->id}}.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${current_page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_exchange_period_{{$row->id}}.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${current_page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${current_page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }

                $('.page_exchange_period_{{$row->id}}').html(str);
                $('.warning').hide();

                $('.page_exchange_period_{{$row->id}} a.page-link').click(function(){
                    $('.warning').show();
                    $('.sjlist_exchange_period_{{$row->id}}').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_exchange_period_{{$row->id}}.page = parseInt(Page_exchange_period_{{$row->id}}.page) - 1; break;
                        case 'last': Page_exchange_period_{{$row->id}}.page = parseInt(Page_exchange_period_{{$row->id}}.page) + 1; break;
                        default: Page_exchange_period_{{$row->id}}.page = parseInt($(this).data('p'));
                    }
                    Page_exchange_period_{{$row->id}}.DrawPage(total);
                    date= $('input[name=RadioGroup1]:checked').val();

                    if(date==7) {
                        $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                    }else{
                        $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                    }

                    $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();

                    if($('.sjlist_exchange_period_{{$row->id}}>li:visible').length==0){
                        $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                        $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                    }
                });
            }
        };
        @endforeach

        var Page_warned = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page_warned.row) == 0 ? 1 : Math.ceil(total/Page_warned.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;

                if(total_page==1){
                    str   = '';
                }else if(Page_warned.page==1){
                    str =`<a href="javascript:" class="" data-p="next">上一頁</a>
                    <span class="new_page">${Page_warned.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>`;
                }else if(Page_warned.page==total_page){
                    str =`<a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_warned.page}/${total_page}</span>
                    <a href="javascript:" class="" data-p="last">下一頁</a>`;
                }else{
                    str = `
                    <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                    <span class="new_page">${Page_warned.page}/${total_page}</span>
                    <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                `;
                }


                $('.page_warned').html(str);
                $('.warning').hide();
                $('.page_warned a.page-link').click(function(){
                    $('.warning').show();

                    $('.sjlist_alert').children().css('display', 'none');

                    switch($(this).data('p')) {
                        case 'next': Page_warned.page = parseInt(Page_warned.page) - 1; break;
                        case 'last': Page_warned.page = parseInt(Page_warned.page) + 1; break;
                        default: Page_warned.page = parseInt($(this).data('p'));
                    }
                    Page_warned.DrawPage(total);
                    date= $('input[name=RadioGroup1]:checked').val();

                    if(date==7) {
                        $('.sjlist_alert>.date7.novipMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                    }else if(date==30){
                        $('.sjlist_alert>.common30.novipMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                    }else{
                        $('.sjlist_alert>.novipMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                    }

                    $('.sjlist_alert>.li_no_data').remove();

                    if($('.sjlist_alert>li:visible').length==0){
                        $('#sjlist_alert_warning').hide();
                        $('.sjlist_alert').append(no_row_li);
                    }
                });
            }
        };


        // var page = 1;//初始資料
        // var row = 10;//預設產出資料筆數
        //var total = 0;//總筆數
        var date = 7;

        if(window.location.hash) {
            // Fragment exists
            var hash = window.location.hash.substring(1);
            date = hash;
            // alert(hash);
        }
        if(userIsVip == 1){
            date='all';
        }

        function startOfWeek(dt)
        {
            var diff = dt.getDate() - dt.getDay() + (dt.getDay() === 0 ? -6 : 1);
            return new Date(dt.setDate(diff));
        }
        function startOfMonth(dt)
        {
            return new Date(dt.getFullYear(), dt.getMonth(), 1);
        }
        function liContent(pic,user_name,content,created_at,read_n,i,user_id,isVip,show,isWarned,exchange_period){
            var li='';
            var ss =((i+1)>Page.row)?'display:none;':'display:none;';
            var username = '{{$user->name}}';
            var engroup = '{{$user->engroup}}';


            var url = '{{ route("chat2WithUser", ":id") }}';
            url = url.replace(':id', user_id);
            var del_url = '{!! url("/dashboard/chat2/deleterow/:uid/:sid") !!}';

            var sid = '{{$user->id}}';
            del_url = del_url.replace(':uid', sid);
            del_url = del_url.replace(':sid', user_id);
            //${content}
            if(user_id==1049) {
                li += `
                <li class="row_data hy_bg02" style="${ss}" id="${user_id}">
                `;
            }else{
                li += `
                <li class="row_data" style="${ss}" id="${user_id}">
                `;
            }

            if(show==0 && engroup==1){
                li += `
                     <div class="vipOnlyAlert" data-toggle="popover" data-content="${username}您好，普通會員只能看到最先通訊的十位女會員，請至「全部訊息」確認通訊人數是否已超過10人，即可發訊息給${user_name}" style="width: 100%">
                   `;
            }else if(show==0 && engroup==2){
                li += `
                     <div class="vipOnlyAlert" data-toggle="popover" data-content="${username}您好，普通會員只能看到最先通訊的十位男會員，請上傳大頭貼＋三張生活照就可以取得　ＶＩＰ　權限或是到「全部訊息」確認通訊人數是否已超過10人，即可發訊息給${user_name}" style="width: 100%">
                  `;
            }

            li += `<div class="si_bg">`;

            if(show==1) {
                li += `<a href="${url}" target="_self">
                  `;
            }else if(show==0){
                li += `<a href="javascript:void(0)" target="_self">
                  `;
            }
            li +=`
                        <div class="sjpic"><img src="${pic}"></div>
                        <div class="sjleft">
                            <div class="sjtable">${(read_n!=0?`<i class="number">${read_n}</i>`:'')}<span class="ellipsis" style="width: 60%;">${user_name}</span></div>

                  `;
            if(show==1) {
                li += `
                            <span class="box"><font class="ellipsis">${content}</font></span>
                        </div>
                        </a>
                   `;
            }else if(show==0 && engroup==1){
                li += `


                     <font><img src="/new/images/icon_35.png"></font>
                     </div></a>
                   `;
            }else if(show==0 && engroup==2){
                li += `


                     <font><img src="/new/images/icon_35.png"></font>
                     </div></a>
                   `;
            }
            li += `
                        <div class="sjright">
                            <h3>${created_at}</h3>
                            <h4>
                  `;
            if(userIsVip==1) {
                li += `
                        <a href="javascript:void(0)" onclick="block('${user_id}');"><img src="/new/images/del_05.png">封鎖</a>
                      `;
            }
            li +=`
                          <a href="javascript:void(0)" onclick="chk_delete('${del_url}');"><img src="/new/images/del_03.png">刪除</a>
                         </h4>
                        </div>
                    </div>
            `;
            if(show==0){
                li += `
                     </div>
                   `;
            }

            li +=`</li>`;


            return li;
        }



        let dt = new Date();
        let temp_week =  new Date(startOfWeek(dt));
        let temp_month =  new Date(startOfMonth(dt));

        let this_week = temp_week.getFullYear() + '-' + ("0" + (temp_week.getMonth() + 1)).slice(-2) + '-' + ("0" + (temp_week.getDate())).slice(-2);
        let this_month = temp_month.getFullYear() + '-' + ("0" + (temp_month.getMonth() + 1)).slice(-2) + '-' + ("0" + (temp_month.getDate())).slice(-2);

        let before7days = new Date(dt.getTime() - (7 * 24 * 60 * 60 * 1000));
        let before30days = new Date(dt.getTime() - (30 * 24 * 60 * 60 * 1000));

        var this_7daysBefore = before7days.getFullYear() + '-' + ("0" + (before7days.getMonth()+1)).slice(-2) + '-' + ("0" + (before7days.getDate())).slice(-2);
        var this_30daysBefore = before30days.getFullYear() + '-' + ("0" + (before30days.getMonth()+1)).slice(-2) + '-' + ("0" + (before30days.getDate())).slice(-2);

        var counter=1;
        //ajax資料
        function LoadTable(){
            div = '';

            // alert(date);
            $.ajax({
                url: '{{ route('showMessages') }}',
                type: 'POST',
                dataType: 'json',
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                data: {
                    _token:"{{ csrf_token() }}",
                    date : date,
                    uid : '{{ $user->id }}',
                    isVip : '{{ $isVip }}',
                    userAgent: "Agent: " + String(navigator.userAgent) + " Platform: " + String(navigator.platform),
                },
                beforeSend:function(){//表單發送前做的事
                    @if($user->engroup==2)
                        $('.sjlist_vip').html('');
                        $('.sjlist_novip').html('');
                        $('.page_vip').hide();
                        $('.page_novip').hide();
                    @elseif($user->engroup==1)
                        @php
                            $exchange_period_name = DB::table('exchange_period_name')->get();
                        @endphp
                        @foreach($exchange_period_name as $row)
                        $('.sjlist_exchange_period_{{$row->id}}').html('');
                        $('.page_exchange_period_{{$row->id}}').hide();
                        @endforeach
                    @endif

                    $('.sjlist_alert').html('');
                    $('.page_warning').hide();
                    $('.warning').show();
                },
                complete: function () {
                    //alert($('.sjlist_vip>li:visible').length);

                },
                success:function(res){
                    // console.log(res.msg);
                    var li = '';//樣板容器
                    // var p = page;
                    // var data = res.list;        //回傳資料
                    // var data_num = data.length; //資料筆數
                    // page=page+data_num;
                    // //若有資料時
                    //console.log(res.msg);
                    var rr=0;
                    //total=res.msg.length;

                    if(res.msg.length>0){
                        $('#rows').val(res.msg.length);
                    }

                    var hide_vip_counts = 0;
                    hide_vip_counts = $('#rows').val() - 10;

                    $.each(res.msg,function(i,e) {
                       if(e.user_id==1049) {
                           hide_vip_counts = $('#rows').val() - 10 - 1;
                       }
                    });

                    $.each(res.msg,function(i,e) {
                        rr += parseInt(e.read_n);
                        if (userIsVip != 1 && i < hide_vip_counts && hide_vip_counts > 0 ) {
                            if(e.user_id == 1049){
                                hide_vip_counts = hide_vip_counts+1;
                                if (e && e.user_id) li = liContent(e.pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 1,e.isWarned,e.exchange_period);
                            }else {
                                if (e && e.user_id) li = liContent(e.pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 0,e.isWarned,e.exchange_period);
                            }
                        }else {
                            if (e && e.user_id) li = liContent(e.pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 1,e.isWarned,e.exchange_period);
                        }

                        if (typeof e.created_at !== 'undefined') {
                            if (e.created_at.substr(0, 10) >= this_7daysBefore) {
                                if(e.isWarned==1) {
                                    $('.sjlist_alert').append(li).find('.row_data').addClass('date7 alertMember common30');
                                }else if (e.isVip == 1 && userGender==2) {
                                    $('.sjlist_vip').append(li).find('.row_data').addClass('date7 vipMember common30');
                                }else if (e.isVip == 0 && userGender==2) {
                                    $('.sjlist_novip').append(li).find('.row_data').addClass('date7 novipMember common30');
                                }

                                @if($user->engroup==1)
                                    if (userGender==1 && e.user_id == 1049){
                                        $('.sjlist_exchange_period_1').append(li).find('.row_data').addClass('date7 exchange_period_member_1 common30');
                                    }
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)
                                        if (userGender==1 && e.exchange_period=='{{$row->id}}' && e.user_id != 1049 && e.isWarned == 0){
                                            $('.sjlist_exchange_period_{{$row->id}}').append(li).find('.row_data').addClass('date7 exchange_period_member_{{$row->id}} common30');
                                        }
                                    @endforeach
                                @endif

                            }else if (e.created_at != '' && e.created_at.substr(0, 10) >= this_30daysBefore) {
                                if(e.isWarned==1){
                                    $('.sjlist_alert').append(li).find('.row_data').addClass('date30 alertMember common30');
                                }else if (e.isVip == 1 && userGender==2) {
                                    $('.sjlist_vip').append(li).find('.row_data').addClass('date30 vipMember common30');
                                }else if (e.isVip == 0 && userGender==2)  {
                                    $('.sjlist_novip').append(li).find('.row_data').addClass('date30 novipMember common30');
                                }

                                @if($user->engroup==1)
                                    if (userGender==1 && e.user_id == 1049){
                                        $('.sjlist_exchange_period_1').append(li).find('.row_data').addClass('date30 exchange_period_member_1 common30');
                                    }
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)
                                        if (userGender==1 && e.exchange_period=='{{$row->id}}' && e.user_id != 1049 && e.isWarned == 0){
                                            $('.sjlist_exchange_period_{{$row->id}}').append(li).find('.row_data').addClass('date30 exchange_period_member_{{$row->id}} common30');
                                        }
                                    @endforeach
                                @endif

                            } else {
                                if(e.isWarned==1) {
                                    $('.sjlist_alert').append(li).find('.row_data').addClass('dateAll alertMember');
                                }else if (e.isVip == 1 && userGender==2) {
                                    $('.sjlist_vip').append(li).find('.row_data').addClass('dateAll vipMember');
                                }else if (e.isVip == 0 && userGender==2)  {
                                    $('.sjlist_novip').append(li).find('.row_data').addClass('dateAll novipMember');
                                }

                                @if($user->engroup==1)
                                    if (userGender==1 && e.user_id == 1049){
                                        $('.sjlist_exchange_period_1').append(li).find('.row_data').addClass('dateAll exchange_period_member_1');
                                    }
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)
                                        if (userGender==1 && e.exchange_period=='{{$row->id}}' && e.user_id != 1049 && e.isWarned == 0){
                                            $('.sjlist_exchange_period_{{$row->id}}').append(li).find('.row_data').addClass('dateAll exchange_period_member_{{$row->id}}');
                                        }
                                    @endforeach
                                @endif

                            }

                        }
                    });

                    setTimeout(function(){
                        if(date==7){
                            $("input[name*='RadioGroup1'][value='7']").prop("checked", true);
                        }else if(date==30){
                            $("input[name*='RadioGroup1'][value='30']").prop("checked", true);
                        }else if(date=='all'){
                            $("input[name*='RadioGroup1'][value='all']").prop("checked", true);
                        }

                        $('.dateAll').hide();
                        $('.date30').hide();
                        $('.date7').hide();

                        if(userIsVip != 1){
                            // alert(hash);
                            if(!hash){
                                $("input[name*='RadioGroup1'][value='7']").prop("checked", true);
                            }
                            if(hash==7 || !hash) {

                                @if($user->engroup==2)

                                    let vip_counts = $('.date7.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.date7.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.date7.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)

                                    let exchange_period_counts_{{$row->id}} = $('.date7.exchange_period_member_{{$row->id}}').length;
                                    if (exchange_period_counts_{{$row->id}} > 10) {
                                        $('.page_exchange_period_{{$row->id}}').show();
                                    }
                                    Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                    $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif

                                let alert_counts = $('.date7.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                            }else if(hash==30){
                                @if($user->engroup==2)

                                    let vip_counts = $('.common30.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.common30.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.common30.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                        @php
                                            $exchange_period_name = DB::table('exchange_period_name')->get();
                                        @endphp
                                        @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.common30.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                        @endforeach

                                @endif


                                let alert_counts = $('.common30.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_alert').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                            }else if(hash=='all'){
                                @if($user->engroup==2)
                                let vip_counts = $('.vipMember').length;
                                if (vip_counts > 10) {
                                    $('.page_vip').show();
                                }
                                Page.DrawPage(vip_counts);
                                $('.sjlist_vip>.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                let novip_counts = $('.novipMember').length;
                                if (novip_counts > 10) {
                                    $('.page_novip').show();
                                }
                                Page_noVip.DrawPage(novip_counts);
                                $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                        @php
                                            $exchange_period_name = DB::table('exchange_period_name')->get();
                                        @endphp
                                        @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                        @endforeach
                                @endif

                                let alert_counts = $('.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                            }

                        }else{
                            if (date == 7) {
                                $('.row_data').hide();

                                @if($user->engroup==2)
                                    let vip_counts = $('.date7.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.date7.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.date7.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)

                                    let exchange_period_counts_{{$row->id}} = $('.date7.exchange_period_member_{{$row->id}}').length;
                                    if (exchange_period_counts_{{$row->id}} > 10) {
                                        $('.page_exchange_period_{{$row->id}}').show();
                                    }
                                    Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                    $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif

                                let alert_counts = $('.date7.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                            } else if (date == 30) {
                                $('.row_data').hide();

                                @if($user->engroup==2)
                                    let vip_counts = $('.common30.vipMember').length;
                                    if (vip_counts > 10) {
                                        $('.page_vip').show();
                                    }
                                    Page.DrawPage(vip_counts);
                                    $('.sjlist_vip>.common30.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                    let novip_counts = $('.common30.novipMember').length;
                                    if (novip_counts > 10) {
                                        $('.page_novip').show();
                                    }
                                    Page_noVip.DrawPage(novip_counts);
                                    $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.common30.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif

                                let alert_counts = $('.common30.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                            } else {

                                @if($user->engroup==2)
                                let vip_counts = $('.vipMember').length;
                                if (vip_counts > 10) {
                                    $('.page_vip').show();
                                }
                                Page.DrawPage(vip_counts);
                                $('.sjlist_vip>.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                                let novip_counts = $('.novipMember').length;
                                if (novip_counts > 10) {
                                    $('.page_novip').show();
                                }
                                Page_noVip.DrawPage(novip_counts);
                                $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                                @elseif($user->engroup==1)
                                    @php
                                        $exchange_period_name = DB::table('exchange_period_name')->get();
                                    @endphp
                                    @foreach($exchange_period_name as $row)

                                        let exchange_period_counts_{{$row->id}} = $('.exchange_period_member_{{$row->id}}').length;
                                        if (exchange_period_counts_{{$row->id}} > 10) {
                                            $('.page_exchange_period_{{$row->id}}').show();
                                        }
                                        Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                                        $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');

                                    @endforeach
                                @endif

                                let alert_counts = $('.alertMember').length;
                                if (alert_counts > 10) {
                                    $('.page_warned').show();
                                }
                                Page_warned.DrawPage(alert_counts);
                                $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');
                            }
                        }

                        $('.warning').hide();

                        @if($user->engroup==2)
                            $('.sjlist_vip>.li_no_data').remove();
                            $('.sjlist_novip>.li_no_data').remove();
                            $('.sjlist_alert>.li_no_data').remove();
                            if ($('.sjlist_vip>li:visible').length == 0) {
                                $('#sjlist_vip_warning').hide();
                                $('.sjlist_vip').append(no_row_li);
                            }
                            if ($('.sjlist_novip>li:visible').length == 0) {
                                $('#sjlist_novip_warning').hide();
                                $('.sjlist_novip').append(no_row_li);
                            }
                            if ($('.sjlist_alert>li:visible').length == 0) {
                                $('#sjlist_alert_warning').hide();
                                $('.sjlist_alert').append(no_row_li);
                            }
                        @elseif($user->engroup==1)
                            @php
                                $exchange_period_name = DB::table('exchange_period_name')->get();
                            @endphp
                            @foreach($exchange_period_name as $row)
                            $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();
                            if ($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0) {
                                $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                                $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                            }
                            @endforeach
                        @endif
                    }, 300);

                    $('div[data-toggle="popover"]').popover({
                        animated: 'fade',
                        placement: 'bottom',
                        trigger: 'hover',
                        html: true,
                        content: function () { return '<h4' + $(this).data('content') + '</h4>'; }
                    });

                }
            })
                .done(function() {
                    // if(page-1>=total){
                    //     $('.listMoreBtn').attr('disabled', 'true').removeClass('cursor-pointer').html('NO MORE');
                    // }else{
                    //     $('.listMoreBtn').removeAttr('disabled').addClass('cursor-pointer').html('MORE');
                    // }
                    // check li rows

                });

        }

        LoadTable();

        $('input[name=RadioGroup1]').on('click', function(event) {

            // $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('off');
            // $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('on');
            {{--$('.lebox1').removeClass('off');--}}
            {{--$('.lebox1').removeClass('on');--}}
            {{--// $('.lebox1').toggleClass('on');--}}

            {{--@if($user->engroup==2)--}}
            {{--$('.lebox1').toggleClass('on');--}}
            {{--@elseif($user->engroup==1)--}}
            {{--$('.lebox1').toggleClass('off');--}}
            {{--@endif--}}
            {{--$('.lebox2,.lebox3,.lebox_alert').removeClass('off');--}}
            {{--$('.lebox2,.lebox3,.lebox_alert').removeClass('on');--}}
            {{--$('.lebox2,.lebox3,.lebox_alert').toggleClass('off');--}}
            {{--$('.lebox2,.lebox3,.lebox_alert').next('dd').show();--}}
            {{--$('.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle();--}}

            @if($user->engroup==2)
            $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('on off');
            $('.lebox1').toggleClass('on');
            $('.lebox2,.lebox3,.lebox_alert').toggleClass('off');
            // $('.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");
            @elseif($user->engroup==1)
            $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('on off');
            $('.lebox1,.lebox2,.lebox3,.lebox_alert').toggleClass('off');
            // $('.lebox1,.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");
            @endif


            date= $('input[name=RadioGroup1]:checked').val();
            window.location.hash = '#'+ date;
            @if($user->engroup==1)
                    @php
                        $exchange_period_name = DB::table('exchange_period_name')->get();
                    @endphp
                    @foreach($exchange_period_name as $row)
                        Page_exchange_period_{{$row->id}}.page=1;
                        $('.page_exchange_period_{{$row->id}}').hide();
                    @endforeach
            @elseif($user->engroup==2)
                Page.page=1;
                Page_noVip.page=1;
                $('.page_vip').hide();
                $('.page_novip').hide();
            @endif

            $('.warning').show();
            if(userIsVip==1){
                LoadTable();
            }else{

                 if (date == 7) {
                    $('.row_data').hide();
                 @if($user->engroup==2)

                        let vip_counts = $('.date7.vipMember').length;
                        if (vip_counts > 10) {
                            $('.page_vip').show();
                        }
                        Page.DrawPage(vip_counts);
                        $('.sjlist_vip>.date7.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                        let novip_counts = $('.date7.novipMember').length;
                        if (novip_counts > 10) {
                            $('.page_novip').show();
                        }
                        Page_noVip.DrawPage(novip_counts);
                        $('.sjlist_novip>.date7.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                 @elseif($user->engroup==1)
                         @php
                             $exchange_period_name = DB::table('exchange_period_name')->get();
                         @endphp
                         @foreach($exchange_period_name as $row)
                            let exchange_period_counts_{{$row->id}} = $('.date7.exchange_period_member_{{$row->id}}').length;
                            if (exchange_period_counts_{{$row->id}} > 10) {
                                $('.page_exchange_period_{{$row->id}}').show();
                            }
                            Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                            $('.sjlist_exchange_period_{{$row->id}}>.date7.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                         @endforeach

                 @endif

                    let alert_counts = $('.date7.alertMember').length;
                    if (alert_counts > 10) {
                        $('.page_warned').show();
                    }
                    Page_warned.DrawPage(alert_counts);
                    $('.sjlist_alert>.date7.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                 } else if (date == 30) {
                    $('.row_data').hide();

                     @if($user->engroup==2)
                        let vip_counts = $('.common30.vipMember').length;
                        if (vip_counts > 10) {
                            $('.page_vip').show();
                        }
                        Page.DrawPage(vip_counts);
                        $('.sjlist_vip>.common30.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                        let novip_counts = $('.common30.novipMember').length;
                        if (novip_counts > 10) {
                            $('.page_novip').show();
                        }
                        Page_noVip.DrawPage(novip_counts);
                        $('.sjlist_novip>.common30.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');
                     @elseif($user->engroup==1)
                         @php
                             $exchange_period_name = DB::table('exchange_period_name')->get();
                         @endphp
                         @foreach($exchange_period_name as $row)
                             let exchange_period_counts_{{$row->id}} = $('.common30.exchange_period_member_{{$row->id}}').length;
                             if (exchange_period_counts_{{$row->id}} > 10) {
                                 $('.page_exchange_period_{{$row->id}}').show();
                             }
                             Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                             $('.sjlist_exchange_period_{{$row->id}}>.common30.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                         @endforeach
                     @endif

                    let alert_counts = $('.common30.alertMember').length;
                    if (alert_counts > 10) {
                        $('.page_warned').show();
                    }
                    Page_warned.DrawPage(alert_counts);
                    $('.sjlist_alert>.common30.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                 }else{
                     @if($user->engroup==2)
                         let vip_counts = $('.vipMember').length;
                         if (vip_counts > 10) {
                             $('.page_vip').show();
                         }
                         Page.DrawPage(vip_counts);
                         $('.sjlist_vip>.vipMember').slice((Page.page - 1) * Page.row, Page.page * Page.row).css('display', '');

                         let novip_counts = $('.novipMember').length;
                         if (novip_counts > 10) {
                             $('.page_novip').show();
                         }
                         Page_noVip.DrawPage(novip_counts);
                         $('.sjlist_novip>.novipMember').slice((Page_noVip.page - 1) * Page_noVip.row, Page_noVip.page * Page_noVip.row).css('display', '');

                     @elseif($user->engroup==1)
                         @php
                             $exchange_period_name = DB::table('exchange_period_name')->get();
                         @endphp
                         @foreach($exchange_period_name as $row)
                             let exchange_period_counts_{{$row->id}} = $('.exchange_period_member_{{$row->id}}').length;
                             if (exchange_period_counts_{{$row->id}} > 10) {
                                 $('.page_exchange_period_{{$row->id}}').show();
                             }
                             Page_exchange_period_{{$row->id}}.DrawPage(exchange_period_counts_{{$row->id}});
                             $('.sjlist_exchange_period_{{$row->id}}>.exchange_period_member_{{$row->id}}').slice((Page_exchange_period_{{$row->id}}.page - 1) * Page_exchange_period_{{$row->id}}.row, Page_exchange_period_{{$row->id}}.page * Page_exchange_period_{{$row->id}}.row).css('display', '');
                         @endforeach
                     @endif
                     let alert_counts = $('.alertMember').length;
                     if (alert_counts > 10) {
                         $('.page_warned').show();
                     }
                     Page_warned.DrawPage(alert_counts);
                     $('.sjlist_alert>.alertMember').slice((Page_warned.page - 1) * Page_warned.row, Page_warned.page * Page_warned.row).css('display', '');

                 }
                    $('.warning').hide();

                    @if($user->engroup==2)
                        $('.sjlist_vip>.li_no_data').remove();
                        $('.sjlist_novip>.li_no_data').remove();
                        $('.sjlist_alert>.li_no_data').remove();
                        if ($('.sjlist_vip>li:visible').length == 0) {
                            $('#sjlist_vip_warning').hide();
                            $('.sjlist_vip').append(no_row_li);
                        }
                        if ($('.sjlist_novip>li:visible').length == 0) {
                            $('#sjlist_novip_warning').hide();
                            $('.sjlist_novip').append(no_row_li);
                        }
                        if ($('.sjlist_alert>li:visible').length == 0) {
                            $('#sjlist_alert_warning').hide();
                            $('.sjlist_alert').append(no_row_li);
                        }
                    @elseif($user->engroup==1)
                        @php
                            $exchange_period_name = DB::table('exchange_period_name')->get();
                        @endphp
                        @foreach($exchange_period_name as $row)
                            $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();
                            if ($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0) {
                                $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                                $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                            }
                        @endforeach
                    @endif
            }

        });

        function chk_delete(url) {
            c4('確定要刪除嗎?');
            $(".n_left").on('click', function () {
                $("#tab04").hide();
                show_message('刪除成功');
                window.location=url;
            });
            return false;
        }

        function block(sid){
            c4('確定要封鎖嗎?');
            var sid = sid;
            $(".n_left").on('click', function() {
                url=
                $.post('{{ route('postBlockAJAX') }}', {
                    uid: '{{ $user->id }}',
                    sid: sid,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    $("#tab04").hide();
                    show_message('封鎖成功');
                    window.location.reload();
                });
            });
            return false;
        }

            function banned(sid,name){
                $("input[name='uid']").val(sid);
                $(".banned_name").append("<span>" + name + "</span>")
                $(".announce_bg").show();
                $("#show_banned").show();
            }

        $('.vip_delete').on('click', function() {

            var IDs = [];
            $(".sjlist_vip").find("li").each(function(){ IDs.push(this.id); });
            // alert(IDs);
            // alert($('.sjlist_vip.row_data>li:visible').length);
            if($.trim(IDs) !== '') {
                c4('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c2('沒有可刪除資料');
            }
        });
        $('.novip_delete').on('click', function() {
            // c4('確定要全部刪除嗎?');
            var IDs = [];
            $(".sjlist_novip").find("li").each(function(){ IDs.push(this.id); });

            // alert(IDs);
            if($.trim(IDs) !== '') {
                c4('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c2('沒有可刪除資料');
            }
        });
        $('.alert_delete').on('click', function() {
            // c4('確定要全部刪除嗎?');
            var IDs = [];
            $(".sjlist_alert").find("li").each(function(){ IDs.push(this.id); });

            if($.trim(IDs) !== '') {
                c4('確定要全部刪除嗎?');
                deleteRowAll(IDs);
            }else{
                c2('沒有可刪除資料');
            }
        });

        @php
        $exchange_period_name = DB::table('exchange_period_name')->get();
        @endphp
        @foreach($exchange_period_name as $row)
            $('.exchange_period_delete_{{$row->id}}').on('click', function() {
                // c4('確定要全部刪除嗎?');
                var IDs = [];
                $(".sjlist_exchange_period_{{$row->id}}").find("li").each(function(){ IDs.push(this.id); });

                // alert(IDs);
                if($.trim(IDs) !== '') {
                    c4('確定要全部刪除嗎?');
                    deleteRowAll(IDs);
                }else{
                    c2('沒有可刪除資料');
                }
            });
        @endforeach

        function deleteRowAll(IDs) {
            var del_url = '{!! url("/dashboard/chat2/deleterowall/:uid/:sid") !!}';

            var sid = '{{$user->id}}';
            del_url = del_url.replace(':uid', IDs);
            del_url = del_url.replace(':sid', sid);
            $(".n_left").on('click', function() {

                $("#tab04").hide();
                show_message('刪除成功');
                window.location=del_url;
            });
            return false;

        }

            function showChatSet() {
                $(".blbg").show();
                $("#tab03").show();
            }

    </script>

@stop

@section('javascript')


<style>
    .box {
        width: 100%;
    }
    .ellipsis {
        overflow:hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .comt{
        top: 8px;
        position: relative;
    }
    .popover  {
        background: #e2e8ff!important;
        color: #6783c7;
    }
    .popover.right .arrow:after {
        border-right-color:#e2e8ff;
    }
    .popover.bottom .arrow:after {
        border-bottom-color:#e2e8ff;
    }
</style>

    <script>
        $('.blbut').on('click', function() {
            $("#tab03").hide();
            $.post('{{ route('chatSet') }}', {
                uid: '{{ $user->id }}',
                notifmessage:$('#notifmessage').val(),
                notifhistory:$('#notifhistory').val(),
                _token: '{{ csrf_token() }}'
            }, function (data) {
                //$("#tab03").hide();
                show_message('資料更新成功');
                //window.location.reload();
            });
        });

        // $('.lebox1,.lebox2,.lebox3').toggleClass('on');

        // $('.lebox1').removeClass('off');
        // $('.lebox1').removeClass('on');

        $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('off');
        $('.lebox1,.lebox2,.lebox3,.lebox_alert').removeClass('on');

        @if($user->engroup==2)
        $('.lebox1').toggleClass('on');
        $('.lebox2,.lebox3,.lebox_alert').toggleClass('off');
        $('.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");
        @elseif($user->engroup==1)
        $('.lebox1,.lebox2,.lebox3,.lebox_alert').toggleClass('off');
        $('.lebox1,.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");
        @endif
        // $('.lebox1,.lebox2,.lebox3,.lebox_alert').toggleClass('off');
        // $(".leftsidebar_box dd").show();
        // $('.lebox2,.lebox3,.lebox_alert').next('dd').slideToggle("slow");
        //
        //
        $('.lebox1,.lebox2,.lebox3,.lebox_alert').click(function(e) {
            if ($(this).hasClass('off')) {
                $(this).removeClass('off');
                $(this).toggleClass('on');
            }else if($(this).hasClass('on')){
                $(this).removeClass('on');
                $(this).toggleClass('off');
            }

            $(this).next('dd').slideToggle("slow");
            @if($user->engroup==2)
            $('.sjlist_vip>.li_no_data').remove();
            $('.sjlist_novip>.li_no_data').remove();

            if ($('.sjlist_vip>li:visible').length == 0) {
                $('#sjlist_vip_warning').hide();
                $('.sjlist_vip').append(no_row_li);
            }
            if ($('.sjlist_novip>li:visible').length == 0) {
                $('#sjlist_novip_warning').hide();
                $('.sjlist_novip').append(no_row_li);
            }
            @elseif($user->engroup==1)
                @php
                    $exchange_period_name = DB::table('exchange_period_name')->get();
                @endphp
                @foreach($exchange_period_name as $row)
                $('.sjlist_exchange_period_{{$row->id}}>.li_no_data').remove();
                if ($('.sjlist_exchange_period_{{$row->id}}>li:visible').length == 0) {
                    $('#sjlist_exchange_period_warning_{{$row->id}}').hide();
                    $('.sjlist_exchange_period_{{$row->id}}').append(no_row_li);
                }
                @endforeach
            @endif

            $('.sjlist_alert>.li_no_data').remove();
            if ($('.sjlist_alert>li:visible').length == 0) {
                $('#sjlist_alert_warning').hide();
                $('.sjlist_alert').append(no_row_li);
            }else{
                if($(this).hasClass('on') && $(this).hasClass('lebox_alert')){
                    c3('此為警示會員，要與此區會員交流請務必小心。');
                }
            }
        });

    </script>
@stop