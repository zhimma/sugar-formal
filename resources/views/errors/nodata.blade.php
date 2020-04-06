@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy" style="min-height: 0!important;">
                    <div class="wxsy_title">
                        錯誤：沒有資料
                    </div>
                    <div class="wxsy_k">
                        <div class="wknr">
                            <p>系統中沒有符合的資料，若您確定問題出在本站，敬請聯擊站長，並協助我們解決問題，謝謝。</p>
                            <a class="btn btn-success" href="{!! url('contact') !!}" role="button">聯繫站長</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


