<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Http\Requests\Frontend2faRequest;
use Cms\Modules\Auth\Http\Requests\FrontendSecurityRequest;
use Cms\Modules\Auth\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function verify2fa(Frontend2faRequest $input, Google2FA $google2fa) {

        $secret = $input->get('verify_2fa');
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->secret_2fa, $secret);
        if ($valid === false) {
            return redirect()->back()->withErrors([
                'verify_2fa' => trans('auth::auth.user.2fa_code_error'),
            ]);
        }

        $user->verified_2fa = 1;
        if ($user->save() === false) {
            return redirect()
                ->back()
                ->withError(trans('auth::auth.user.2fa_code_error'));
        }

        return redirect()->back()->withInfo(trans('auth::auth.user.2fa_verified'));
    }

    public function disable2fa(Google2FA $google2fa) {
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
            'user_email' => $user->email
        ]));
    }

    public function enable2fa(Google2FA $google2fa) {
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

    /**
     * TODO: check out https://github.com/Cysha/pxcms-auth/issues/4 for augmenting this...
     */
    public function updatePassword(FrontendSecurityRequest $input)
    {
        $newPass = $input->get('new_password', null);
        $newPassConfirm = $input->get('new_password_confirmation', null);

        // check the new passwords match first
        if (md5($newPass) !== md5($newPassConfirm)) {
            return redirect()->back()->withErrors([
                'new_password' => 'Passwords do not match',
                'new_password_confirmation' => 'Passwords do not match',
            ]);
        }

        // grab the old password
        $oldPass = $input->get('old_password', null);

        // grab the current user
        $user = Auth::user();

        // make sure its valid against current users password
        if (!Hash::check($oldPass, $user->password)) {
            return redirect()->back()->withErrors([
                'old_password' => 'Old password doesnt match one on file.'
            ]);
        }

        // if all checks out, change the users password to the new one
        // password auto gets run through bcrypt() via the model attributes
        $user->hydrateFromInput(['password' => $newPass]);

        // make sure we can save
        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        // TODO: check to see if we need to fire off an event notifying user about this change
        // if () {
        //    event(new UserPasswordWasChanged($user));
        // }

        // redirect back!
        return redirect()->back()->withInfo('Password Updated');
    }

}
