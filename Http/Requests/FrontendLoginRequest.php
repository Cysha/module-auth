<?php

namespace Cms\Modules\Auth\Http\Requests;

use Cms\Http\Requests\Request;
use Auth;

class FrontendLoginRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // set basic rules up
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        // if we wanted it there, enforce it
        if ($this->needRecaptcha()) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return $rules;
    }

    private function needRecaptcha()
    {
        // test for recaptcha
        $setting = config('cms.auth.config.recaptcha.login_form', 'false');
        $recaptcha = [config('recaptcha.public_key', null), config('recaptcha.private_key', null)];
        if (in_array(null, $recaptcha)) {
            $setting = 'false';
        }

        return $setting === 'true';
    }
}
