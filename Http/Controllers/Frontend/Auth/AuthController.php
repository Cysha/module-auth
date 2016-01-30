<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\Auth;

use Cms\Modules\Auth\Http\Requests\Frontend2faRequest;
use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepo;
use Cms\Modules\Core\Http\Controllers\BaseFrontendController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends BaseFrontendController
{

    public $layout = '2-column-left';

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * User Repository
     *
     * @var \Cms\Modules\Auth\Repositories\User\RepositoryInterface
     */
    protected $user;


    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Cms\Modules\Auth\Repositories\User\RepositoryInterface $user
     * @return void
     */
    public function __construct(Guard $auth, UserRepo $user)
    {
        // set dependencies
        $this->auth = $auth;
        $this->user = $user;
        $this->_setDependencies(
            app('Teepluss\Theme\Contracts\Theme'),
            app('Illuminate\Filesystem\Filesystem')
        );

        $this->middleware('guest', ['except' => ['getLogout', 'get2fa', 'post2fa']]);
    }

    /**
     * Render the login form.
     */
    public function getLogin()
    {
        $this->setLayout('1-column');
        return $this->setView('partials.core.login', [], 'theme');
    }

    /**
     * Process the login details and check if the user can be authenticated
     */
    public function postLogin(FrontendLoginRequest $request)
    {

        // grab the credentials, and use them to attempt an auth
        $credentials = $request->only('email', 'password');
        if ($this->auth->attempt($credentials, $request->has('remember'))) {

            event(new \Cms\Modules\Auth\Events\UserHasLoggedIn(Auth::user()->id));

            // if they have 2fa, redirect em to put the code in
            if (Auth::user()->has2fa) {
                return redirect()->to(route('pxcms.user.2fa'));
            } else {
                return redirect()->intended(route(config('cms.auth.paths.redirect_login', 'pxcms.pages.home')));
            }
        }

        // if we get this far, we have a problem
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => trans('auth::auth.user.incorrect_credentials'),
            ]);
    }

    /**
     * Process a Logout
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect(route(config('cms.auth.paths.redirect_logout', 'pxcms.pages.home')))
            ->withInfo(trans('auth::auth.user.logged_out_successfully'));
    }

    public function get2fa()
    {
        $this->setLayout('1-column');
        return $this->setView('partials.core.2fa', [], 'theme');
    }

    public function post2fa(Frontend2faRequest $input, Google2FA $google2fa)
    {
        $secret = $input->get('verify_2fa');
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->secret_2fa, $secret);
        if ($valid === false) {
            return redirect()->back()->withErrors([
                'verify_2fa' => trans('auth::auth.user.2fa_code_error'),
            ]);
        } else {
            Session::put('verified_2fa', true);
        }

        return redirect()->to(route('pxcms.pages.home'))->withInfo(trans('auth::auth.user.2fa_thanks'));
    }

    /**
     * Show the register form
     */
    public function getRegister()
    {
        $this->setLayout('1-column');
        return $this->setView('partials.core.register', [], 'theme');
    }

    /**
     *
     */
    public function getRegistered()
    {
        return $this->setView('partials.pages.registered', [], 'theme');
    }

    /**
     * Process a register request
     */
    public function postRegister(FrontendRegisterRequest $request)
    {
        event(new \Cms\Modules\Auth\Events\UserIsRegistering($request));

        // create the user
        $user = $this->user->createWithRoles(
            $request->all(),
            config('cms.auth.config.roles.user_group'),
            config('cms.auth.config.users.require_activating', false)
        );

        // if the user requires activating, then dont log them in automatically
        if (config('cms.auth.config.users.require_activating', false) === false) {
            $this->auth->login($user);
            event(new \Cms\Modules\Auth\Events\UserHasLoggedIn($user->id));
        }

        event(new \Cms\Modules\Auth\Events\UserHasRegistered($user->id));

        // redirect them back
        return redirect(route(config('cms.auth.config.paths.redirect_register', 'pxcms.pages.home')))
            ->withInfo(trans('auth::auth.user.registered'));
    }

}
