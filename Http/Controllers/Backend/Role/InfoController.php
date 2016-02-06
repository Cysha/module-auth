<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as RoleRepo;
use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

class InfoController extends BaseRoleController
{

    public function getForm(Auth\Models\Role $role, RoleRepo $roles)
    {
        $data = $this->getRoleDetails($role);

        return $this->setView('admin.role.edit-basic', $data);
    }

    public function redirect(Auth\Models\Role $role) {
        return redirect()->route('admin.role.edit', $role->id);
    }
}
