<?php


/*
|--------------------------------------------------------------------------
| All users be blocked
|--------------------------------------------------------------------------
*/

// All Timer are XX(seconds)

return [
    'block' => [
        'block-people' => 10
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
        'viewed-seconds' => 604800
    ],
    'payment' => [
        'returnURL' => "http://localhost:8000/dashboard/upgradepay",
        'actionURL' => "https://testmaple2.neweb.com.tw/NewebmPP/cdcard.jsp",
        'code' => "abcd1234"
    ],
    'admin' => [
        'email' => 'mmmaya111@gmail.com'
    ]
];
