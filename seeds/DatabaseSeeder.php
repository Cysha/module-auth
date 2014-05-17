<?php namespace Cysha\Modules\Auth\Seeds;

use Seeder;
use Eloquent;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('Cysha\Modules\Auth\Seeds\RoleSeeder');
        $this->call('Cysha\Modules\Auth\Seeds\PermissionSeeder');
        $this->call('Cysha\Modules\Auth\Seeds\UserSeeder');

    }
}
