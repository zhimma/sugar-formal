@include('partials.newheader')
<body>
    
    <div class="centerbg">
        <div class="weui-pt30 weui-pb30 container">

            <div class="row">
                <div class="col-md-12">
                    @include('partials.errors')
                    @include('partials.message')
                </div>
            </div>
        @include('layouts.newnavigation')
<?php
$block_people =  Config::get('social.block.block-people');
$admin_email = Config::get('social.admin.email');

if (isset($to)) $orderNumber = $to->id;
else $orderNumber = "";
$code = Config::get('social.payment.code');
$umeta = $user->meta_();

?>
<div class="photo weui-t_c">
            <img src="{{$umeta->pic}}">
            <p class="weui-pt20 weui-f18">{{$user->name}}</p>
            @if ((isset($cur) && $cur->isVip()) || $user->isVip()) 
                <p class="weui-pt10 m_p">
                    <span class="weui-pl10 weui-pr10">
                        <img src="/images/sousuo_03.png">
                        <span class="weui-v_m gj">高级会员</span>
                    </span>
                    <!-- <span class="weui-pl10 weui-pr10">
                        <img src="//images/sousuo_06.png">
                        <span class="weui-v_m bzj"> 保证金</span>
                    </span>
                    <span class="weui-pl10 weui-pr10">
                        <img src="//images/sousuo_08.png">
                        <span class="weui-v_m bwj"> 百万级</span>
                    </span> -->
                </p>
            @endif
        </div>
    </div>
</div>


<div class="container weui-pb30">

<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 style="text-align:left;" class="m-portlet__head-text">
                檢舉訊息 - 請填寫理由
            </h3>
            <span style="text-align:right;float: right;" class="m-portlet__head-text ">
                <a class="btn btn-danger m-btn m-btn--air m-btn--custom" href="/dashboard/chat/{{ $sid }}"> 回去訊息內容</a>
            </span>
        </div>
    </div>
</div>
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportMessage') }}">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="hidden" name="sid" value="{{ $sid }}">
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-lg-9">
                <textarea class="form-control m-input" rows="4" id="content" required name="content" maxlength="500"></textarea>
            </div>
        </div>

        <div class="m-form__actions">
            <div class="row">
                <div class="col-lg-9">
                    <button id="msgsnd" type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom">送出檢舉</button>&nbsp;&nbsp;
                    <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
                </div>
            </div>
        </div>
    </div>
</form>
</div>


