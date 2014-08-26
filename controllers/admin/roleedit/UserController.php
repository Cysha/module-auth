<?php namespace Cysha\Modules\Auth\Controllers\Admin\RoleEdit;

use Cysha\Modules\Auth as Auth;
use Former, URL;

class UserController extends BaseRoleEditController
{
    public function getEdit(Auth\Models\Role $objRole)
    {
        $this->objTheme->setTitle('Role Manager <small>> '.$objRole->name.' > User List</small>');
        $this->objTheme->breadcrumb()->add('Users', URL::route('admin.role.user', $objRole->id));



        return $this->setView('role.admin.users', array(
            'role'        => $objRole,
        ), 'module');
    }

}
