<?php

namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepo;
use Cms\Modules\Auth\Http\Requests\Frontend2faRequest;
use Cms\Modules\Auth\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class SecurityController extends BaseController
{
    public function getForm(Google2FA $google2fa)
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Security Settings', route('pxcms.user.security'));

        $data['qr2fa'] = $google2fa->getQRCodeInline(
            config('cms.core.app.site-name'),
            $data['user']['email'],
            $data['user']['secret_2fa']
        );

        return $this->setView('controlpanel.security', $data);
    }

    public function verify2fa(Frontend2faRequest $input, Google2FA $google2fa)
    {
        $secret = $input->get('verify_2fa');
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->secret_2fa, $secret);
        if ($valid === false) {
            return redirect()->back()->withErrors([
                'verify_2fa' => trans('auth::auth.user.2fa_code_error'),
            ]);
        }

        // set this session, stop the user being kicked out via the enforce part of auth middleware
        Session::put('verified_2fa', true);

        $user->verified_2fa = 1;
        if ($user->save() === false) {
            return redirect()
                ->back()
                ->withError(trans('auth::auth.user.2fa_code_error'));
        }

        return redirect()->back()->withInfo(trans('auth::auth.user.2fa_verified'));
    }

    public function disable2fa(Google2FA $google2fa)
    {
        $user = Auth::user();

        $user->secret_2fa = null;
        $user->verified_2fa = 0;

        if ($user->save() === false) {
            return redirect()
                ->back()
                ->withError(trans('auth::auth.user.2fa_code_error'));
        }

        return redirect()->back()->withInfo(trans('auth::auth.user.2fa_disabled', [
            'site_name' => config('cms.core.app.site-name'),
            'user_email' => $user->email,
        ]));
    }

    public function enable2fa(Google2FA $google2fa)
    {
        $user = Auth::user();

        $user->hydrateFromInput([
            'secret_2fa' => $google2fa->generateSecretKey(16, config('cms.core.app.site-name')),
        ]);

        if ($user->save() === false) {
            return redirect()
                ->back()
                ->withError(trans('auth::auth.2fa_enable_error'));
        }

        return redirect()->back()->withInfo(trans('auth::auth.user.2fa_enabled'));
    }

    public function updatePassword(ChangePasswordRequest $input, UserRepo $userRepo)
    {
        // try and update the password
        $return = $userRepo->updatePassword(Auth::user(), $input);
        if (is_array($return)) {
            return redirect()->back()
                ->withErrors($return);
        }

        // redirect back!
        return redirect()->back()->withInfo('Password Updated');
    }
}
