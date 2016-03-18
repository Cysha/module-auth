<?php

namespace Cms\Modules\Auth\Models;

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
     * Returns all the permissions for a caller.
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     *
     * @return \BeatSwitch\Lock\Permissions\Permission[]
     */
    public function getCallerPermissions(LockCaller $caller)
    {
        $key = $this->getCacheKey(__FUNCTION__, $caller->getCallerType(), $caller->getCallerId());
        $permissions = cache('auth_permissions', $key, 1, function () use ($caller) {
            return DB::table('auth_permissions')
            ->join('auth_permissionables', function ($join) use ($caller) {
                $join->on('auth_permissions.id', '=', 'auth_permissionables.permission_id')
                    ->where('auth_permissionables.caller_type', '=', $caller->getCallerType())
                    ->where('auth_permissionables.caller_id', '=', $caller->getCallerId());
            })
            ->get(['auth_permissions.*']);
        });

        return empty($permissions) ? $permissions : PermissionFactory::createFromData($permissions);
    }

    /**
     * Stores a new permission for a caller.
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     */
    public function storeCallerPermission(LockCaller $caller, LockPermission $permission)
    {
        // if permission already exists in this config, use that
        $objPerm = DB::table('auth_permissions')->where([
            'type' => $permission->getType(),
            'action' => $permission->getAction(),
            'resource_type' => $permission->getResourceType(),
            'resource_id' => $permission->getResourceId(),
        ])->first();
        if ($objPerm === null) {
            $id = DB::table('auth_permissions')->insertGetId([
                'type' => $permission->getType(),
                'action' => $permission->getAction(),
                'resource_type' => $permission->getResourceType(),
                'resource_id' => $permission->getResourceId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $objPerm = DB::table('auth_permissions')->find($id);
        }

        // create a subsequent permissionable record
        DB::table('auth_permissionables')->insert([
            'permission_id' => $objPerm->id,
            'caller_type' => $caller->getCallerType(),
            'caller_id' => $caller->getCallerId(),
        ]);
    }

    /**
     * Removes a permission for a caller.
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     */
    public function removeCallerPermission(LockCaller $caller, LockPermission $permission)
    {
        $permission = DB::table('auth_permissions')
            ->where('type', $permission->getType())
            ->where('action', $permission->getAction())
            ->where('resource_type', $permission->getResourceType())
            ->where('resource_id', $permission->getResourceId())
            ->first(['id']);

        DB::table('auth_permissions')
            ->where('id', $permission->id)
            ->delete();

        DB::table('auth_permissionables')
            ->where('permission_id', $permission->id)
            ->where('caller_type', $caller->getCallerType())
            ->where('caller_id', $caller->getCallerId())
            ->delete();
    }

    /**
     * Checks if a permission is stored for a caller.  This method is used
     * so we don't duplicate data on storeCallerPermission method.
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     *
     * @return bool
     */
    public function hasCallerPermission(LockCaller $caller, LockPermission $permission)
    {
        $key = $this->getCacheKey(__FUNCTION__, $permission->getType(), $permission->getAction(), $permission->getResourceType(), $permission->getResourceId());

        return cache('auth_permissions', $key, 1, function () use ($permission) {
            return (bool) DB::table('auth_permissions')
                ->where('type', $permission->getType())
                ->where('action', $permission->getAction())
                ->where('resource_type', $permission->getResourceType())
                ->where('resource_id', $permission->getResourceId())
                ->first();
        });
    }

    /**
     * Returns all the permissions for a role.
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     *
     * @return \BeatSwitch\Lock\Permissions\Permission[]
     */
    public function getRolePermissions(LockRole $role)
    {
        $key = $this->getCacheKey(__FUNCTION__, $role->getRoleName());
        $permissions = cache('auth_permissions', $key, 1, function () use ($role) {
            return DB::table('auth_permissions')
                ->join('auth_permission_role', 'auth_permissions.id', '=', 'auth_permission_role.permission_id')
                ->join('auth_roles', 'roles.id', '=', 'auth_permission_role.role_id')
                ->where('auth_roles.name', $role->getRoleName())
                ->get(['auth_permissions.*']);
        });

        return empty($permissions) ? $permissions : PermissionFactory::createFromData($permissions);
    }

    /**
     * todo add logic for inherited roles
     * Stores a new permission for a role.
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     */
    public function storeRolePermission(LockRole $role, LockPermission $permission)
    {

        // if permission already exists in this config, use that
        $objPerm = DB::table('auth_permissions')
            ->where('type', $permission->getType())
            ->where('action', $permission->getAction())
            ->where('resource_type', $permission->getResourceType())
            ->where('resource_id', $permission->getResourceId())
            ->first();
        if ($objPerm === null) {
            $id = DB::table('auth_permissions')->insertGetId([
                'type' => $permission->getType(),
                'action' => $permission->getAction(),
                'resource_type' => $permission->getResourceType(),
                'resource_id' => $permission->getResourceId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $objPerm = DB::table('auth_permissions')->find($id);
        }

        // if role exists, otherwise create it
        $objRole = DB::table('auth_roles')->where('name', $role->getRoleName())->first();
        if ($objRole === null) {
            $objRole = DB::table('auth_roles')->insert([
                'name' => $role->getRoleName(),
            ]);
        }

        DB::table('auth_permission_role')->insert([
            'permission_id' => $objPerm->id,
            'role_id' => $objRole->id,
        ]);

        // clear this cache tag
        cache_flush('auth_permissions');
    }

    /**
     * Removes a permission for a role.
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     */
    public function removeRolePermission(LockRole $role, LockPermission $permission)
    {
        $dbPermission = DB::table('auth_permissions')
            ->where('type', $permission->getType())
            ->where('action', $permission->getAction())
            ->where('resource_type', $permission->getResourceType())
            ->where('resource_id', $permission->getResourceId())
            ->first(['id']);

        DB::table('auth_roles')
          ->where('name', $role->getRoleName())
          ->delete();

        if (!empty($dbPermission)) {
            DB::table('auth_permissions')
                ->where('id', $dbPermission->id)
                ->delete();

            DB::table('auth_permissionables')
                ->where('permission_id', $dbPermission->id)
                ->delete();

            DB::table('auth_permission_role')
                ->where('permission_id', $dbPermission->id)
                ->delete();
        }

        // clear this cache tag
        cache_flush('auth_permissions');
    }

    /**
     * Checks if a permission is stored for a role.
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     *
     * @return bool
     */
    public function hasRolePermission(LockRole $role, LockPermission $permission)
    {
        $key = $this->getCacheKey(__FUNCTION__, $role->getRoleName(), $permission->getType(), $permission->getAction(), $permission->getResourceType(), $permission->getResourceId());

        $permissionForRole = cache('auth_permissions', $key, 1, function () use ($role, $permission) {
            return (bool) DB::table('auth_permissions')
                ->join('auth_permission_role', 'auth_permissions.id', '=', 'auth_permission_role.permission_id')
                ->join('auth_roles', 'roles.id', '=', 'auth_permission_role.role_id')
                ->where('auth_roles.name', $role->getRoleName())
                ->where('auth_permissions.type', $permission->getType())
                ->where('auth_permissions.action', $permission->getAction())
                ->where('auth_permissions.resource_type', $permission->getResourceType())
                ->where('auth_permissions.resource_id', $permission->getResourceId())
                ->first();
        });

        return $permissionForRole;
    }
}
