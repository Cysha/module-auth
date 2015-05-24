<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class InfoController extends BaseUserController
{
    public function getIndex(Auth\Models\User $user)
    {
    }

    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->setTitle('User Manager <small>> '.$user->screenname.' > Edit</small>');

        return $this->setView('admin.user.edit-basic', $data);
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->only(['username', 'first_name', 'last_name', 'email']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->back()->withInfo('User Updated');
    }
}
