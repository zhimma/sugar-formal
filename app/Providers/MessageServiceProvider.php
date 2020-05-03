<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MessageService;
// 會員間的訊息
class MessageServiceProvider extends ServiceProvider
{

    protected $defer = true;

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
        $this->app->binding(MessageService::class, function($app){
            return new MessageService(Message::class);
        });
    }
}
