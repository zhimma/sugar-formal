<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\LineNotifyService as LineNotify;

class HideOnlinePaymentTest extends TestCase
{
    public function testEcpayCheckoutHideOnlineSingleMonthATM()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "688",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "ALL",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "WebATM#Credit#CVS#BARCODE",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "one_month_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "PaymentInfoURL"=> "https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 688 元 x 1",
                "CheckMacValue"=> "76947D2AFBB83B71E7009A1B0DCD2397A4ADED5C045CA823F07BBA2B90002F04",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function testEcpayCheckoutHideOnlineSingleMonthCreditCard()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "688",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "ALL",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "WebATM#ATM#CVS#BARCODE",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "one_month_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "PaymentInfoURL"=> "https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 688 元 x 1",
                "CheckMacValue"=> "634716797C542D9E0995C23CD5FACF465FE0CC5F610B846641AEA89B95EF6B84",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function testEcpayCheckoutHideOnlineSingleMonthCVSorBarCode()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "688",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "ALL",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "WebATM#Credit#ATM",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "one_month_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "PaymentInfoURL"=> "https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 688 元 x 1",
                "CheckMacValue"=> "EBC0D9E399B7FD014BB74190C807D0F91B25A5CF39D4EDC299E5EEB6CD4CF69D",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function testEcpayCheckoutHideOnlineSingleQuarterATM()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "1164",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "ALL",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "WebATM#Credit#CVS#BARCODE",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "one_quarter_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "PaymentInfoURL"=> "https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 1164 元 x 1",
                "CheckMacValue"=> "D28E65EEEADCF32F7545D5EA82F08583BD148DEAB6D9A966B981E6A95AED78E7",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function testEcpayCheckoutHideOnlineSingleQuarterCreditCard()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                'PaymentType'=> "aio",
                "TotalAmount"=> "1164",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "ALL",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "WebATM#ATM#CVS#BARCODE",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "one_quarter_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "PaymentInfoURL"=> "https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 1164 元 x 1",
                "CheckMacValue"=> "E9DDEFF197F511FA39A512585983CE4A3107C006B02B68C95099200DB715926D",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function testEcpayCheckoutHideOnlineSingleCVSorBarCode()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "1164",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "ALL",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "WebATM#Credit#ATM",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "one_quarter_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "PaymentInfoURL"=> "https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 1164 元 x 1",
                "CheckMacValue"=> "B23F9BA8FA497C6E58236E748019A11FA7D47483687D023A0CB8EF87579D26BA",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function testEcpayCheckoutHideOnlineEveryMonth()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "688",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "Credit",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "cc_monthly_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 688 元 x 1",
                "Redeem"=> "",
                "UnionPay"=>"",
                "PeriodAmount"=> "688",
                "PeriodType"=> "M",
                "Frequency"=> "1",
                "ExecTimes"=> "99",
                "CreditInstallment"=> "",
                "InstallmentAmount"=> "0",
                "Language"=> "",
                "BindingCard"=> "",
                "MerchantMemberID"=> "",
                "PeriodReturnURL"=>"",
                "CheckMacValue"=> "60DFCFB2765B33DB18C0DB397B32B51F5F8DFAE77EC22CDC523098F02842C9CA",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
        
    }

    public function testEcpayCheckoutHideOnlineEveryQuarter()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=> "2000132",
                "EncryptType"=> "1",
                "ReturnURL"=> "https://sg-aws.test-tw.icu/dashboard/postValueAddedService",
                "ClientBackURL"=> "https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=> "",
                "MerchantTradeNo"=> "SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=> "aio",
                "TotalAmount"=> "1164",
                "TradeDesc"=> "SG-hideOnline(15598)",
                "ChoosePayment"=> "Credit",
                "Remark"=> "",
                "ChooseSubPayment"=> "",
                "NeedExtraPaidInfo"=> "N",
                "DeviceSource"=> "",
                "IgnorePayment"=> "",
                "InvoiceMark"=> "",
                "StoreID"=> "",
                "CustomField1"=> "15598",
                "CustomField2"=> "",
                "CustomField3"=> "cc_quarterly_payment",
                "CustomField4"=> "hideOnline",
                "HoldTradeAMT"=> "0",
                "ItemURL"=> "",
                "ItemName"=> "SG-hideOnline(15598) 1164 元 x 1",
                "Redeem"=> "",
                "UnionPay"=> "",
                "PeriodAmount"=> "1164",
                "PeriodType"=> "M",
                "Frequency"=> "3",
                "ExecTimes"=> "99",
                "CreditInstallment"=> "",
                "InstallmentAmount"=> "0",
                "Language"=> "",
                "BindingCard"=> "",
                "MerchantMemberID"=> "",
                "PeriodReturnURL"=> "",
                "CheckMacValue"=> "C72B7B7F162832E20A994D2141EA0FDFE74E0DE6329A00EFE1167E8E1D42385F",
                );

            $response = Http::post($url);
            $this->assertEquals(200, $response->status());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
