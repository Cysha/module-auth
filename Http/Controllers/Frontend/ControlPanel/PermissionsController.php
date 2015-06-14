<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Models\Permission;

class PermissionsController extends BaseController
{

    public function getForm()
    {

        $permissions = Permission::orderBy('resource_type', 'asc')->get();

        $groups = [];
        $modulePermissions = get_array_column(config('cms'), 'admin.permission_manage');
        foreach ($modulePermissions as $module => $permission_groups) {

            $groups = array_merge($groups, $permission_groups);
        }
        $groups = array_unique($groups);

        return $this->setView('controlpanel.permissions', compact('role', 'permissions', 'groups'));
    }

}
