<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70 chat">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                @include('new.partials.message')
                <div class="g_pwicon">
                    <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                    <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                    <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 g_hicon3"><span>更改帳號</span></a></li>
                    <li><a href="{!! url('/dashboard/new_vip') !!}" class="g_pwicon_t4"><span>VIP</span></a></li>
                </div>
                <div class="gg_zh">
                    <div class="gg_mm"><span><i></i>會員帳號開啟/關閉</span><img src="/new/images/darkPinkKey.png">
                </div>

                <div class="n_shtab">
                    <h2>普通會員每個月僅可變更一次, VIP則沒有限制, 並請填寫關閉原因。</h2>
                </div>
                <div class="sjlist_li">
                    <div class="leftsidebar_box">
                        <dl class="system_log n_input">
                            @if($reasonType == 1)
                            <dt class="blxg">以下是所有與您通訊過的會員，請勾選出欲檢舉的對象(最多三位)。</dt>
                            <dd class="lebox3_content">
                                <div class="row mb-4 ">
                                    <div class="sjlist">
                                        <form action="{{ url('/dashboard/updateAccountStatus') }}" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="status" value="close">
                                            <input type="hidden" name="userId" value="{{ $user->id }}">
                                            <input type="hidden" name="reasonType" value="1">
                                            <input type="hidden" name="reportedId" value="">
                                            <ul style="margin: 0px 15px;">
                                                <?php
                                                $userList = \App\Models\Message::orwhere('from_id', $user->id)
                                                    ->orwhere('to_id', $user->id)
                                                    ->where('content', 'NOT LIKE', '%系統通知%')->distinct()->select('from_id')->get()->toArray();

                                                $accountList = \App\Models\UserMeta::leftJoin('users', 'users.id', '=', 'user_meta.user_id')->whereIn('user_meta.user_id', $userList)->where('user_meta.user_id', '!=', $user->id)->get();
                                                ?>
                                                @foreach($accountList as $account)
                                                @php
                                                    $changeName = DB::table('account_name_change')->where('user_id', $account->user_id)->where('status',1)->orderBy('passed_at', 'desc')->first();
                                                @endphp
                                                <li>
                                                    <div class="si_bg" style="margin-left: 0;">
                                                        <div class="sjpic"><a href="/dashboard/viewuser/{{ $account->user_id }}"><img src="{{ $account->pic }}"></a></div>
                                                        <div class="sjleft">
                                                            <div class="sjtable">
                                                                <span>{{ $account->name }}</span>
                                                                <div class="sjright" >
                                                                    <h4>
                                                                        <a href="javascript:" class="remove reportUser" data-id="{{ $account->user_id }}" style="margin-right: 20px;"><img src="/new/images/ncion_07.png">檢舉</a>
{{--                                                                        <input type="checkbox" name="reportedId[]" value="{{ $account->user_id }}">--}}
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                            @if(!is_null($changeName))
                                                                <font>{{  date("Y-m-d",strtotime($changeName->created_at)) }}之前並非使用此帳號名稱</font>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                                </li>
                                                <br>
                                                <span>說明（必填）</span>
                                                <span><textarea minlength="4" data-parsley-minlength="4" name="content" rows="3" class="select_xx05" placeholder="給站務人員的備註,或期望處理方式" required></textarea></span>
                                            </ul>
                                            <div class="col-sm-12 col-lg-12" style="margin-left: 18px;">
                                                <!-- name 要與 FileUploader 相同 -->
                                                <input type="file" name="image" data-fileuploader-files='' required>
                                                <input type="submit" class="vipbut upload_btn abtn" value="上傳證據" style="border-style: none;  margin-bottom: 10px;">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </dd>
                            @endif

                            @if($reasonType == 2)
                                <form method="POST" action="/dashboard/updateAccountStatus">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="status" value="close">
                                    <input type="hidden" name="userId" value="{{ $user->id }}">
                                    <input type="hidden" name="reasonType" value="2">

                                    <dt class="">介面設計不美觀 請勾選頁面圖示（複選）</dt>
                                    <div class="g_pwicon" style="width: 100%; padding: 0px 0px;">
                                        <li class="section1">
                                            <input name="content[]"  type="checkbox" class="pick1" value="介面設計不美觀-整體設計">
                                            <img src="/new/images/01-photo-index-uiux.jpg"><span class="reason">1.整體設計</span>
                                        </li>
                                        <li class="section2">
                                            <input name="content[]"  type="checkbox"  class="pick2" value="介面設計不美觀-訊息收件">
                                            <img src="/new/images/02-photo-chat-uiux.jpg"><span class="reason">2.訊息收件</span>
                                        </li>
                                        <li class="section3">
                                            <input name="content[]"  type="checkbox" class="pick3" value="介面設計不美觀-搜索功能">
                                            <img src="/new/images/03-photo-search-uiux.jpg"><span class="reason">3.搜索功能</span>
                                        </li>
                                        <li class="section4">
                                            <input name="content[]"  type="checkbox" class="pick4" value="介面設計不美觀-瀏覽會員">
                                            <img src="/new/images/04-photo-viewUser-uiux.jpg"><span class="reason">4.瀏覽會員</span>
                                        </li>
                                    </div>

                                    <dt class="">載入速度太慢 請勾選頁面圖示（複選）</dt>
                                    <div class="g_pwicon" style="width: 100%; padding: 0px 0px;">
                                        <li class="section5">
                                            <input name="content[]"  type="checkbox" class="pick5" value="載入速度太慢-整體設計">
                                            <img src="/new/images/01-photo-index-uiux.jpg"><span class="reason">5.整體設計</span>
                                        </li>
                                        <li class="section6">
                                            <input name="content[]"  type="checkbox" class="pick6" value="載入速度太慢-訊息收件">
                                            <img src="/new/images/02-photo-chat-uiux.jpg"><span class="reason">6.訊息收件</span>
                                        </li>
                                        <li class="section7">
                                            <input name="content[]"  type="checkbox" class="pick7" value="載入速度太慢-搜索功能">
                                            <img src="/new/images/03-photo-search-uiux.jpg"><span class="reason">7.搜索功能</span>
                                        </li>
                                        <li class="section8" >
                                            <input name="content[]"  type="checkbox" class="pick8" value="載入速度太慢-瀏覽會員">
                                            <img src="/new/images/04-photo-viewUser-uiux.jpg"><span class="reason">8.瀏覽會員</span>
                                        </li>
                                    </div>

                                    <dt>
                                        <span>功能操作不實用</span>
                                        <span>
                                        <textarea minlength="4" data-parsley-minlength="4" required="" data-parsley-errors-messages-disabled="" name="about" cols="" rows="3" class="select_xx05" data-parsley-id="25" placeholder="請輸入功能名稱"></textarea>
                                        </span>
                                    </dt>

                                    <dt>
                                        <span>其他</span>
                                        <span>
                                        <textarea minlength="4" data-parsley-minlength="4" required="" data-parsley-errors-messages-disabled="" name="about" cols="" rows="3" class="select_xx05" data-parsley-id="25" placeholder="請輸入功能名稱"></textarea>
                                        </span>
                                    </dt>

                                    <div class="n_bbutton" style="text-align: center;">
                                        <button type="submit" class="n_center" style="border-style: none; background: #FF8197; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px; margin-bottom: 20px;">送出</button>
                                    </div>
                                </form>
                            @endif

                            @if($reasonType == 3)
                                <dt class="lebox_alert tab">已找到長期穩定對象</dt>
                                <dd class="lebox_alert_content">
                                    <div class="bl bl_tab" id="show_banned" style="display: block; position: unset; width: 80%; margin: 20px auto;">
                                        <div class="bltitle"><span>已找到長期穩定對象</span></div>
                                        <div class="n_blnr01 ">
                                            <form method="POST" action="/dashboard/updateAccountStatus">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="status" value="close">
                                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                                <input type="hidden" name="reasonType" value="3">
                                                <label><input type="checkbox" name="color1" value="blue" checked>已找到長期</label>
                                                <div class="n_bbutton" style="text-align: center;">
                                                    <button type="submit" class="n_center" style="border-style: none; background: #8a9ff0; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">送出</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </dd>
                            @endif

                            @if($reasonType == 4)
                            <dt>
                                <span>其他原因<i>(必填)</i></span>
                                <span>
                                    <form method="POST" action="/dashboard/updateAccountStatus">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="userId" value="{{ $user->id }}">
                                        <input type="hidden" name="status" value="close">
                                        <input type="hidden" name="reasonType" value="4">
                                        <textarea minlength="4" data-parsley-minlength="4" required="" data-parsley-errors-messages-disabled="" name="about" cols="" rows="3" class="select_xx05" data-parsley-id="25" placeholder="請輸入欲關閉帳號之原因"></textarea>
                                        <div class="n_bbutton" style="text-align: center;">
                                            <button type="submit" class="n_center" style="border-style: none; background: #8a9ff0; color:#ffffff; text-align: center; border-radius: 200px; width:150px; height: 40px;">送出</button>
                                        </div>
                                    </form>
                                </span>
                            </dt>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
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

    .reportFlag{background: #b4d7ff!important;}

    .reason{
        padding: 0px 0px!important;
    }

    .reasonPick{
        border: 1px solid pink;
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
    .reportFlag{
        background: rgba(255,24,34,0.71);
    }
</style>
<script src="/plugins/hopscotch/js/hopscotch.min.js"></script>
<script src="/plugins/fileuploader2.2/src/jquery.fileuploader.js"></script>
<script>
    //preload pictures
    $("input[name='image']").fileuploader({
        addMore: true,
        limit: 1,
        editor: {
            ratio: "1:1",
            showGrid: true
        },
        onRemove: function(item) {
            var isRemovable = true;
            if(item.data.isPreload === true){
                $.ajax({
                    url: "/dashboard/avatar/delete/" + $("input[name='userId']").val(),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data){
                        //c2("刪除成功")
                        $(".announce_bg").hide();
                        $("#tab02").hide();
                        c3(data);
                        isRemovable = true
                    },
                    error: function(xhr, status, msg){
                        c2("刪除失敗")
                        isRemovable = false
                    }
                })
            }

            return isRemovable
        },
        captions: {
            errors: {
                filesLimit: function(){
                    return '照片上傳限制最多為一張！';
                }
            }
        },
        dialogs: {
            // alert dialog
            alert: function(text) {
                return c5(text);
            },
            // confirm dialog
            confirm: function(text, callback) {
                c2(text) ? callback() : null;
            }
        }
    })

    $('.g_pwicon li').click(function() {
        $(this).toggleClass('reasonPick');
    });

    $('.section1').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick1').attr('checked', true);
        else
            $('.pick1').attr('checked', false);
    });

    $('.section2').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick2').attr('checked', true);
        else
            $('.pick2').attr('checked', false);
    });

    $('.section3').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick3').attr('checked', true);
        else
            $('.pick3').attr('checked', false);
    });

    $('.section4').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick4').attr('checked', true);
        else
            $('.pick4').attr('checked', false);
    });

    $('.section5').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick5').attr('checked', true);
        else
            $('.pick5').attr('checked', false);
    });

    $('.section6').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick6').attr('checked', true);
        else
            $('.pick6').attr('checked', false);
    });

    $('.section7').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick7').attr('checked', true);
        else
            $('.pick7').attr('checked', false);
    });

    $('.section8').click(function() {
        if($(this).hasClass('reasonPick'))
            $('.pick8').attr('checked', true);
        else
            $('.pick8').attr('checked', false);
    });

    $('.reportUser').click(function() {
        //$(this).toggleClass('reportFlag');
        $('.reportUser').removeClass('reportFlag');
        $(this).addClass('reportFlag');
        $("input[name=reportedId]").val($(this).attr("data-id"));
    });
</script>
@stop