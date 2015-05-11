<?php namespace Cms\Modules\Auth\Models;

class Role extends BaseModel
{
    public function users()
    {
        return $this->morphedByMany(config('auth.model'), 'caller', 'roleables');
    }

    public function permissions()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Permission');
    }
}
