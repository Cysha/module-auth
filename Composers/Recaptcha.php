<?php

namespace Cms\Modules\Auth\Composers;

use Illuminate\Contracts\View\View;

class Recaptcha
{
    protected $recaptcha_enabled = 'false';

    public function __construct()
    {
        $this->recaptcha_enabled = in_array(null, [
            config('recaptcha.public_key', null),
            config('recaptcha.private_key', null),
        ]) ? 'false' : 'true';
    }

    public function loginForm(View $view)
    {
        $setting = config('cms.auth.config.recaptcha.login_form', 'false');

        if ($this->recaptcha_enabled === 'false') {
            $setting = 'false';
        }

        $view->with('showRecaptcha', $setting === 'true' ? true : false);
    }

    public function registerForm(View $view)
    {
        $setting = config('cms.auth.config.recaptcha.register_form', 'false');

        if ($this->recaptcha_enabled === 'false') {
            $setting = 'false';
        }

        $view->with('showRecaptcha', $setting === 'true' ? true : false);
    }
}
