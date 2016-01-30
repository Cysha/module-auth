<?php namespace Cms\Modules\Auth\Http\Requests;

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

        // test for recaptcha
        $setting = config('cms.auth.config.recaptcha.login_form', 'false');

        if (in_array(null, [
                config('recaptcha.public_key', null),
                config('recaptcha.private_key', null)]
            )) {
            $setting = 'false';
        }

        // if we wanted it there, enforce it
        if ($setting === 'true') {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return $rules;
    }
}
