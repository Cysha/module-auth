<?php namespace Cms\Modules\Auth\Models;

use DB;
use Carbon\Carbon;
use BeatSwitch\Lock\Roles\Role;
use BeatSwitch\Lock\Drivers\Driver;
use BeatSwitch\Lock\Callers\Caller;
use BeatSwitch\Lock\Permissions\Permission;
use BeatSwitch\Lock\Permissions\PermissionFactory;

class CustomLockDriver implements Driver
{
    /**
     * Returns all the permissions for a caller
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @return \BeatSwitch\Lock\Permissions\Permission[]
     */
    public function getCallerPermissions(Caller $caller)
    {
        $permissions = DB::table('permissions')
                        ->join('permissionables', function ($join) use ($caller) {
                            $join->on('permissions.id', '=', 'permissionables.permission_id')
                                ->where('permissionables.caller_type', '=', $caller->getCallerType())
                                ->where('permissionables.caller_id', '=', $caller->getCallerId());

                        })
                        ->get(['permissions.*']);

        return empty($permissions) ? $permissions : PermissionFactory::createFromData($permissions);
    }

    /**
     * @todo for some reason the query in the original db driver doesn't run more than once figure out why
     * Stores a new permission for a caller
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function storeCallerPermission(Caller $caller, Permission $permission)
    {

        $callerPermissionId = DB::table('permissions')
                                ->insertGetId([
                                    'type'          =>  $permission->getType(),
                                    'action'        =>  $permission->getAction(),
                                    'resource_type' =>  $permission->getResourceType(),
                                    'resource_id'   =>  $permission->getResourceId(),
                                    'created_at'    =>  Carbon::now(),
                                    'updated_at'    =>  Carbon::now(),
                                ]);

        //create a subsequent permissionable record
        DB::table('permissionables')
            ->insert([
                'permission_id' => $callerPermissionId,
                'caller_type'   => $caller->getCallerType(),
                'caller_id'     => $caller->getCallerId()
            ]);
    }

    /**
     * todo I should check for whereNull explicitly since mysql can't do where('resource_type', null)
     *
     * Removes a permission for a caller
     *
     * @param \BeatSwitch\Lock\Callers\Caller $caller
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function removeCallerPermission(Caller $caller, Permission $permission)
    {
        $permission = (array) DB::table('permissions')
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
    public function hasCallerPermission(Caller $caller, Permission $permission)
    {
        return (bool) DB::table('permissions')
                        ->where('type', $permission->getType())
                        ->where('action', $permission->getAction())
                        ->where('resource_type', $permission->getResourceType())
                        ->where('resource_id', $permission->getResourceId())
                        ->first();
    }

    /**
     * Returns all the permissions for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @return \BeatSwitch\Lock\Permissions\Permission[]
     */
    public function getRolePermissions(Role $role)
    {
        $permissions = DB::table('permissions')
                            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
                            ->where('roles.name', $role->getRoleName())
                            ->get(['permissions.*']);

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
    public function storeRolePermission(Role $role, Permission $permission)
    {
        $permissionId =DB::table('permissions')->insertGetId([
            'type'          => $permission->getType(),
            'action'        => $permission->getAction(),
            'resource_type' => $permission->getResourceType(),
            'resource_id'   => $permission->getResourceId(),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        $roleId =
        DB::table('roles')->insertGetId([
            'name'       => $role->getRoleName(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => $permissionId,
            'role_id'       => $roleId
        ]);
    }

    /**
     * Removes a permission for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return void
     */
    public function removeRolePermission(Role $role, Permission $permission)
    {
        $dbPermission = (array) DB::table('permissions')
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
    }

    /**
     * Checks if a permission is stored for a role
     *
     * @param \BeatSwitch\Lock\Roles\Role $role
     * @param \BeatSwitch\Lock\Permissions\Permission
     * @return bool
     */
    public function hasRolePermission(Role $role, Permission $permission)
    {
        $permissionForRole = (bool) DB::table('permissions')
                                        ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                                        ->join('roles', 'roles.id', '=', 'permission_role.role_id')
                                        ->where('roles.name', $role->getRoleName())
                                        ->where('permissions.type', $permission->getType())
                                        ->where('permissions.action', $permission->getAction())
                                        ->where('permissions.resource_type', $permission->getResourceType())
                                        ->where('permissions.resource_id', $permission->getResourceId())
                                        ->first();

        return $permissionForRole;
    }

}
