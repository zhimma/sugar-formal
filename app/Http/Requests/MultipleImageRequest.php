<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class MultipleImageRequest extends FormRequest
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
            'images.*' => 'mimes:jpeg,png,jpg,gif,svg|max:22000'
        ];
    }

    public function messages()
    {
        return [
            'images.mimes' => '圖片上傳錯誤，請輸入正確格式圖片(jpeg,png,jpg,gif,svg)',
            'images.max' => '最大圖片限制22M',
        ];
    }
}
