@section('tip-invite')

@if($user->engroup == 1)
<form id="chemafeibtn" style="display: none;" action=<?php echo Config::get('social.payment.actionURL'); ?>  method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">
    <input type="hidden" name="to" value="@if(isset($cur)){{$cur->id}}@endif">
    <input type=hidden name="MerchantNumber" value="761404">
    <input type=hidden name="OrderNumber" value="<?php echo $orderNumber; ?>">
    <input type=hidden name="OrgOrderNumber" value="SG-車馬費({{$user->id}})">
    <input type=hidden name="ApproveFlag" value="1">
    <input type=hidden name="DepositFlag" value="1">
    <input type=hidden class="device" name="iphonepage" value="">
    <input type=hidden name="Amount" value=<?php echo Config::get('social.payment.tip-amount'); ?>>
    <input type=hidden name="op" value="AcceptPayment">
    <input type=hidden name="checksum" value="<?php print md5("761404".$orderNumber.$code.Config::get('social.payment.tip-amount')); ?>">
    <input type=hidden name="ReturnURL" value="{{ route('chatpay') }}">
    <input type=hidden name="OrderURL" value=<?php echo Config::get('social.payment.orderURL'); ?>>
    <!-- <button class="invite" type="submit" style="background: none; border: none; padding: 0">
        <i class="m-nav__link-icon flaticon-profile"></i>
        <span class="m-nav__link-text">車馬費邀請</span>
    </button> -->
</form>
<a href="#myModal"  name="submit" onclick="document.getElementById('chemafeibtn').submit();return false"  class=" btn btn-danger1 weui-f16 weui-box_s weui-ml10  cmfbtn" >車馬費邀請</a>

@endif

@show
