<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\User;

use Cms\Modules\Core\Http\Controllers\BaseAdminController;
use Cms\Modules\Auth as Auth;
use Former;

class BaseUserController extends BaseAdminController
{
    public function boot()
    {
        parent::boot();

        $this->theme->setTitle('User Manager');
        $this->theme->breadcrumb()->add('User Manager', route('admin.user.index'));
    }

    public function getUserDetails(Auth\Models\User $user)
    {
        Former::populate($user);

        return compact('user');
    }

}
