<?php namespace Cms\Modules\Auth\Models;

use BeatSwitch\Lock\Callers\Caller;

class Role extends BaseModel implements Caller
{
    public function users()
    {
        return $this->belongsToMany(config('auth.model'), 'roleables', 'role_id', 'caller_id')
            ->where('caller_type', $this->getCallerType());
    }

    public function permissions()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Permission');
    }

    public function getUserCount()
    {
        return count($this->users);
    }

    public function scopeFindGroupByName($query, $group)
    {
        return $query->whereName($group)->get();
    }

    /**
     * Beatswitch\Lock Methods
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
            'id'           => $this->id,
            'name'         => $this->name,
        ];
    }
}
