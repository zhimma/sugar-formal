<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogUserLogin;
use App\Models\SimpleTables\member_vip;
use App\Models\Vip;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Role;
use App\Models\SetAutoBan;
use Auth;
use App\Models\SimpleTables\banned_users;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Session;
use App\Observer\BadUserCommon;

class LoginController extends \App\Http\Controllers\BaseController
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
    protected $redirectTo = 'dashboard/personalPage';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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
        $is_new_7 = false;
        if( Carbon::parse(\Auth::user()->created_at)->diffInDays(Carbon::now())<7) {
            $is_new_7 = true;
        }
        $announceRead = \App\Models\AnnouncementRead::select('announcement_id')->where('user_id', \Auth::user()->id)->get();
        $aq = \App\Models\AdminAnnounce::where('en_group', \Auth::user()->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'desc');
        if(!$is_new_7) $aq = $aq->where('is_new_7','<>',1);
        $announcement = $aq->get();
        //$announcement = \App\Models\AdminAnnounce::where('en_group', \Auth::user()->engroup)->whereNotIn('id', $announceRead)->orderBy('sequence', 'desc')->get();
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
        $banned_users = \App\Models\SimpleTables\banned_users::where('member_id',$user->meta_()->user_id)->where(
            function ($query) {
                $query->whereNull('expire_date')->orWhere('expire_date', '>=', \Carbon\Carbon::now());
            })
            ->get();
        if(count($banned_users) > 0){
            $diff_in_days = '';
            $banned_user = $banned_users->first();
            if(isset($banned_user->expire_date)){
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $banned_user->expire_date);
                $now = \Carbon\Carbon::now();

                $diff_in_days = ' ' . $to->diffInDays($now) . ' 天';
            }
            $reason = $banned_user->reason;
            if($reason == '自動封鎖' || $reason == '' || $reason == null){
                $reason = '系統原因';
            }
            $request->session()->flash('expire_diff_in_days', $diff_in_days);
            $request->session()->flash('banned_reason', $reason);
        }

        return redirect('/dashboard/personalPage');
    }

    /**
     * Overwrite default login method to help migrate viewers to using
     * bcrypt encrypted passwords
     */
    public function login(Request $request)
    {
        $user = User::select('id', 'engroup', 'last_login','login_times','intro_login_times','line_notify_alert')->withOut(['vip', 'user_meta'])->where('email', $request->email)->get()->first();
        if(isset($user) && Role::join('role_user', 'role_user.role_id', '=', 'roles.id')->where('roles.name', 'admin')->where('role_user.user_id', $user->id)->exists()){
            $request->remember = true;
        }
        if(isset($user)){
            $request->session()->put('last_login', $user->last_login);
        }
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
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
                    if(\DB::table('banned_users_implicitly')->insert(
                        ['fp' => 'DirectlyBanned',
                            'user_id' => '0',
                            'target' => $uid,
                            'created_at' => \Carbon\Carbon::now()]
                    ))
                    {
                        BadUserCommon::addRemindMsgFromBadId($userId);
                    }
                }
            }

            //更新login_times
            User::where('id',$user->id)->update(['login_times'=>$user->login_times +1]);
            //更新教學<->登入次數
            User::where('id',$user->id)->update(['intro_login_times'=>$user->intro_login_times +1]);
            //更新會員專屬頁通知<->登入次數
            User::where('id',$user->id)->update(['line_notify_alert'=>$user->line_notify_alert +1]);

            if($request->cfp_hash && strlen($request->cfp_hash) == 50){
                $cfp = \App\Services\UserService::checkcfp($request->cfp_hash, $user->id);
                //新增登入紀錄
                $logUserLogin = LogUserLogin::create([
                        'user_id' => $user->id,
                        'cfp_id' => $cfp->id,
                        'userAgent' => $_SERVER['HTTP_USER_AGENT'],
                        'ip' => $request->ip(),
                        'created_date' =>  date('Y-m-d'),
                        'created_at' =>  date('Y-m-d H:i:s')]
                );
            }
            else{
                logger("CFP debug data: " . $request->debug);
                $logUserLogin = LogUserLogin::create([
                        'user_id' => $user->id,
                        'userAgent' => $_SERVER['HTTP_USER_AGENT'],
                        'ip' => $request->ip(),
                        'created_date' =>  date('Y-m-d'),
                        'created_at' =>  date('Y-m-d H:i:s')]
                );
            }

            try{
                $country = null;
                // 先檢查 IP 是否有記錄
                $ip_record = LogUserLogin::where('ip', $request->ip())->first();
                if($ip_record && $ip_record->country && $ip_record->country != "??"){
                    $country = $ip_record->country;
                }
                // 否則從 API 查詢
                else{
                    $client = new \GuzzleHttp\Client();
                    $response = $client->get('http://ipinfo.io/' . $request->ip() . '?token=27fc624e833728');
                    $content = json_decode($response->getBody());
                    if(isset($content->country)){
                        $country = $content->country;
                    }
                    else{
                        $country = "??";
                    }
                }

                if(isset($country)){
                    $logUserLogin->country = $country;
                    $logUserLogin->save();
                    $whiteList = [
                        "pig820827@yahoo.com.tw",
                        "henyanyilily@gmail.com",
                        "chenyanyilily@gmail.com",
                        "sa83109@gmail.com",
                        "frebert456@gmail.com",
                        "sagitwang@gmail.com",
                    ];
                    if(!in_array($request->email, $whiteList)){
                        if($country != "TW" && $country != "??") {
                            logger("None TW login, user id: " . $user->id);
                            Auth::logout();
                            return back()->withErrors('Forbidden.');
                        }
                    }
                }
            }
            catch (\Exception $e){
                logger($e);
            }

            return $this->sendLoginResponse($request);
        }

        //一年以上未登入帳號,需從users_bak 找是否有符合帳號
        $this->findAccountAndRollbackToUsers($request);

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

    public function logout(Request $request) {
        //登出自動警示
        SetAutoBan::logout_warned(Auth::id());
        Session::flush();
        $request->session()->forget('announceClose');
        Auth::logout();
        return redirect('/login');
    }

    public function findAccountAndRollbackToUsers($request){

        $findUser = DB::table('users_bak')->where('email', $request->email);

        if(!is_null($findUser->first())) {
            DB::beginTransaction();
            try {
                //rollback users
                $data = (array)$findUser->first();
                DB::table('users')->updateOrInsert(['id'=> array_get($data,'id')], $data);
                $findUser->delete();

                //rollback user_meta
                $findUserMeta = DB::table('user_meta_bak')->where('user_id', array_get($data,'id'));
                $data = (array)$findUserMeta->first();
                DB::table('user_meta')->updateOrInsert([
                    'id'=>array_get($data, 'id'),
                    'user_id' => array_get($data, 'user_id')], $data);
                $findUserMeta->delete();

                DB::commit();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::info($e);
                DB::rollBack();
            }

            //重新驗證帳號密碼
            if (\Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect('/dashboard/personalPage');
            }
        }
    }
}
