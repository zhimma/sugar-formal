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
          <div class="n_adbut"><a href="/dashboard/viewuser/{{$user->id}}" style="cursor:pointer"><img src="/new/images/1_06.png">預覽</a></div>
          <div class="n_adbut editAllBtn"><a style="cursor:pointer"><img src="/new/images/pencil-edit-button.png">編輯</a></div>
          <div class="n_adbut recoverAllBtn" style="display:none"><a style="cursor:pointer"><img src="/new/images/1_06.png">復原</a></div>
            <ul class="n_ulpic">
            @if($user->engroup==1)
                @php $count = 6-count($member_pics) @endphp
                  
                    @foreach($member_pics as $key=>$member_pic)
                    @if($key==0)
                    <li class="write_img editBtn" id="{{$member_pic->id}}"><div class="delpicBtn"><img src="/new/images/gb_icon01.png" width="30px" height="30px"></div><div class="n_ulhh"><img src="/new/images/ph_03.png"></div><b class="img" style="background:url('{{$member_pic->pic}}'); background-size:100% 100%"></b></li>
                    @else
                    <li class="write_img editBtn" id="{{$member_pic->id}}"><div class="delpicBtn"><img src="/new/images/gb_icon01.png" width="30px" height="30px"></div><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url('{{$member_pic->pic}}'); background-size:100% 100%"></b></li>
                    @endif
                    @endforeach

                @if($count>0)
                    @for($i=0;$i<$count;$i++)
                    <li class="write_img"><div class="n_ulhh"><img src="@if($i==0 && $count==6) /new/images/ph_03.png @else /new/images/ph_05.png @endif"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li>
                    @endfor
                @endif 
            @else
                @php 
                    $count = 6-count($member_pics);
                    $count_vip = 4-count($member_pics);
                @endphp
                
                    @foreach($member_pics as $key=>$member_pic)
                    @if($key==0)
                    <li class="write_img editBtn" id="{{$member_pic->id}}"><div class="delpicBtn"><img src="/new/images/gb_icon01.png" width="30px" height="30px"></div><div class="n_ulhh"><img src="/new/images/ph_03.png"></div><b class="img" style="background:url('{{$member_pic->pic}}'); background-size:100% 100%"></b></li>
                    @else
                    <li class="write_img editBtn" id="{{$member_pic->id}}"><div class="delpicBtn"><img src="/new/images/gb_icon01.png" width="30px" height="30px"></div><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url('{{$member_pic->pic}}'); background-size:100% 100%"></b></li>
                    @endif
                    @endforeach

                @if($count>0)
                    @if($count_vip>0)
                        @for($i=0;$i<$count_vip;$i++)
                        <li class="write_img"><div class="n_ulhh"><img src="@if($i==0 &&$count_vip==4) /new/images/ph_03.png @else /new/images/ph_05.png @endif"></div><b class="img" style="background:url('@if($i==0 &&$count_vip==4) /new/images/ph_11.png @else /new/images/ph_10.png @endif'); background-size:100% 100%"></b></li>
                        @endfor
                        @for($i=0;$i<($count-$count_vip);$i++)
                        <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li>
                        @endfor
                    @else
                        @for($i=0;$i<$count;$i++)
                        <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li>
                        @endfor
                    @endif
                @endif 
            @endif
            
               <!-- <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_10.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_10.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_10.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li>
               <li class="write_img"><div class="n_ulhh"><img src="/new/images/ph_05.png"></div><b class="img" style="background:url(/new/images/ph_12.png); background-size:100% 100%"></b></li> -->
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
           <a class="vipbut save_images abtn">上傳照片</a>
          </div>
        </div>
      </div>

    </div>
  </div>
  
  <script src="/plugins/hopscotch/js/hopscotch.min.js"></script>
  <script src="/plugins/fileuploader2.2/src/jquery.fileuploader.js"></script>
  <script src="/new/js/fileuploader-ajax-image.js"></script>
  <script type="text/javascript">

     
   
      /* 說明 */
      function tour(which) {
          hopscotch.startTour(which);
      }
      let a = fileuploaderAjaxImage($('input[name="files[]"]'), {
          data:{_token:"{{ csrf_token() }}"},
          url: '{!! url('fileuploader_image_upload') !!}',
      });
      var toArray = function (Ob) {
            try {
                return Array.prototype.slice.call(Ob);
            } catch (e) {
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

      $('.save_images').on('click',function(){
        //   console.log('123');
        //   return false;
        //   waitingDialog.show();

          var data = new FormData();
          var api = $.fileuploader.getInstance($('input[name="files[]"]'));
          var fileList = api.getFileList();
          console.log('1',api.getOptions());
          console.log('2',api.getInputEl());
          console.log('3',api.getNewInputEl());
          console.log('4',api.getListEl());
          console.log('5',api.getListInputEl());
          console.log('6',api.getFiles());
          console.log('7',api.getChoosedFiles());
          console.log('8',api.getAppendedFiles());
          var _name = [];
          var _reader = [];
          console.log(data)
          console.log(fileList)
          $.each(fileList, function(i, item) {
              _name.push(item.file.name);
              _reader.push(item.reader.src);
          });
          
        //   console.log(_list, _editor, JSON.stringify(_editor));
          // 使用者選取的圖片
        //   data.append('fileuploader_images', JSON.stringify(_list));
        //   console.log(data);
          // 使用者選取圖片的裁切資訊
        //   data.append('fileuploader_editor', JSON.stringify(_editor));
          // 新增的圖片
        //   console.log(data);
        //   data.append('fileuploader_uploaded_images', JSON.stringify(api.getUploadedFiles().map(e=>e.name)));
        //   console.log(data);
        //   data = toArray(fileList);
        //   console.log(data[0]);
        //   data_array = JSON.parse(data[0])
        //   console.log(data_array);
            data['name'] = _name;
            data['reader']=_reader;
            console.log(data);
            
          $.ajax({
              url: '/dashboard/save_img',
              type: 'POST',
            //   dataType:'json',
              data: {
                  'data':JSON.stringify(data),
                //   "editor": JSON.stringify(_editor),
                  "_token": "{{ csrf_token() }}"
                  },
                success: function(res){

                    
                    
            //         console.log(res['code']);
              res = JSON.parse(res);
              waitingDialog.hide();
              if(res.code=='200'){
                  c2('上傳成功');
                // Swal.fire({
                // position: 'center',
                // icon: 'success',
                // title: '上傳成功',
                // showConfirmButton: false,
                // timer: 1500
                // });
                // window.location.reload();
              }else if(res.code=='400'){
                  c2('超過數量限制');
                // Swal.fire({
                // position: 'center',
                // icon: 'success',
                // title: '超過數量限制',
                // showConfirmButton: false,
                // timer: 1500
                // });
                // window.location.reload();
              }else if(res.code=='600'){
                  c2('請選取照片');
                // Swal.fire({
                // position: 'center',
                // icon: 'success',
                // title: '請選取照片',
                // showConfirmButton: false,
                // timer: 1500
                // });
                // window.location.reload();
              }else if(res.code=='800'){
                  c2('照片上傳成功，已升級為VIP會員');
                // Swal.fire({
                // position: 'center',
                // icon: 'success',
                // title: '照片上傳成功<br>已升級為VIP會員',
                // showConfirmButton: false,
                // timer: 1500
                // });
                // window.location.reload();
              }else{
                  c2('上傳失敗');
                // Swal.fire({
                // position: 'top-end',
                // icon: 'failed',
                // title: '上傳失敗',
                // showConfirmButton: false,
                // timer: 1500
                // });
                // window.location.reload();
              }
                },
            //   processData: false,
            //   contentType: false,
          });
           
              
              
// console.log('success')
            //   ResultData('this is test');
              
          
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
    <script>
             $(document).ready(function(){
              $(".delpicBtn").on('click', function(){
              var id = $(this).parents('.write_img').attr('id');
               

              // var r=confirm("確認刪除此照片？")
                  c4('確認刪除此照片？');
              // if (r==true)
              // {
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
              // }

              
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