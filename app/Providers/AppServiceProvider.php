<?php

namespace App\Providers;

use App\Observers\BannedObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Factory $validator) {
        if (class_exists('Debugbar')) {
            \Debugbar::disable();
        }
        //
        require_once app_path() . '/validators.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
