<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLoginListener',
        ],
        'App\Events\FemaleVipActive' => [
            'App\Listeners\FemaleVipActiveListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
