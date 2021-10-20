<?php

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use App\Services\ActivateService;
use App\Models\MasterWords;
use App\Models\SetAutoBan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ActivateController extends \App\Http\Controllers\BaseController
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
        if(db_config('send-email')){
            $this->service->sendActivationToken();
        }
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
        // activateUser() returns boolean.
        $user = $this->service->activateUser($token);

        if ($user) {
            // 目前程式碼並不會在驗證完成後正確判斷，
            // 若要正確判斷，需啟用以下程式碼，啟用前需報備並測試。
            // $user = auth()->user();
            // 註冊成功後判斷是否需備自動封鎖
            SetAutoBan::auto_ban($user->id);

            return view('new.auth.activate.activationSucceed')->with('user', $user)->with('message', '驗證成功');
            //return redirect('dashboard')->with('message', '驗證成功');
        }
        $user = auth()->user();
        return view('new.auth.activate.email', compact('user'))->withErrors(['驗證失敗']);
    }
}
