<?php

namespace Cms\Modules\Auth\Providers;

use BeatSwitch\Lock\Integrations\Laravel\LockServiceProvider;
use BeatSwitch\Lock\Drivers\ArrayDriver;

class CustomLockServiceProvider extends LockServiceProvider
{
    /**
     * Returns the configured driver.
     *
     * @return \BeatSwitch\Lock\Drivers\Driver
     */
    protected function driver()
    {
        // obtain the driver details
        $driver = config('lock.driver', null);

        // If the user choose the persistent database driver, bootstrap
        // the database driver with the default database connection.
        if ($driver !== null) {
            return new $driver($this->app['db']->connection(), config('lock.table'));
        }

        // Otherwise use the static array driver.
        return new ArrayDriver();
    }
}
