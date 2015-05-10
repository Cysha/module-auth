<?php namespace Cms\Modules\Auth\Models;

class Role extends Eloquent
{
    public function users()
    {
        return $this->morphedByMany(__NAMESPACE__.'\User', 'caller', 'roleables');
    }

    public function permissions()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Permission');
    }
}
