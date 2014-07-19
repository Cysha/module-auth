<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\Role as VerifyVersion;
use Config;

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

    // public function permissions()
    // {
    //     return $this->hasMany(Config::get('verify::user_model'));
    // }

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

    public function isModerator()
    {
        return $this->pivot->is_moderator == 1 ? true : false;
    }

    public function transform()
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'level'        => $this->level,
            'color'        => $this->color,

            'is_moderator' => ($this->isModerator()),
            'is_own_group' => ($this->isSingleUser() === true && $this->isModerator()),
        ];
    }
}
