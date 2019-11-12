@extends('new.layouts.website')

@section('app-content')

	<div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy">
                   <div class="wxsy_title">驗證成功</div>
                   <div class="wxsy_k">
                        <div class="wknr001">
                            <div class="wktk">{{ $user->name }}註冊完成</div>
                            <h3 class=" ye_d">請再次確認您的帳號為： <span class="yzred "><a style="font-weight: bold">{{ $user->email }}</a></span></h3>
                            <h3 class="ye_d">現在您可以正常使用您的帳號了，按<a href="{!! url('login') !!}" style="color: red; font-weight: bold;">這裡登入</a>，以開始您的第一步。</h3>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
@stop