<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as RoleRepo;
use Illuminate\Http\Request;
use Cms\Modules\Auth as Auth;

use Cms\Modules\Auth\Models\Role;
use Cms\Modules\Auth\Models\Permission;
use BeatSwitch\Lock\Manager;

class PermissionController extends BaseRoleController
{

    public function getForm(Auth\Models\Role $role, RoleRepo $roles)
    {
        $data = $this->getRoleDetails($role);

        $permissions = Permission::all();

        $groups = [];
        $modulePermissions = get_array_column(config('cms'), 'admin.permission_manage');
        foreach ($modulePermissions as $module => $permission_groups) {

            $groups = array_merge($groups, $permission_groups);
        }
        $groups = array_unique($groups);

        return $this->setView('admin.role.permissions', compact('role', 'permissions', 'groups'));
    }

    public function postForm(Auth\Models\Role $role, RoleRepo $roles, Request $input)
    {
        echo \Debug::dump($input->all(), '');die;

        foreach ($input->get('permissions') as $permission => $mode) {
            list($permission, $resource) = processPermission($permission);

            switch (strtolower($mode)) {
                case 'privilege':
                    LockManager::role($role->name)->allow($permission, $resource);
                break;

                case 'restriction':
                    LockManager::role($role->name)->deny($permission, $resource);
                break;

                case 'inherit':
                    $perm_id = with(new Permission)
                        ->whereAction($permission)
                        ->whereResourceType($resource)
                        ->get()
                        ->id;

                    // TODO: fix me
                break;
            }
        }
    }
}
