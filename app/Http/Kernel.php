<?php

namespace App\Http;

use App\Http\Middleware\HasReferer;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\IPAddressesAllow::class,
        // \Inspector\Laravel\Middleware\WebRequestMonitoring::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            \App\Http\Middleware\ApiDataLogger::class,
        ],

        'tipApi' => [
            \App\Http\Middleware\TipApiDataLogger::class,
        ],

        'valueAddedServiceApi' => [
            \App\Http\Middleware\ValueAddedServiceApiDataLogger::class,
        ],

        'mobileVerifyApi' => [
            \App\Http\Middleware\MobileVerifyApiDataLogger::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => \App\Http\Middleware\Admin::class,
        'permissions' => \App\Http\Middleware\Permissions::class,
        'roles' => \App\Http\Middleware\Roles::class,
        'active' => \App\Http\Middleware\Active::class,
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'global' => \App\Http\Middleware\GlobalVariables::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'pseudoThrottle' => \App\Http\Middleware\PseudoThrottle::class,
        'Admin' => \App\Http\Middleware\Admin::class,
        'ReadOnly' => \App\Http\Middleware\ReadOnly::class,
        'vipc' => \App\Http\Middleware\Vipc::class,
        'filled' => \App\Http\Middleware\Filled::class,
        'newerManual' => \App\Http\Middleware\NewerManual::class,
        'femaleActive' => \App\Http\Middleware\FemaleVipActive::class,
        'vipCheck' => \App\Http\Middleware\VipCheck::class,
        // 'CheckIsWarned' => \App\Http\Middleware\CheckIsWarned::class,
        'CheckAccountStatus' => \App\Http\Middleware\CheckAccountStatus::class,
        'CheckDiscussPermissions' => \App\Http\Middleware\CheckDiscussPermissions::class,

        'appGlobal' => \App\Http\Middleware\AppGlobalVariables::class,

        //一段時間未動作就自動登出
        'SessionExpired'=> \App\Http\Middleware\SessionExpired::class,

        //檢查是否是連結訪問
        "HasReferer"=>HasReferer::class,
        //檢查是否要作答FAQ
        "FaqCheck"=>\App\Http\Middleware\FaqCheck::class,
        //登入三次更新包養關係
        'AdjustedPeriodCheck'=>\App\Http\Middleware\AdjustedPeriodCheck::class,
    ];
}
