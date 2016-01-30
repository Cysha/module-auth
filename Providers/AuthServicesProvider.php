<?php namespace Cms\Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AuthServicesProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // if recaptcha is installed, load it up & sort the aliasing
        if (class_exists('Greggilbert\Recaptcha\RecaptchaServiceProvider')) {
            \Config::set(
                'cms.admin.admin.services_views',
                array_merge(config('cms.admin.admin.services_views'), ['auth::admin.config.partials.recaptcha'])
            );

            $this->app->register('Greggilbert\Recaptcha\RecaptchaServiceProvider');
        }


    }
}
