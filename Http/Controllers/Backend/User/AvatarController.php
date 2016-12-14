<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Models\User;
use Illuminate\Http\Request;

class AvatarController extends BaseUserController
{
    public function getForm(User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Avatars', route('admin.user.avatar', $user->id));

        return $this->setView('admin.user.avatar', $data);
    }

    public function postForm(User $user, Request $input)
    {
        $input = $input->only(['avatar']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->back()->withInfo('User Updated');
    }
}
