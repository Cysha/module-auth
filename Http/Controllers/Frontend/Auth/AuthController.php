<?php

namespace Cms\Modules\Auth\Http\Controllers\Frontend\Auth;

use Cms\Modules\Auth\Http\Requests\ChangePasswordRequest;
use Cms\Modules\Auth\Http\Requests\Frontend2faRequest;
use Cms\Modules\Auth\Http\Requests\FrontendLoginRequest;
use Cms\Modules\Auth\Http\Requests\FrontendRegisterRequest;
use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepo;
use Cms\Modules\Core\Http\Controllers\BaseFrontendController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends BaseFrontendController
{
    use AuthenticatesUsers;

    public $layout = '2-column-left';

    /**
     * User Repository.
     *
     * @var \Cms\Modules\Auth\Repositories\User\RepositoryInterface
     */
    protected $user;

    protected $lockoutTime;
    protected $maxLoginAttempts;

    /**
     * Create a new authentication controller instance.
     *
     * @param \Cms\Modules\Auth\Repositories\User\RepositoryInterface $user
     */
    public function __construct(UserRepo $user)
    {
        // set dependencies
        $this->user = $user;
        $this->setDependencies(
            app('Teepluss\Theme\Contracts\Theme'),
            app('Illuminate\Filesystem\Filesystem')
        );

        $this->lockoutTime = config('cms.auth.config.users.login.lockoutTime', 60);
        $this->maxLoginAttempts = config('cms.auth.config.users.login.maxLoginAttempts', 5);
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
     * Process the login details and check if the user can be authenticated.
     */
    public function postLogin(FrontendLoginRequest $request)
    {

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = ($this->isUsingThrottlesLoginsTrait() && config('cms.auth.config.users.login.throttlingEnabled', 'false') === 'true');

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            event(new \Cms\Modules\Auth\Events\NotifyUser(Auth::id(), 'auth::notify.account.lockout'));

            return $this->sendLockoutResponse($request);
        }

        // grab the credentials, and use them to attempt an auth
        if ($this->attemptLogin($request)) {
            $events = event(new \Cms\Modules\Auth\Events\UserHasLoggedIn(Auth::id()));

            return redirect()->intended(route(config('cms.auth.paths.redirect_login', 'pxcms.pages.home')));
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => trans('auth::auth.user.incorrect_credentials'),
            ]);
    }

    /**
     * Process a Logout.
     */
    public function getLogout()
    {
        $this->auth->logout();
        \Session::flush();

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
        }

        // the key was valid, forget about 2fa now
        Session::forget('actions.require_2fa');

        return redirect(route(config('cms.auth.paths.redirect_logout', 'pxcms.pages.home')))
            ->withInfo(trans('auth::auth.user.2fa_thanks'));
    }

    /**
     * Show the register form.
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
     * Process a register request.
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

        event(new \Cms\Modules\Auth\Events\UserHasRegistered($user->id));

        // if the user requires activating, then dont log them in automatically
        if (config('cms.auth.config.users.require_activating', false) === false) {
            $this->auth->login($user);
            event(new \Cms\Modules\Auth\Events\UserHasLoggedIn($user->id));
        }

        // redirect them back
        return redirect(route(config('cms.auth.config.paths.redirect_register', 'pxcms.pages.home')))
            ->withInfo(trans('auth::auth.user.registered'));
    }

    public function getPassExpired()
    {
        $this->setLayout('1-column');

        return $this->setView('controlpanel.partials.change_password', []);
    }

    public function postPassExpired(ChangePasswordRequest $input, UserRepo $userRepo)
    {
        // try and update the password
        $return = $userRepo->updatePassword(Auth::user(), $input);
        if (is_array($return)) {
            return redirect()->back()
                ->withErrors($return);
        }

        // redirect home!
        return redirect()->to('/')
            ->withInfo(trans('auth::auth.user.password_changed'));
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return 'email';
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
           ThrottlesLogins::class, class_uses_recursive(get_class($this))
       );
    }
}
