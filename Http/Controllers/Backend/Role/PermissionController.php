<?php namespace Cms\Modules\Auth\Http\Controllers\Backend\Role;

use Cms\Modules\Auth\Repositories\Role\RepositoryInterface as RoleRepo;
use Cms\Modules\Auth\Models\Permission;
use Illuminate\Support\Facades\DB;
use Cms\Modules\Auth\Models\Role;
use BeatSwitch\Lock\Manager;
use Illuminate\Http\Request;

class PermissionController extends BaseRoleController
{

    public function getForm(Role $role, RoleRepo $roles)
    {
        $data = $this->getRoleDetails($role);

        $permissions = Permission::orderBy('resource_type', 'asc')->get();

        $groups = [];
        $modulePermissions = get_array_column(config('cms'), 'admin.permission_manage');
        foreach ($modulePermissions as $module => $permission_groups) {

            $groups = array_merge($groups, $permission_groups);
        }
        $groups = array_unique($groups);


        return $this->setView('admin.role.permissions', compact('role', 'permissions', 'groups'));
    }

    public function postForm(Role $role, RoleRepo $roles, Request $input, Manager $lockManager)
    {

        $lock = $lockManager->role($role->name);
        foreach ($input->get('permissions') as $permission => $mode) {
            list($permission, $resource) = processPermission($permission);

            switch (strtolower($mode)) {
                case 'privilege':
                    $lock->allow($permission, $resource);
                break;

                case 'restriction':
                    $lock->deny($permission, $resource);
                break;

                case 'inherit':
                    $perm = with(new Permission)
                        ->whereAction($permission)
                        ->whereResourceType($resource)
                        ->get();

                    if ($perm !== null) {
                        DB::table('permission_role')
                            ->whereRoleId($role->id)
                            ->whereIn('permission_id', $perm->lists('id')->toArray())
                            ->delete();
                    }
                break;
            }
        }

        artisan_call('cache:clear');
        return redirect()->back()
            ->withInfo('Permissions Processed.');
    }
}
