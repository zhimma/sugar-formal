<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;


class UserAllowFeature extends Facade
{
    public static function get($user) {
        if (env("APP_ENV") != "production") {
            return true;
        }
        $allowList = config('app.newFeatureAllowList') ?? [];
        return in_array($user->email, $allowList, true);
    }
}