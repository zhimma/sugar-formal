@extends('layouts.master')

@section('app-content')

<?php
$orderNumber = \App\Models\Vip::lastid();
$code = Config::get('social.payment.code');

?>

<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
                升級 VIP
            </h3>
        </div>
    </div>
</div>

<div class="m-portlet__body">

@if(!$user->isVip() && $user->engroup == 1)
<div class="row">
   	<div class="col-lg-12">
   		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							網站專屬 Vip
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<h3>價格: 888 $NTD / 每月</h3><br>
				<form class="m-form m-form--fit" action=<?php echo Config::get('social.payment.actionURL'); ?> method=post>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type=hidden name="ReturnURL" value=<?php echo Config::get('social.payment.returnURL'); ?>>
                    <input type=hidden name="OrderURL" value=<?php echo Config::get('social.payment.orderURL'); ?>>
                    <input type=hidden name="MerchantNumber" value="761404">
                    <input type=hidden name="OrderNumber"    value="<?php echo "30".$orderNumber; ?>">
                    <input type=hidden name="OrgOrderNumber" value="22222">
                    <input type=hidden name="ApproveFlag" value="1">
                    <input type=hidden name="DepositFlag" value="1">
                    <input type=hidden class="device" name="iphonepage" value="">
                    <input type=hidden name="Period" value="30">
                    <input type=hidden name="Amount" value=<?php echo Config::get('social.payment.vip-amount'); ?>>
                    <input type=hidden name="op" value="AcceptPayment">
                    <input type=hidden name="checksum" value="<?php print md5("761404"."30".$orderNumber.$code.Config::get('social.payment.vip-amount')); ?>">
                    <input type=hidden name="Englishmode" value="0">

    				<div class="m-form__actions">
                    <div class="row">
                        <div class="col-9">
                            <button type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom upgradevip">購買</button>&nbsp;&nbsp;
                            <img src="/img/cclogos.jpg" style="width: 50%; margin-bottom: 0">
                        </div>
                    </div>
                </form>
            </div>
			</div>
		</div>
	</div>
</div>

@elseif(!$user->isVip() && $user->engroup == 2)
<div class="row">
   	<div class="col-lg-6">
   		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							方案一
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<h3>價格: 888 $NTD / 每月</h3><br>
				<form class="m-form m-form--fit" action=<?php echo Config::get('social.payment.actionURL'); ?> method=post>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <input type=hidden name="ReturnURL" value=<?php echo Config::get('social.payment.returnURL'); ?>>
                        <input type=hidden name="OrderURL" value=<?php echo Config::get('social.payment.orderURL'); ?>>
                        <input type=hidden name="MerchantNumber" value="761404">
                        <input type=hidden name="OrderNumber"    value="<?php echo "30".$orderNumber; ?>">
                        <input type=hidden name="OrgOrderNumber" value="Test Order">
                        <input type=hidden name="ApproveFlag" value="1">
                        <input type=hidden name="DepositFlag" value="1">
                        <input type=hidden class="device" name="iphonepage" value="">
                        <input type=hidden name="Period" value="30">
                        <input type=hidden name="Amount" value=<?php echo Config::get('social.payment.vip-amount'); ?>>
                        <input type=hidden name="op" value="AcceptPayment">
                        <input type=hidden name="checksum" value="<?php print md5("761404"."30".$orderNumber.$code.Config::get('social.payment.vip-amount')); ?>">
                        <input type=hidden name="Englishmode" value="0">
				<div class="m-form__actions">
                <div class="row">
                    <div class="col-9">
                        <button type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom upgradevip">購買</button>&nbsp;&nbsp;
                        <img src="/img/cclogos.jpg" style="width: 50%; margin-bottom: 0">
                    </div>
                </div>
                </form>
            </div>
			</div>
		</div>
	</div>

        <div class="col-lg-6">
       		<div class="m-portlet m-portlet--mobile">
    			<div class="m-portlet__head">
    				<div class="m-portlet__head-caption">
    					<div class="m-portlet__head-title">
    						<h3 class="m-portlet__head-text">
    							方案二
    						</h3>
    					</div>
    				</div>
    			</div>
    			<div class="m-portlet__body">
    				<h3>說明</h3>
                    <h5>上傳大頭貼 + 三張生活照，即可試用幾天VIP</h5>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-9">
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>

	</div>
@endif
</div>



@stop

@section('javascript')

<script>

</script>

@stop
