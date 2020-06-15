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
            <li><a href="{!! url('dashboard/announcement') !!}"><img src="/new/images/z_01.png"><span class="n_zylg">站方公告</span></a></li>
            <li><a href="{!! url('/dashboard/banned') !!}"><img src="/new/images/z_02.png"><span class="n_zylg">懲處名單</span></a></li>
{{--            <li><a href="{!! url('dashboard/board') !!}"><img src="/new/images/z_03.png"><span>留言板</span></a></li>--}}
            <li><a href="{!! url('dashboard/newer_manual') !!}"><img src="/new/images/z_08.png"><span class="n_zylg">新手教學</span></a></li>
            <li><a href="{!! url('dashboard/anti_fraud_manual') !!}"><img src="/new/images/z_07.png"><span class="n_zylg zpfont">拒絕詐騙手冊</span></a></li>
            <li><a href="{!! url('dashboard/web_manual') !!}"><img src="/new/images/z_09.png"><span class="n_zylg01">網站進階<font class="n_flbr">使用主頁</font></span></a></li>
            <li><a href="{!! url('dashboard/visited') !!}"><img src="/new/images/z_04.png"><span class="n_zylg">誰來看我</span></a></li>

            @if (isset($user) && $user->isVip())
                <li><a href="{!! url('dashboard/fav') !!}"><img src="/new/images/z_05.png"><span class="n_zylg">收藏名單</span></a></li>
                <li><a href="{!! url('dashboard/block') !!}"><img src="/new/images/z_06.png"><span class="n_zylg">封鎖名單</span></a></li>
            @endif
            {{-- <li><a href="{!! url('dashboard/posts_list') !!}"><img src="/new/images/letter.png"><span class="n_zylg">投稿文章</span></a></li> --}}
        </div>
      </div>
    </div>
  </div>

@stop