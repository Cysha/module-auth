<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Models\User;
use Illuminate\Http\Request;

class InfoController extends BaseUserController
{
    public function getForm(User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Basic Info', route('admin.user.edit', $user->id));

        return $this->setView('admin.user.edit-basic', $data);
    }

    public function postForm(User $user, Request $input)
    {
        $input = $input->only(['username', 'name', 'email']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->back()->withInfo('User Updated');
    }
}
