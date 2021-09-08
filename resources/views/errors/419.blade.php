@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy" style="min-height: 0!important;">
                    <div class="wxsy_title">
                        419
                    </div>
                    {{ logger('419 occurred, url: ' . url()->current()) }}
                    @if(\Auth()::user())
                        {{ logger('user id: ' . \Auth()::user()->id) }}
                    @endif
                    <div class="wxsy_k">
                        <div class="wknr">
                            <h4>網頁過期</h4>
                            <ul>
                                <li>您可能閒置過久導致頁面過期，<a href="{!! url('login') !!}" style="font-weight: bold;">請點此重新登入</a>。</li>
                                <li>如果都還不行，請與站長連繫：<a href="{!! url('contact') !!}" style="font-weight: bold;">聯絡我們</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop