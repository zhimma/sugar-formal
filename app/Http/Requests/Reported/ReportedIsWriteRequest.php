<?php

namespace App\Http\Requests\Reported;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ReportedIsWriteRequest extends FormRequest
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
            'memberId'          => 'required|numeric',
            'reportedId'        => 'required|numeric',
            'reportedIndexId'   => 'required|numeric',
        ];
    }
}
