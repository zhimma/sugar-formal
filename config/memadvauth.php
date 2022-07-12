<?php

return [
    'service'=> [
        'host'=>env('ADV_AUTH_SERVICE_HOST','midonline.twca.com.tw'),
        'port'=>env('ADV_AUTH_SERVICE_PORT',null),
        'uri'=>env('ADV_AUTH_SERVICE_URI','IDPortal/MIDClause'),
        'business_no'=>env('ADV_AUTH_BUSINESS_NO','54666024'),
        'hash_key'=>env('ADV_AUTH_HASH_KEY','2f2ea269-6b84-441f-b333-0a75605265e9'),
        'hash_key_no'=>env('ADV_AUTH_HASH_KEY_NO','12'),
    ],
    'user' => [
        'pause_during' => env('ADV_AUTH_USER_PAUSE_DURING',1440*3),
        'allow_fault' => env('ADV_AUTH_USER_ALLOW_FAULT',3),        
    ],
    'api'=>[
        'line_token'=>env('ADV_AUTH_LINE_TOKEN','dalgcYQoasC68P4wUIiD241DHZmAfP5fyoHDh8WLea9'),
        'check'=>[
            's'=>[
                'interval'=>env('ADV_AUTH_CHECK_S_INTERVAL', 200),
                'notify_count'=>env('ADV_AUTH_CHECK_S_NOTIFY_COUNT',10),
                'pause_count'=>env('ADV_AUTH_CHECK_S_PAUSE_COUNT',15),
            ],
            'l'=>[
                'interval'=>env('ADV_AUTH_CHECK_L_INTERVAL',1440),
                'notify_count'=>env('ADV_AUTH_CHECK_L_NOTIFY_COUNT',30),
                'pause_count'=>env('ADV_AUTH_CHECK_L_PAUSE_COUNT',50),
            ]            
        ],
        'pause_during'=>env('ADV_AUTH_API_PAUSE_DURING',1440),
    ]

];