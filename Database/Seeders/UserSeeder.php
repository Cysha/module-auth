<?php

namespace Cms\Modules\Auth\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $models = [
            [
                'username'    => 'admin',
                'email'       => 'xlink@cybershade.org',
                'password'    => 'password',
                'verified_at' => Carbon::now(),
                'role'        => 1,
            ],

        ];

        $seedModel = config('auth.model');
        foreach ($models as $model) {
            $user = with(new $seedModel());
            $user->fill(array_except($model, 'role'));
            $save = $user->save();

            if ($save === false) {
                print_r($user->getErrors());
                die();
            }

            $user->roles()->attach(
                array_get($model, 'role'),
                ['caller_type' => $user->getCallerType()]
            );
        }
    }
}
