<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;


class DBConfig extends Facade
{
    public static function db_config($key) {
        \DB::table("queue_global_variables")->where("name", $key)->first()->value ?? config('social.send-email') ?? false;
    }
}