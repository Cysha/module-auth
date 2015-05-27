<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as RoleRepo;
use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

use Cms\Modules\Auth\Models\Role;
use Cms\Modules\Auth\Models\Permission;

class PermissionController extends BaseRoleController
{

    public function getForm(Auth\Models\Role $role, RoleRepo $roles)
    {
        $data = $this->getRoleDetails($role);

        $permissions = Permission::all();

        return $this->setView('admin.role.permissions', compact('role', 'permissions'));
    }
}
