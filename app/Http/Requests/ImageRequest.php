<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:22000',
        ];
    }

    public function messages()
    {
        return [
            'image.*.mimes' => '圖片上傳錯誤，請輸入正確格式圖片(jpeg,png,jpg,gif,svg)',
            'image.required' => '請上傳圖片',
            'image.max' => '最大圖片限制22M',
        ];
    }
}
