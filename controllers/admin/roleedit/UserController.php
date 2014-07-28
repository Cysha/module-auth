<?php namespace Cysha\Modules\Users\Controllers\Admin\RoleEdit;

use Cysha\Modules\Users as Users;
use Former, URL;

class UserController extends BaseRoleEditController {

    public function getEdit(Users\Models\Role $objRole){
        $this->theme->setTitle('Role Manager <small>> '.$objRole->name.' > User List</small>');
        $this->theme->breadcrumb()->add('Users', URL::route('admin.role.user', $objRole->id));



        return $this->setView('role.admin.users', array(
            'role'        => $objRole,
        ), 'module');
    }

}