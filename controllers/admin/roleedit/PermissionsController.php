<?php namespace Cysha\Modules\Auth\Controllers\Admin\RoleEdit;

use Cysha\Modules\Auth as Auth;
use Former, URL;

class PermissionsController extends BaseRoleEditController
{
    public function getEdit(Auth\Models\Role $objRole)
    {
        $this->objTheme->setTitle('Role Manager <small>> '.$objRole->name.' > Permissions</small>');
        $this->objTheme->breadcrumb()->add('Permissions', URL::route('admin.role.permissions', $objRole->id));

        $permissions = Auth\Models\Permission::all();

        Former::populate($objRole);

        return $this->setView('role.admin.permissions', array(
            'role'        => $objRole,
            'permissions' => $permissions,
        ), 'module');
    }


}
