<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class PasswordController extends BaseUserController
{
    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);

        return $this->setView('admin.user.password', $data, 'module');
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->only(['password', 'password_confirmation']);

        if ($input['password'] !== $input['password_confirmation']) {
            return redirect()->back()->withError('Passwords did not match, try again!');
        }

        $user->hydrateFromInput(array_only($input, 'password'));

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->back()->withInfo('Password Updated');
    }
}
