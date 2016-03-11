<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Http\Requests\BackendUpdatePasswordRequest;
use Cms\Modules\Auth as Auth;

class PasswordController extends BaseUserController
{
    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Password', route('admin.user.password', $user->id));

        return $this->setView('admin.user.password', $data, 'module');
    }

    public function postForm(Auth\Models\User $user, BackendUpdatePasswordRequest $input)
    {
        $input = $input->only(['password', 'password_confirmation']);

        if ($input['password'] !== $input['password_confirmation']) {
            return redirect()->to(route('admin.user.password', $user->id))
                ->withError('Passwords did not match, try again!');
        }

        $user->hydrateFromInput(array_only($input, 'password'));

        if ($user->save() === false) {
            return redirect()->to(route('admin.user.password', $user->id))
                ->withError('Could not update user, try again!')
                ->withErrors($user->getErrors());
        }

        return redirect()->to(route('admin.user.password', $user->id))
            ->withInfo('Password Updated');
    }
}
