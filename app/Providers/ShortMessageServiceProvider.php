<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observer\ShortMessageObserver;
use App\Models\SimpleTables\short_message;

class ShortMessageServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        short_message::observe(ShortMessageObserver::class);	        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
