<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ];
    }

    public function messages() {
        return [
            'new_password.confirmed' => '新密碼不匹配',
            'old_password.required' => '舊密碼不可為空',
            'new_password.required' => '新密碼不可為空',
            'new_password_confirmation.required' => '確認密碼不可為空',
        ];
    }
}
