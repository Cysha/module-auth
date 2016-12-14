<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Auth\Http\Requests\BackendCreateUserRequest;

class CreateController extends BaseUserController
{
    public function getForm()
    {
        $this->setTitle('Create new user');
        $this->theme->breadcrumb()->add('Create User', route('admin.user.add'));

        return $this->setView('admin.user.edit-basic');
    }

    public function postForm(BackendCreateUserRequest $input)
    {
        $input = $input->only(['username', 'name', 'email']);

        $authModel = config('cms.auth.config.user_model');
        $user = with(new $authModel());
        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->to(route('admin.user.edit', $user->id))->withInfo('User Created');
    }
}
