<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notifications\Sms;

class SmsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public $bindings = [
        App\Notifications\Sms::class => Sms::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
