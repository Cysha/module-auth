<?php namespace Cysha\Modules\Users\Controllers\Admin\UserEdit;

use Cysha\Modules\Users as Users;
use Former, URL;

class PermissionsController extends BaseUserEditController {

    public function getEdit(Users\Models\Role $objRole){
        $this->theme->setTitle('Role Manager <small>> '.$objRole->name.' > Permissions</small>');
        $this->theme->breadcrumb()->add('Permissions', URL::route('admin.role.permissions', $objRole->id));

        $permissions = Users\Models\Permission::all();

        Former::populate($objRole);

        return $this->setView('role.admin.permissions', array(
            'role'        => $objRole,
            'permissions' => $permissions,
        ), 'module');
    }


}