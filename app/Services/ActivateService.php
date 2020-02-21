<?php

namespace App\Services;

use App\Notifications\ActivateUserEmail;
use App\Services\UserService;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ActivateService
{
    /**
     * UserService
     *
     * @var UserService
     */
    protected $userService;

    /**
     * Construct
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Send the current user a new activation token
     *
     * @return bool
     */
    public function sendActivationToken()
    {
        $token = md5(str_random(40));

        auth()->user()->meta_()->update([
            'activation_token' => $token
        ]);
        return auth()->user()->notify(new ActivateUserEmail($token));
    }

    /**
     * Activate the user
     *
     * @return bool
     */
    public function activateUser($token)
    {
        $user = UserMeta::where('activation_token', $token)->first();
        if ($user) {
            if($user->update([
                'is_active' => true,
                'activation_token' => null
            ])){
                return User::find($user->user_id);
            }
            return false;
        }

        return false;
    }
}
