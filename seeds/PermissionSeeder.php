<?php namespace Cysha\Modules\Auth\Seeds;

use Cysha\Modules\Auth as Auth;
use Config;

class PermissionSeeder extends \Seeder
{
    public $debug = false;

    public function run()
    {
        $objRoleA = Auth\Models\Role::whereName(Config::get('auth::roles.admin_group_name'))->first()->id;
        $objRoleU = Auth\Models\Role::whereName(Config::get('auth::roles.default_group_name'))->first()->id;

        $permissions = array(
            (object) array('name' => 'admin.auth.login',             'roles' => array($objRoleA), 'description' => 'Admin Login' ),
            (object) array('name' => 'admin.auth.logout',            'roles' => array($objRoleA), 'description' => 'Admin Logout' ),
            (object) array('name' => 'admin.dashboard.index',        'roles' => array($objRoleA), 'description' => 'Admin Dashboard' ),

            (object) array('name' => 'user.auth.login',              'roles' => array($objRoleU), 'description' => 'User Login' ),
            (object) array('name' => 'user.auth.logout',             'roles' => array($objRoleU), 'description' => 'User Logout' ),
            (object) array('name' => 'user.auth.register',           'roles' => array($objRoleU), 'description' => 'User Register' ),
            (object) array('name' => 'user.auth.registered',         'roles' => array($objRoleU), 'description' => 'User Registered' ),
            (object) array('name' => 'user.auth.activate',           'roles' => array($objRoleU), 'description' => 'User Activate' ),
            (object) array('name' => 'user.dashboard',               'roles' => array($objRoleU), 'description' => 'User Dashboard' ),

            (object) array('name' => 'admin.config.store',           'roles' => array($objRoleA), 'description' => 'Save Site Config' ),
            (object) array('name' => 'admin.config.theme',           'roles' => array($objRoleA), 'description' => 'Edit Site Config[Theme]' ),
            (object) array('name' => 'admin.config.site',            'roles' => array($objRoleA), 'description' => 'Edit Site Config[Site]' ),
            (object) array('name' => 'admin.config.index',           'roles' => array($objRoleA), 'description' => 'Edit Site Config[Core]' ),

            (object) array('name' => 'admin.user.add',               'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.user.edit',              'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.user.delete',            'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.user.permissions.store', 'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.user.permissions',       'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.user.ajax',              'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.user.index',             'roles' => array($objRoleA), 'description' => '' ),

            (object) array('name' => 'admin.role.add',               'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.role.view',              'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.role.edit',              'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.role.ajax',              'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.role.index',             'roles' => array($objRoleA), 'description' => '' ),

            (object) array('name' => 'admin.permission.add',         'roles' => array($objRoleA), 'description' => '' ),
            (object) array('name' => 'admin.permission.index',       'roles' => array($objRoleA), 'description' => '' ),

        );

        // run through each permission
        if ( count($permissions) ) {
            foreach ($permissions as $p) {
                // add it to the db
                $objPerm = new Auth\Models\Permission;
                $objPerm->name          = $p->name;
                $objPerm->description   = $p->description;
                $save = $objPerm->save();

                if ($this->debug === true) {
                    if ($save === false) {
                        print_r(array($objPerm->name, $objPerm->getErrors()));
                        die();
                    }
                    print_r('Perm: '.$objPerm->name.': added with id '. $objPerm->id."\r\n");
                }
                // if we have to add it to any roles do so
                if ( count($p->roles) > 0 ) {
                    $objPerm->roles()->sync($p->roles);
                }

            }
        }

    }
}
