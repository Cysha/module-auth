<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\Role as VerifyVersion;

class Role extends VerifyVersion
{
    use \Cysha\Modules\Core\Traits\SelfValidationTrait{
        \Cysha\Modules\Core\Traits\SelfValidationTrait::boot as validationBoot;
    }

    protected static $rules = array(
        'name'              => 'required|alpha_dash',
        'description'       => 'required',
        'color'             => array('regex:/^#?[a-fA-F0-9]{3,6}$/'),
    );

    protected $fillable = array('name', 'color', 'description', 'level', 'single_user_group');
    protected $hidden = array();
    protected $appends = array();
    protected static $purge = array();
    protected static $messages = array();

    public function moderator()
    {
        return $this->hasOne('Cysha\Modules\Auth\Models\User', 'id', 'moderator_id');
    }

    public function permissions()
    {
        return $this->hasMany(__NAMESPACE__.'\Permission');
    }

    public static function boot()
    {
        static::validationBoot();
    }

    public function scopeNotSingleUser($query)
    {
        return $query->whereSingleUser(0);
    }

    public function isSingleUser()
    {
        return $this->single_user === 0 ? false : true;
    }

    public function getUserCount()
    {
        return count($this->users);
    }
}
