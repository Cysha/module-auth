<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend;

use Cms\Modules\Core\Http\Controllers\BaseModuleController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends BaseModuleController
{
    use AuthenticatesAndRegistersUsers;

    public $layout = '2-column-left';

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        // set dependencies
        $this->auth = $auth;
        $this->registrar = $registrar;
        $this->_setDependencies(
            app('Teepluss\Theme\Contracts\Theme'),
            app('Illuminate\Filesystem\Filesystem')
        );

        // set redirect routes
        $this->redirectAfterLogout = route('pxcms.pages.home');
        $this->redirectTo = route('pxcms.pages.home');
        $this->loginPath = route('pxcms.user.login');

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        return $this->setView('partials.core.login', [], 'theme');
    }

}
