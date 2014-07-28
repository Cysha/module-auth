<?php namespace Cysha\Modules\Users\Controllers\Admin\RoleEdit;

use Cysha\Modules\Users as Users;
use Former, URL, Redirect;

class RoleController extends BaseRoleEditController {

    public function getEdit(Users\Models\Role $objRole){
        $this->theme->setTitle('Role Manager <small>> '.$objRole->name.' > Edit</small>');
        $this->theme->breadcrumb()->add('Edit', URL::route('admin.role.edit', $objRole->id));

        $permissions = Users\Models\Permission::all();

        Former::populate($objRole);

        return $this->setView('role.admin.form', array(
            'role'        => $objRole,
            'permissions' => $permissions,
        ), 'module');
    }

    public function postEdit(Users\Models\Role $objRole){
        $objRole->hydrateFromInput();

        if( $objRole->save() === false ){
            return Redirect::back()->withErrors($objRole->getErrors());
        }

        return Redirect::route('admin.role.edit', $objRole->id)->withInfo('Role Updated');
    }
}