<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Factory $validator) {
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
        if (class_exists(MeiliSearch::class)) {
            $client = app(Client::class);
            $config = config('scout.meilisearch.settings');
            collect($config)
            ->each(function ($settings, $class) use ($client) {
                $model = new $class;
                $index = $client->index($model->searchableAs());
                collect($settings)
                ->each(function ($params, $method) use ($index) {
                    $index->{$method}($params);
                });
            });
        }
    }
}
