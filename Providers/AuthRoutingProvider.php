<?php

namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Core\Providers\CmsRoutingProvider;
use Illuminate\Support\Facades\Route;

class AuthRoutingProvider extends CmsRoutingProvider
{
    protected $namespace = 'Cms\Modules\Auth\Http\Controllers';

    /**
     * @return string
     */
    protected function getFrontendRoute()
    {
        return __DIR__.'/../Http/routes-frontend.php';
    }

    /**
     * @return string
     */
    protected function getBackendRoute()
    {
        return __DIR__.'/../Http/routes-backend.php';
    }

    /**
     * @return string
     */
    protected function getApiRoute()
    {
        return __DIR__.'/../Http/routes-api.php';
    }

    public function boot()
    {
        parent::boot();

        Route::bind('auth_user', function ($user) {
            $model = config('cms.auth.config.user_model');

            return with(new $model())->where('username', $user)->firstOrFail();
        });

        Route::bind('auth_user_id', function ($id) {
            $model = config('cms.auth.config.user_model');

            return with(new $model())->findOrFail($id);
        });

        Route::bind('auth_role_id', function ($id) {
            return with(new \Cms\Modules\Auth\Models\Role())->with('permissions')->findOrFail($id);
        });

        Route::bind('auth_apikey_id', function ($id) {
            return with(new \Cms\Modules\Auth\Models\ApiKey())->findOrFail($id);
        });
    }
}
