<?php
 
namespace App\Providers;
 
use App\Extensions\CustomFileSessionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

 
class CustomSessionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('customfile', function ($app) {
            // Return an implementation of SessionHandlerInterface...
            return new CustomFileSessionHandler;
        });
    }
}
