<?php namespace Cysha\Modules\Auth;

use Illuminate\Foundation\AliasLoader;
use Cysha\Modules\Core\BaseServiceProvider;
use Cysha\Modules\Auth\Commands\InstallCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        \Config::set('auth.driver', 'verify');
        \Config::set('auth.model', 'Cysha\Modules\Auth\Models\User');

        $this->registerInstallCommand();
        $this->registerOtherPackages();
    }

    private function registerInstallCommand()
    {
        $this->app['cms.modules.auth:install'] = $this->app->share(function () {
            return new InstallCommand($this->app);
        });
        $this->commands('cms.modules.auth:install');
    }

    private function registerOtherPackages()
    {
        $serviceProviders = [
            'Toddish\Verify\VerifyServiceProvider',
        ];

        foreach ($serviceProviders as $sp) {
            $this->app->register($sp);
        }

        // $aliases = [
        // ];

        // foreach ($aliases as $alias => $class) {
        //     AliasLoader::getInstance()->alias($alias, $class);
        // }
    }
}
