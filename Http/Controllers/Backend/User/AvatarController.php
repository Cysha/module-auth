<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class AvatarController extends BaseUserController
{

    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);

        return $this->setView('admin.user.avatar', $data);
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->only(['avatar']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->back()->withInfo('User Updated');
    }
}
