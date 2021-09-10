@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy" style="min-height: 0!important;">
                    <div class="wxsy_title">
                        @if(isset($exception))
                            發生錯誤
                        @else
                            發生不明錯誤
                        @endif
                    </div>
                    <div class="wxsy_k">
                        <div class="wknr">
                            @if(isset($exception))
                                <h5>網站目前正在更新，請半小時後重試。如果還是出現此畫面，請與站長聯繫(右下角聯絡我們)，並提供以下資訊：</h5>
                                <h4>時間：{{ \Carbon\Carbon::now()->toDateTimeString() }}</h4>
                                <h4>錯誤類型：{{ substr($exception, 0, strpos($exception, ':')) }}</h4>
                                {{ logger('??? error occurred, url: ' . url()->current()) }}
                                @if(isset($user))
                                    {{ logger('user id: ' . $user->id) }}
                                @endif
                            @else
                                <h4>這是系統錯誤頁面</h4>
                                <ul>
                                    <li>1. 如果您是登入失敗，請點此連結：<a href="{{ route("loginIOS") }}" style="font-weight: bold;">重新登入</a></li>
                                    <li>2. 如果不是登入失敗，請十分鐘後重新嘗試原先操作</li>
                                    <li>3. 如果都還不行，請與站長連繫：<a href="{!! url('contact') !!}" style="font-weight: bold;">聯絡我們</a></li>
                                </ul>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop