<?php namespace Cysha\Modules\Auth\Controllers\Admin\RoleEdit;

use Cysha\Modules\Auth as Auth;
use Former, URL, Redirect;

class RoleController extends BaseRoleEditController
{
    public function getEdit(Auth\Models\Role $objRole)
    {
        $this->objTheme->setTitle('Role Manager <small>> '.$objRole->name.' > Edit</small>');
        $this->objTheme->breadcrumb()->add('Edit', URL::route('admin.role.edit', $objRole->id));

        $permissions = Auth\Models\Permission::all();

        Former::populate($objRole);

        return $this->setView('role.admin.form', array(
            'role'        => $objRole,
            'permissions' => $permissions,
        ), 'module');
    }

    public function postEdit(Auth\Models\Role $objRole)
    {
        $objRole->hydrateFromInput();

        if( $objRole->save() === false ){
            return Redirect::back()->withErrors($objRole->getErrors());
        }

        return Redirect::route('admin.role.edit', $objRole->id)->withInfo('Role Updated');
    }
}
