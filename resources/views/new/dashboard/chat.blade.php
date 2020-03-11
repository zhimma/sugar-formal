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
                    <font>inbox</font>
                    <a href="" class="shou_but">全部刪除</a>
                    <a href="javascript:void(0);" onclick="showChatSet()"><img src="/new/images/ncion_03.png" class="whoicon02 marlr10"></a>
                </div>
                <div class="n_shtab">

{{--                    <h2><span>您目前為高級會員</span>訊息可保存天數：30，可通訊人數:無限</h2>--}}
                    @if($user->isVip())
                        <h2><span>您目前為VIP會員</span>訊息可保存天數：180，可通訊人數:無限</h2>
                        @else
                        <h2><span>您目前為普通會員</span>訊息可保存天數：7，可通訊人數:10</h2>
                    @endif
                </div>
                <div class="sjlist_li">
                    <div class="leftsidebar_box">
                        <dl class="system_log">
                            <dt class="lebox1">VIP會員</dt>
                            <dd>
                                <p style="width: 20%;margin: 0 auto;" class="warning" id="sjlist_vip_warning">
                                    <img src="/new/images/Spin-1s-75px.svg">
                                </p>
                                <ul class="sjlist sjlist_vip">
                                </ul>
                            </dd>
                            <dt class="lebox2">普通會員</dt>
                            <dd>
                                <p style="width: 20%;margin: 0 auto;" class="warning" id="sjlist_novip_warning">
                                    <img src="/new/images/Spin-1s-75px.svg">
                                </p>
                                <ul class="sjlist sjlist_novip">
                                </ul>
                            </dd>
                            <dt class="lebox3">警示會員</dt>
                            <dd>
                                <p style="width: 20%;margin: 0 auto;" class="warning" id="sjlist_alert_warning">
                                    <img src="/new/images/Spin-1s-75px.svg">
                                </p>
                                <ul class="sjlist sjlist_alert">
                                </ul>
                            </dd>
                        </dl>
                    </div>
{{--                <div class="sjlist">--}}
{{--                    <ul>--}}

{{--                    </ul>--}}
{{--                    <p style="color:red; font-weight: bold; display: none;margin-left: 20px;" id="warning">載入中，請稍候</p>--}}
{{--                    <p style="width: 20%;margin: 0 auto; display: none;" id="warning">--}}
{{--                        <img src="/new/images/Spin-1s-75px.svg">--}}
{{--                    </p>--}}

