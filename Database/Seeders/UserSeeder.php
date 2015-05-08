<?php namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Cms\Modules\Auth;

class UserSeeder extends Seeder
{
    public $debug = false;

    public function run()
    {
        // create the default user
        $objUser = new Auth\Models\User;
        $objUser->username    = 'admin';
        $objUser->first_name  = '';
        $objUser->last_name   = '';
        $objUser->email       = 'xlink@cybershade.org';
        $objUser->password    = 'password';
        $objUser->verified_at = date('Y-m-d h:i:s');
        $save = $objUser->save();
        if ($this->debug === true) {
            if ($save === false) {
                print_r($objUser->getErrors());
                die();
            }
            print_r('User: '.$objUser->username.': added with id '. $objUser->id."\r\n");
        }
    }
}
