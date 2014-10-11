<?php namespace Cysha\Modules\Auth\Validators;

use Cysha\Modules\Core\Helpers\Forms\FormValidator;
use Lang;

class Login extends FormValidator
{
    /**
     * Validation rules
     *
     * @route pxcms.auth.login
     * @return array
     */
    public function _rules()
    {
        return [
            'email'    => 'required|min:5',
            'password' => 'required|min:5',
        ];
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function _messages()
    {
        return [
            'email.min' => Lang::get('auth::auth.login.email.min'),
            'password'  => Lang::get('auth::auth.login.password'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guest();
    }

}
