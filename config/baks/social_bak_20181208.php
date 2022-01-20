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
        'start' => 86400,
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
        'returnURL' => "http://www.sugar-garden.org/dashboard/upgradepay",
        'actionURL' => "https://taurus.newebpay.com.tw/NewebmPP/cdcard.jsp",
        'orderURL' => "http://www.sugar-garden.org/dashboard/upgradepay",
        'code' => "j6SL9E6E",
        'tip-amount' => "1788",
        'vip-amount' => "888",
    ],
    'admin' => [
        'email' => 'mmmaya111@gmail.com',
        'showMessageCount' => 50
    ]
];
