<?php namespace Cms\Modules\Auth\Models;

class Permission extends BaseModel
{
    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role');
    }

    public function users()
    {
        return $this->morphedByMany(config('auth.model'), 'caller', 'permissionables');
    }
}
