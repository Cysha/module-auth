<?php namespace Cysha\Modules\Auth\Seeds;

use Cysha\Modules\Auth as Auth;
use Config;

class UserSeeder extends \Seeder
{
    public $debug = false;

    public function run()
    {
        // create the super admin group
        $objRoleSA = Auth\Models\Role::whereName(Config::get('auth::roles.super_group_name'))->first();

        // create the default user
        $objUser = new Auth\Models\User;
        $objUser->username              = 'admin';
        $objUser->first_name            = '';
        $objUser->last_name             = '';
        $objUser->email                 = 'xlink@cybershade.org';
        $objUser->password              = 'password'; // This is automatically salted and encrypted
        $objUser->verified              = 1;
        $save = $objUser->save();
        if ($this->debug === true) {
            if ($save === false) {
                print_r($objUser->getErrors());
                die();
            }
            print_r('User: '.$objUser->username.': added with id '. $objUser->id."\r\n");
        }

        // attach the user to the admin group
        $objUser->roles()->sync(array($objRoleSA->id));

    }
}
