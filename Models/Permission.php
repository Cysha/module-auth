<?php namespace Cms\Modules\Auth\Models;

class Permission extends Eloquent
{
    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role');
    }
    public function users()
    {
        return $this->morphedByMany(__NAMESPACE__.'\User', 'caller', 'permissionables');
    }
}
