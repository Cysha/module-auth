<?php namespace Cysha\Modules\Auth\Seeds;

use Cysha\Modules\Auth as Auth;
use Config;

class RoleSeeder extends \Seeder
{
    public $debug = false;

    public function run()
    {
        $roles = array(
            array(
                'name'         => Config::get('auth::roles.super_group_name'),
                'description'  => Config::get('auth::roles.super_group_name').' Group',
                'moderator_id' => 1,
                'single_user'  => 0,
                'color'        => '#FF0000',
            ),
            array(
                'name'         => Config::get('auth::roles.admin_group_name'),
                'description'  => Config::get('auth::roles.admin_group_name').' Group',
                'moderator_id' => 1,
                'single_user'  => 0,
                'color'        => '#FF0000',
            ),
            array(
                'name'         => Config::get('auth::roles.default_group_name'),
                'description'  => Config::get('auth::roles.default_group_name').' Group',
                'moderator_id' => 1,
                'single_user'  => 0,
                'color'        => '#999999',
            ),
        );

        foreach ($roles as $role) {
            $objRole = new Auth\Models\Role;
            $objRole->fill($role);
            $save = $objRole->save();

            if ($this->debug === true) {
                if ($save === false) {
                    print_r(array($objRole->name, $objRole->getErrors()));
                    die();
                }
                print_r('Role: '.$objRole->name.': added with id '. $objRole->id."\r\n");
            }
        }
    }
}
