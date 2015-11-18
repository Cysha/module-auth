<?php namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AuthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(__NAMESPACE__.'\RoleSeeder');
        $this->call(__NAMESPACE__.'\UserSeeder');
        $this->call(__NAMESPACE__.'\PermissionSeeder');
    }

}
