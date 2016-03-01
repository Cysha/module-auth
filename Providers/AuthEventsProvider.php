<?php namespace Cms\Modules\Auth\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Cms\Modules\Core\Providers\BaseEventsProvider;
use Cms\Modules\Core;
use Cms\Modules\Auth;
use Cache;

class AuthEventsProvider extends BaseEventsProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * AuthController@postLogin
         * AuthController@postRegister
         */
        'Cms\Modules\Auth\Events\UserHasLoggedIn' => [
            'Cms\Modules\Auth\Events\Handlers\CheckFor2Fa',
            'Cms\Modules\Auth\Events\Handlers\CheckForExpiredPassword',
            'Cms\Modules\Auth\Events\Handlers\UpdateLastLogin',
        ],

        /**
         * AuthController@postRegister
         */
        'Cms\Modules\Auth\Events\UserIsRegistering' => [
        ],

        /**
         * AuthController@postRegister
         */
        'Cms\Modules\Auth\Events\UserHasRegistered' => [
        ],

        /**
         * SecurityController@postRegister
         */
        'Cms\Modules\Auth\Events\UserPasswordWasChanged' => [
            'Cms\Modules\Auth\Events\Handlers\RemovePasswordChangeLock',
        ],

        'Cms\Modules\Admin\Events\GotDatatableConfig' => [
            'Cms\Modules\Auth\Events\Handlers\ManipulateUserPermissionsDatatable',
            'Cms\Modules\Auth\Events\Handlers\ManipulateUserApiKeyDatatable',
            'Cms\Modules\Auth\Events\Handlers\ManipulateRoleUsersDatatable',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [

    ];


    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        // clear acp badge caches
        $models = [
            'User', 'Role', 'ApiKey',
        ];

        foreach ($models as $model) {
            $path = 'Cms\\Modules\\Auth\\Models\\'.$model;
            $path::saved(function() use ($model) {
                \Cache::forget('sidebar.auth.'.strtolower($model).'.count');
            });
        }

        parent::boot($events);
    }

}
