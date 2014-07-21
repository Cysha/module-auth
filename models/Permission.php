<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\Permission as VerifyVersion;

class Permission extends VerifyVersion
{

    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role', $this->prefix.'permission_role')->withTimestamps();
    }

    public function can($permission, $resource)
    {
        echo \Debug::dump(func_get_args(), '');die;

        return $query->wherePermission();
    }


}
