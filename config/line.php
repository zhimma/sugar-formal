<?php

return [

    'line_notify' => [
        'token_url' => 'https://notify-bot.line.me/oauth/token',//固定
        'client_id' => env('LINE_NOTIFY_CLIENT_ID'),
        'client_secret' => env('LINE_NOTIFY_CLIENT_SECRET'),
        'callback_url' => env('LINE_NOTIFY_CALLBACK_URL'),
        'notify_url' => 'https://notify-api.line.me/api/notify',
        'revoke_url' => 'https://notify-api.line.me/api/revoke',
        'authorize_url' => 'https://notify-bot.line.me/oauth/authorize'
    ],

];