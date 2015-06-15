<?php namespace Cms\Modules\Auth\Models;

use BeatSwitch\Lock\Callers\Caller as LockCaller;
use BeatSwitch\Lock\Drivers\Driver;
use BeatSwitch\Lock\Permissions\Permission as LockPermission;
use BeatSwitch\Lock\Permissions\PermissionFactory;
use BeatSwitch\Lock\Roles\Role as LockRole;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;

class CustomLockDriver implements Driver
{
    /**
     *
     */
    public function getCacheKey()
    {
        $uid = \Auth::check() ? \Auth::id() : 0;
        return implode(':', array_merge(['lock', $uid], func_get_args()));
    }

    /**
     * Returns all the permissions for a caller
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @return \BeatSwitch\Lock\Permissions\Permission[]
     */
    public function getCallerPermissions(LockCaller $caller)
    {
        $key = $this->getCacheKey(__FUNCTION__, $caller->getCallerType(), $caller->getCallerId());
        $permissions = cache('auth_permissions', $key, 1, function () use ($caller) {
            return DB::table('permissions')
            ->join('permissionables', function ($join) use ($caller) {
                $join->on('permissions.id', '=', 'permissionables.permission_id')
                    ->where('permissionables.caller_type', '=', $caller->getCallerType())
                    ->where('permissionables.caller_id', '=', $caller->getCallerId());
            })
            ->get(['permissions.*']);
        });

        return empty($permissions) ? $permissions : PermissionFactory::createFromData($permissions);
    }

    /**
     * Stores a new permission for a caller
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function storeCallerPermission(LockCaller $caller, LockPermission $permission)
    {
        // if permission already exists in this config, use that
        $objPerm = DB::table('permissions')->where([
            'type'          => $permission->getType(),
            'action'        => $permission->getAction(),
            'resource_type' => $permission->getResourceType(),
            'resource_id'   => $permission->getResourceId(),
        ])->first();
        if ($objPerm === null) {
            $objPerm = DB::table('permissions')->insert([
                'type'          => $permission->getType(),
                'action'        => $permission->getAction(),
                'resource_type' => $permission->getResourceType(),
                'resource_id'   => $permission->getResourceId(),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        // create a subsequent permissionable record
        DB::table('permissionables')->insert([
            'permission_id' => $objPerm->id,
            'caller_type'   => $caller->getCallerType(),
            'caller_id'     => $caller->getCallerId()
        ]);
    }

    /**
     * Removes a permission for a caller
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function removeCallerPermission(LockCaller $caller, LockPermission $permission)
    {
        $permission = DB::table('permissions')
                ->where('type', $permission->getType())
                ->where('action', $permission->getAction())
                ->where('resource_type', $permission->getResourceType())
                ->where('resource_id', $permission->getResourceId())
                ->first(['id']);

        DB::table('permissions')
          ->where('id', $permission['id'])
          ->delete();

        DB::table('permissionables')
            ->where('permission_id', $permission['id'])
            ->where('caller_type', $caller->getCallerType())
            ->where('caller_id', $caller->getCallerId())
            ->delete();
    }

    /**
     * Checks if a permission is stored for a caller.  This method is used
     * so we don't duplicate data on storeCallerPermission method
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return bool
     */
    public function hasCallerPermission(LockCaller $caller, LockPermission $permission)
    {
        $key = $this->getCacheKey(__FUNCTION__, $permission->getType(), $permission->getAction(), $permission->getResourceType(), $permission->getResourceId());
        return cache('auth_permissions', $key, 1, function () use ($permission) {
            return (bool) DB::table('permissions')
                ->where('type', $permission->getType())
                ->where('action', $permission->getAction())
                ->where('resource_type', $permission->getResourceType())
                ->where('resource_id', $permission->getResourceId())
                ->first();
        });
    }

    /**
     * Returns all the permissions for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @return \BeatSwitch\Lock\Permissions\Permission[]
     */
    public function getRolePermissions(LockRole $role)
    {
        $key = $this->getCacheKey(__FUNCTION__, $role->getRoleName());
        $permissions = cache('auth_permissions', $key, 1, function () use ($role) {
            return DB::table('permissions')
                ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                ->join('roles', 'roles.id', '=', 'permission_role.role_id')
                ->where('roles.name', $role->getRoleName())
                ->get(['permissions.*']);
        });

        return empty($permissions) ? $permissions : PermissionFactory::createFromData($permissions);
    }

    /**
     * todo add logic for inherited roles
     * Stores a new permission for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function storeRolePermission(LockRole $role, LockPermission $permission)
    {

        // if permission already exists in this config, use that
        $objPerm = DB::table('permissions')->where([
            'type'          => $permission->getType(),
            'action'        => $permission->getAction(),
            'resource_type' => $permission->getResourceType(),
            'resource_id'   => $permission->getResourceId(),
        ])->first();
        if ($objPerm === null) {
            $objPerm = DB::table('permissions')->insert([
                'type'          => $permission->getType(),
                'action'        => $permission->getAction(),
                'resource_type' => $permission->getResourceType(),
                'resource_id'   => $permission->getResourceId(),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        // if role exists, otherwise create it
        $objRole = DB::table('roles')->where(['name' => $role->getRoleName()])->first();
        if ($objRole === null) {
            $objRole = DB::table('roles')->insert([
                'name' => $role->getRoleName(),
            ]);
        }

        DB::table('permission_role')->insert([
            'permission_id' => $objPerm->id,
            'role_id'       => $objRole->id
        ]);

        // clear this cache tag
        cache_flush('auth_permissions');
    }

    /**
     * Removes a permission for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function removeRolePermission(LockRole $role, LockPermission $permission)
    {
        $dbPermission = DB::table('permissions')
            ->where('type', $permission->getType())
            ->where('action', $permission->getAction())
            ->where('resource_type', $permission->getResourceType())
            ->where('resource_id', $permission->getResourceId())
            ->first(['id']);

        DB::table('roles')
          ->where('name', $role->getRoleName())
          ->delete();

        if (!empty($dbPermission)) {

            DB::table('permissions')
                ->where('id', $dbPermission['id'])
                ->delete();

            DB::table('permissionables')
                ->where('permission_id', $dbPermission['id'])
                ->delete();

            DB::table('permission_role')
                ->where('permission_id', $dbPermission['id'])
                ->delete();
        }

        // clear this cache tag
        cache_flush('auth_permissions');
    }

    /**
     * Checks if a permission is stored for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return bool
     */
    public function hasRolePermission(LockRole $role, LockPermission $permission)
    {
        $key = $this->getCacheKey(__FUNCTION__, $role->getRoleName(), $permission->getType(), $permission->getAction(), $permission->getResourceType(), $permission->getResourceId());

        $permissionForRole = cache('auth_permissions', $key, 1, function () use ($role, $permission) {
            return (bool) DB::table('permissions')
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->where('roles.name', $role->getRoleName())
            ->where('permissions.type', $permission->getType())
            ->where('permissions.action', $permission->getAction())
            ->where('permissions.resource_type', $permission->getResourceType())
            ->where('permissions.resource_id', $permission->getResourceId())
            ->first();
        });

        return $permissionForRole;
    }

}
