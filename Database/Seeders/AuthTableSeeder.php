<?php

namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AuthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        $this->call(__NAMESPACE__.'\RoleSeeder');
        $this->call(__NAMESPACE__.'\PermissionSeeder');
    }
}
