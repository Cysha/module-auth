<?php

namespace Cms\Modules\Auth\Http\Controllers\Frontend\Auth;

use Cms\Modules\Core\Http\Controllers\BaseFrontendController;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends BaseFrontendController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {
        // set dependencies
        $this->setDependencies(
            app('Teepluss\Theme\Contracts\Theme'),
            app('Illuminate\Filesystem\Filesystem')
        );

        // set redirect routes
        $this->redirectTo = route('pxcms.pages.home');
        $this->redirectPath = route('pxcms.user.forgotpassword');

        $this->middleware('guest');
    }

    public function getEmail()
    {
        return $this->setView('partials.core.password', [], 'theme');
    }
}
