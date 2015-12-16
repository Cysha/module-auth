<?php

namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Auth as Auth;
use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as RoleRepo;

class InfoController extends BaseRoleController
{
    public function getForm(Auth\Models\Role $role, RoleRepo $roles)
    {
        $data = $this->getRoleDetails($role);

        return $this->setView('admin.role.edit-basic', $data);
    }
}
