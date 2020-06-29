@extends('new.layouts.website')

@section('app-content')
{{--  <style type="text/css">--}}
{{--    li a{display: block;}--}}
{{--  </style>--}}
  <div class="container matop70 chat">
    <div class="row">
      <div class="col-sm-2 col-xs-2 col-md-2 dinone">
          @include('new.dashboard.panel')
      </div>
      <div class="col-sm-12 col-xs-12 col-md-10">
        <div class="n_zy"><span>瀏覽資料</span></div>
        <div class="n_zytab">
            <a class="item" href="{!! url('dashboard/announcement') !!}"><li><img src="/new/images/z_01.png"><span class="n_zylg">站方公告</span></li></a>
            <a class="item"href="{!! url('/dashboard/banned') !!}"><li style="float: right;"><img src="/new/images/z_02.png"><span class="n_zylg">懲處名單</span></li></a>
{{--            <a class="item" href="{!! url('dashboard/board') !!}"><li><img src="/new/images/z_03.png"><span>留言板</span></li></a>--}}
            <a class="item" href="{!! url('dashboard/newer_manual') !!}"><li><img src="/new/images/z_08.png"><span class="n_zylg">新手教學</span></li></a>
            <a class="item" href="{!! url('dashboard/anti_fraud_manual') !!}"><li style="float: right;"><img src="/new/images/z_07.png"><span class="n_zylg zpfont">拒絕詐騙手冊</span></li></a>
            <a class="item" href="{!! url('dashboard/web_manual') !!}"><li><img src="/new/images/z_09.png"><span class="n_zylg01">網站進階<font class="n_flbr">使用主頁</font></span></li></a>
            <a class="item" href="{!! url('dashboard/visited') !!}"><li style="float: right;"><img src="/new/images/z_04.png"><span class="n_zylg">誰來看我</span></li></a>

            @if (isset($user) && $user->isVip())
                <a class="item" href="{!! url('dashboard/fav') !!}"><li><img src="/new/images/z_05.png"><span class="n_zylg">收藏名單</span></li></a>
                <a class="item" href="{!! url('dashboard/block') !!}"><li style="float: right;"><img src="/new/images/z_06.png"><span class="n_zylg">封鎖名單</span></li></a>
            @endif
            {{-- <a class="item" href="{!! url('dashboard/posts_list') !!}"><li><img src="/new/images/letter.png"><span class="n_zylg">投稿文章</span></li></a> --}}
        </div>
      </div>
    </div>
  </div>
  <style>
      .item, .item:visited, item:hover{font-size: 16px;color:white;background-color: white;text-decoration: none;}
  </style>
@stop