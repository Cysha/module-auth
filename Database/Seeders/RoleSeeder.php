<?php namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $models = [
            [
                'name'        => 'Admin',
                'description' => 'Administration Role',
            ],
            [
                'name'        => 'Staff',
                'description' => 'Staff Role',
            ],
            [
                'name'        => 'User',
                'description' => 'User Role',
            ],
        ];

        $seedModel = 'Cms\Modules\Auth\Models\Role';
        foreach ($models as $model) {
            with(new $seedModel)->create($model);
        }
    }
}
