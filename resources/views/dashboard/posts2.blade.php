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
    
    <script src='/plugins/tinymce/tinymce.js' referrerpolicy="origin">
    </script>
    <script>
        tinymce.init({
        selector: '#contents',
        language: 'zh_TW'
        });
    </script>
	
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
          <form action="/dashboard/doPosts?{{ csrf_token() }}={{ \Carbon\Carbon::now()->timestamp }}" method="POST">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <h1>投稿</h1>
          <input type="text" name="title">
          <div>
            <textarea name="contents" id="contents" required></textarea>
          </div>
          <input type="checkbox" name="anonymous">匿名於站內發布</br>
          <input type="checkbox" name="combine">站內發布並與本站帳號連結</br>
          <input type="checkbox" name="agreement">同意站方匿名行銷使用(男會員贈送一個月vip，女會員給一個 tag)</br>
          <input type="submit">
          </form>
        </div>
        
      </div>

    </div>
  </div>
  
  <script src="/plugins/hopscotch/js/hopscotch.min.js"></script>
  <script src="/plugins/fileuploader2.2/src/jquery.fileuploader.js"></script>
  <script src="/new/js/fileuploader-ajax-image.js"></script>

@stop