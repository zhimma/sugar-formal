<?php

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use App\Services\ActivateService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ActivateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActivateService $activateService)
    {
        $this->service = $activateService;
    }

    /**
     * Inform the user they must activate thier account
     *
     * @return Illuminate\Http\Response
     */
    public function showActivate()
    {
        $user = auth()->user();

        return view('auth.activate.email')->with('user', $user)->with('register', true);
    }

    /**
     * Send a new token for activation
     *
     * @return User
     */
    public function sendToken()
    {
        $this->service->sendActivationToken();
        $user = auth()->user();

        return view('auth.activate.token', compact('user'));
    }

    /**
     * Activate a user account
     *
     * @return User
     */
    public function activate($token)
    {
        $user = $this->service->activateUser($token);
        if ($user) {
            return view('auth.activate.activationSucceed')->with('user', $user)->with('message', '驗證成功');
            //return redirect('dashboard')->with('message', '驗證成功');
        }
        $user = auth()->user();
        return view('auth.activate.email', compact('user'))->withErrors(['驗證失敗']);
    }
}
