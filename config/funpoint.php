<?php


/*
|--------------------------------------------------------------------------
| All users be blocked
|--------------------------------------------------------------------------
*/

// All Timer are XX(seconds)

return [
    'payment' => [
        'MerchantID' => "1010336",
        'uid' => "24470001",  //統一編號
        'HashKey' => "xcmzAyKJM7I8gssu",
        'HashIV' => "7h5B9EIcEWEFIkPW",
        'ActionURL' => "https://payment.funpoint.com.tw/Cashier/AioCheckOut/V5",  //送出訂單的網址
        'ReturnURL' => "https://www.sugar-garden.org/dashboard/upgradepayEC",  //背景傳送付款結果的網址        
        'postChatpayReturnURL' => "https://www.sugar-garden.org/dashboard/postChatpayEC",  //背景傳送車馬費付款結果的網址
        'PeriodReturnURL' => "https://www.sugar-garden.org/dashboard/upgradepayEC",  //背景傳送定期定額付款交易結果的網址
 	    'ClientChatpayBackURL' => "https:///www.sugar-garden.org/dashboard/chat2", //返回商店的網址
        'ClientBackURL' => "https://www.sugar-garden.org/dashboard", //返回商店的網址
        'ServiceURL' => "https://payment.funpoint.com.tw/Cashier/QueryCreditCardPeriodInfo", //定期定額查詢訂單(測試用)
        'PaymentInfoURL' => "https://www.sugar-garden.org/dashboard/paymentInfoEC",	
        // 'OrderResultURL' => "http://www.sugar-garden.org/dashboard/upgradepay",  //付款結果的網址，若不設則會使用綠界的付款結果
        'postValueAddServiceReturnURL' => "https://www.sugar-garden.org/dashboard/postValueAddedService",  //背景傳送加值服務付款結果的網址
        'postMobileVerifyReturnURL' => "https://www.sugar-garden.org/dashboard/postMobileVerifyPayEC",  //背景傳送手機驗證通過付款結果的網址
        'ClientMobileVerifyBackURL' => "https:///www.sugar-garden.org/member_auth", //返回商店的網址
        'OrderQueryURL' => 'https://payment.funpoint.com.tw/Cashier/QueryTradeInfo/V5',
    ],
    'payment_test' => [
        'MerchantID' => "1000031",
        'uid' => "12345678",  //統一編號
        'HashKey' => "265flDjIvesceXWM",
        'HashIV' => "pOOvhGd1V2pJbjfX",
        'ActionURL' => "https://payment-stage.funpoint.com.tw/Cashier/AioCheckOut/V5",  //送出訂單的網址
        //'ReturnURL' => "https://linna.test-tw.icu/dashboard/upgradepayEC",  //背景傳送付款結果的網址
        //'postChatpayReturnURL' => "https://linna.test-tw.icu/dashboard/postChatpayEC",  //背景傳送車馬費付款結果的網址
        //'PeriodReturnURL' => "https://linna.test-tw.icu/dashboard/upgradepayEC",  //背景傳送定期定額付款交易結果的網址
        //'ClientChatpayBackURL' => "https://linna.test-tw.icu/dashboard/chat2", //返回商店的網址
        //'ClientBackURL' => "https://linna.test-tw.icu/dashboard", //返回商店的網址
        'ServiceURL' => "https://payment-stage.funpoint.com.tw/Cashier/QueryCreditCardPeriodInfo",//定期定額查詢訂單
        //'PaymentInfoURL' => "https://linna.test-tw.icu/dashboard/paymentInfoEC",
        // 'OrderResultURL' => "http://fixedip.lzong.tw/dashboard/upgradepay",  //付款結果的網址，若不設則會使用綠界的付款結果
        //'postValueAddServiceReturnURL' => "https://linna.test-tw.icu/dashboard/postValueAddedService",  //背景傳送加值服務付款結果的網址
        //'postMobileVerifyReturnURL' => "https://linna.test-tw.icu/dashboard/postMobileVerifyPayEC",  //背景傳送手機驗證通過付款結果的網址
        //'ClientMobileVerifyBackURL' => "https://linna.test-tw.icu/member_auth", //返回商店的網址
        'OrderQueryURL' => 'https://payment-stage.funpoint.com.tw/Cashier/QueryTradeInfo/V5',
    ],
];
