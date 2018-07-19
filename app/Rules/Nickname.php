<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Nickname implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        return strpos($value, '站長') === false || strpos($value, '管理員') === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '請勿使用包含「站長」或「管理員」的字眼做為暱稱！';
    }
}
