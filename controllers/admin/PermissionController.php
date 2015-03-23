<?php namespace Cysha\Modules\Auth\Controllers\Admin;

use Cysha\Modules\Auth as PXAuth;
use Auth;
use DB;
use DataGrid;
use Former;
use URL;
use Redirect;

class PermissionController extends BaseAdminController
{
    use \Cysha\Modules\Admin\Traits\DataTableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->objTheme->setTitle('<i class="fa fa-check-square-o"></i> Permission Manager');
        $this->objTheme->breadcrumb()->add('Permission Manager', URL::route('admin.permission.index'));
        $this->assets();

        $this->setActions(array(
            'header' => array(
                array(
                    'btn-text'  => 'Create Permission',
                    'btn-link'  => URL::Route('admin.permission.add'),
                    'btn-class' => 'btn btn-info btn-labeled',
                    'btn-icon'  => 'fa fa-plus'
                ),
            ),
            //'row' => array(
            //    array(
            //        'btn-text'  => 'Edit',
            //        'btn-link'  => ( Auth::user()->can('admin.permission.edit') ? '///admin/roles/[[ id ]]/edit' : '#' ),
            //        'btn-class' => ( Auth::user()->can('admin.permission.edit') ? 'btn //btn-warning btn-sm btn-labeled' : 'btn btn-warning btn-sm btn-//labeled disabled' ),
            //        'btn-icon'  => 'fa fa-pencil'
            //    ),
            //)
        ));

        $this->setTableOptions(array(
            'filtering'     => false,
            'pagination'    => false,
            'sorting'       => true,
            'sort_column'   => 'id',
            'source'        => URL::route('admin.permission.ajax'),
            'collection'    => function () {
                return PXAuth\Models\Permission::get();
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

}
