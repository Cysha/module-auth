<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend;

use Cms\Modules\Core\Http\Controllers\BaseModuleController;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\Request;

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

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            event(new \Cms\Modules\Auth\Events\UserHasLoggedIn(\Auth::user()->id));
            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => $this->getFailedLoginMessage(),
                    ]);
    }
}
