<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    protected $commands = [
        \App\Console\Commands\InsertPR::class,
    ];
    public function boot()
    {
        //
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command(\App\Console\Commands\InsertPR::class)->timezone('Asia/Taipei')->dailyAt('04:00');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->commands($this->commands);
    }
}
