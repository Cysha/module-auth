<?php namespace Cms\Modules\Auth\Models;

use BeatSwitch\Lock\Callers\Caller;
use BeatSwitch\Lock\LockAware;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Cms\Modules\Core\Traits\DynamicRelationsTrait;

class User extends BaseModel implements Caller, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable,
        DynamicRelationsTrait,
        CanResetPassword,
        LockAware;

    protected $table = 'users';
    protected $fillable = ['id', 'username', 'first_name', 'last_name', 'password', 'email', 'verified_at', 'disabled_at'];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['screenname', 'avatar'];
    protected $with = ['roles'];
    protected $identifiableName = 'screenname';

    protected $link = [
        'route'      => 'pxcms.user.view',
        'attributes' => ['name' => 'screenname'],
    ];

    /**
     * Relationships
     */
    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role', 'roleables', 'caller_id', 'role_id')
            ->where('caller_type', $this->getCallerType())
            ->remember(10);
    }

    public function permissions()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Permission', 'permissionables', 'caller_id', 'role_id')
            ->where('caller_type', $this->getCallerType())
            ->remember(10);
    }


    /**
     * Attributes
     */
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


    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Other Methods
     */
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
        return $this->hasRole('Admin');
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getCallerRoles());
    }

    /**
     * Beatswitch\Lock Methods
     */
    public function getCallerType()
    {
        return 'auth_user';
    }

    public function getCallerId()
    {
        return $this->id;
    }

    public function getCallerRoles()
    {
        return $this->roles->fetch('name')->toArray();
    }

    /**
     * Transformer!
     */
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

            'roles'      => $this->getCallerRoles(),


            'verified'   => date_array($this->verified_at),
            'registered' => date_array($this->created_at),
        ];
    }

}
