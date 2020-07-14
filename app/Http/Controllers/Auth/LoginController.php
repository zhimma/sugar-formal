<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Fingerprint;
use App\Models\SimpleTables\member_vip;
use App\Models\Vip;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\SimpleTables\banned_users;
use Illuminate\Support\Facades\Config;
use App\Services\FingerprintService;

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

    protected $fingerprint;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FingerprintService $fingerprint)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->fingerprint = $fingerprint;
    }
    //新樣板
    public function showLoginForm2()
    {
        return view('new.auth.login');
    }

    /**
     * Check user's role and redirect user based on their role
     * @return redirect
     */
    public function authenticated(Request $request)
    {
        if (auth()->user()->hasRole('readonly')) {
             return redirect(route('users/VIP/ECCancellations/readOnly'));
        }
        $banned_users = banned_users::select('*')->where('member_id', \Auth::user()->id)->orderBy('expire_date', 'desc')->get()->first();
        $now = new Carbon;
        if(isset($banned_users) && isset($banned_users->expire_date) && $now >= $banned_users->expire_date){
            \Illuminate\Support\Facades\Log::info('User unbanned, ID: ' . $banned_users->member_id . '. Expiry: ' . $banned_users->expire_date);
            $banned_users->delete();
        }
        $userMeta = UserMeta::where('user_id', \Auth::user()->id)->get()->first();
        $announceRead = \App\Models\AnnouncementRead::select('announcement_id')->where('user_id', \Auth::user()->id)->get();
        $announcement = \App\Models\AdminAnnounce::where('en_group', \Auth::user()->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'desc')->get();
        //$announcement = $announcement->content;
        //$announcement = str_replace(PHP_EOL, '\n', $announcement);
        foreach ($announcement as &$a){
            $a = str_replace(array("\r\n", "\r", "\n"), "<br>", $a);
        }
        $request->session()->flash('announcement', $announcement);

        $user = \Auth::user();
        if($user->engroup == 2){
            $user_last_login = Carbon::parse($request->session()->get('last_login'));
            $vip_record = Carbon::parse($user->vip_record);
            $pics = \App\Models\MemberPic::where('member_id', $user->id)->count();
            if((!isset($userMeta->pic) || !($pics >= 3)) && !$user->isVip()) {
                //沒大頭貼、三張照片
                $image = 0b000;
                //001沒大頭照，010沒滿三張圖，011兩者皆無
                if(!isset($userMeta->pic)){
                    $image |= 0b001;
                }
                if($pics < 3){
                    $image |= 0b010;
                }
            }
            if($vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.start') && !$user->isVip()) {
                //免費VIP失效
                $switch = 0;
            }
            if($user->isVip() && $vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.free-days')) {
                //VIP還在免費期內
                $switch = 1;
            }
            if(isset($switch)){
                if($switch == 1){
                    $vip_expire_date = $vip_record->addSeconds(Config::get('social.vip.free-days'));
                    $vip_expire_date = $vip_expire_date->year.'年'.$vip_expire_date->month.'月'.$vip_expire_date->day.'日'.$vip_expire_date->hour.'點'.$vip_expire_date->minute.'分';
                    $request->session()->flash('vip_expire_date', $vip_expire_date);
                }
                elseif($switch == 0){
                    $vip_gain_date = Carbon::parse($user->last_login)->addSeconds(Config::get('social.vip.start'));
                    $vip_gain_date = $vip_gain_date->year.'年'.$vip_gain_date->month.'月'.$vip_gain_date->day.'日'.$vip_gain_date->hour.'點'.$vip_gain_date->minute.'分';
                    $request->session()->flash('vip_gain_date', $vip_gain_date);
                }
            }
            if(isset($image) && $image > 0){
                if(($image & 0b001) == 0b001){
                    $string = '上傳大頭照';
                }
                if(($image & 0b010) == 0b010){
                    if(isset($string)){
                        $string .= '、上傳三張相片';
                    }
                    else{
                        $string = '上傳三張相片';
                    }
                }
                $request->session()->flash('vip_pre_requirements', isset($string) ? $string : null);
            }
        }
        else{
            //男性會員取消付費後還沒過期
            $vip_data = member_vip::where('member_id', \Auth::user()->id)->where('active', 1)->orderBy('expiry', 'desc')->get()->first();
            if(isset($vip_data) && $vip_data->expiry!='0000-00-00 00:00:00'){
                $str = \Auth::user()->name."您好，您目前已取消VIP定期付費，<br>您的VIP資格將持續至".$vip_data->expiry."，<br>過期後，將會自動失去VIP資格，<br>若要繼續使用VIP功能，請在失去VIP資格後重新付費，謝謝。";
                $request->session()->flash('male_vip_expire_date', $str);
            }
        }
        if (empty($userMeta->pic)) {
            $request->session()->reflash();
            //return view('noAvatar');
        }

        return redirect('/dashboard');
    }

    /**
     * Overwrite default login method to help migrate viewers to using
     * bcrypt encrypted passwords
     */
    public function login(Request $request)
    {
        $uid = User::select('id', 'last_login')->where('email', $request->email)->get()->first();
        if(isset($uid) && Role::join('role_user', 'role_user.role_id', '=', 'roles.id')->where('roles.name', 'admin')->where('role_user.user_id', $uid->id)->exists()){
            $request->remember = true;
        }
        if(isset($uid)){
            $request->session()->put('last_login', $uid->last_login);
        }
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
        if (isset($user) && !$user->password_updated) {
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

        // if ($this->attemptLogin($request)) {
        //     return $this->sendLoginResponse($request);
        // }
        if (\Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->remember)) {
            $payload = $request->all();
            $email = $payload['email'];
            $uid = \Auth::user()->id;
            $domains = config('banned.domains');
            foreach ($domains as $domain){
                if(str_contains($email, $domain)
                    && !\DB::table('banned_users_implicitly')->where('target', $uid)->exists()){
                    \DB::table('banned_users_implicitly')->insert(
                        ['fp' => 'DirectlyBanned',
                            'user_id' => '0',
                            'target' => $uid,
                            'created_at' => \Carbon\Carbon::now()]
                    );
                }
            }
            if(isset($payload['fp'])){
                $ip = $request->ip();
                $isFp = \DB::table('fingerprint2')
                    ->where('fp', $payload['fp'])
                    ->where('user_id', $uid)
                    ->where('ip', $ip)
                    ->get()->count();
                if($isFp <= 0){
                    unset($payload['_token']);
                    unset($payload['email']);
                    unset($payload['password']);
                    $payload['user_id'] = $uid;
                    $payload['ip'] = $ip;
                    $payload['mac_address'] = $this->get_mac_address();
                    $result = \DB::table('fingerprint2')->insert($payload);
                }
                try{
                    $this->fingerprint->judgeUserFingerprintAll($uid, $payload);
                    $this->fingerprint->judgeUserFingerprintCanvasOnly($uid, $payload);
                }
                catch (\Exception $e){
                    \Illuminate\Support\Facades\Log::info($e);
                }
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function get_mac_address(){
        $string=exec('getmac');
        $mac=substr($string, 0, 17); 
        return $mac;
    }
}
