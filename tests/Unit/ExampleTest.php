<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Unit\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testUpgradepay()
    {
        $response = $this->call('POST', '/dashboard/upgradepay', [
            'final_result' => '0', 'P_MerchantNumber' => '761404', 'P_OrderNumber' => '3019138', 'P_Amount' => '888.00', 'P_CheckSum' => 'fd635143a3f7bafcbdb8b39e448a7c8b', 'final_return_PRC' => '8', 'final_return_SRC' => '204', 'final_return_ApproveCode' => '', 'final_return_BankRC' => '', 'final_return_BatchNumber' => '', 'final_redemption_point' => '', 'final_redemption_amount' => '', 'final_redemption_remain' => '', 'final_redemption_payamount' => '', '_token' => 'sdf23'
        ]);
        $this->assertEquals(200, $response->status());
    }
}
