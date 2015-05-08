<?php namespace Cms\Modules\Auth\Models;

use BeatSwitch\Lock\Callers\Caller;
use BeatSwitch\Lock\LockAware;

class User extends BaseModel implements Caller
{
    use LockAware;

    protected $table = 'users';
    protected $fillable = ['id', 'username', 'first_name', 'last_name', 'password', 'email', 'verified_at', 'disabled_at'];
    protected $hidden = ['password'];
    protected $appends = ['usercode', 'screenname', 'avatar'];
    protected $identifiableName = 'screenname';

    protected $link = [
        'route'      => 'pxcms.user.view',
        'attributes' => ['name' => 'screenname'],
    ];


    public function getScreennameAttribute()
    {
        if ($this->use_nick == '-1' && !empty($this->first_name) && !empty($this->last_name)) {
            return $this->fullName;
        }

        if (!isset($this->nicks) || !count($this->nicks)) {
            return $this->username;
        }

        return array_get($this->nicks, $this->use_nick, $this->username);
    }

    public function getFullNameAttribute()
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }

    public function getCodeSaltAttribute()
    {
        return substr($this->salt, -5);
    }

    public function getUsercodeAttribute()
    {
        return md5($this->id . $this->codesalt);
    }

    public function getAvatarAttribute($val, $size = 64)
    {
        if (empty($val) || $val == 'gravatar') {
            return sprintf(
                'http://www.gravatar.com/avatar/%s.png?s=%d&d=mm&rating=g',
                md5($this->attributes['email']),
                $size
            );
        }

        return $val;
    }

    /**
     * Salts and saves the password
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $salt = md5(str_random(64) . time());
        $hashed = \Hash::make($salt . $password);
        $this->attributes['password'] = $hashed;
        $this->attributes['salt'] = $salt;
    }


    public function avatar($size)
    {
        return $this->getAvatarAttribute($this->getOriginal('avatar'), $size);
    }

    public function verify($code)
    {
        if ($this->usercode === md5($this->id.$code)) {

            $this->verified = 1;

            if ($this->save()) {
                return true;
            }

            throw new \RuntimeException(Lang::get('auth::verify.failed'));
        }

        return false;
    }

    public function isAdmin()
    {
        return false;
    }


    /**
     * Beatswitch\Lock Methods
     */
    public function getCallerType()
    {
        return 'users';
    }

    public function getCallerId()
    {
        return $this->id;
    }

    public function getCallerRoles()
    {
        return ['user'];
    }


    public function transform()
    {
        return [
            'id'         => (int)$this->id,
            'username'   => (string) $this->username,
            'screenname' => (string) $this->screenname,
            'name'       => (string) $this->name,
            'href'       => (string) $this->makeLink(true),
            'link'       => (string) $this->makeLink(false),

            'email'      => (string) $this->email,
            'avatar'     => (string) $this->avatar,


            'registered' => date_array($this->created_at),
        ];
    }

}
