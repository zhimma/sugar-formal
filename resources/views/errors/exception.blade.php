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
                                <h5>很抱歉，網站發生錯誤，請與站長聯繫(右下角聯絡我們)，並提供以下資訊：</h5>
                                <h4>時間：{{ \Carbon\Carbon::now()->toDateTimeString() }}</h4>
                                <h4>錯誤類型：{{ substr($exception, 0, strpos($exception, ':')) }}</h4>
                            @else
                                <h5>近期系統轉換升級~~如果認證失敗。請兩分鐘後重試。其他錯誤請與站長聯繫(右下角聯絡我們)</h5>
                            @endif
                            <h4>如遭遇登入問題，請先嘗試改用此頁面登入：<a href="{{ route("loginIOS") }}">點此進入</a></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop