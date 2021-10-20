@extends('new.layouts.website')
@section('app-content')

<?php
    if (!isset($user)) {
        $umeta = null;
    } else {
        $umeta = $user->meta_();
        if(isset($umeta->city)){
            $umeta->city = explode(",",$umeta->city);
            $umeta->area = explode(",",$umeta->area);
        }
    }
?>
<style type="text/css">
    .abtn{cursor: pointer;}
    #fileuploader-ajax{font-size: 20px;}
    #fileuploader-ajax a{color:#0275d8;margin-left: 5PX;}
    .abtn{cursor: pointer;}
    .twzip {display: inline-block !important;width: auto !important;min-width: 45%;margin-right: 10PX;}
    .select_xx2{width: 100%;border: #d2d2d2 1px solid;border-radius: 4px;height: 40px;padding: 0 6px;color:#555;background:#ffffff;font-size: 15px;margin-top: 10px;}
    @media (max-width:414px) {
        .column-title div{
            width: 208px !important;
        }
    }
    @media (max-width:375px) {
        .column-title div{
            width: 188px !important;
        }
    }
    @media (max-width:320px) {
        .column-title div{
            width: 154px !important;
        }
    }
    /* 2-24 */
    .two_container{width: 100%; border: #fe92a9 dashed 1px; padding: 10px; background: #fff; box-shadow: #ffdfe6 0 0 10px;}
    .two_container h2{ background: #fff4f6; padding:5px; color: #db5b7a; font-weight: bold; font-size: 18px;}
    .two_container ul{width: 100%; padding: 8px 0; border-bottom: #eee 1px solid;}
    .two_container ul li{color: #333; line-height: 30px; font-size: 15px;}
    .two_container ul li span{ color: #999; padding-right: 5px;line-height: 30px;font-size:18px; vertical-align: middle;}

    .two_container h3{width: 100%; display: table; font-size: 15px; color: #666; margin-top: 10px;}
    .two_container h4{width: 100%; display: table; font-size: 15px; color: #333; margin-top:5px; font-weight: bold;}
    .two_container h4 span{ margin-right:35px;}
    .two_container h4 span input{ margin-right: 3px;}

</style>
<script src="/plugins/hopscotch/js/hopscotch.min.js"></script>
<script src="/plugins/fileuploader2.2/src/jquery.fileuploader.js"></script>
<div class="container matop70 chat">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="g_password">
                <div class="g_pwicon">
                    <li><a href="/dashboard/viewuser/{{$user->id}}" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
                    <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                    <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2 g_hicon2"><span>照片管理</span></a></li>
                    <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3"><span>帳號設定</span></a></li>
{{--                    <li><a href="{!! url('/dashboard/new_vip') !!}" class="g_pwicon_t4"><span>升級付費</span></a></li>--}}
                </div>
                <div class="addpic g_inputt">
                    <!--div class="n_adbut">
                        <a href="/dashboard/viewuser/{{$user->id}}" style="cursor:pointer"><img src="/new/images/1_06.png">預覽</a>
                    </div>
                    <div class="n_adbut editAllBtn">
                        <a style="cursor:pointer"><img src="/new/images/pencil-edit-button.png">編輯</a>
                    </div>
                    <div class="n_adbut recoverAllBtn" style="display:none">
                        <a style="cursor:pointer"><img src="/new/images/1_06.png">復原</a>
                    </div-->
                    <ul class="n_ulpic">
                    @if($user->engroup==1)
                        @if($avatar)
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_03.png">
                                </div>
                                @php
                                    // 沒有頭像
                                    if(is_null($avatar->pic)) {
                                        $avatar = '/new/images/ph_12.png';
                                    } else {
                                        $avatar = $avatar->pic . '?' . time();
                                        // 檢查是否被刪除
                                        $chk_deleted_avatar = \App\Models\AvatarDeleted::where('user_id', $user->id)->orderByDesc('created_at')->first();

                                        // 被管理員刪除
                                        if ($chk_deleted_avatar && $chk_deleted_avatar->operator != $user->id) {
                                            $avatar = null;
                                        }
                                    }
                                @endphp
                                <b class="img" style="background:url('{{ $avatar ?? '/new/images/ph_12.png' }}'); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($avatar == '/img/illegal.jpg') ? 'cover' : 'contain' }};"></b>
                            </li>
                        @endif
                        @if ($member_pics)
                            @for ($i = 0; $i < 6 ; $i++)
                                <li class="write_img">
                                    <div class="n_ulhh">
                                        <img src="/new/images/ph_05.png">
                                    </div>
                                    @php
                                        $pic = isset($member_pics[$i]->pic) ? $member_pics[$i]->pic . '?' . \Carbon\Carbon::now() : NULL;
                                        
                                        if (isset($member_pics[$i]->pic) && \App\Models\AdminPicturesSimilarActionLog::where('pic', $member_pics[$i]->pic)->first()) {
                                            $pic = '/img/illegal.jpg';
                                        }
                                    @endphp
                                    <b class="img" style="background:url(' {{ $pic ?? '/new/images/ph_12.png' }} '); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($pic == '/img/illegal.jpg') ? 'cover' : 'contain' }};"></b>
                                </li>
                            @endfor
                        @endif
                    @else
                        {{-- 會員為女性 --}}
                        <li class="write_img editBtn" id="{{$avatar->id}}">
                            <div class="delpicBtn">
                                <img src="/new/images/gb_icon01.png" width="30px" height="30px">
                            </div>
                            <div class="n_ulhh">
                                <img src="/new/images/ph_03.png">
                            </div>
                            @php
                                $defaultAvatar = $user->isVip() ? '/new/images/ph_12.png' : '/new/images/ph_11.png';
                                // 添加日期參數, 讓圖片不使用快取機制
                                // $avatar = isset($avatar->pic) ? $avatar->pic . '?' . \Carbon\Carbon::now() : null;

                                // 沒有頭像
                                if(is_null($avatar->pic)) {
                                    $avatar = '/new/images/ph_12.png';
                                } else {
                                    $avatar = $avatar->pic . '?' . time();

                                    // 檢查是否被刪除
                                    $chk_deleted_avatar = \App\Models\AvatarDeleted::where('user_id', $user->id)->orderByDesc('created_at')->first();

                                    // 被管理員刪除
                                    if ($chk_deleted_avatar && $chk_deleted_avatar->operator != $user->id) {
                                        $avatar = null;}
                                }
                            @endphp
                            <b class="img" style="background:url('{{ $avatar ?? $defaultAvatar}}'); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($avatar == '/img/illegal.jpg') ? 'cover' : 'contain' }};">
                                
                            </b>
                        </li>
                        {{-- 生活照五張，若無照片則顯示預設圖片 --}}
                        @for ($i = 0; $i < 6 ; $i++)
                            @php
                                $default = '/new/images/';
                                if(!$user->isVip() and $i < 3)
                                    $default .= 'ph_10.png';
                                else
                                    $default .= 'ph_12.png';

                                $pic = isset($member_pics[$i]->pic) ? $member_pics[$i]->pic . '?' .\Carbon\Carbon::now() : NULL;

                                if (isset($member_pics[$i]->pic) && \App\Models\AdminPicturesSimilarActionLog::where('pic', $member_pics[$i]->pic)->first()) {
                                    $pic = '/img/illegal.jpg';
                                }
                            @endphp
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                <b class="img" style="background:url('{{ $pic ?? $default }}'); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($pic == '/img/illegal.jpg') ? 'cover' : 'contain' }};"></b>
                            </li>
                        @endfor
                    @endif
                    </ul>
                    <h2 class="h5" id="fileuploader-ajax">上傳照片 (點擊圖片可以裁切)<a href="javascript:;"  onclick="tour(fileuploader_ajax_tour)"><i class="ion ion-md-help-circle"></i></a></h2>
                    <h4>如未更新上傳後照片, 請嘗試重新整理<br>
                    如照片無法順利上傳，請點擊頁面最下方聯絡我們加站長 line 洽詢。</h4>
                    @if($user->engroup==2)
                    <div class="two_container">
                        @php
                            $blurryAvatar = isset($blurry_avatar)? $blurry_avatar : '';
                            $blurryAvatar = explode(',', $blurryAvatar);
                            $isVVIP = true;$isVIP = true;$isGeneral = true;
                            foreach($blurryAvatar as $row){
                                if($row == 'V_VIP'){
                                    $isVVIP = false;
                                } elseif($row == 'VIP') {
                                    $isVIP = false;
                                } elseif($row == 'general') {
                                    $isGeneral = false;
                                }
                            }
                        @endphp
                         <h2>為保護會員隱私，網站可以設定照片自動模糊化</h2>
                          <ul>
                              <li><span>◎</span>預設為只給 VIP 看清楚的照片</li>
                              <li><span>◎</span>如果你想要開放給所有人看照片</li>
                              <li><span>◎</span>請自行勾選下方的 "普通會員"</li>
                          </ul>
                          <h3>清晰照片開放給</h3>
                          <h4>
                              <span><input name="picBlurryAvatar" type="checkbox" value="VIP" @if($isVIP) checked @endif>VIP</span>
                              <span><input name="picBlurryAvatar" type="checkbox" value="general" @if($isGeneral) checked @endif>普通會員</span>
                          </h4>
                    </div>
                    @endif
                    
                    <div class="row mb-4 ">
                        <div class="col-sm-12 col-lg-12">
                            <form id="avatar_upload_form"  action="{{ url('/dashboard/avatar/upload') }}" method="post" enctype="multipart/form-data">
                                <input type="file" name="avatar" data-fileuploader-files=''>
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="vipbut upload_btn abtn" value="上傳大頭照" style="border-style: none;">
                            </form>
                        </div>
                    </div>

                    @if($user->engroup==2)
                    <div class="two_container" style="margin-top: 3%;">
                        @php
                            $blurryLifePhoto = isset($blurry_life_photo)? $blurry_life_photo : '';
                            $blurryLifePhoto = explode(',', $blurryLifePhoto);
                            $isVVIP = true;$isVIP = true;$isGeneral = true;
                            foreach($blurryLifePhoto as $row){
                                if($row == 'V_VIP'){
                                    $isVVIP = false;
                                } elseif($row == 'VIP') {
                                    $isVIP = false;
                                } elseif($row == 'general') {
                                    $isGeneral = false;
                                }
                            }
                        @endphp
                         <h2>為保護會員隱私，網站可以設定照片自動模糊化</h2>
                          <ul>
                              <li><span>◎</span>預設為只給 VIP 看清楚的照片</li>
                              <li><span>◎</span>如果你想要開放給所有人看照片</li>
                              <li><span>◎</span>請自行勾選下方的 "普通會員"</li>
                          </ul>
                          <h3>清晰照片開放給</h3>
                          <h4>
                              <span><input name="picBlurryLifePhoto" type="checkbox" value="VIP" @if($isVIP) checked @endif>VIP</span>
                              <span><input name="picBlurryLifePhoto" type="checkbox" value="general" @if($isGeneral) checked @endif>普通會員</span>
                          </h4>
                    </div>
                    @endif

                    <div class="row mb-4 ">
                        <div class="col-sm-12 col-lg-12">
                            <form id="mempic_upload_form" action="{{ url('/dashboard/pictures/upload') }}" method="post" enctype="multipart/form-data">
                                <!-- name 要與 FileUploader 相同 -->
                                <input type="file" name="pictures" data-fileuploader-files=''>
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="vipbut upload_btn abtn" value="上傳生活照" style="border-style: none;">
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
  

<script src="{{ asset('new/js/heic2any.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('new/js/resize_before_upload.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    /* 說明 */
    function tour(which) {
        hopscotch.startTour(which);
    }

    var toArray = function (Ob) {
        try {
            return Array.prototype.slice.call(Ob);
        } 
        catch (e) {
            var arr = [];
            for (var i = 0, len = s.length; i < len; i++) {
                arr[i] = s[i];
            }
            return arr;
        }
    }
    let object1 = {
        '0': 3,
        '1': 13,
        '2': 23,
        '3': 33,
        'length': 5,
        'name': 330
    }
 
    $(document).ready(function(){

        
        @if(Session::has('message'))
            @if(Session::get('message')=='上傳成功' && $user->existHeaderImage() && $user->engroup==2 && !$user->isVip())//防呆
                @php
                    $vip_record = \Carbon\Carbon::parse($user->vip_record);
                @endphp
                @if($vip_record->diffInSeconds(\Carbon\Carbon::now()) <= 86400)
                    c5('照片上傳成功，24H後升級為VIP會員');
                @endif
            @else
                c5("{{ Session::get('message') }}");
            @endif
        @endif

        //errors
        @foreach ($errors->all() as $error)
            c5('{{$error}}');
        @endforeach

        let userId = $("input[name='userId']").val()

    //preload avatar
    $.ajax({
        url: '/dashboard/avatar/' + userId,
        method: 'GET',
        dataType: 'json',

        success: function(data) {
            data = JSON.stringify(data,null,2)
            $("input[name='avatar']").attr('data-fileuploader-files', data)
            //uploader 一定要在 data-fileuploader-files 設定之後才能 preload
            uploaderOfAvatar = $("input[name='avatar']").fileuploader({
                addMore: true,
                enableApi: true,
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
                                show_pop_message(data);
                                // if(data.length>4){
                                //     c2(data);
                                // }else {
                                //     c2(data);
                                // }
                                isRemovable = true
                            },
                            error: function(xhr, status, msg){
                                c5("刪除失敗")
                                isRemovable = false
                            }
                        })
                    }

                    return isRemovable
                },
                captions: {
                    errors: {
                        filesLimit: function(){
                            return '大頭照上傳限制最多為一張！';
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
                        c5(text) ? callback() : null;
                    }
                }
            })
            resize_before_upload(uploaderOfAvatar,1000,300);
            
        },
        error: function(xhr, status, msg) {
            console.log(xhr.reponseText);
        }
    })

    //preload pictures
    $.ajax({
        url: '/dashboard/pictures/' + userId,
        method: "GET",
        dataType: 'json',

        success: function(data){
            data = data === null ? "" : JSON.stringify(data,null,2)
            $("input[name='pictures']").attr('data-fileuploader-files', data)
            uploaderOfMemberPic = $("input[name='pictures']").fileuploader({
                addMore: true,
                enableApi: true,
                limit: 6,
                editor: {
                    showGrid: true
                },
                onRemove: function(item){
                    // 修改刪除單一照片
                    var isRemovable = true;
                    if(item.data.isPreload === true){
                        $.ajax({
                            url: "/dashboard/pictures/delete",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                picture: item.file
                            },
                            success: function(data){
                                $(".announce_bg").hide();
                                $("#tab02").hide();
                                show_pop_message(data);
                                // if(data.length>4){
                                //     c1(data);
                                // }else {
                                //     c2(data);
                                // }
                                isRemovable = true

                            },
                            error: function(xhr, status, msg){
                                c5("刪除失敗")
                                isRemovable = false
                            }
                        })
                    }

                    return isRemovable
                },
                captions: {
                    errors: {
                        filesLimit: function(){
                            return '生活照上傳限制最多為六張！';
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
                        c5(text) ? callback() : null;
                    }
                }
            })
            
            resize_before_upload(uploaderOfMemberPic,1000,300);
        },
        error: function(xhr, status, msg) {
            console.log(xhr);
            console.log(status);
            console.log(msg);
        }
    })

    $("input:checkbox[name='picBlurryAvatar']").on('click', function() {
        var values = "";
        $.each($("input[name='picBlurryAvatar']"), function() {
            if(!$(this).is(':checked')){
                values = values + $(this).val() +',';
            }
        });
        $.ajax({
            url: '/dashboard/avatar/blurry/' + userId,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                'blurrys': values
            },
            dataType: 'json',

            success: function(data) {
            }
        });
    });

    $("input:checkbox[name='picBlurryLifePhoto']").on('click', function() {
        var values = "";
        $.each($("input[name='picBlurryLifePhoto']"), function() {
            if(!$(this).is(':checked')){
                values = values + $(this).val() +',';
            }
        });
        $.ajax({
            url: '/dashboard/lifephoto/blurry/' + userId,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                'blurrys': values
            },
            dataType: 'json',

            success: function(data) {
            }
        });
    });
    //preload avatar

})

    var fileuploader_ajax_tour = {
        id: "fileuploader-ajax",
        steps: [
            {
                target: document.querySelector(".fileuploader-thumbnails-input"),
                content: "按下 + 選擇照片上傳,上傳的第一張照片為大頭照，之後的圖片為生活照，以此類推。",
                placement: 'top',
            },
            {
                target: document.querySelector(".fileuploader-thumbnails-input"),
                content: "選擇照片上傳後，再點選圖片即可做裁切功能。",
                placement: 'top',
            },
            {
                target: document.querySelector(".save_images"),
                content: "按下上傳照片按鈕儲存修改資訊。",
                placement: 'top',
            },
            {
                target: document.querySelector(".save_images"),
                content: "如未按下上傳照片按鈕，新增刪除裁切等資訊將不會儲存資料庫。",
                placement: 'top',
            },
        ],
    }

    $(document).ready(function() {
        if(window.matchMedia("(max-width: 775px)").matches){
            $('.column-title div').css('width', $( window ).width() - 90 - 36 - 56);
            $(window).resize(function () {
                $('.column-title div').css('width', $( window ).width() - 90 - 36 - 56);
            });
        }
    });
</script>

@stop
