<?php namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Core\Providers\CmsRoutingProvider;
use Illuminate\Routing\Router;

class AuthRoutingProvider extends CmsRoutingProvider
{

    protected $namespace = 'Cms\Modules\Auth\Http\Controllers';

    /**
     * @return string
     */
    protected function getFrontendRoute()
    {
        return __DIR__ . '/../Http/routes-frontend.php';
    }

    /**
     * @return string
     */
    protected function getBackendRoute()
    {
        return __DIR__ . '/../Http/routes-backend.php';
    }

    /**
     * @return string
     */
    protected function getApiRoute()
    {
        return __DIR__ . '/../Http/routes-api.php';
    }

    public function boot(Router $router)
    {
        parent::boot($router);

        $router->bind('auth_user', function ($user) {
            $model = config('auth.model');

            return with(new $model)->where('username', $user)->firstOrFail();
        });

        $router->bind('auth_user_id', function ($id) {
            $model = config('auth.model');

            return with(new $model)->findOrFail($id);
        });

        $router->bind('auth_role_id', function ($id) {
            return with(new \Cms\Modules\Auth\Models\Role)->with('permissions')->findOrFail($id);
        });
    }
}
