<?php

namespace Cms\Modules\Auth\Http\Requests;

use Cms\Http\Requests\Request;
use Auth;

class FrontendRegisterRequest extends Request
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
        $tblPrefix = config('cms.auth.table-prefix', 'auth_');

        // set basic rules up
        $rules = [
            'username' => 'required|max:255|unique:'.$tblPrefix.'users',
            'email' => 'required|email|max:255|unique:'.$tblPrefix.'users',
            'password' => 'required|confirmed|min:8',
        ];

        // if we wanted secure passwords...
        if (config('cms.auth.users.login.force_password', 'false') === 'true') {
            $rules['password'] .= '|regex:^(?=\d*)(?=[a-z]*)(?=[A-Z]*)(?=[\W]*).{8,}';
        }

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

        if (in_array(null, [
                config('recaptcha.public_key', null),
                config('recaptcha.private_key', null), ])) {
            $setting = 'false';
        }

        return $setting === 'true';
    }
}
