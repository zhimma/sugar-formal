<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class AnonymousEvaluationChatMessageRequest extends FormRequest
{
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
            'files.*' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:22000',
            'content'=> 'required',
        ];
    }

    public function messages()
    {
        return [
            'files.*.mimes' => '圖片上傳錯誤，請輸入正確格式圖片(jpeg,png,jpg,gif,svg)',
            'files.max' => '最大圖片限制22M',
            'content.required' => '請輸入內容',
        ];
    }
}
