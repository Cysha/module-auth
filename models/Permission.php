<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\Permission as VerifyVersion;

class Permission extends VerifyVersion
{

    public function can($permission, $resource)
    {
        echo \Debug::dump(func_get_args(), '');die;

        return $query->wherePermission();
    }
}
