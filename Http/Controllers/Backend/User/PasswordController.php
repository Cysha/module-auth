<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class PasswordController extends BaseUserController
{
    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->setTitle('User Manager <small>> '.$user->screename.' > Edit</small>');

        return $this->setView('admin.user.password', $data, 'module');
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->only(['password', 'password_confirmation']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return Redirect::back()->withErrors($user->getErrors());
        }

        return Redirect::route('admin.user.password', $user->id)->withInfo('Password Updated');
    }
}
