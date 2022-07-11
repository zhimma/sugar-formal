<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observer\RealAuthUserApplyObserver;
use App\Observer\RealAuthUserModifyObserver;
use App\Models\RealAuthUserApply;
use App\Models\RealAuthUserModify;
// 會員間的訊息
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
       // $this->app->binding(MessageService::class, function($app){
        //    return new MessageService(Message::class);
        //});
    }
}
