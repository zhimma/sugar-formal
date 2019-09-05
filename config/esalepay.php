<?php


/*
|--------------------------------------------------------------------------
| All users be blocked
|--------------------------------------------------------------------------
*/

// All Timer are XX(seconds)

return [
    'payment' => [
        'MerchantID' => "S1234567890",//商家代號（信用卡）（可登入商家專區至「服務設定」中查詢Buysafe服務的代碼）
        'transPassword' => "abcd1234",//交易密碼（可登入商家專區至「密碼修改」處設定，此abcd1234密碼非後台登入密碼）
        'uid' => "53538851",  //統一編號
        'ActionURL' => "https://www.esafe.com.tw/Service/Etopm.aspx",  //送出訂單的網址
        'ReturnURL' => "http://www.sugar-garden.org/dashboard/upgradepay",  //背景傳送付款結果的網址
        'PeriodReturnURL' => "http://www.sugar-garden.org/dashboard/upgradepay",  //背景傳送定期定額付款交易結果的網址
        'ClientBackURL' => "http://www.sugar-garden.org/dashboard/upgradepay", //返回商店的網址

    ],
    'payment_test' => [
        'MerchantID' => "S1234567890",//商家代號（信用卡）（可登入商家專區至「服務設定」中查詢Buysafe服務的代碼）
        'transPassword' => "abcd1234",//交易密碼（可登入商家專區至「密碼修改」處設定，此abcd1234密碼非後台登入密碼）
        'uid' => "53538851",  //統一編號
        'ActionURL' => "https://test.esafe.com.tw/Service/Etopm.aspx",  //送出訂單的網址
        'ReturnURL' => "http://fixedip.lzong.tw/dashboard/upgradepay",  //背景傳送付款結果的網址
        'PeriodReturnURL' => "http://fixedip.lzong.tw/dashboard/upgradepay",  //背景傳送定期定額付款交易結果的網址
        'ClientBackURL' => "http://sugar.formal/dashboard", //返回商店的網址
        
    ],
];
