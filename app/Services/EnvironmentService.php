<?php

namespace App\Services;

class EnvironmentService
{
    /*
    test-machine:
    simon-test
    */

    public static function isLocalOrTestMachine()
    {
        return app()->isLocal() || app()->environment('simon-test') || app()->environment('staging');
    }
}