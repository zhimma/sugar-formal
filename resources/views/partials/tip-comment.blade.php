@section('tip-comment')

@if($user->engroup == 1)
<?
    $orderNumber = $orderNumber.time();
?>
<form action=<?php echo Config::get('social.payment.actionURL'); ?> class="m-nav__link nav-link m-tabs__link" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">
    <input type="hidden" name="to" value="@if(isset($to)){{$to->id}}@endif">
    <input type=hidden name="MerchantNumber" value="761404">
    <input type=hidden name="OrderNumber" value="<?php echo $orderNumber; ?>">
    <input type=hidden name="OrgOrderNumber" value="SG-車馬費評價({{$user->id}})">
    <input type=hidden name="ApproveFlag" value="1">
    <input type=hidden name="DepositFlag" value="1">
    <input type=hidden class="device" name="iphonepage" value="">
    <input type=hidden name="Amount" value=<?php echo Config::get('social.payment.tip-amount'); ?>>
    <input type=hidden name="op" value="AcceptPayment">
    <input type=hidden name="checksum" value="<?php print md5("761404".$orderNumber.$code.Config::get('social.payment.tip-amount')); ?>">
    <input type=hidden name="ReturnURL" value="{{ route('chatpay') }}">
    <input type=hidden name="OrderURL" value=<?php echo Config::get('social.payment.orderURL'); ?>>
    <a href="#m_modal_2" class="m-nav__link" data-toggle="modal" data-target="">
        <i class="m-nav__link-icon flaticon-comment"></i>
        <span class="m-nav__link-text">車馬費評價</span>
    </a>
</form>
@endif

@show
