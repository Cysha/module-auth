<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\User as VerifyVersion;
use Auth;
use Lang;
use Config;
use Input;

class User extends VerifyVersion
{
    use \Cysha\Modules\Core\Traits\LinkableTrait,
        \Cysha\Modules\Core\Traits\DynamicRelationsTrait,
        \Venturecraft\Revisionable\RevisionableTrait{
        \Venturecraft\Revisionable\RevisionableTrait::boot as revisionableBoot;
    }

    protected $revisionEnabled = false;

    protected $fillable = ['id', 'username', 'first_name', 'last_name', 'password', 'email', 'salt', 'verified', 'disabled'];
    protected $hidden = ['password', 'salt'];
    protected $appends = ['usercode', 'screenname', 'avatar'];
    protected $identifiableName = 'screenname';

    protected $link = [
        'route'      => 'pxcms.user.view',
        'attributes' => ['name' => 'screenname'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->linkableConstructor();
    }

    public static function boot()
    {
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

    public function ApiKey()
    {
        return $this->hasMany(__NAMESPACE__.'\ApiKey')->withTimestamps();
    }

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

    public function getAvatarAttribute($val)
    {
        if (empty($val) || $val == 'gravatar') {
            return sprintf(
                'http://www.gravatar.com/avatar/%s.png?s=64&d=mm&rating=g',
                md5($this->attributes['email'])
            );
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
            'screenname' => (string) $this->screenname,
            'name'       => (string) $this->name,
            'href'       => (string) $this->makeLink(true),
            'link'       => (string) $this->makeLink(false),

            'email'      => (string) $this->email,
            'avatar'     => (string) $this->avatar,


            'registered' => date_array($this->created_at),
        ];
    }

    /**
     * Fill attributes in $this from Input
     */
    public function hydrateFromInput(array $input = array())
    {
        if (!isset($this->fillable)) {
            return $this->fill(\Input::all());
        }

        if (empty($input)) {
            $input = \Input::only($this->fillable);
        } else {
            $input = array_only($input, $this->fillable);
        }

        $input = array_filter($input, 'strlen');

        return $this->fill($input);
    }
}
