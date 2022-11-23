<?php


/*
|--------------------------------------------------------------------------
| All users be blocked
|--------------------------------------------------------------------------
*/

// All Timer are XX(seconds)

return [
    'payment' => [
        'MerchantID' => "3137610",
        'uid' => "24470001",  //統一編號
        'HashKey' => "BOerb1FcOOjccN8o",
        'HashIV' => "KOBKiDuvxIvjCSBz",
        'ActionURL' => "https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5",  //送出訂單的網址
        'ReturnURL' => "https://www.sugar-garden.org/dashboard/upgradepayEC",  //背景傳送付款結果的網址        
        'postChatpayReturnURL' => "https://www.sugar-garden.org/dashboard/postChatpayEC",  //背景傳送車馬費付款結果的網址
        'PeriodReturnURL' => "https://www.sugar-garden.org/dashboard/upgradepayEC",  //背景傳送定期定額付款交易結果的網址
 	    'ClientChatpayBackURL' => "https:///www.sugar-garden.org/dashboard/chat2", //返回商店的網址
        'ClientBackURL' => "https://www.sugar-garden.org/dashboard/personalPage", //返回商店的網址
        'ServiceURL' => "https://payment.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo", //定期定額查詢訂單(測試用)
        'PaymentInfoURL' => "https://www.sugar-garden.org/dashboard/paymentInfoEC",	
        // 'OrderResultURL' => "http://www.sugar-garden.org/dashboard/upgradepay",  //付款結果的網址，若不設則會使用綠界的付款結果
        'postValueAddServiceReturnURL' => "https://www.sugar-garden.org/dashboard/postValueAddedService",  //背景傳送加值服務付款結果的網址
        'postMobileVerifyReturnURL' => "https://www.sugar-garden.org/dashboard/postMobileVerifyPayEC",  //背景傳送手機驗證通過付款結果的網址
        'ClientMobileVerifyBackURL' => "https:///www.sugar-garden.org/member_auth", //返回商店的網址
        'OrderQueryURL' => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],
    'payment_test' => [
        'MerchantID' => "2000132",
        'uid' => "53538851",  //統一編號
        'HashKey' => "5294y06JbISpM5x9",
        'HashIV' => "v77hoKGq4kWxNNIS",
        'ActionURL' => "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5",  //送出訂單的網址
        'ReturnURL' => "https://sg.sert.test-tw.icu/dashboard/upgradepayEC",  //背景傳送付款結果的網址
        'postChatpayReturnURL' => "https://sg.sert.test-tw.icu/dashboard/postChatpayEC",  //背景傳送車馬費付款結果的網址
        'PeriodReturnURL' => "https://sg.sert.test-tw.icu/dashboard/upgradepayEC",  //背景傳送定期定額付款交易結果的網址
        'ClientChatpayBackURL' => "https://sg.sert.test-tw.icu/dashboard/personalPage", //返回商店的網址
        'ClientBackURL' => "https://sg.sert.test-tw.icu/dashboard/personalPage", //返回商店的網址
        'ServiceURL' => "https://payment-stage.ecpay.com.tw/Cashier/QueryCreditCardPeriodInfo",//定期定額查詢訂單
        'PaymentInfoURL' => "https://sg.sert.test-tw.icu/dashboard/paymentInfoEC",
        // 'OrderResultURL' => "http://fixedip.lzong.tw/dashboard/upgradepay",  //付款結果的網址，若不設則會使用綠界的付款結果
        'postValueAddServiceReturnURL' => "https://sg.sert.test-tw.icu/dashboard/postValueAddedService",  //背景傳送加值服務付款結果的網址
        'postMobileVerifyReturnURL' => "https://sg.sert.test-tw.icu/dashboard/postMobileVerifyPayEC",  //背景傳送手機驗證通過付款結果的網址
        'ClientMobileVerifyBackURL' => "https://sg.sert.test-tw.icu/member_auth", //返回商店的網址
        'OrderQueryURL' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],
];
