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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
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
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
        $messages = [
            'not_contains'  => '請勿使用包含「站長」或「管理員」的字眼做為暱稱！',
            'required'      => ':attribute不可為空',    
            'email.email'   => 'E-mail格式錯誤',
            'email.unique'  => '此 E-mail 已被註冊',
            'password.confirmed' => '密碼確認錯誤'
        ];
        $attributes = [
            'name'      => '暱稱',
            'email'     => 'E-mail信箱',
            'password'  => '密碼',
        ];
        $validator = \Validator::make($data, $rules, $messages, $attributes);
        return $validator;
        // return Validator::make($data, [
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|max:255|unique:users',
        //     'password' => 'required|min:6|confirmed',
        // ], [
        //     'name.required' => '暱稱不可為空',
        //     'email.required' => 'E-mail信箱不可為空',
        //     'email.email' => 'E-mail格式錯誤',
        //     'email.unique' => '此 E-mail 已被註冊',
        //     'password.required' => '密碼不可為空',
        //     'password.confirmed' => '密碼確認錯誤'
        // ]);
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
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'title' => $data['title'],
                'engroup' => $data['engroup']
            ]);

            return $this->service->create($user, $data['password']);
        });
    }
}
