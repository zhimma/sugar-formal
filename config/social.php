<?php


/*
|--------------------------------------------------------------------------
| All users be blocked
|--------------------------------------------------------------------------
*/

// All Timer are XX(seconds)

return [
    'block' => [
        'block-people' => 1000
    ],
    'limit' => [
        'show-chat' => 10,
        'board-days' => 86400
    ],
    'vip' => [
        // seconds
        'start' => 0,
        'free-days' => 1296000
    ],
    'comment' => [
        'end' => 604800
    ],
    'user' => [
        'viewed-seconds' => 604800,
        'avatar-wait-seconds' => 5
    ],
    'payment' => [
        'returnURL' => "http://sugar.formal/dashboard/upgradepay",
        'actionURL' => "https://testmaple2.neweb.com.tw/NewebmPP/cdcard.jsp",
        'code' => "abcd1234",
        'tip-amount' => 1788
    ],
    'admin' => [
        'email' => 'mmmaya111@gmail.com',
        'showMessageCount' => 50,
        'mobile' => '0911020102',
        'mobile2' => '0972531383'
    ]
];
