<?php

return [
    'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY'),
    'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY'),
    'RECAPTCHA_URL' => env('RECAPTCHA_URL', 'https://www.google.com/recaptcha/api/siteverify'),
    'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE'),
];
