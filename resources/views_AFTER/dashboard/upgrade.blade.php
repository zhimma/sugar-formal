

@extends('layouts.newmaster')

@section('app-content')

<?php


$umeta = $user->meta_();
$orderNumber = \App\Models\Vip::lastid();
$code = Config::get('social.payment.code');

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


<div class="container weui-pt30">
  <div class="minh weui-pt30 weui-pb30">
       <div class="row">
           <div class="col-lg-6 col-md-6 col-sm-6 weui-lh30">
                <ul class="vipul">
                    <li class="on">888 $NTD / 每月</li>
                    <li>1888 $NTD / 每月</li>
                    <li>2888 $NTD / 每月</li>
                </ul>
                <!-- <p>此處可寫壹些說明性文字客戶自由添加此處可寫壹些說明性文字客戶自由添加此處可寫壹些說明性文字客戶自由添加此處可寫壹些說明性文字客戶自由添加</p> -->
                <p class="weui-pt15">
                    <p>可支持支付類型</p>
                    <img src="/images/shengji_11.png">
                    <img src="/images/shengji_13.png">
                    <img src="/images/shengji_15.png">
                </p>
                <p class="weui-pt30">
                <!-- <a href="#" class="btn btn-danger weui-f16 weui-box_s">購買</a> -->
                <form style="display: inline-block;" action=<?php echo Config::get('social.payment.actionURL'); ?> method=post>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type=hidden name="ReturnURL" value=<?php echo Config::get('social.payment.returnURL'); ?>>
                    <input type=hidden name="OrderURL" value=<?php echo Config::get('social.payment.orderURL'); ?>>
                    <input type=hidden name="MerchantNumber" value="761404">
                    <input type=hidden name="OrderNumber"    value="<?php echo "30".$orderNumber; ?>">
                    <input type=hidden name="OrgOrderNumber" value="SG-VIP({{$user->id}})">
                    <input type=hidden name="ApproveFlag" value="1">
                    <input type=hidden name="DepositFlag" value="1">
                    <input type=hidden class="device" name="iphonepage" value="">
                    <input type=hidden name="Period" value="30">
                    <input type=hidden name="Amount" value=<?php echo Config::get('social.payment.vip-amount'); ?>>
                    <input type=hidden name="op" value="AcceptPayment">
                    <input type=hidden name="checksum" value="<?php print md5("761404"."30".$orderNumber.$code.Config::get('social.payment.vip-amount')); ?>">
                    <input type=hidden name="Englishmode" value="0">
                            <button type="submit" class="btn btn-danger weui-f16 weui-box_s  m-btn m-btn--air m-btn--custom upgradevip">購買</button>&nbsp;&nbsp;
                </form>

                </p>
           </div>
           <div class="col-lg-6 col-md-6 col-sm-6 m_none">
                <img src="/images/shengji_05.png" class="m_img ">
           </div>
       </div>
  </div>
</div>






@stop



@section('javascript')

<script>
       $('.vipul li').click(function(e) {
        $('.vipul li').removeClass('on');
        $(this).addClass('on');
    });
    </script>

@stop
