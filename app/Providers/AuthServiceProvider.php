<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('admin', 
            function ($user) {
                if($user->roles->first()){
                    return ($user->roles->first()->name == 'admin');
                }
                else{
                    return false;
                }
            }
        );

        Gate::define('readonly',
            function ($user) {
                if($user->roles->first()){
                    return ($user->roles->first()->name == 'readonly');
                }
                else{
                    return false;
                }
            }
        );

        Gate::define('juniorAdmin',
            function ($user) {
                if($user->roles->first()){
                    return ($user->roles->first()->name == 'juniorAdmin');
                }
                else{
                    return false;
                }
            }
        );

        //
    }
}
