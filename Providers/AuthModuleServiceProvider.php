<?php namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Core\Providers\BaseModuleProvider;
use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;

class AuthModuleServiceProvider extends BaseModuleProvider
{

    /**
     * Register the defined middleware.
     *
     * @var array
     */
    protected $middleware = [
        'Auth' => [
            'hasRole' => 'HasRoleMiddleware',
            'hasPermission' => 'HasPermissionMiddleware',
        ],
    ];

    /**
     * The commands to register.
     *
     * @var array
     */
    protected $commands = [
        'Auth' => [
            'make:user' => 'MakeUserCommand',
            'module:publish-permissions' => 'ModulePublishPermissionsCommand',
        ],
    ];

    /**
     * Register repository bindings to the IoC
     *
     * @var array
     */
    protected $bindings = [
        'Cms\Modules\Auth\Repositories\User' => ['RepositoryInterface' => 'EloquentRepository'],
        'Cms\Modules\Auth\Repositories\Role' => ['RepositoryInterface' => 'EloquentRepository'],
    ];

    /**
     * Register Auth related stuffs
     */
    public function register()
    {
        parent::register();

        // override some config settings
        $userModel = 'Cms\Modules\Auth\Models\User';
        Config::set('auth.model', $userModel);
        Config::set('auth.table', with(new $userModel)->getTable());

        // attach view composer to the login & register form
        view()->composer('theme.*::views.partials.core._login_form', 'Cms\Modules\Auth\Composers\Recaptcha@loginForm');
        view()->composer('theme.*::views.partials.core._register_form', 'Cms\Modules\Auth\Composers\Recaptcha@registerForm');


    }

}