{{--                    <div class="nodata" style="display: none;">--}}
{{--                        <div class="fengsicon"><img src="/new/images/chatnodata.png" class="feng_img"><span>您目前尚無訊息</span></div>--}}
{{--                    </div>--}}


                </div>
                <div class="page" style="text-align: center;"></div>
                <input name="rows" type="hidden" id="rows" value="">
                {{--                    <div class="fenye">--}}
                {{--                        <!-- <a href="javascript:" class="page-link" data-p="next">上一頁</a>--}}
                {{--                        <a href="javascript:" class="page-link" data-p="last">下一頁</a> -->--}}
                {{--                    </div>--}}
                <div class="zixun">
                    <span><input type="radio" name="RadioGroup1" value="7" id="RadioGroup1_0" checked>本周訊息</span>
                    <span><input type="radio" name="RadioGroup1" value="30" id="RadioGroup1_1">本月訊息</span>
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
                <textarea name="content" cols="" rows="" class="n_nutext" placeholder="請輸入檢舉理由"></textarea>
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
            var Page = {
            page : 1,
            row  : 10,
            DrawPage:function(total){
                var total_page  = Math.ceil(total/Page.row) == 0 ? 1 : Math.ceil(total/Page.row);
                var span_u      = 0;
                var str         = '';
                var i,active,prev_active,last_active;
                // if(total_page==1){
                //     str   = '';
                // }else if(Page.page==1){
                //     str ='<a href="javascript:" class="page-link" data-p="last">下一頁</a>';
                // }else if(Page.page==total_page){
                //     str ='<a href="javascript:" class="page-link" data-p="next">上一頁</a>';
                // }else{
                //     str = `
                //         <a href="javascript:" class="page-link" data-p="next">上一頁</a>
                //         <a href="javascript:" class="page-link" data-p="last">下一頁</a>
                //     `;
                // }
                // $('.fenye').html(str);
                // $('.fenye a.page-link').click(function(){
                //     $('.sjlist>ul').children().css('display', 'none');
                //     //if ($(this).data('p') == Page.page) return false;
                //     switch($(this).data('p')) {
                //         case 'next': Page.page = parseInt(Page.page) - 1; break;
                //         case 'last': Page.page = parseInt(Page.page) + 1; break;
                //         //default: Page.page = parseInt($(this).data('p'));
                //     }
                //     Page.DrawPage(total);
                //     $('.sjlist>ul').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                // });
                //alert(total_page);
                if(Page.page>1){
                    prev_active = '';
                    str = `
                        <ul class="pagination">
                        <li class="` + prev_active + `">
                            <a href="javascript:" class="page-link" data-p="next">&laquo;</a>
                        </li>
                    `;
                }else{
                    prev_active = 'disabled sg-pages-disabled';
                    str = `
                        <ul class="pagination">
                        <li class="` + prev_active + `">
                            <a href="javascript:">&laquo;</a>
                        </li>
                    `;
                }
                    // str = `
                    //     <ul class="pagination">
                    //     <li class="` + prev_active + `">
                    //         <a href="javascript:" class="page-link" data-p="next">&laquo;</a>
                    //     </li>
                    // `;
                    for(i=1;i<=total_page;i++) {
                        if(i==Page.page){
                            active = 'active sg-pages-active'
                        }else{
                            active = ''
                        }
                        str += `<li class="` + active + `">
                            <a href="javascript:" class="page-link" data-p="` + i + `">` + i + `</a>
                            </li>
                            `;
                    }
                if(Page.page==total_page){
                    last_active = 'disabled sg-pages-disabled';
                    str += `
                           <li class="` + last_active + `">
                                <a href="javascript:">&raquo;</a>
                            </li>
                           </ul>
                          `;
                }else{
                    last_active = '';
                    str += `
                           <li class="` + last_active + `">
                                <a href="javascript:" class="page-link" data-p="last">&raquo;</a>
                            </li>
                           </ul>
                          `;
                }
                         // str += `
                         //   <li class="` + last_active + `">
                         //        <a href="javascript:" class="page-link" data-p="last">&raquo;</a>
                         //    </li>
                         //   </ul>
                         //  `;
                $('.page').html(str);
                $('.warning').hide();
                // if(total_page<=1){
                //     $('.page').hide();
                // }
                $('.page a.page-link').click(function(){
                    $('.warning').show();

                    $('.sjlist').children().css('display', 'none');
                    //if ($(this).data('p') == Page.page) return false;
                    switch($(this).data('p')) {
                        case 'next': Page.page = parseInt(Page.page) - 1; break;
                        case 'last': Page.page = parseInt(Page.page) + 1; break;
                        default: Page.page = parseInt($(this).data('p'));
                    }
                    Page.DrawPage($('#rows').val());
                    $('.sjlist').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                    //check li rows
                    //alert($('.sjlist_vip>li').length);
                    $('.sjlist_vip>.li_no_data').remove();
                    $('.sjlist_novip>.li_no_data').remove();
                    $('.sjlist_alert>.li_no_data').remove();
                    if($('.sjlist_vip>li:visible').length==0){
                        $('#sjlist_vip_warning').hide();
                        $('.sjlist_vip>.li_no_data').remove();
                        $('.sjlist_vip').append(no_row_li);
                    }
                    if($('.sjlist_novip>li:visible').length==0){
                        $('#sjlist_novip_warning').hide();
                        $('.sjlist_novip>.li_no_data').remove();
                        $('.sjlist_novip').append(no_row_li);
                    }
                    if($('.sjlist_alert>li:visible').length==0){
                        $('#sjlist_alert_warning').hide();
                        $('.sjlist_alert>.li_no_data').remove();
                        $('.sjlist_alert').append(no_row_li);
                    }
                });
            }
        };


        // var page = 1;//初始資料
        // var row = 10;//預設產出資料筆數
        //var total = 0;//總筆數
        var date=7;


        function liContent(pic,user_name,content,created_at,read_n,i,user_id,isVip,show,isAdmin){
            var li='';
            var ss =((i+1)>Page.row)?'display:none;':'display:none;';


            var url = '{{ route("chat2WithUser", ":id") }}';
            url = url.replace(':id', user_id);
            var del_url = '{!! url("/dashboard/chat2/deleterow/:uid/:sid") !!}';

            var sid = '{{$user->id}}';
            del_url = del_url.replace(':uid', sid);
            del_url = del_url.replace(':sid', user_id);
            //${content}
            if(user_id==1049) {
                li += `
                <li class="hy_bg02" style="${ss}">
                    <div class="si_bg">
                `;
            }else{
                li += `
                <li style="${ss}">
                    <div class="si_bg">
                `;
            }
            if(show==1) {
                li += `
                        <a href="${url}" target="_self">
                  `;
            }else if(show==0){
                li += `
                        <a href="javascript:void(0)" target="_self">
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
                   `;
            }else if(show==0){
                li += `
                            <font><img src="/new/images/icon_35.png"></font>
                   `;
            }
            li += `
                        </div>
                        </a>
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
                </li>
            `;
            return li;
        }

        var counter=1;
        //ajax資料
        function LoadTable(){
            div = '';

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
                    $('.sjlist_vip').html('');
                    $('.sjlist_novip').html('');
                    $('.sjlist_alert').html('');
                    //$('#warning').fadeIn(150);
                    // let wait = document.getElementById("warning");
                    // let text = '載入中，請稍候';
                    // let length = wait.innerHTML.length + 10;
                    // let dots = window.setInterval( function() {
                    //     let wait = document.getElementById("warning");
                    //     if (wait.innerHTML.length > length) {
                    //         //wait.innerText = text;
                    //         $('.warning').fadeOut(150);
                    //         //$('#warning').hide();
                    //     } else {
                    //         //wait.innerText += ".";
                    //         $('.warning').fadeOut(150);
                    //     }
                    //
                    // }, 0);
                    $('#warning').show();
                },
                complete: function () {
                },
                success:function(res){
                    var li = '';//樣板容器
                    // var p = page;
                    // var data = res.list;        //回傳資料
                    // var data_num = data.length; //資料筆數
                    // page=page+data_num;
                    // //若有資料時
                    //console.log(res.msg);
                    var rr=0;
                    total=res.msg.length;
                    //alert(res.msg.length);
                    if(res.msg.length>0){
                        $('#rows').val(res.msg.length);
                    }



                    var hide_vip_counts = 0;
                    hide_vip_counts = $('#rows').val()-10;
                    $.each(res.msg,function(i,e){

                        rr +=parseInt(e.read_n);
                        if(userIsVip==0 && e.user_id != 1049 && i<hide_vip_counts && hide_vip_counts>0) {
                            if (e && e.user_id) li = liContent(e.pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 0, i);
                        }else if(userIsVip==0 && e.user_id != 1049){
                            if (e && e.user_id) li = liContent(e.pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 1, i);
                        }else{
                            if (e && e.user_id) li = liContent(e.pic, e.user_name, e.content, e.created_at, e.read_n, i, e.user_id, e.isVip, 1, i);
                        }
                        if(e.isVip==1) {
                            $('.sjlist_vip').append(li)
                        }else{
                            $('.sjlist_novip').append(li)
                        }
                    });
                    //$('.sjlist>ul').html(li);
                    setTimeout(function(){
                        Page.DrawPage($('#rows').val());
                        $('.sjlist').children().slice((Page.page-1)*Page.row, Page.page*Page.row).css('display', '');
                        $('.warning').hide();
                    }, 100);

                    //check li rows
                    //alert($('.sjlist_vip>li').length);
                    if($('.sjlist_vip>li').length==0){
                        $('#sjlist_vip_warning').hide();
                        $('.sjlist_vip').append(no_row_li);
                    }
                    if($('.sjlist_novip>li').length==0){
                        $('#sjlist_novip_warning').hide();
                        $('.sjlist_novip').append(no_row_li);
                    }
                    if($('.sjlist_alert>li').length==0){
                        $('#sjlist_alert_warning').hide();
                        $('.sjlist_alert').append(no_row_li);
                    }



                    // if(isNaN(rr) || (isNaN(rr) && rr==0) || total==0){
                    //     $('.nodata').show();
                    // }else if(rr>0 || total>0){
                    //     $('.nodata').hide();
                    // }
                    //$('.warning').hide();
                }
            })
            .done(function() {
                // if(page-1>=total){
                //     $('.listMoreBtn').attr('disabled', 'true').removeClass('cursor-pointer').html('NO MORE');
                // }else{
                //     $('.listMoreBtn').removeAttr('disabled').addClass('cursor-pointer').html('MORE');
                // }
            });
        }

        LoadTable();

        $('input[name=RadioGroup1]').on('click', function(event) {
            Page.page=1;
            date= $('input[name=RadioGroup1]:checked').val();
            $('.warning').show();
            LoadTable();
        });

        function chk_delete(url) {
            c4('確定要刪除嗎?');
            $(".n_left").on('click', function () {
                $("#tab04").hide();
                show_message('刪除成功');
                window.location = url;
            });
            return false;
        }

        function block(sid){
            c4('確定要封鎖嗎?');
            var sid = sid;
            $(".n_left").on('click', function() {
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
                {{--c4('確定要封鎖嗎?');--}}
                {{--var sid = sid;--}}
                {{--$(".n_left").on('click', function() {--}}
                {{--    $.post('{{ route('postBlockAJAX') }}', {--}}
                {{--        uid: '{{ $user->id }}',--}}
                {{--        sid: sid,--}}
                {{--        _token: '{{ csrf_token() }}'--}}
                {{--    }, function (data) {--}}
                {{--        $("#tab04").hide();--}}
                {{--        show_message('封鎖成功');--}}
                {{--        window.location.reload();--}}
                {{--    });--}}
                {{--});--}}
                {{--return false;--}}
            }

        $('.shou_but').on('click', function() {
            c4('確定要全部刪除嗎?');
            $(".n_left").on('click', function() {
                //window.location = {!! route('delete2All', ['uid' => $user->id]) !!};
                $.post('{{ route('delete2All') }}', {
                    uid: '{{ $user->id }}',
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    $("#tab04").hide();
                    show_message('刪除成功');
                    window.location.reload();
                });
            });
            return false;
        });

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

        $('.lebox1,.lebox2,.lebox3').toggleClass('on');
        $(".leftsidebar_box dd").show();


        $('.lebox1,.lebox2,.lebox3').click(function(e) {
            $(this).toggleClass('on');
            $(this).next('dd').slideToggle();
        });
    </script>
@stop