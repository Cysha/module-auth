<?php namespace Cysha\Modules\Auth\Validators;

use Cysha\Modules\Core\Helpers\Forms\FormValidator;

class Login extends FormValidator
{
    /**
     * Validation rules for logging in
     *
     * @var array
     * @route pxcms.auth.login
     */
    protected $rules = [
        'email'    => 'required|email|min:5',
        'password' => 'required|min:5',
    ];

}
