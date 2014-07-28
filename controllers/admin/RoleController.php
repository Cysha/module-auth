<?php namespace Cysha\Modules\Auth\Controllers\Admin;

use Cysha\Modules\Auth as PXAuth;
use Auth;
use DB;
use DataGrid;
use Former;
use URL;
use Redirect;

class RoleController extends BaseAdminController
{
    use \Cysha\Modules\Admin\Traits\DataTable;

    public function __construct()
    {
        parent::__construct();

        $this->objTheme->setTitle('<i class="fa fa-users"></i> Role Manager');
        $this->objTheme->breadcrumb()->add('Role Manager', URL::route('admin.role.index'));
        $this->assets();

        $this->setActions(array(
            'header' => array(
                array(
                    'btn-text'  => 'Create Role',
                    'btn-link'  => URL::Route('admin.role.add'),
                    'btn-class' => 'btn btn-info btn-labeled',
                    'btn-icon'  => 'fa fa-plus'
                ),
            ),
            'row' => array(
                array(
                    'btn-text'  => 'Edit',
                    'btn-link'  => ( Auth::user()->can('admin.role.edit') ? '/admin/roles/[[ id ]]/edit' : '#' ),
                    'btn-class' => ( Auth::user()->can('admin.role.edit') ? 'btn btn-warning btn-sm btn-labeled' : 'btn btn-warning btn-sm btn-labeled disabled' ),
                    'btn-icon'  => 'fa fa-pencil'
                ),
                // array(
                //     'btn-text'  => 'Delete',
                //     'btn-link'  => ( Auth::user()->can('admin.role.delete') ? URL::Route('admin.role.delete') : '#' ),
                //     'btn-class' => ( Auth::user()->can('admin.role.delete') ? 'btn btn-danger btn-sm btn-labeled' : 'btn btn-danger btn-sm btn-labeled disabled' ),
                //     'btn-icon'  => 'fa fa-times'
                // ),
            )
        ));

        $this->setTableOptions(array(
            'filtering'     => false,
            'pagination'    => false,
            'sorting'       => true,
            'sort_column'   => 'id',
            'source'        => URL::route('admin.role.ajax'),
            'collection'    => function () {
                return PXAuth\Models\Role::with('permissions')->notSingleUser()->get();
            },
            'alert'         => array(
                'class' => 'info',
                'text'  => '<i class="fa fa-info-circle"></i> From this panel you can administer all your user groups. You can delete, create and edit existing groups. You may choose moderators, toggle open/closed group status and set the group name and description'
            ),
        ));

        $this->setTableColumns(array(
            'id' => array(
                'th'      => '&nbsp;',
                'tr'      => '[[ id ]]',
                'sorting' => true,
                'width'   => '5%',
            ),
            'name' => array(
                'th'        => 'Role Name',
                'tr'        => '<span class="label label-default" style="background-color: [[ color ]];">[[ name ]]</span>',
                'filtering' => true,
                'width'     => '15%',
            ),
            'color' => array(
                'th'      => 'Color',
            ),
            'description' => array(
                'th'      => 'Description',
                'tr'      => '[[ description ]]',
                'width'   => '40%',
            ),
            'created_at' => array(
                'alias'     => 'created',
                'th'        => 'DateCreated',
                'tr'        => '[[ created ]]',
                'th-class'  => 'hidden-xs hidden-sm',
                'tr-class'  => 'hidden-xs hidden-sm',
                'width'     => '15%',
            ),
            'updated_at' => array(
                'alias'     => 'updated',
                'th'        => 'Last Updated',
                'tr'        => '[[ updated ]]',
                'th-class'  => 'hidden-xs hidden-sm',
                'tr-class'  => 'hidden-xs hidden-sm',
                'width'     => '15%',
            ),
        ));
    }

    public function getAddRole()
    {
        $permissions = PXAuth\Models\Permission::all();
        $role = new PXAuth\Models\Role();
        return $this->setView('role.admin.form', compact('role', 'permissions'), 'module');
    }

    public function postAddRole()
    {
        $objRole = new PXAuth\Models\Role();
        $objRole->hydrateFromInput();

        if ($objRole->save() === false) {
            return Redirect::back()->withErrors($objRole->getErrors());
        }

        return Redirect::route('admin.role.edit', array('role' => $objRole->id))->withInfo('Role Created');
    }
}
