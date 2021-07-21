<?php
namespace App\Providers;

use App\Observer\BannedUserObserver;
use App\Models\SimpleTables\banned_users;
use App\Models\BannedUsersImplicitly;
use Illuminate\Support\ServiceProvider;

class BannedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {      
        banned_users::observe(BannedUserObserver::class);
        BannedUsersImplicitly::observe(BannedUsersImplicitlyObserver::class);
    }
}