<?php

namespace Cms\Modules\Auth\Providers;

use Cms\Modules\Core\Providers\BaseEventsProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class AuthEventsProvider extends BaseEventsProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /*
         * AuthController@postLogin
         * AuthController@postRegister
         */
        'Cms\Modules\Auth\Events\UserHasLoggedIn' => [
            'Cms\Modules\Auth\Events\Handlers\UpdateLastLogin',
        ],

        /*
         * AuthController@postRegister
         */
        'Cms\Modules\Auth\Events\UserIsRegistering' => [
        ],

        /*
         * AuthController@postRegister
         */
        'Cms\Modules\Auth\Events\UserHasRegistered' => [
        ],

        'Cms\Modules\Admin\Events\GotDatatableConfig' => [
            'Cms\Modules\Auth\Events\Handlers\ManipulateUserPermissionsDatatable',
            'Cms\Modules\Auth\Events\Handlers\ManipulateUserApiKeyDatatable',
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
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }
}
