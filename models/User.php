<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\User as VerifyVersion;
use Auth;
use Lang;
use Config;

class User extends VerifyVersion
{
    use \Cysha\Modules\Core\Traits\SelfValidationTrait,
        \Cysha\Modules\Core\Traits\LinkableTrait,
        \Venturecraft\Revisionable\RevisionableTrait{
        \Cysha\Modules\Core\Traits\SelfValidationTrait::boot as validationBoot;
        \Venturecraft\Revisionable\RevisionableTrait::boot as revisionableBoot;
    }

    protected $revisionEnabled = false;

    protected static $rules = array(
        'creating' => array(
            'username' => 'required|min:5|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
        ),
        'updating' => array(
            'username' => 'min:5|unique:users,username,:id:',
            'email'    => 'email|unique:users,email,:id:',
            'password' => 'min:5|confirmed',
        ),
    );
    protected static $messages;
    protected $fillable = array('id', 'username', 'first_name', 'last_name', 'password', 'salt', 'password_confirmation', 'email', 'salt', 'verified', 'disabled');
    protected $hidden = array('password', 'salt');
    protected $appends = array('usercode');
    protected static $purge = array('password_confirmation', 'tnc');
    protected $identifiableName = 'name';

    protected $link = [
        'route'      => 'pxcms.user.view',
        'attributes' => ['name' => 'name'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->linkableConstructor();

        self::$messages = array(
            'username.unique' => Lang::get('auth::register.username'),
            'email.unique'    => Lang::get('auth::register.email'),
            'password'        => Lang::get('auth::register.password'),
            'password.min'    => Lang::get('auth::register.password.min'),
        );
    }

    public static function boot()
    {
        static::validationBoot();
        static::revisionableBoot();
    }

    public function roles()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Role', $this->prefix.'role_user')->withTimestamps();
    }

    // public function permissions()
    // {
    //     return $this->hasManyThrough(Config::get('verify::permission_model'), Config::get('verify::group_model'));
    // }

    public function getNameAttribute()
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

    public function getAvatarAttribute($val)
    {
        if (empty($val) || $val == 'gravatar') {
            return sprintf('http://www.gravatar.com/avatar/%s.png?s=64&d=mm&rating=g', md5($this->attributes['email']));
        }

        return $val;
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
        return $this->is(array(Config::get('auth::roles.super_group_name'), Config::get('auth::roles.admin_group_name')));
    }

    public function transform()
    {
        return [
            'id'         => (int)$this->id,
            'username'   => (string) $this->username,
            'name'       => (string) $this->name,
            'href'       => (string) $this->makeLink(true),
            'link'       => (string) $this->makeLink(false),

            'email'      => (string) $this->email,
            'avatar'     => (string) $this->avatar,


            'registered' => date_array($this->created_at),
        ];
    }
}
