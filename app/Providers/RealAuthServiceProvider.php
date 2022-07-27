<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observer\RealAuthUserApplyObserver;
use App\Observer\RealAuthUserModifyObserver;
use App\Models\RealAuthUserApply;
use App\Models\RealAuthUserModify;

class RealAuthServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        RealAuthUserApply::observe(RealAuthUserApplyObserver::class);	
        RealAuthUserModify::observe(RealAuthUserModifyObserver::class);		        
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
