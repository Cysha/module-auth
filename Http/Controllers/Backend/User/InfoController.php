<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use Cms\Modules\Auth;

class InfoController extends BaseUserController
{
    public function boot()
    {
        parent::boot();

    }

    public function getIndex(Auth\Models\User $user)
    {
    }

    public function getForm(Auth\Models\User $user)
    {
        $data = $this->getUserDetails($user);
        $this->theme->breadcrumb()->add('Basic Info', route('admin.user.edit', $user->id));

        return $this->setView('admin.user.edit-basic', $data);
    }

    public function postForm(Auth\Models\User $user, Request $input)
    {
        $input = $input->only(['username', 'name', 'email']);

        $user->hydrateFromInput($input);

        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        return redirect()->back()->withInfo('User Updated');
    }
}
