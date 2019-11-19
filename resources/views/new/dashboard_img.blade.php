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
            <li><a href=""><img src="/new/images/mm_07.png"><span>更改密碼</span></a></li>
            <li><a href=""><img src="/new/images/mm_09.png"><span>VIP</span></a></li>
          </div>
          <div class="addpic g_inputt">
            <div class="n_adbut"><a href=""><img src="/new/images/1_06.png">預覽</a></div>
            <ul class="n_ulpic">
              <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_03.png"></div><b class="img" style="background:url(/new/images/ph_11.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_10.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_10.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_10.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li>
           </ul>
            <h2 class="h5" id="fileuploader-ajax">上傳照片<a href="javascript:;"  onclick="tour(fileuploader_ajax_tour)"><i class="ion ion-md-help-circle"></i></a></h2>
                                <div class="row mb-4">
                                    <div class="col-sm-12 col-lg-12">
                                        <form action="/Backend/Template/save_images">
                                            <input type="file" name="files[]" data-fileuploader-files=''>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                    </div>
                                </div>
           <a class="vipbut save_images abtn" onclick="">上傳照片</a>
          </div>
        </div>
      </div>

    </div>
  </div>
  <script src="/plugins/hopscotch/js/hopscotch.min.js"></script>
  <script src="/plugins/fileuploader2.2/dist/jquery.fileuploader.min.js"></script>
  <script src="/new/js/fileuploader-ajax-image.js?20191112"></script>
  <script type="text/javascript">
      /* 說明 */
      function tour(which) {
          hopscotch.startTour(which);
      }
      let a = fileuploaderAjaxImage($('input[name="files[]"]'), {
          data:{_token:"{{ csrf_token() }}"},
          url: '{!! url('fileuploader_image_upload') !!}',
      });


      $('.save_images').click(function(){
          return false;
          waitingDialog.show();

          var data = new FormData();
          var api = $.fileuploader.getInstance($('input[name="files[]"]'));
          var fileList = api.getFileList();
          var _list = [];
          var _editor = [];

          $.each(fileList, function(i, item) {
              _list.push(item.name);
              _editor.push(item.editor);
          });
          // 使用者選取的圖片
          data.append('fileuploader_images', JSON.stringify(_list));
          // 使用者選取圖片的裁切資訊
          data.append('fileuploader_editor', JSON.stringify(_editor));
          // 新增的圖片
          data.append('fileuploader_uploaded_images', JSON.stringify(api.getUploadedFiles().map(e=>e.name)));
          $.ajax({
              url: '/Backend/Template/save_images',
              type: 'POST',
              data: data,
              processData: false,
              contentType: false,
          }).then((result) => {
              waitingDialog.hide();

              ResultData(result);
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
      $(document).ready(function() {
        
      });

  </script>

@stop