<?php
if (! function_exists("db_config")) {
    function db_config($key) {
        return \DB::table("queue_global_variables")->where("name", $key)->first()->value ?? config('social.send-email') ?? false;
    }
}