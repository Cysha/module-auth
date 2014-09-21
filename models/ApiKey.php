<?php namespace Cysha\Modules\Auth\Models;

use DB;

class ApiKey extends BaseModel
{
    public $table = 'api_auth';
    public $fillable = ['user_id', 'key', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(__NAMESPACE__.'\User')->withTimestamps();
    }

    public function scopeKeyExists($query, $key)
    {
        return $query->whereKey($key)->where('expires_at', '>=', DB::Raw('NOW()'))->first();
    }

}
