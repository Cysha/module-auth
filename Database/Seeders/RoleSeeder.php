<?php

namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $models = [
            [
                'name' => 'Admin',
                'description' => 'Administration Role',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Staff',
                'description' => 'Staff Role',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'User',
                'description' => 'User Role',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Guest',
                'description' => 'Guest Role',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        $seedModel = 'Cms\Modules\Auth\Models\Role';
        $roles = with(new $seedModel())->insert($models);
    }
}
