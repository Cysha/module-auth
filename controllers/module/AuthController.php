<?php namespace Cysha\Modules\Auth\Controllers\Module;

use Cysha\Modules\Auth\Controllers\AuthBaseController;
use Cysha\Modules\Auth as PXAuth;
use Cysha\Modules\Auth\Validators;
use Toddish\Verify as Verify;
use Auth;
use Config;
use Event;
use Redirect;
use Input;
use Session;
use URL;
use Lang;

class AuthController extends AuthBaseController
{

    public $layout = 'col-1';
    protected $validatorLogin;

    public function __construct(Validators\Login $validatorLogin, Validators\Register $validatorRegister)
    {
        parent::__construct();

        $this->validatorLogin = $validatorLogin;
        $this->validatorRegister = $validatorRegister;
    }

    /**
     *
     * Login
     *
     */
    public function getLogin()
    {
        if (!Auth::guest()) {
            return Redirect::route('pxcms.user.dashboard');
        }

        return $this->setView('partials.core.login', array(
        ), 'theme');
    }

    public function postLogin()
    {
        $input = Input::only('email', 'password');

        $this->validatorLogin->validate($input);

        try {
            Auth::attempt(array(
                'identifier' => $input['email'],
                'password'   => $input['password']
            ), Input::get('remember', false));

        } catch (Verify\UserDeletedException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('auth::auth.user.deleted'));

        } catch (Verify\UserNotFoundException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('auth::auth.user.notfound'));

        } catch (Verify\UserPasswordIncorrectException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('auth::auth.user.passwordincorrect'));

        } catch (Verify\UserUnverifiedException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('auth::auth.user.unverified'));

        } catch (Exception $e) {
            return Redirect::route('pxcms.user.login')->withError($e->message());
        }

        // say no to fixating on sessions!
        Session::regenerate();

        return Redirect::intended(URL::route(Config::get('auth::user.redirect_to')));
    }

    public function getLogout()
    {
        if (Auth::check()) {
            Auth::logout();
            Session::flush();

            return Redirect::route('pxcms.pages.home')->withInfo('Successfully logged out.');
        }

        return Redirect::back();
    }

    /**
     *
     * Account Activation
     *
     */
    public function getActivate($code)
    {
        if ($user->isActive()) {
            return Redirect::to('/')->withWarning(Lang::get('auth::auth.user.alreadyactive'));
        }

        if ($user->activate($code)) {
            Auth::login($user);

            return Redirect::route('pxcms.user.dashboard')->withInfo(Lang::get('auth::auth.user.activated'));
        } else {
            return Redirect::to('/')->withError(Lang::get('auth::auth.user.invalidkey'));
        }
    }

    /**
     *
     * Register
     *
     */
    public function getRegister()
    {
        if (!Auth::guest()) {
            return Redirect::route('pxcms.user.dashboard');
        }

        return $this->setView('partials.core.register', array(
        ), 'theme');
    }

    public function postRegister()
    {
        $this->validatorRegister->validate(Input::all());

        $event = Event::fire('auth.user.register', array(Input::all()));

        if (($event[0] instanceof \Cysha\Modules\Auth\Models\User) === false) {
            return Redirect::back()->withInput()->withError('User was not registered, please try again.');
        }

        if (Config::get('users::user.require_activating') === false) {
            Auth::login($event[0]);
        }

        Event::fire('user.created', array($event[0]->toArray()));
        return Redirect::route('pxcms.pages.home')->withInfo(Lang::get('auth::auth.user.registered'));
    }

    /**
     *
     * Forgot Password
     *
     */
    public function getForgotPassword()
    {
    }

    public function postForgotPassword()
    {
    }
}
