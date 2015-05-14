<?php namespace Cms\Modules\Auth\Providers;

use BeatSwitch\Lock\Integrations\Laravel\LockServiceProvider;
use BeatSwitch\Lock\Drivers\ArrayDriver;

class CustomLockServiceProvider extends LockServiceProvider
{
    /**
     * @inheritDoc
     */
    protected function getDriver()
    {
        // Get the configuration options for Lock.
        $driver = $this->app['config']->get('lock.driver');

        // If the user choose the persistent database driver, bootstrap
        // the database driver with the default database connection.
        if ($driver === 'database') {
            return new \Cms\Modules\Auth\Models\CustomLockDriver;
        }

        // Otherwise bootstrap the static array driver.
        return new ArrayDriver();
    }
}
