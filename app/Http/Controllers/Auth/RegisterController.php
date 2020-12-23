<?php

namespace App\Http\Controllers\Auth;

use DB;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Carbon\Carbon;

class RegisterController extends Controller
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
    public function __construct(UserService $userService)
    {
        $this->middleware('guest');
        $this->service = $userService;
    }
    //新樣板
    public function showRegistrationForm2()
    {
        return view('new.auth.register');
    }

    //新樣板
    public function checkAdult()
    {
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
        ];
        $messages = [
            'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
            'agree.required'=> '您必須同意本站的使用條款和隱私政策，才可完成註冊。',
            'required'      => ':attribute不可為空',    
            'email.email'   => 'E-mail格式錯誤',
            'email.unique'  => '此 E-mail 已被註冊',
            'min:6' =>'密碼欄位需6個字元以上',
            'password.confirmed' => '密碼確認錯誤'
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
    public function register(\Illuminate\Http\Request $request) : \Illuminate\Http\Response {
        $this->validator($request->all())->validate();

        $request->validate([
            'google_recaptcha_token' => ['required', 'string', new \App\Rules\GoogleRecapchaV3Case()],
        ]);

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

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

            return $this->service->create($user, $data['password']);
        });
    }
}
