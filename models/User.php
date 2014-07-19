<?php namespace Cysha\Modules\Auth\Models;

use \Toddish\Verify\Models\User as VerifyVersion;
use Auth;
use Lang;
use Config;

class User extends VerifyVersion
{
    use \Cysha\Modules\Core\Traits\SelfValidationTrait,
        \Cysha\Modules\Core\Traits\LinkableTrait{
        \Cysha\Modules\Core\Traits\SelfValidationTrait::boot as validationBoot;
    }

    protected $softDelete = true;

    protected static $rules = array(
        'creating' => array(
            'username' => 'required|min:5|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
            'tnc'      => 'required|accepted',
        ),
        'updating' => array(
            'username' => 'min:5|unique:users,username,:id:',
            'email'    => 'email|unique:users,email,:id:',
            'password' => 'min:5|confirmed',
        ),
    );
    protected static $messages;
    protected $fillable = array('id', 'username', 'first_name', 'last_name', 'password', 'salt', 'password_confirmation', 'email', 'salt', 'verified', 'disabled', 'tnc');
    protected $hidden = array('password', 'salt');
    protected $appends = array('usercode');
    protected static $purge = array('password_confirmation', 'tnc');
    protected $identifiableName = 'username';

    protected $link = [
        'route'      => 'pxcms.user.view',
        'attributes' => ['name' => 'username'],
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
    }

    public function permissions()
    {
        return $this->hasManyThrough(Config::get('verify::permission_model'), Config::get('verify::group_model'));
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
            $gravatar = \App::make('simplegravatar');

            return $gravatar->setDefault('mm')->getGravatar($this->attributes['email']);
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
            'id'       => $this->id,
            'username' => $this->username,
            'name'     => $this->username,
            'href'     => $this->makeLink(true),
            'link'     => $this->makeLink(false),

            'email'    => $this->email,
            'avatar'   => $this->avatar,
        ];
    }
}
