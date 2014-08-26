<?php namespace Cysha\Modules\Auth\Controllers;

use Cysha\Modules\Auth as PXAuth;
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

        try {
            Auth::attempt(array(
                'identifier' => $input['email'],
                'password'   => $input['password']
            ), Input::get('remember', false));

        } catch (Verify\UserDeletedException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.deleted'));

        } catch (Verify\UserNotFoundException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.notfound'));

        } catch (Verify\UserPasswordIncorrectException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.passwordincorrect'));

        } catch (Verify\UserUnverifiedException $e) {
            return Redirect::route('pxcms.user.login')->withError(Lang::get('core::auth.user.unverified'));

        } catch (Exception $e) {
            return Redirect::route('pxcms.user.login')->withError($e->message());

        }

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
            return Redirect::to('/')->withWarning(Lang::get('core::auth.user.alreadyactive'));
        }

        if ($user->activate($code)) {
            Auth::login($user);

            return Redirect::route('pxcms.user.dashboard')->withInfo(Lang::get('core::auth.user.activated'));
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
            return Redirect::route('pxcms.user.dashboard');
        }

        return $this->setView('partials.core.register', array(
        ), 'theme');
    }

    public function getRegistered()
    {
        if (!Auth::guest()) {
            return Redirect::route('pxcms.user.dashboard');
        }

        $user_id = Session::get('user') ?: 0;
        if ($user_id == 0) {
            return Redirect::route('pxcms.pages.home');
        }

        $objUser = PXAuth\Models\User::findOrFail($user_id);
        if ($objUser === null) {
            return Redirect::route('pxcms.pages.home');
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
