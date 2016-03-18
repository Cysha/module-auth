<?php

namespace Cms\Modules\Auth\Models;

class Permission extends BaseModel
{
    protected $table = 'permissions';
    protected $fillable = ['id', 'type', 'action', 'resource_type', 'resource_id', 'readable_name'];

    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role');
    }

    public function users()
    {
        return $this->morphedByMany(config('auth.model'), 'caller', 'auth_permissionables');
    }
}
