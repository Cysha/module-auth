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

        echo ('Seeding Roles'."\n");
        $this->call('Cysha\Modules\Auth\Seeds\RoleSeeder');
        echo ('Seeding Permissions'."\n");
        $this->call('Cysha\Modules\Auth\Seeds\PermissionSeeder');
        echo ('Seeding Users'."\n");
        $this->call('Cysha\Modules\Auth\Seeds\UserSeeder');

    }
}
