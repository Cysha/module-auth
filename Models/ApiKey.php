<?php

namespace Cms\Modules\Auth\Models;

class ApiKey extends BaseModel
{
    public $table = 'api_auth';
    public $fillable = ['user_id', 'key', 'description', 'expires_at'];
    protected $dates = ['created_at', 'updated_at', 'expires_at'];

    public function user()
    {
        $authModel = config('auth.model');

        return $this->belongsTo($authModel);
    }

    public function scopeKeyExists($query, $key)
    {
        return $query->whereKey($key)->where('expires_at', '>=', \DB::Raw('NOW()'))->first();
    }
}
