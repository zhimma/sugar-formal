<?php
    use Illuminate\Support\Facades\Http;

    test('EcpayCheckoutSingleMonthATM',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=>"2000132",
                "EncryptType"=>"1",
                "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=>"",
                "MerchantTradeNo"=>"SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=>"aio",
                "TotalAmount"=>"1388",
                "TradeDesc"=>"SG-VIP(15598)",
                "ChoosePayment"=>"ALL",
                "Remark"=>"",
                "ChooseSubPayment"=>"",
                "NeedExtraPaidInfo"=>"N",
                "DeviceSource"=>"",
                "IgnorePayment"=>"WebATM#Credit#CVS#BARCODE",
                "InvoiceMark"=>"",
                "StoreID"=>"",
                "CustomField1"=>"15598",
                "CustomField2"=>"",
                "CustomField3"=>"one_month_payment",
                "CustomField4"=>"",
                "HoldTradeAMT"=>"0",
                "PaymentInfoURL"=>"https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=>"",
                "ItemName"=>"SG-VIP(15598) 1388 元 x 1",
                "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeSingleMonthCreditCard',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=>"2000132",
                "EncryptType"=>"1",
                "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=>"",
                "MerchantTradeNo"=>"SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=>"aio",
                "TotalAmount"=>"1388",
                "TradeDesc"=>"SG-VIP(15598)",
                "ChoosePayment"=>"ALL",
                "Remark"=>"",
                "ChooseSubPayment"=>"",
                "NeedExtraPaidInfo"=>"N",
                "DeviceSource"=>"",
                "IgnorePayment"=>"WebATM#ATM#CVS#BARCODE",
                "InvoiceMark"=>"",
                "StoreID"=>"",
                "CustomField1"=>"15598",
                "CustomField2"=>"",
                "CustomField3"=>"one_month_payment",
                "CustomField4"=>"",
                "HoldTradeAMT"=>"0",
                "PaymentInfoURL"=>"https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=>"",
                "ItemName"=>"SG-VIP(15598) 1388 元 x 1",
                "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeSingleMonthCVSorBarCode',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=>"2000132",
                "EncryptType"=>"1",
                "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=>"",
                "MerchantTradeNo"=>"SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=>"aio",
                "TotalAmount"=>"1388",
                "TradeDesc"=>"SG-VIP(15598)",
                "ChoosePayment"=>"ALL",
                "Remark"=>"",
                "ChooseSubPayment"=>"",
                "NeedExtraPaidInfo"=>"N",
                "DeviceSource"=>"",
                "IgnorePayment"=>"WebATM#Credit#ATM",
                "InvoiceMark"=>"",
                "StoreID"=>"",
                "CustomField1"=>"15598",
                "CustomField2"=>"",
                "CustomField3"=>"one_month_payment",
                "CustomField4"=>"",
                "HoldTradeAMT"=>"0",
                "PaymentInfoURL"=>"https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=>"",
                "ItemName"=>"SG-VIP(15598) 1388 元 x 1",
                "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeSingleQuarterATM',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=>"2000132",
                "EncryptType"=>"1",
                "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=>"",
                "MerchantTradeNo"=>"SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=>"aio",
                "TotalAmount"=>"2964",
                "TradeDesc"=>"SG-VIP(15598)",
                "ChoosePayment"=>"ALL",
                "Remark"=>"",
                "ChooseSubPayment"=>"",
                "NeedExtraPaidInfo"=>"N",
                "DeviceSource"=>"",
                "IgnorePayment"=>"WebATM#Credit#CVS#BARCODE",
                "InvoiceMark"=>"",
                "StoreID"=>"",
                "CustomField1"=>"15598",
                "CustomField2"=>"",
                "CustomField3"=>"one_quarter_payment",
                "CustomField4"=>"",
                "HoldTradeAMT"=>"0",
                "PaymentInfoURL"=>"https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=>"",
                "ItemName"=>"SG-VIP(15598) 2964 元 x 1",
                "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeSingleQuarterCreditCard',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                "MerchantID"=>"2000132", 
                "EncryptType"=>"1",
                "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                "OrderResultURL"=>"",
                "MerchantTradeNo"=>"SG".time(),
                "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                "PaymentType"=>"aio",
                "TotalAmount"=>"2964",
                "TradeDesc"=>"SG-VIP(15598)",
                "ChoosePayment"=>"ALL",
                "Remark"=>"",
                "ChooseSubPayment"=>"",
                "NeedExtraPaidInfo"=>"N",
                "DeviceSource"=>"",
                "IgnorePayment"=>"WebATM#ATM#CVS#BARCODE",
                "InvoiceMark"=>"",
                "StoreID"=>"",
                "CustomField1"=>"15598",
                "CustomField2"=>"",
                "CustomField3"=>"one_quarter_payment",
                "CustomField4"=>"",
                "HoldTradeAMT"=>"0",
                "PaymentInfoURL"=>"https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                "ItemURL"=>"",
                "ItemName"=>"SG-VIP(15598) 2964 元 x 1",
                "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeSingleQuarterCVSorBarCode',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                    "MerchantID"=>"2000132",
                    "EncryptType"=>"1",
                    "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                    "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                    "OrderResultURL"=>"",
                    "MerchantTradeNo"=>"SG".time(),
                    "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                    "PaymentType"=>"aio",
                    "TotalAmount"=>"2964",
                    "TradeDesc"=>"SG-VIP(15598)",
                    "ChoosePayment"=>"ALL",
                    "Remark"=>"",
                    "ChooseSubPayment"=>"",
                    "NeedExtraPaidInfo"=>"N",
                    "DeviceSource"=>"",
                    "IgnorePayment"=>"WebATM#Credit#ATM",
                    "InvoiceMark"=>"",
                    "StoreID"=>"",
                    "CustomField1"=>"15598",
                    "CustomField2"=>"",
                    "CustomField3"=>"one_quarter_payment",
                    "CustomField4"=>"",
                    "HoldTradeAMT"=>"0",
                    "PaymentInfoURL"=>"https://sg-aws.test-tw.icu/dashboard/paymentInfoEC",
                    "ItemURL"=>"",
                    "ItemName"=>"SG-VIP(15598) 2964 元 x 1",
                    "CheckMacValue"=>"",
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeEveryMonth',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                    "MerchantID"=>"2000132",
                    "EncryptType"=>"1",
                    "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                    "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                    "OrderResultURL"=>"",
                    "MerchantTradeNo"=>"SG".time(),
                    "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                    "PaymentType"=>"aio",
                    "TotalAmount"=>"1388",
                    "TradeDesc"=>"SG-VIP(15598)",
                    "ChoosePayment"=>"Credit",
                    "Remark"=>"",
                    "ChooseSubPayment"=>"",
                    "NeedExtraPaidInfo"=>"N",
                    "DeviceSource"=>"",
                    "IgnorePayment"=>"",
                    "InvoiceMark"=>"",
                    "StoreID"=>"",
                    "CustomField1"=>"15598",
                    "CustomField2"=>"",
                    "CustomField3"=>"cc_monthly_payment",
                    "CustomField4"=>"",
                    "HoldTradeAMT"=>"0",
                    "ItemURL"=>"",
                    "ItemName"=>"SG-VIP(15598) 1388 元 x 1",
                    "Redeem"=>"",
                    "UnionPay"=>"",
                    "PeriodAmount"=>"1388",
                    "PeriodType"=>"M",
                    "Frequency"=>"1",
                    "ExecTimes"=>"99",
                    "CreditInstallment"=>"",
                    "InstallmentAmount"=>"0",
                    "Language"=>"",
                    "BindingCard"=>"",
                    "MerchantMemberID"=>"",
                    "PeriodReturnURL"=>"",
                    "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('EcpayCheckoutUpgradeEveryQuarter',function ()
    {
        try{
            $url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";

            $data = array(
                    "MerchantID"=>"2000132",
                    "EncryptType"=>"1",
                    "ReturnURL"=>"https://sg-aws.test-tw.icu/dashboard/upgradepayEC",
                    "ClientBackURL"=>"https://sg-aws.test-tw.icu/dashboard",
                    "OrderResultURL"=>"",
                    "MerchantTradeNo"=>"SG".time(),
                    "MerchantTradeDate"=> date("Y/m/d H:i:s", strtotime("now")),
                    "PaymentType"=>"aio",
                    "TotalAmount"=>"2964",
                    "TradeDesc"=>"SG-VIP(15598)",
                    "ChoosePayment"=>"Credit",
                    "Remark"=>"",
                    "ChooseSubPayment"=>"",
                    "NeedExtraPaidInfo"=>"N",
                    "DeviceSource"=>"",
                    "IgnorePayment"=>"",
                    "InvoiceMark"=>"",
                    "StoreID"=>"",
                    "CustomField1"=>"15598",
                    "CustomField2"=>"",
                    "CustomField3"=>"cc_quarterly_payment",
                    "CustomField4"=>"",
                    "HoldTradeAMT"=>"0",
                    "ItemURL"=>"",
                    "ItemName"=>"SG-VIP(15598) 2964 元 x 1",
                    "Redeem"=>"",
                    "UnionPay"=>"",
                    "PeriodAmount"=>"2964",
                    "PeriodType"=>"M",
                    "Frequency"=>"3",
                    "ExecTimes"=>"99",
                    "CreditInstallment"=>"",
                    "InstallmentAmount"=>"0",
                    "Language"=>"",
                    "BindingCard"=>"",
                    "MerchantMemberID"=>"",
                    "PeriodReturnURL"=>"",
                    "CheckMacValue"=>""
                );

            $response = Http::post($url);
            expect($response->status())->toBe(200);
        }catch(Throwable $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

