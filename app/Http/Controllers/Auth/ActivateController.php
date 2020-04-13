<?php

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use App\Services\ActivateService;
use App\Models\MasterWords;
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
        $masterwords = MasterWords::where('en_group', $user->engroup)->orderBy('sequence','asc')->orderBy('updated_at', 'desc')->get()->first();
        // dd($masterwords->content);
        
        return view('new.auth.activate.email')
                ->with('user', $user)
                ->with('register', true)
                ->with('masterwords', $masterwords->content);
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

        return view('new.auth.activate.token', compact('user'));
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
            return view('new.auth.activate.activationSucceed')->with('user', $user)->with('message', '驗證成功');
            //return redirect('dashboard')->with('message', '驗證成功');
        }
        $user = auth()->user();
        return view('new.auth.activate.email', compact('user'))->withErrors(['驗證失敗']);
    }
}
