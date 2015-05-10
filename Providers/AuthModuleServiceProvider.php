<?php namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Core\Providers\BaseModuleProvider;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Auth\Guard;
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
     * Register Auth related stuffs
     */
    public function register()
    {
        parent::register();

        // override some config settings
        $userModel = 'Cms\Modules\Auth\Models\User';
        Config::set('auth.model', $userModel);
        Config::set('auth.table', with(new $userModel)->getTable());
    }

}
