<?php

namespace Cms\Modules\Auth\Models;

use BeatSwitch\Lock\Callers\Caller;
use BeatSwitch\Lock\Integrations\Laravel\Facades\Lock;
use BeatSwitch\Lock\LockAware;
use Cms\Modules\Core\Traits\DynamicRelationsTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements Caller, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable,
        DynamicRelationsTrait,
        CanResetPassword,
        LockAware;

    protected $table = 'users';
    protected $fillable = ['id', 'username', 'name', 'password', 'salt', 'email', 'avatar', 'use_nick', 'secret_2fa', 'verified_2fa', 'verified_at', 'disabled_at'];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['screenname', 'avatar'];
    protected $dates = ['pass_expires_on', 'last_logged_at', 'verified_at', 'disabled_at', 'created_at', 'updated_at'];
    protected $with = ['roles'];
    protected $casts = [
        'verified_2fa' => 'bool',
    ];

    protected $identifiableName = 'screenname';
    protected $link = [
        'route' => 'pxcms.user.dashboard', #'pxcms.user.view',
        'attributes' => ['auth_user' => 'username'],
    ];

    /**
     * Relationships.
     */
    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role', 'auth_roleables', 'caller_id', 'role_id')
            ->where('caller_type', $this->getCallerType());
    }

    public function permissions()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Permission', 'auth_permissionables', 'caller_id', 'role_id')
            ->where('caller_type', $this->getCallerType());
    }

    /**
     * Attributes.
     */
    public function getScreennameAttribute()
    {
        // admin wants to override what the user's screenname comes out as
        if (($setting = config('cms.auth.config.users.force_screenname', 'NULL')) !== 'NULL') {
            $this->use_nick = $setting;
        }

        // this usually happens if social login was how they registered
        if (empty($this->username)) {
            return $this->name;
        }

        // switch as needed
        return $this->use_nick == 1 ? $this->name : $this->username;
    }

    public function getHasEnabled2faAttribute()
    {
        // check if the user has enabled 2fa
        return !empty($this->secret_2fa);
    }

    public function getRequire2faAttribute()
    {
        // check if the user has enabled 2fa
        return !empty($this->secret_2fa) && $this->verified_2fa;
    }

    public function getAvatarAttribute($val, $size = 64)
    {
        if (empty($val) || $val === 'gravatar') {
            return $this->gravatar($size);
        }

        return $this->urlAvatar();
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getUploadDirAttribute()
    {
        return 'uploads/'.sha1($this->id);
    }

    /**
     * Other Methods.
     */
    public function getAvatarList()
    {
        $avatars = [];

        // gravatar
        $avatars['gravatar'] = $this->gravatar('64');

        // socials
        if (app('modules')->has('Social') && app('modules')->get('Social')->enabled()) {
            \Debug::console($this->providers()->get());
            foreach ($this->providers()->get() as $provider) {
                $avatars[$provider->provider] = $provider->avatar;
            }
        }

        // uploadables
        $avatarDir = \File::files($this->uploadDir);
        if (!empty($avatarDir)) {
            foreach ($avatarDir as $id => $avatar) {
                $avatars['Upload '.($id + 1)] = '/'.$avatar;
            }
        }

        return $avatars;
    }

    public function urlAvatar()
    {
        return $this->getOriginal('avatar');
    }

    public function gravatar($size)
    {
        return sprintf(
            'http://www.gravatar.com/avatar/%s.png?s=%d&d=mm&rating=g',
            md5($this->attributes['email']),
            $size
        );
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
        return Lock::can('access', 'admin_config');
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getCallerRoles());
    }

    public function hasRoles($roles)
    {
        if (count(func_get_args()) == 1) {
            $roles = [func_get_args()];
        } else {
            $roles = func_get_args();
        }

        if (empty($roles)) {
            return false;
        }

        $return = true;
        foreach ($roles as $role) {
            $hasRole = in_array($role, $this->getCallerRoles());

            if ($hasRole === false) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * Beatswitch\Lock Methods.
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
        $return = [
            'id' => (int) $this->id,
            'username' => (string) $this->username,
            'screenname' => (string) $this->screenname,
            'name' => (string) $this->name,

            'links' => [
                'self' => (string) $this->makeLink(true),
                'html' => (string) $this->screenname, #$this->makeLink(false),
            ],

            'email' => (string) $this->email,
            'avatar' => (string) $this->avatar,

            'require2fa' => $this->require2fa,
            'last_logged_at' => date_array($this->last_logged_at),
            'verified' => date_array($this->verified_at),
            'registered' => date_array($this->created_at),

            'roles' => [],
        ];

        if ($this->roles !== null) {
            $return['roles'] = $this->getCallerRoles();
        }

        return $return;
    }
}
