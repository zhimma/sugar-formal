<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;


class UserAllowFeature extends Facade
{
    public static function get($user) {
        $allowList = config('app.newFeatureAllowList');
        return in_array($user->email, $allowList, true);
    }
}