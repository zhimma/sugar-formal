<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class GoogleRecapchaV3Case implements Rule
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
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (config('recaptcha.RECAPTCHA_ENABLE') == true) {
            return $this->verify($value);
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return ':attribute.failed';
    }

    /**
     * Verify token.
     */
    private function verify(string $token = null): bool
    {
        $url = config('recaptcha.RECAPTCHA_URL');

        $response = (new Client())->request('POST', $url, [
            'form_params' => [
                'secret' => config('recaptcha.RECAPTCHA_SECRET_KEY'),
                'response' => $token,
            ],
        ]);

        $code = $response->getStatusCode();
        $content = json_decode($response->getBody()->getContents());

        if($code == 200 && $content->success == true){
            return true;
        }
        else{
            logger("reCAPTCHA failed.");
            return false;
        }
    }
}
