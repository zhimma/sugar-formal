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
</style>

<div class="container matop70 chat">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="g_password">
                <div class="g_pwicon">
                    <li><a href="{!! url('dashboard') !!}"><img src="/new/images/mm_03.png"><span>基本資料</span></a></li>
                    <li><a href="{!! url('dashboard_img') !!}"><img src="/new/images/mm_16.png"><span>照片管理</span></a></li>
                    <li><a href="{!! url('/dashboard/password') !!}"><img src="/new/images/mm_07.png"><span>更改密碼</span></a></li>
                    <li><a href="{!! url('/dashboard/vip') !!}"><img src="/new/images/mm_09.png"><span>VIP</span></a></li>
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
                                <b class="img" style="background:url(' {{ $avatar->pic or '/new/images/ph_12.png' }} '); background-size:100% 100%"></b>
                            </li>
                        @endif
                        @if ($member_pics)
                            @for ($i = 0; $i < 6 ; $i++)
                                <li class="write_img">
                                    <div class="n_ulhh">
                                        <img src="/new/images/ph_05.png">
                                    </div>
                                    <b class="img" style="background:url(' {{ $member_pics[$i]->pic  or '/new/images/ph_12.png' }} '); background-size:100% 100%"></b>
                                </li>
                            @endfor
                        @endif
                    @else
                        {{-- 會員為男性 --}}
                        <li class="write_img editBtn" id="{{$avatar->id}}">
                            <div class="delpicBtn">
                                <img src="/new/images/gb_icon01.png" width="30px" height="30px">
                            </div>
                            <div class="n_ulhh">
                                <img src="/new/images/ph_03.png">
                            </div>
                            <b class="img" style="background:url('{{ $avatar->pic or '/new/images/'. ($user->isVip() ? 'ph_12.png' : 'ph_11.png')}}'); background-size:100% 100%">
                                
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
                            @endphp
                            <li class="write_img">
                                <div class="n_ulhh">
                                    <img src="/new/images/ph_05.png">
                                </div>
                                <b class="img" style="background:url('{{ $member_pics[$i]->pic or $default }}'); background-size:100% 100%"></b>
                            </li>
                        @endfor
                    @endif
                    </ul>
                    <h2 class="h5" id="fileuploader-ajax">上傳照片 (點擊圖片可以裁切)<a href="javascript:;"  onclick="tour(fileuploader_ajax_tour)"><i class="ion ion-md-help-circle"></i></a></h2>
                    <h4>若要移除全部照片，請在刪除全部後點擊相對應的上傳即可<br>如照片無法順利上傳，請點擊頁面最下方聯絡我們加站長 line 洽詢。</h4>

                    <div class="row mb-4 ">
                        <div class="col-sm-12 col-lg-12">
                            <form action="{{ url('/dashboard/avatar/upload') }}" method="post" enctype="multipart/form-data">
                                <input type="file" name="avatar" data-fileuploader-files=''>
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="vipbut upload_btn abtn" value="上傳大頭照"></input>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-4 ">
                        <div class="col-sm-12 col-lg-12">
                            <form action="{{ url('/dashboard/pictures/upload') }}" method="post" enctype="multipart/form-data">
                                <!-- name 要與 FileUploader 相同 -->
                                <input type="file" name="pictures" data-fileuploader-files=''>
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="vipbut upload_btn abtn" value="上傳生活照"></input>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
  
<script src="/plugins/hopscotch/js/hopscotch.min.js"></script>
<script src="/plugins/fileuploader2.2/src/jquery.fileuploader.js"></script>

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
                $("input[name='avatar']").fileuploader({
                    addMore: true,
                    limit: 1,
                    editor: {
                        ratio: "1:1",
                        showGrid: true
                    },
                    beforeSend: function(item, listEl, parentEl, newInputEl, inputEl) {
                        console.log(item, listEl, parentEl, newInputEl, inputEl)
                        return true
                    }
                })
            },
            error: function(xhr, status, msg) {
                console.log(xhr)
                console.log(status)
                console.log(msg)
            }
        })

        //preload pictures
        $.ajax({
            url: '/dashboard/pictures/' + userId,
            method: "GET",
            dataType: 'json',

            success: function(data){
                data = JSON.stringify(data,null,2)
                $("input[name='pictures']").attr('data-fileuploader-files', data)
                $("input[name='pictures']").fileuploader({
                    addMore: true,
                    limit: 7,
                    editor: {
                        showGrid: true
                    }
                })
            },
            error: function(xhr, status, msg) {
                console.log(xhr)
                console.log(status)
                console.log(msg)
            }
        })
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

  
</script>
<script>
    $(document).ready(function(){
        $(".delpicBtn").on('click', function(){
            var id = $(this).parents('.write_img').attr('id');

            c4('確認刪除此照片？');

            $(".n_left").on('click', function() {
                $.ajax({
                    url: '/dashboard/delPic',
                    type: 'POST',
                    data: {
                        'pic_id': id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        res = JSON.parse(res);

                        //alert(res.code);
                        if(res.code == '800'){
                            $("#tab04").hide();
                            c2('照片少於4張照片，未達VIP資格');
                        }else if(res.code == '200'){
                            $("#tab04").hide();
                            c2('刪除成功');
                        }
                    },
                });
            });
        });
    });

    $(document).ready(function(){
        $(".editAllBtn").on('click',function(){
            $(this).css('display', 'none');
            $(".recoverAllBtn").css('display','block');
            $(".delpicBtn").css('display','block');
        });

            $(".recoverAllBtn").on('click',function(){
            $(this).css('display', 'none');
            $(".editAllBtn").css('display','block');
            $(".delpicBtn").css('display','none');
        });
    });
            
    

</script>

@stop