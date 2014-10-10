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
        'email'    => 'required|min:5',
        'password' => 'required|min:5',
    ];

    /**
     * Validation messages
     *
     * @var array
     */
    protected $messages;

    public function register()
    {
        \Log::info('register triggered');
        $this->messages = array(
            'email.min' => \Lang::get('auth::auth.login.email'),
            'password'  => \Lang::get('auth::auth.login.password'),
        );
    }

}
