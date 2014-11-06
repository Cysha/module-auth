<?php namespace Cysha\Modules\Auth\Validators;

use Cysha\Modules\Core\Helpers\Forms\FormValidator;
use Lang;
use Auth;

class Register extends FormValidator
{
    /**
     * Validation rules
     *
     * @route pxcms.auth.login
     * @return array
     */
    public function _rules()
    {
        $authModel = \Config::get('auth.model');
        $table = with(new $authModel)->table;

        return [
            'username' => 'required|min:5|unique:'.$table.',username',
            'email'    => 'required|email|min:5|unique:'.$table.',email',
            'password' => 'required|confirmed|min:5',
            'tnc'      => 'required|in:0,1',
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
            'username.unqiue' => Lang::get('auth::auth.register.username.unqiue'),
            'username.min'    => Lang::get('auth::auth.register.username.min'),

            'email.unqiue'    => Lang::get('auth::auth.register.email.unqiue'),
            'email.min'       => Lang::get('auth::auth.register.email.min'),

            'password'        => Lang::get('auth::auth.register.password'),
            'password.min'    => Lang::get('auth::auth.register.password.min'),

            'tnc.required'    => Lang::get('auth::auth.register.tnc.required'),
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
