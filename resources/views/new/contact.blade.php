@extends('new.layouts.website')

@section('app-content')

	<div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy">
                       <div class="wxsy_title">聯絡我們</div>
                       <div class="wxsy_k">
                            <div class="wknr">
                                @if(Auth::check() && isset($user))
                                    <div class="wk_lx"><img src="/new/images/lx.png"><span>站長Line</span><font>@giv4956r (包含@哦)</font></div>
                                    <div class="wk_lx"><img src="/new/images/lo_10.png"><span>站長信箱</span><font>admin@sugar-garden.org</font></div>
                                    <div class="wk_lx"><img src="/new/images/lo_1x.png"><span>網站問題回報</span><font>@giv4956r (包含@哦)</font></div>
                                    <div class="wk_lx"><img src="/new/images/lo_1x.png"><span>網站問題回報</span><font>admin@sugar-garden.org</font></div>
                                @else
                                    {{-- no login --}}
                                    <p>請註冊會員，或者參考<a href="http://blog-tw.net/Sugar/">站長的碎碎念</a></p>
                                @endif
                            </div>
                       </div>
                </div>
            </div>
        </div>
  </div>

@stop