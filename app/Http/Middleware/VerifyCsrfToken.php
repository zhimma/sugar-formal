<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/dashboard/receive_esafe',
        '/dashboard/repaid_esafe',
        '/dashboard/upgradepay',
        '/dashboard/chatpay',
        '/dashboard/upgradepayEC',
        '/dashboard/paymentInfoEC',
        '/dashboard/chatpay_ec',
        '/dashboard/postChatpayEC',
        '/dashboard/valueAddedService_ec',
        '/dashboard/postValueAddedService',
        '/dashboard/mobileVerifyPay_ec',
        '/dashboard/postMobileVerifyPayEC',
        '/admin/api/aws-sns/ses',
        '/dashboard/line/callback',
        '/cfp',
        '/video/log_video_chat_process',
        '/LocalMachineReceive/BanAndWarn',
        '/LocalMachineReceive/BanSetIPUpdate',
        '/deploy',
    ];
}
