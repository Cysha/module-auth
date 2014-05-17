<?php namespace Cysha\Modules\Auth\Controllers;

use Cysha\Modules\Auth as PXAuth;
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

    public function __construct()
    {
        parent::__construct();

        Sentry::getThrottleProvider()->enable();
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

        try {

            $creds = Input::only('email', 'password');
            $rememberMe = Input::get('remember', false);

            $user = PXAuth\Models\User::whereEmail(e($data['email']));
            $throttle = Sentry::getThrottleProvider()->findByUserId($user->id);
            $throttle->check();

            $auth = Sentry::authenticate($creds, $rememberMe);

        } catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.passwordincorrect'));

        } catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            $url = route('resendActivationForm');

            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.notactive', $url));

        } catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            $time = $throttle->getSuspensionTime();

            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.suspended', $time));

        } catch (\Cartalyst\Sentry\Throttling\UserBannedException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.banned'));

        } catch (Exception $e) {
            return Redirect::route('pxcms.user.login')->withError($e->message());
        }

        return Redirect::intended(URL::route('pxcms.user.login'));
    }

    public function getLogout()
    {
        if (Auth::check()) {
            Auth::logout();
            Session::flush();

            return Redirect::back();
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
            return Redirect::to('/')->withWarning(Lang::get('core::auth.user.alreadyactive'));
        }

        if ($user->activate($code)) {
            Auth::login($user);

            return Redirect::route('user.dashboard')->withInfo(Lang::get('core::auth.user.activated'));
        } else {
            return Redirect::to('/')->withError(Lang::get('core::auth.user.invalidkey'));
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
            return Redirect::route('user.dashboard');
        }

        return $this->setView('partials.core.register', array(
        ), 'theme');
    }

    public function getRegistered()
    {
        if (!Auth::guest()) {
            return Redirect::route('user.dashboard');
        }

        $user_id = Session::get('user') ?: 0;
        if ($user_id == 0) {
            return Redirect::to('/');
        }

        $objUser = PXAuth\Models\User::findOrFail($user_id);
        if ($objUser === null) {
            return Redirect::to('/');
        }

        return $this->setView('partials.core.registered', array(
            'user' => $objUser
        ), 'theme');
    }

    public function postRegister()
    {
        $objUser = new PXAuth\Models\User;
        $objUser->hydrateFromInput();

        if (Config::get('users::user.require_activating') === false) {
            $objUser->verified = 1;
        }

        if ($objUser->save()) {
            Event::fire('user.created', array($objUser));

            return Redirect::route('user.registered')->withUser($objUser->id);
        }

        Input::flash();

        return Redirect::route('user.register')->withErrors($objUser->getErrors());
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
