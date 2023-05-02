<?php

test('BasicTest', function () {
    expect(true)->toBeTrue();
});

test('BasicFalseTest', function () {
    expect(false)->toBeFalse();
});

//use function Pest\Laravel\{post};

test('Upgradepay', function () {
    try{
        $this->actingAs(\App\Models\User::find(15601))->post( '/dashboard/upgradepay', [
                    'final_result' => '0', 'P_MerchantNumber' => '761404', 'P_OrderNumber' => '3019138', 'P_Amount' => '888.00', 'P_CheckSum' => 'fd635143a3f7bafcbdb8b39e448a7c8b', 'final_return_PRC' => '8', 'final_return_SRC' => '204', 'final_return_ApproveCode' => '', 'final_return_BankRC' => '', 'final_return_BatchNumber' => '', 'final_redemption_point' => '', 'final_redemption_amount' => '', 'final_redemption_remain' => '', 'final_redemption_payamount' => '', '_token' => 'sdf23'
                ])->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }    
});
