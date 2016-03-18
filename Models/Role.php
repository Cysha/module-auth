<?php

namespace Cms\Modules\Auth\Models;

use BeatSwitch\Lock\Callers\Caller;

class Role extends BaseModel implements Caller
{
    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany(config('auth.model'), 'auth_roleables', 'role_id', 'caller_id')
            ->where('caller_type', 'auth_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Permission', 'auth_permission_role', 'role_id', 'permission_id');
    }

    public function getUserCountAttribute()
    {
        return $this->users->count();
    }

    public function scopeFindGroupByName($query, $group)
    {
        return $query->whereName($group)->get();
    }

    public function hasPermission($id)
    {
        $perm = $this->permissions->find($id);

        if ($perm !== null) {
            return true;
        }

        return false;
    }

    public function getPermissionProperty($id, $key)
    {
        if ($this->hasPermission($id)) {
            return $this->permissions->find($id)->$key;
        }

        return false;
    }

    /**
     * Beatswitch\Lock Methods.
     */
    public function getCallerType()
    {
        return 'auth_role';
    }

    public function getCallerId()
    {
        return $this->id;
    }

    public function getCallerRoles()
    {
        return [];
    }

    public function transform()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->userCount,
        ];
    }
}
