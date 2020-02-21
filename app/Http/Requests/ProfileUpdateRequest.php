<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    protected $redirect = '/dashboard';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'height' => 'required|digits_between:2,3|numeric',
            'name' => 'required',
            'title' => 'required',
            'about'=> 'required',
            'style' => 'required',
            'assets' => 'required_if:voucher_enabled,1|integer|nullable'
            //'height' => 'required|digits_between:2,3|numeric',
            //'name' => array('required', 'regex:/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9\s\.\,\。\;\'\"\(\)\，\/\-\=\+\?\!\~\>\<\^\、\♥]+$/u'),
            //'title' => array('required', 'regex:/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9\s\.\,\。\;\'\"\(\)\，\/\-\=\+\?\!\~\>\<\^\、\♥]+$/u'),
            //'about'=> array('regex:/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9\s\.\,\。\;\'\"\(\)\，\/\-\=\+\?\!\~\>\<\^\、\♥]+$/u'),
            //'style' => array('regex:/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9\s\.\,\。\;\'\"\(\)\，\/\-\=\+\?\!\~\>\<\^\、\♥]+$/u'),
            //'assets' => 'required_if:voucher_enabled,1|integer|nullable'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '請輸入暱稱',
            'title.required' => '請輸入標題',
            'name.regex' => '暱稱格式輸入錯誤',
            'title.regex' => '標題格式輸入錯誤',
            'about.regex' => '關於我格式輸入錯誤',
            'style.regex' => '期待的約會模式輸入錯誤',
            'height.numeric' => '請輸入數字',
            'height.required' => '請輸入身高',
            'height.digits_between' => '身高請輸入兩到三位數字',
            'assets.integer' => '資產必須為數字'
            //'height.digits_between' => '請輸入1~200的數字'
        ];
    }
}
