@extends('new.layouts.website')
@section('style')
<style>
.se_but3{width:240px;height: 50px;background: #fe92a8;border-radius:100px;color: #ffffff;text-align: center;line-height: 18; margin: 0 auto;display: table;
font-size:16px;cursor: pointer; box-shadow: 0 0 20px #ffb6c5;cursor: pointer;line-height:18px; padding: 0 10px; margin-bottom: 15px;}
.se_but3:hover{color:#ffffff;box-shadow:inset 0px 13px 10px -10px #f83964,inset 0px -10px 10px -20px #f83964;}
.se_but3 span{padding-top:8px; display: table; text-align: center !important;width: 100%;}
.se_but3 font{font-size: 10px; line-height: 18px; display: table;text-align: center !important;width: 100%;}

.se_but4{width: 240px;height: 50px;background: #ffffff; border:#e44e71 1px solid;border-radius: 100px;color: #e44e71;text-align: center;line-height: 50px;
 margin: 0 auto;display: table;font-size:16px;box-shadow: 0 0 20px #ffb6c5;cursor: pointer;}
.se_but4:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #f83964,inset 0px -10px 10px -20px #f83964;
background:#fe92a8; border:#fe92a8 1px solid;}

@media (max-width:360px) {

}

</style>
<style>
  
  
  .ga_dtie{width:100%; margin: 0 auto; display: table; padding: 10px; border: #fe92a8 1px dashed;background-image: linear-gradient(to right,#fff8f9 ,#fffefe); color: #fe92a8; 
  border-radius: 5px; margin-top: 10px;box-shadow: 0 0 10px #ffeff6;}

</style>
<style>
.real_auth_bg{width:100%; height:100%;width: 100%;height: 100%;position: fixed;top: 0px;left: 0;background: rgba(0,0,0,0.5);z-index: 9;display:none;}
.waiting_delete_check_mask {position:absolute;width:100%;height:100%;top:0;left:0;background:gray;opacity:0.5;z-index:2;}
</style>
@if($rap_service->isInRealAuthProcess())
<script>
    function real_auth_process_check()
    {
        $('body').hide();
        $.get( "{{route('check_is_in_real_auth_process')}}"+location.search+"&{{csrf_token()}}="+(new Date().getTime()),function(data){
            if(data!='1') {
                window.history.replaceState( {} , $('title').html(), '{{route("real_auth")}}' );
                location.href='{{route("real_auth")}}';
            }
            else {
                $('body').show();
            }
        });
    } 
    
    real_auth_process_check();
</script>   
@endif
<script>
    function tab_real_auth_uploadPic(pic_kind,org_pic) 
    {
        $('.announce_bg').hide();
        $('.blbg').hide();
        $(".real_auth_bg").show();
        var tab_elt = $("#tab_real_auth_uploadPic");
        var title_str = '';
        var indicate_str = '';
        
        switch(pic_kind) {
            case 'avatar':
                indicate_str = '請選取新的大頭照並點按送出異動申請按鈕';
                title_str= '申請大頭照異動';
                act_url = "{{ url('/dashboard/avatar/upload') }}";
            break;
            case 'mempic':
                indicate_str = '請選取新的生活照並點按送出異動申請按鈕';
                title_str= '申請生活照異動';
                act_url = "{{ url('/dashboard/pictures/upload') }}";
            break;
        }
        
        tab_elt.show();
        tab_elt.find('.bltitle').html(title_str).next().find('.apply_indicate').html(indicate_str);
        tab_elt.find('input[name=pic_kind]').val(pic_kind);
        tab_elt.find('form').attr('action',act_url);
        if(org_pic!=undefined) tab_elt.find('input[name=org_pic]').val(org_pic);
        $('body').css("overflow", "hidden");
    }
    
    function tab_real_auth_uploadPic_close() {
        $(".real_auth_bg").hide();
        $('.blbg').hide();
        $(".announce_bg").hide();        
        $("#tab_real_auth_uploadPic").hide();
        $("#tab_loading").hide();
        $('body').css("overflow", "auto").css("fixed", "");
    }    
    
    function apply_replace_pic(dom)
    {
        var now_elt = $(dom);
        $.fn.fileuploader.defaults.dialogs.remove_pic(true);
        $('#tab05').hide();
        $('.announce_bg').hide();
        $('.blbg').hide();        
        now_elt.attr('onclick',now_elt.data('onclick')).removeAttr('data-onclick').html(now_elt.data('html')).removeAttr('data-html').closest('#tab06').find('.bltext_tmp').addClass('bltext').removeClass('bltext_tmp').html('');
    }
    
    function form_uploadPic_submit(){
        var tab_elt = $("#tab_real_auth_uploadPic");
        var num_of_images=tab_elt.find('.fileuploader-items-list .fileuploader-item').length;
        if(num_of_images>0) {
            tab_elt.find('form').submit();
        }
    }    
    
</script>
@stop
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
<script>
function requestBlurryLifePhoto(ruserId,rvalues) {
    $.ajax({
        url: '/dashboard/lifephoto/blurry/' + ruserId + '?{{csrf_token()}}={{now()->timestamp}}',
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            'blurrys': rvalues
        },
        dataType: 'json',

        success: function(data) {
        }
    });    
}

function requestBlurryLifePhotoDefault() {
    requestBlurryLifePhoto('{{$user->id}}','general,');
}

function requestBlurryAvatar(userId,values) {
    $.ajax({
        url: '/dashboard/avatar/blurry/' + userId + '?{{csrf_token()}}={{now()->timestamp}}',
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            'blurrys': values
        },
        dataType: 'json',

        success: function(data) {
        }
    });    
}

function requestBlurryAvatarDefault() {
    requestBlurryAvatar('{{$user->id}}','general,');
}
</script>
<div class="container matop70 chat">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="g_password">
                <div class="g_pwicon">
                    <li><a href="@if($user->isVVIP()) /dashboard/viewuser_vvip/{{$user->id}} @else /dashboard/viewuser/{{$user->id}} @endif" class="g_pwicon_t5 "><span>自我預覽</span></a></li>
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

                                        // 檢查是否被刪除
                                        $chk_deleted_avatar = \App\Models\AvatarDeleted::where('user_id', $user->id)->orderByDesc('created_at')->first();

                                        // 被管理員刪除
                                        if ($chk_deleted_avatar && $chk_deleted_avatar->operator != $user->id) {
                                            $avatar = '/img/illegal.jpg';
                                        }
                                    } else {
                                        $avatar = $avatar->pic . '?' . time();
                                    }
                                @endphp
                                <b class="img" style="background:url('{{ $avatar ?? '/new/images/ph_12.png' }}'); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($avatar == '/img/illegal.jpg') ? 'cover' : 'contain' }};"></b>
                            </li>
                        @endif
                        @php
                            $ImgCount=0;
                        @endphp
                        @foreach($member_pics as $key =>$lifeImg)
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                @php
                                    $ImgCount+=1;
                                    $pic = isset($member_pics[$key]->pic) ? $member_pics[$key]->pic . '?' . \Carbon\Carbon::now() : NULL;

                                    if (isset($member_pics[$key]->pic) && \App\Models\AdminPicturesSimilarActionLog::where('pic', $member_pics[$key]->pic)->first()) {
                                        $pic = '/img/illegal.jpg';
                                    }
                                @endphp
                                <b class="img" style="background:url(' {{ $pic ?? '/new/images/ph_12.png' }} '); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($pic == '/img/illegal.jpg') ? 'cover' : 'contain' }};"></b>
                            </li>
                        @endforeach
                        @php
                            //取得由後台刪除的生活照
                            $illegalRemoveCount=\App\Models\MemberPic::getIllegalLifeImagesCount($user->id);
                        @endphp
                        @for ($i = 0; $i <$illegalRemoveCount ; $i++)
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                <b class="img" style="background:url('/img/illegal.jpg'); background-position:50% 50%; background-repeat: no-repeat; background-size: cover; }};"></b>
                            </li>
                        @endfor
                        @php
                            $lifeImgLimit=(6-$ImgCount-$illegalRemoveCount);
                        @endphp
                        @for ($i = 0; $i < $lifeImgLimit ; $i++)
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                <b class="img" style="background:url('/new/images/ph_12.png'); background-position:50% 50%; background-repeat: no-repeat; background-size: contain;"></b>
                            </li>
                        @endfor
                    @else
                        {{-- 會員為女性 --}}
                        @if($rap_service->getLatestActualUncheckedAvatarModifyEntry())
                        <li class="write_img editBtn" id="{{$rap_service->modify_entry()->real_auth_user_modify_pic->first()->id}}">
                            <div class="n_ulhh">
                                <img src="/new/images/ph_03.png">
                            </div>                            
                            <b class="img" style="background:url('{{ $rap_service->modify_entry()->real_auth_user_modify_pic->first()->pic}}?{{time()}}'); background-position:50% 50%; background-repeat: no-repeat; background-size:contain;">
                                
                            </b>
                            <div class="n_shenhe"><img src="/new/images/shenhe.png"></div>                            
                        </li>
                        @endif
                        @foreach($rap_service->getLatestActualUncheckedDistinctOldPicModifyMemPicList() as $pic)
                        <li class="write_img editBtn" id="{{$pic->id}}">
                            <div class="n_ulhh">
                                <img src="/new/images/ph_05.png">
                            </div>                            
                            <b class="img" style="background:url('{{ $pic->real_auth_user_modify_pic->pic}}?{{time()}}'); background-position:50% 50%; background-repeat: no-repeat; background-size:contain;">
                                
                            </b>
                            <div class="n_shenhe"><img src="/new/images/shenhe.png"></div>                            
                        </li>  
                        @endforeach
                        <li class="write_img editBtn" id="{{$avatar->id}}">
                            <div class="delpicBtn">
                                <img src="/new/images/gb_icon01.png" width="30px" height="30px">
                            </div>
                            <div class="n_ulhh">
                                <img src="/new/images/ph_03.png">
                            </div>
                            @php
                                $defaultAvatar = $user->isVipOrIsVvip() ? '/new/images/ph_12.png' : '/new/images/ph_11.png';
                                // 添加日期參數, 讓圖片不使用快取機制
                                // $avatar = isset($avatar->pic) ? $avatar->pic . '?' . \Carbon\Carbon::now() : null;

                                // 沒有頭像
                                if(is_null($avatar->pic)) {
                                    $avatar = '/new/images/ph_12.png';

                                    // 檢查是否被刪除
                                    $chk_deleted_avatar = \App\Models\AvatarDeleted::where('user_id', $user->id)->orderByDesc('created_at')->first();

                                    // 被管理員刪除
                                    if ($chk_deleted_avatar && $chk_deleted_avatar->operator != $user->id) {
                                        $avatar = '/img/illegal.jpg';
                                    }
                                } else {
                                    $avatar = $avatar->pic . '?' . time();
                                }
                            @endphp
                            <b class="img" style="background:url('{{ $avatar ?? $defaultAvatar}}'); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($avatar == '/img/illegal.jpg') ? 'cover' : 'contain' }};">
                                
                            </b>
                            @if($rap_service->modify_entry() && $rap_service->modify_entry()->real_auth_user_modify_pic->count()  && ($rap_service->modify_entry()->real_auth_user_modify_pic->first()->old_pic??null)==$user->meta->pic)
                            <div class="waiting_delete_check_mask"></div>
                            @endif
                        </li>
                        {{-- 生活照五張，若無照片則顯示預設圖片 --}}
                        @php
                            $ImgCount=0;
                        @endphp
                        @foreach($member_pics as $key =>$lifeImg)
                            @php
                                $ImgCount+=1;
                                $default = '/new/images/';
                                if(!$user->isVipOrIsVvip() and $key < 3)
                                    $default .= 'ph_10.png';
                                else
                                    $default .= 'ph_12.png';

                                $pic = isset($member_pics[$key]->pic) ? $member_pics[$key]->pic . '?' .\Carbon\Carbon::now() : NULL;

                                if (isset($member_pics[$key]->pic) && \App\Models\AdminPicturesSimilarActionLog::where('pic', $member_pics[$key]->pic)->first()) {
                                    $pic = '/img/illegal.jpg';
                                }
                            @endphp
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                <b class="img" style="background:url('{{ $pic ?? $default }}'); background-position:50% 50%; background-repeat: no-repeat; background-size: {{ ($pic == '/img/illegal.jpg') ? 'cover' : 'contain' }};"></b>
                                @if($rap_service->modify_pic_list()->where('old_pic',$member_pics[$key]->pic)->count())
                                <div class="waiting_delete_check_mask"></div>
                                @endif                            
                            </li>
                        @endforeach
                        @php
                            //取得由後台刪除的生活照
                            $illegalRemoveCount=\App\Models\MemberPic::getIllegalLifeImagesCount($user->id);
                        @endphp
                        @for ($i = 0; $i <$illegalRemoveCount ; $i++)
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                <b class="img" style="background:url('/img/illegal.jpg'); background-position:50% 50%; background-repeat: no-repeat; background-size: cover; }};"></b>
                            </li>
                        @endfor

                        @php
                            $showVipUploadCount=$ImgCount+$illegalRemoveCount;
                            $commonCount=$showVipUploadCount<3 ? 3 : 6-$showVipUploadCount;
                        @endphp
                        @if($showVipUploadCount <3)
                            @for ($i = 0; $i < 3-$showVipUploadCount ; $i++)
                                <li class="write_img">
                                    <div class="n_ulhh"><img src="/new/images/ph_05.png"></div>
                                    <b class="img" style="background:url('/new/images/ph_10.png'); background-position:50% 50%; background-repeat: no-repeat; background-size: contain;"></b>
                                </li>
                            @endfor
                            @for ($i = 0; $i < $commonCount; $i++)
                                <li class="write_img">
                                    <div class="n_ulhh"><img src="/new/images/ph_05.png"></div>
                                    <b class="img" style="background:url('/new/images/ph_12.png'); background-position:50% 50%; background-repeat: no-repeat; background-size: contain;"></b>
                                </li>
                            @endfor
                        @else
                            @php
                                $lifeImgLimit=(6-$ImgCount-$illegalRemoveCount);
                            @endphp
                            @for ($i = 0; $i < $lifeImgLimit ; $i++)
                                <li class="write_img">
                                    <div class="n_ulhh"><img src="/new/images/ph_05.png"></div>
                                    <b class="img" style="background:url('/new/images/ph_12.png'); background-position:50% 50%; background-repeat: no-repeat; background-size: contain;"></b>
                                </li>
                            @endfor
                        @endif
                    @endif
                    </ul>
                    <h2 class="h5" id="fileuploader-ajax">上傳照片 (點擊圖片可以裁切)<a href="javascript:;"  onclick="tour(fileuploader_ajax_tour)"><i class="ion ion-md-help-circle"></i></a></h2>
                    <h4>如未更新上傳後照片, 請嘗試重新整理<br>
                    如照片無法順利上傳，請點擊頁面最下方聯絡我們加站長 line 洽詢。</h4>
                    <div class="two_container">
                        @php
                            $blurryAvatar = isset($blurry_avatar)? $blurry_avatar : '';
                            $blurryAvatar = explode(',', $blurryAvatar);
                            $isVVIP = true;$isVIP = true;$isGeneral = true;
                            $isDefault = false;
                            if(!($blurry_avatar??null)) {                              
                                $isGeneral = false;
                                $isDefault = true;
                            }                            
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
                    
                    <div class="row mb-4 ">
                        <div class="col-sm-12 col-lg-12">
                            <form id="avatar_upload_form"  action="{{ url('/dashboard/avatar/upload') }}" method="post" enctype="multipart/form-data">
                                <input type="file" name="avatar" data-fileuploader-files=''>
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="vipbut upload_btn abtn" value="上傳大頭照" style="border-style: none;box-shadow: 0 0 20px #ffb6c5;">
                            </form>
                        </div>
                    </div>

                    <div class="two_container" style="margin-top: 3%;">
                        @php
                            $blurryLifePhoto = isset($blurry_life_photo)? $blurry_life_photo : '';
                            $blurryLifePhoto = explode(',', $blurryLifePhoto);
                            $isVVIP = true;$isVIP = true;$isGeneral = true;
                            $isDefault=false;
                            if(!($blurry_life_photo??null)) {                              
                                $isGeneral = false;
                                $isDefault = true;
                            }

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
                              <span><input name="picBlurryLifePhoto" type="checkbox" value="general"  @if($isGeneral) checked @endif>普通會員</span>
                              @if($isDefault) 
                              <script>  
                                requestBlurryLifePhotoDefault();
                              </script>
                              @endif
                          </h4>
                    </div>

                    <div class="row mb-4 ">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <form id="mempic_upload_form" action="{{ url('/dashboard/pictures/upload') }}" method="post" enctype="multipart/form-data">
                                    <!-- name 要與 FileUploader 相同 -->
                                    <input type="file" name="pictures" data-fileuploader-files=''>
                                    <input type="hidden" name="userId" value="{{ $user->id }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="vipbut upload_btn abtn" value="上傳生活照" style="border-style: none;box-shadow: 0 0 20px #ffb6c5;">
                                </form>
                            </div>
                            <div>
                            
                            </div>
                        </div>
                    </div>
                    <div class="real_auth_bg" onclick="gmBtnNoReload();tab_real_auth_uploadPic_close();" style="display:none;"></div>
                    @if($rap_service->isInRealAuthProcess())
                    <div class="ga_dtie" style="margin-top: 50px;">
                        此認證將驗證本人，如果您照片不想曝光，請改用背影照或者是馬賽克。禁止使用非本人照片。認證通過後照片如要修改需經過審核。(強烈建議千萬不要使用曾在FB,iG使用過的照片)
                    </div>
                    <div></div>
					<div class="n_txbut" style="width: auto;">
					  <a href="{{route('dashboard',['real_auth'=>request()->real_auth])}}" class="se_but3 " {!! $rap_service->getOnClickAttrForNoUnloadConfirm()  !!}>
						  <span >前往下一步認證</span><font>生活照與大頭照皆已上傳完成</font>
					  </a>
					  <a href="" class="se_but4" >取消認證</a>
				   </div>
                    @endif
                    @if($rap_service->isPassedByAuthTypeId(1))
                    <div class="bl_tab_aa" id="tab_real_auth_uploadPic" style="display: none;left:0;">
                        <form id="form_real_auth_uploadPic" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="userId" value="{{ $user->id }}">
                            <input type="hidden" name="org_pic" value="">
                            <input type="hidden" name="pic_kind" value="">
                            <input type="hidden" name="m_time" @if(isset($m_time)) value="{{ $m_time }}" @else value="" @endif>
                            <input type="hidden" name="{{ \Carbon\Carbon::now()->timestamp }}"
                                value="{{ \Carbon\Carbon::now()->timestamp }}">
                            <div class="bl_tab_bb">
                                <div class="bltitle"><span style="text-align: center; float: none;">上傳照片</span></div>
                                <div class="new_pot1 new_poptk_nn new_height_pop ">
                                    <div class="blnr apply_indicate"></div>
                                    <div class="fpt_pic">
                                        <input id="apply_replace_pic" type="file" name="apply_replace_pic">
                                        <div class="n_bbutton" style="margin-top:0px;">
                                            <a class="n_bllbut" onclick="form_uploadPic_submit()">送出異動申請</a>
                                        </div>
                                    </div>
                                </div>
                                <a onclick="tab_real_auth_uploadPic_close()" class="bl_gb"><img src="/new/images/gb_icon.png"></a>
                            </div>
                        </form>
                    </div>                                        
                   @endif
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
            @if(Session::get('message')=='上傳成功' && $user->existHeaderImage() && $user->engroup==2 && !$user->isVipOrIsVvip())//防呆
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
        url: '/dashboard/avatar/' + userId + '?{{csrf_token()}}={{now()->timestamp}}',
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

                @if($rap_service->isInRealAuthProcess())
                beforeSubmitedSuccess:function() {
                    tabPopM_cancel_onbeforeunload_hint();
                }   ,  
                afterSubmitedSuccess:function() {
                    active_onbeforeunload_hint();
                }   ,
                @endif
                
                @if($rap_service->isPassedByAuthTypeId(1))
                afterRender:function(listEl, parentEl, newInputEl, inputEl) {
                    var override_del_confirm_msg = '已通過本人認證的大頭照，若要刪除必須先上傳一張新的大頭照，並且經過站長審核通過後，才能刪除原本大頭照並替換成新的大頭照。<br><br>您確定要申請大頭照異動嗎?';
                    
                    listEl.find('.fileuploader-action-remove').click(function(){
                        $('#tab06_tmp').attr('id','tab06');
                        $('#tab05_tmp').attr('id','tab05');                    
                        $('.blbg_tmp').addClass('blbg').removeClass('blbg_tmp');
                        $('.announce_bg_tmp').addClass('announce_bg').removeClass('announce_bg_tmp');                        
                        
                        $('#tab06 .bltext').html(override_del_confirm_msg).removeClass('bltext').addClass('bltext_tmp');
                        var button_elt = $('#tab06 .n_left');
                        button_elt.attr('data-html',button_elt.html()).attr('data-onclick',button_elt.attr('onclick')).attr('onclick','apply_replace_pic(this)').html('確定');
                    });
                },
                @endif
                onRemove: function(item) {
                    var isRemovable = true;
                    if(item.data.isPreload === true){
                        @if($rap_service->isPassedByAuthTypeId(1))
                            isRemovable = false;
                            $("#tab05").hide();
                            $('#tab06 .bltext').removeClass('bltext_tmp').addClass('bltext');
                            tab_real_auth_uploadPic('avatar');
                            return false;
                        @endif
                        
                        $.ajax({
                            url: "/dashboard/avatar/delete/" + $("input[name='userId']").val() + '?{{csrf_token()}}={{now()->timestamp}}',
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data){
                                //c2("刪除成功")
                                $(".announce_bg").hide();
                                $("#tab02").hide();
                                @if($rap_service->isInRealAuthProcess())
                                tabPopM_cancel_onbeforeunload_hint();
                                @endif
                                if(data.length>100 || data=='' || data==undefined) {
                                    show_pop_message('刪除已完成，請確認檔案已刪除');
                                } else {
                                    show_pop_message(data);
                                }
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
                    },
                },
                dialogs: {                  
                    // alert dialog
                    alert: function(text) {
                        return c5(text);
                    },

                    // confirm dialog
                    confirm: function(text, callback) {
                        c5(text) ? callback() : null;
                    },                                      
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
        url: '/dashboard/pictures/' + userId + '?{{csrf_token()}}={{now()->timestamp}}',
        method: "GET",
        dataType: 'json',

        success: function(data){
            uploaderOfMemberPic = $("input[name='pictures']").fileuploader({
                files:data,
                addMore: true,
                enableApi: true,
                limit: 6,
                editor: {
                    showGrid: true
                },

                @if($rap_service->isInRealAuthProcess())
                beforeSubmitedSuccess:function() {                   
                    tabPopM_cancel_onbeforeunload_hint();
                }   ,  
                afterSubmitedSuccess:function() {
                    active_onbeforeunload_hint();
                }   ,
                @endif

                @if($rap_service->isPassedByAuthTypeId(1))
                afterRender:function(listEl, parentEl, newInputEl, inputEl) {
                    var override_del_confirm_msg = '已通過本人認證的生活照，若要刪除必須先上傳一張新的生活照，並且經過站長審核通過後，才能刪除原本生活照並替換成新的生活照。<br><br>您確定要申請生活照的刪除異動嗎?';
                    var checking_added_pic_num = {{$rap_service->modify_pic_list()->count()}};
                    var now_api = $.fileuploader.getInstance(inputEl.get(0)); 
                    var now_limit = now_api.getOptions().limit;
                    var new_limit = 0;
                    
                    if(checking_added_pic_num>0) {
                       new_limit = now_limit-checking_added_pic_num;
                       if(new_limit<0) new_limit=0;
                       now_api.setOption('limit',new_limit);
                    }
                    
                    listEl.find('.fileuploader-action-remove').click(function(){
                        $('#tab06_tmp').attr('id','tab06');
                        $('#tab05_tmp').attr('id','tab05');                    
                        $('.blbg_tmp').addClass('blbg').removeClass('blbg_tmp');
                        $('.announce_bg_tmp').addClass('announce_bg').removeClass('announce_bg_tmp');                        
                        
                        $('#tab06 .bltext').html(override_del_confirm_msg).removeClass('bltext').addClass('bltext_tmp');
                        var button_elt = $('#tab06 .n_left');
                        button_elt.attr('data-html',button_elt.html()).attr('data-onclick',button_elt.attr('onclick')).attr('onclick','apply_replace_pic(this)').html('確定');
                    });
                },
                afterResize: function() {
                    c5html('由於您已通過本人認證，因此照片的新增、刪除等異動，都需先經過站長審核通過後，原本的照片資料或數量才會真正的異動改變。<br><br>若確定要提出新增生活照的異動申請，請點按上傳生活照按鈕。');
                    return true;
                },                
                @endif                
                onRemove: function(item){
                    // 修改刪除單一照片
                    var isRemovable = true;
                    if(item.data.isPreload === true){
                        @if($rap_service->isPassedByAuthTypeId(1))
                            isRemovable = false;
                            $("#tab05").hide();
                            $('#tab06 .bltext').removeClass('bltext_tmp').addClass('bltext');
                            tab_real_auth_uploadPic('mempic',item.file);
                            return false;
                        @endif 
                        //{{-- --}}
                        
                        $.ajax({
                            url: "/dashboard/pictures/delete?{{csrf_token()}}={{now()->timestamp}}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                picture: item.file
                            },
                            success: function(data){
                                $(".announce_bg").hide();
                                $("#tab02").hide();
                                @if($rap_service->isInRealAuthProcess())
                                tabPopM_cancel_onbeforeunload_hint();
                                @endif
                                if(data.length>100 || data=='' || data==undefined) {
                                    show_pop_message('刪除已完成，請確認檔案是否已刪除');
                                } else {                                
                                    show_pop_message(data);
                                }
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
                        filesLimit: function(options){
                            var filesLimit_error_msg = '生活照上傳限制最多為六張！';
   
                            @if($rap_service->isPassedByAuthTypeId(1))
                            var real_auth_checking_added_pic_num={{$rap_service->modify_pic_list()->whereNull('old_pic')->count()}};
                            
                            if(real_auth_checking_added_pic_num+data.length>=options.limit
                                || (real_auth_checking_added_pic_num)
                            ) {
                                filesLimit_error_msg = '您的生活照';
                                
                                

                               if(data.length>0) {
                                   filesLimit_error_msg+='通過審核的有'+data.length+'張 (不含審核中的刪除異動申請)，';
                               }
                               
                               if(real_auth_checking_added_pic_num>0) {
                                   filesLimit_error_msg+='正在審核中的新增生活照有'+real_auth_checking_added_pic_num+'張，';
                               }
                               
                               if(real_auth_checking_added_pic_num+data.length<options.limit)
                                   filesLimit_error_msg+='只能再新增'+(options.limit-data.length)+'張生活照。請重新選取照片。';                                
                               else
                                   filesLimit_error_msg+='合計已達到生活照張數限制六張，無法再新增生活照。';                                
                            }

                           @endif
                           return filesLimit_error_msg;
                            
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
    
    @if($rap_service->isPassedByAuthTypeId(1))
        images_uploader = $('input[name="apply_replace_pic"]').fileuploader({
            changeInput: ' ',
            theme: 'thumbnails',
            enableApi: true,
            addMore: false,
            limit: 1,
            thumbnails: {
                box: '<div class="fileuploader-items">' +
                    '<ul class="fileuploader-items-list">' +
                    '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner" style="background: url({{ asset("new/images/addpic.png") }}); background-size:100%"></div></li>' +
                    '</ul>' +
                    '</div>',
                item: '<li class="fileuploader-item">' +
                    '<div class="fileuploader-item-inner">' +
                    '<div class="type-holder">${extension}</div>' +
                    '<div class="actions-holder">' +
                    '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                    '</div>' +
                    '<div class="thumbnail-holder">' +
                    '${image}' +
                    '<span class="fileuploader-action-popup"></span>' +
                    '</div>' +
                    '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                    '<div class="progress-holder">${progressBar}</div>' +
                    '</div>' +
                    '</li>',
                item2: '<li class="fileuploader-item">' +
                    '<div class="fileuploader-item-inner">' +
                    '<div class="type-holder">${extension}</div>' +
                    '<div class="actions-holder">' +
                    '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
                    '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                    '</div>' +
                    '<div class="thumbnail-holder">' +
                    '${image}' +
                    '<span class="fileuploader-action-popup"></span>' +
                    '</div>' +
                    '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
                    '<div class="progress-holder">${progressBar}</div>' +
                    '</div>' +
                    '</li>',
                startImageRenderer: true,
                canvasImage: false,
                _selectors: {
                    list: '.fileuploader-items-list',
                    item: '.fileuploader-item',
                    start: '.fileuploader-action-start',
                    retry: '.fileuploader-action-retry',
                    remove: '.fileuploader-action-remove'
                },
                onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                    if(item.format == 'image') {
                        item.html.find('.fileuploader-item-icon').hide();
                    }

                    if (api.getListEl().length > 0) {
                        $('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
                    }
                },
                onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));

                    html.children().animate({'opacity': 0}, 200, function() {
                        html.remove();

                        if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                            plusInput.show();
                    });

                    if (api.getFiles().length == 1) {
                        $('.fileuploader-thumbnails-input-inner').css('background-image', 'url({{ asset("new/images/addpic.png") }})');
                    }
                }
            },
            dialogs: {
                alert:function(message) {
                    alert(message);
                },
            },
            onRemove: function() {                
                return true;
            },           
            dragDrop: {
                container: '.fileuploader-thumbnails-input'
            },
            beforeResize: function(listEl,parentEl, newInputEl, inputEl) {

            },             
            afterResize: function(listEl,parentEl, newInputEl, inputEl) {

            }, 
            beforeSubmit: function(e,cur_uploader_api) {        

            },  
            afterSubmit: function(e) {        
            }, 
            beforeSubmitedSuccess:function(data,status,xhr,ajaxObj,cur_uploader_api) {
            },
            afterSubmitedSuccess: function(data,status,xhr,ajaxObj,cur_uploader_api) {

            },          
            afterRender: function(listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.on('click', function() {
                    api.open();
                });

                api.getOptions().dragDrop.container = plusInput;
            },
            afterSelect:function(listEl, parentEl, newInputEl, inputEl) {
                $('#tab06').attr('id','tab06_tmp');
                $('#tab05').attr('id','tab05_tmp');
                $('.blbg').addClass('blbg_tmp').removeClass('blbg');
                $('.announce_bg').addClass('announce_bg_tmp').removeClass('announce_bg');
                listEl.find('.fileuploader-action-remove').trigger('click');
                listEl.find('.fileuploader-action-remove').click(function(){
                    var now_api = $.fileuploader.getInstance(inputEl.get(0));

                    var button_elt = $('#tab06 .n_left');

                    now_api.getOptions().dialogs.remove_pic(true);
                    
                });
                
            },            
            editor: {
                cropper: {
                    showGrid: true,
                },
            },
            captions: {
                confirm: '確認',
                cancel: '取消',
                name: '檔案名稱',
                type: '類型',
                size: '容量',
                dimensions: '尺寸',
                duration: '持續時間',
                crop: '裁切',
                rotate: '旋轉',
                sort: '分類',
                download: '下載',
                remove: '刪除',
                drop: '拖曳至此上傳檔案',
                open: '打開',
                removeConfirmation: '確認要刪除檔案嗎?',
                errors: {
                    filesLimit: function(options) {
                        return '最多上傳 ${limit} 張圖片.'
                    },
                    filesType: '檔名: ${name} 不支援此格式, 只允許 ${extensions} 檔案類型上傳.',
                    fileSize: '${name} 檔案太大, 請確認容量需小於 ${fileMaxSize}MB.',
                    filesSizeAll: '上傳的所有檔案過大, 請確認未超過 ${maxSize} MB.',
                    fileName: '${name} 已有選取相同名稱的檔案.',
                }
            }
        });
        resize_before_upload(images_uploader,400,600,'#tab_real_auth_uploadPic');    
    @endif
    

    $("input:checkbox[name='picBlurryAvatar']").on('click', function() {
        var values = "";
        $.each($("input[name='picBlurryAvatar']"), function() {
            if(!$(this).is(':checked')){
                values = values + $(this).val() +',';
            }
        });
        $.ajax({
            url: '/dashboard/avatar/blurry/' + userId + '?{{csrf_token()}}={{now()->timestamp}}',
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
        requestBlurryLifePhoto(userId,values);
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
@if($rap_service->isInRealAuthProcess())
<script>

    $(document).ready(function() {
        active_onbeforeunload_hint();
    });
    
    function tabPopM_cancel_onbeforeunload_hint()
    {
        $('#tabPopM .n_bllbut').attr('onclick',"$('body').attr('onbeforeunload','');$('this').attr('onclick','');");
    }
    
    function active_onbeforeunload_hint()
    {
        $('body').attr('onbeforeunload',"return '';");
        $('body').attr('onkeydown',"if (window.event.keyCode == 116) $(this).attr('onbeforeunload','');");    
    }
</script>
@endif
@stop
