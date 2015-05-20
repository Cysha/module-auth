<?php namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Core\Providers\BaseModuleProvider;
use Illuminate\Foundation\AliasLoader;
use Config;

class AuthModuleServiceProvider extends BaseModuleProvider
{

    /**
     * Register the defined middleware.
     *
     * @var array
     */
    protected $middleware = [
        'Auth' => [
            'hasRole'       => 'HasRoleMiddleware',
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
            'make:user' => 'MakeUserCommand'
        ],
    ];

    /**
     * Register repository bindings to the IoC
     *
     * @var array
     */
    protected $bindings = [
        'Cms\Modules\Auth\Repositories\User' => ['RepositoryInterface' => 'EloquentRepository'],
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

        // if socialite or the socialiteproviders package is installed, load em
        $loadSocialite = false;
        if (class_exists('SocialiteProviders\Manager\ServiceProvider')) {
            $loadSocialite = true;
            $this->app->register('SocialiteProviders\Manager\ServiceProvider');

        } elseif (class_exists('Laravel\Socialite\SocialiteServiceProvider')) {
            $loadSocialite = true;
            $this->app->register('Laravel\Socialite\SocialiteServiceProvider');
        }

        if ($loadSocialite === true) {
            AliasLoader::getInstance()->alias('Socialite', 'Laravel\Socialite\Facades\Socialite');
        }
    }

}
