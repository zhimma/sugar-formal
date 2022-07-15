<?php

namespace App\Http\Requests\UserMessageCheck;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'date_start'            => 'nullable|date',
            'date_end'              => 'nullable|date|after:date_end',
            'message_date_start'    => 'nullable|date',
            'message_date_end'      => 'nullable|date|after:date_end',
            'total'                 => 'nullable|numeric',
            'en_group'              => 'nullable|numeric',
            'page'                  => 'nullable|numeric',
        ];
    }
}
