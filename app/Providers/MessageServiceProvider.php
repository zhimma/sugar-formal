<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MessageService;
use App\Observer\BannedUserObserver;
use App\Observer\BannedUsersImplicitlyObserver;
use App\Observer\WarnedUserObserver;
use App\Models\SimpleTables\banned_users;
use App\Models\SimpleTables\warned_users;
use App\Models\BannedUsersImplicitly;
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
        banned_users::observe(BannedUserObserver::class);
        BannedUsersImplicitly::observe(BannedUsersImplicitlyObserver::class);
		warned_users::observe(WarnedUserObserver::class);		
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
