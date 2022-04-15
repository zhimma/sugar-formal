<?php

namespace App\Http\Controllers\Auth;

use App\Models\LogUserLogin;
use App\Models\CustomFingerPrint;
use App\Models\VisitorID;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Mixed_;
use Session;
use App\Models\IsBannedLog;
use App\Models\BannedUsersImplicitly;
use App\Models\IsWarnedLog;

class RegisterController extends \App\Http\Controllers\BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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
    public function __construct(UserService $userService) {
        $this->middleware('guest');
        $this->service = $userService;
    }
    //新樣板
    public function showRegistrationForm2() {
        $warnNum = $banNum = 0;
        if(\Session::get('is_remind_puppet')) {        
            $warnNum =  IsWarnedLog::select('user_id')->where('reason','like','%多重帳號%')->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->distinct()->count('user_id');
            $bannedIdNum =  IsBannedLog::select('user_id')->where('reason','like','%多重帳號%')->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->distinct()->count('user_id');
            $ibanedNum = BannedUsersImplicitly::select('target')->where('reason','like','%多重帳號%')->where('created_at','>=',\Carbon\Carbon::parse(date("Y-m-01"))->toDateTimeString())->distinct()->count('target');
            $banNum =  $bannedIdNum+$ibanedNum;
        }   
        
            
        return view('new.auth.register')->with('banNum',$banNum)->with('warnNum',$warnNum);
    }

    //新樣板
    public function checkAdult() {
        return view('new.adult');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //todo: Gmail validation.
        /*if(strpos($data['email'], 'gmail.com') !== false) {
            //Removes all the dots that contains in the email that intend to register.
            $email = str_replace('.', '', $data['email']);
            //Removes all the characters that follows after the first '+' shows up.
            $email = substr($email, 0, strpos($email, "+"));
            $emails = User::selectRaw("SUBSTRING(REPLACE(email, '.', ''), 1, LENGTH(email)-10)")
                ->where('email', 'like', '%@gmail.com%')
                ->get();
            if($emails->contains('email', $email)){
                return back()->withErrors(['此電子郵件已在本站註冊過。']);
            }
        }*/

        //Custom validation.
        Validator::extend('not_contains', function($attribute, $value, $parameters)
        {
            $words = array('站長', '管理員');
            foreach ($words as $word)
            {
                if (stripos($value, $word) !== false) return false;
            }
            return true;
        });
        $rules = [
            'name'     => ['required', 'max:255', 'not_contains'],
            'title'    => ['required', 'max:255', 'not_contains'],
            'engroup'  => ['required'],
            'email'    => 'required|email|max:255|unique:users|unique:users_bak',
            'password' => 'required|min:6|confirmed',
            'agree'    => 'required',
            'google_recaptcha_token' => ['required', 'string', new \App\Rules\GoogleRecapchaV3Case()]
        ];
		if(\Session::get('is_remind_puppet')=='1') unset($rules['google_recaptcha_token']);
        $messages = [
            'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
            'agree.required'=> '您必須同意本站的使用條款和隱私政策，才可完成註冊。',
            'required'      => ':attribute不可為空',    
            'email.email'   => 'E-mail格式錯誤',
            'email.unique'  => '此 E-mail 已被註冊',
            'min:6' =>'密碼欄位需6個字元以上',
            'password.confirmed' => '密碼確認錯誤',
            ':attribute.failed' => '您無法通過 Google reCAPTCHA 驗證，請再試一次，如依舊有問題請洽詢站長。'
        ];
        $attributes = [
            'name'      => '暱稱',
            'title'     => '標題',
            'engroup'   => '帳號類型',
            'email'     => 'E-mail信箱',
            'password'  => '密碼',
            'exchange_period'   => '包養關係',
        ];
        $validator = \Validator::make($data, $rules, $messages, $attributes);
        return $validator;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(\Illuminate\Http\Request $request) {
		if(\Session::get('is_remind_puppet')!='1') {
			$this->validator($request->all())->validate();
			if( UserService::isShowMultiUserForbidHintUserId((VisitorID::where('hash', $request->visitor_id_hash)->first())->id ?? '','visitor_id') && UserService::isShowMultiUserForbidHintUserId((CustomFingerPrint::where('hash', $request->cfp_hash)->first())->id ?? '','cfp_id') 
				&& UserService::isShowMultiUserForbidHintUserId($request->ip(),'ip')
			) {
                \Session::put('is_remind_puppet', '1');
                \Session::put('filled_data', $request->all());
                return redirect()->route('register');
			}
		}
		else if(\Session::get('is_remind_puppet')=='1' && \Session::get('filled_data')) {
			$request->request->add(\Session::get('filled_data')); 
			$this->validator($request->all())->validate();
		}
		else if(\Session::get('filled_data')){
			$request->request->add(\Session::get('filled_data')); 
		}
        
        \Session::forget('is_remind_puppet');
        \Session::forget('filled_data');

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));
		$this->guard()->login($user);
        if($request->cfp_hash && strlen($request->cfp_hash) == 50){
            $cfp = \App\Services\UserService::checkcfp($request->cfp_hash, $user->id);
            //新增登入紀錄
            if($request->visitor_id_hash && strlen($request->visitor_id_hash) == 20){
                $visitor = \App\Services\UserService::checkvisitorid($request->visitor_id_hash, $user->id);
                // if($visitor){
                    $logUserLogin = LogUserLogin::create([
                        'user_id' => $user->id,
                        'cfp_id' => $cfp->id,
                        'visitor_id'=>$visitor->id,
                        'userAgent' => $_SERVER['HTTP_USER_AGENT'],
                        'ip' => $request->ip(),
                        'created_date' =>  date('Y-m-d'),
                        'created_at' =>  date('Y-m-d H:i:s')]
                    );
                // }else{
                //     throw new \Exception("Visitor ID is not correspond");
                // }
            }
            else{
                $logUserLogin = LogUserLogin::create([
                    'user_id' => $user->id,
                    'cfp_id' => $cfp->id,
                    'userAgent' => $_SERVER['HTTP_USER_AGENT'],
                    'ip' => $request->ip(),
                    'created_date' =>  date('Y-m-d'),
                    'created_at' =>  date('Y-m-d H:i:s')]
                );
            }
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
        if($user->engroup == 2) {
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
                    if($country != "TW" && $country != "??") {
                        logger("None TW register, user id: " . $user->id);
                    }
                }
            }
            catch (\Exception $e){
                logger($e);
            }
        }



        return $this->registered($request, $user) ? redirect($this->redirectPath()) : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return DB::transaction(function() use ($data) {

            //若為男性註冊，不須存入該欄位，故將預設值設定為2,預防修改性別後仍可正常顯示
            if(empty($data['exchange_period'])){
                $data['exchange_period']=2;
            }
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'title' => $data['title'],
                'engroup' => $data['engroup'],
                'exchange_period' => $data['exchange_period']
            ]);

            //新註冊不須顯示修改提示，故須先將註記資料存入
            DB::table('exchange_period_temp')->insert(['user_id'=>$user->id,'created_at'=> now()]);

            return $this->service->create($user, $data['password'], db_config('send-email'));
        });
    }
}
