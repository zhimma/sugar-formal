<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\SimpleTables\banned_users;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide this functionality to your appliations.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Check user's role and redirect user based on their role
     * @return redirect
     */
    public function authenticated(Request $request)
    {
        // if (auth()->user()->hasRole('admin')) {
        //     return redirect('/admin/search');
        // }
        $banned_users = banned_users::select('*')->where('member_id', \Auth::user()->id)->orderBy('expire_date', 'desc')->get()->first();
        $now = new \DateTime(Carbon::now()->toDateTimeString());
        $expire_date = new \DateTime($banned_users->expire_date);
        if($banned_users && $now < $expire_date){
            return redirect()->route('banned');    
        }
        else{
            if($banned_users){
                $banned_users->delete();
            }
            $announcement = \App\Models\AdminAnnounce::where('en_group', \Auth::user()->engroup)->get()->first();
            $announcement = $announcement->content;
            //$announcement = str_replace(PHP_EOL, '\n', $announcement);
            $announcement = str_replace(array("\r\n", "\r", "\n"), '\n', $announcement);
            $request->session()->flash('announcement', $announcement);
            $userMeta = UserMeta::where('user_id', \Auth::user()->id)->get()->first();
            if (empty($userMeta->pic)) {
                return view('noAvatar');
            }
            return redirect('/dashboard');
        }
    }

    /**
     * Overwrite default login method to help migrate viewers to using
     * bcrypt encrypted passwords
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // check if account logging in for first time
        // check against old md5 password, if correct, create bcrypted updated pw
        //dd($request->input('email'));
        $user = User::findByEmail($request->input('email'));
        //dd($user->password_updated);
        if (!$user->password_updated) {
            //if (md5($request->input('password')) == $user->password) {
            if($user->isLoginSuccess($request->input('email'), $request->input('password'))) {
                $user->password = bcrypt($request->input('password'));
                $user->password_updated = 1;
                $user->save();
            } else {
                //return $this->sendLoginResponse($request);
            }
            //dd($user->password_updated);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
