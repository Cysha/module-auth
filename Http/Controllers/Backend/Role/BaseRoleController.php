<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Core\Http\Controllers\BaseBackendController;
use Cms\Modules\Auth as Auth;
use Former;

class BaseRoleController extends BaseBackendController
{
    public function boot()
    {
        parent::boot();

        $this->theme->setTitle('Role Manager');
        $this->theme->breadcrumb()->add('Role Manager', route('admin.role.index'));
    }

    public function getRoleDetails(Auth\Models\Role $role)
    {
        Former::populate($role);
        $this->theme->setTitle('Role Manager <small>> '.$role->name.'</small>');

        return compact('role');
    }

}
