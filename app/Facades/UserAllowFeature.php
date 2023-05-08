<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;


class UserAllowFeature extends Facade
{
    public static function get($user) {
        $allowList = ["TESTfemaleVIP@test.com",
                        "TESTfemale@test.com",
                        "sandyh.dlc+4@gmail.com",
                        "sandyh.dlc+6@gmail.com",
                        "sandyh.dlc+9@gmail.com",
                    ];
        return in_array($user->email, $allowList, true);
    }
}