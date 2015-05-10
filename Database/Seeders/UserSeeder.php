<?php namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
            ],

        ];

        $seedModel = config('auth.model');
        foreach ($models as $model) {
            with(new $seedModel)->create($model);
        }
    }
}
