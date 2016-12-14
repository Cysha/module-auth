<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Carbon\Carbon;
use Cms\Modules\Auth as Auth;
use Cms\Modules\Auth\Http\Requests\BackendUpdatePasswordRequest;

class SecurityController extends BaseUserController
{
    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Password', route('admin.user.security', $user->id));

        return $this->setView('admin.user.security', $data, 'module');
    }

    public function postForm(Auth\Models\User $user, BackendUpdatePasswordRequest $input)
    {
        $input = $input->only(['password', 'password_confirmation']);

        if ($input['password'] !== $input['password_confirmation']) {
            return redirect()->to(route('admin.user.security', $user->id))
                ->withError('Passwords did not match, try again!');
        }

        $user->hydrateFromInput(array_only($input, 'password'));

        if ($user->save() === false) {
            return redirect()->to(route('admin.user.security', $user->id))
                ->withError('Could not update user, try again!')
                ->withErrors($user->getErrors());
        }

        return redirect()->to(route('admin.user.security', $user->id))
            ->withInfo('Password Updated');
    }

    public function expirePassword(Auth\Models\User $user)
    {
        $user->pass_expires_on = Carbon::now();

        if ($user->save() === false) {
            return redirect()
                ->back()
                ->withError('Could not save the changes.');
        }

        return redirect()->back()->withInfo('Users password expired successfully. They will need to change it when they login next.');
    }

    public function disable2fa(Auth\Models\User $user)
    {
        $user->secret_2fa = null;
        $user->verified_2fa = 0;

        if ($user->save() === false) {
            return redirect()
                ->back()
                ->withError(trans('auth::auth.user.2fa_code_error'));
        }

        return redirect()->back()->withInfo(trans('auth::auth.user.2fa_disabled', [
            'site_name' => config('app.name'),
            'user_email' => $user->email,
        ]));
    }
}
