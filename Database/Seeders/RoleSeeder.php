<?php

namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $models = [
            [
                'name' => 'Admin',
                'description' => 'Administration Role',
            ],
            [
                'name' => 'Staff',
                'description' => 'Staff Role',
            ],
            [
                'name' => 'User',
                'description' => 'User Role',
            ],
            [
                'name' => 'Guest',
                'description' => 'Guest Role',
            ],
        ];

        $seedModel = 'Cms\Modules\Auth\Models\Role';
        foreach ($models as $model) {
            $role = with(new $seedModel());
            $role->fill($model);
            $save = $role->save();

            if ($save === false) {
                print_r($role->getErrors());
                die();
            }
        }
    }
}
