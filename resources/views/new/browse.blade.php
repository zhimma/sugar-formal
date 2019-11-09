@extends('new.layouts.website')

@section('app-content')

  <div class="container matop70 chat">
    <div class="row">
      <div class="col-sm-2 col-xs-2 col-md-2 dinone">
          @include('new.dashboard.panel')
      </div>
      <div class="col-sm-12 col-xs-12 col-md-10">
        <div class="n_zy"><span>瀏覽資料</span></div>
        <div class="n_zytab">
            <li><a href=""><img src="/new/images/z_01.png"><span>站方公告</span></a></li>  
            <li><a href=""><img src="/new/images/z_02.png"><span>懲處名單</span></a></li>  
            <li><a href=""><img src="/new/images/z_03.png"><span>留言板</span></a></li>  
            <li><a href=""><img src="/new/images/z_04.png"><span>誰來看我</span></a></li>  
            <li><a href=""><img src="/new/images/z_05.png"><span>收藏名單</span></a></li>  
            <li><a href=""><img src="/new/images/z_06.png"><span>封鎖名單</span></a></li>  
        </div>
      </div>
    </div>
  </div>

@stop