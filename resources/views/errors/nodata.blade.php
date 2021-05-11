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
                            <p>此帳號已被站方封鎖，或使用者關閉。不再開放查詢。</p>
                            <a class="btn btn-success" href="{!! url('contact') !!}" role="button">聯繫站長</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


