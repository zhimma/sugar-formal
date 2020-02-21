@extends('new.layouts.website')

@section('app-content')

    <div class="container matop120">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="dengl matbot140">

    <div class="col-sm-12 col-xs-12 col-md-12">
        <div class="wd_xsy">
            <img src="/new/images/18.png" class="eigimg">
            <div class="eigfont">此頁面含有限制級內容限年滿18歲以上或達當地國家法定年齡人士閱讀，若您未滿18歲請勿進入！</div>
            <div class="eigbutton">
                <a class="egbuic egbleft" href="{!! url('register') !!}"><font>YES</font><span>我已滿十八歲</span></a>
                <a class="egbuic egbright" href="{!! url('/') !!}"><font>NO</font><span>我未滿十八歲</span></a>
            </div>

        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .bot {
        position:fixed;
        left:0px;
        bottom:0px;
        height:30px;
        width:100%;
    }
</style>

@stop
