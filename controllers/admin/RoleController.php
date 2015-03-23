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
    use \Cysha\Modules\Admin\Traits\DataTableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->objTheme->setTitle('<i class="fa fa-users"></i> Role Manager');
        $this->objTheme->breadcrumb()->add('Role Manager', URL::route('admin.role.index'));
        $this->assets();

        $this->setActions([
            'header' => [
                [
                    'btn-text'  => 'Create Role',
                    'btn-link'  => URL::Route('admin.role.add'),
                    'btn-class' => 'btn btn-info btn-labeled',
                    'btn-icon'  => 'fa fa-plus'
                ],
            ],
        ]);

        $this->setTableOptions([
            'filtering'     => false,
            'pagination'    => false,
            'sorting'       => true,
            'sort_column'   => 'id',
            'source'        => URL::route('admin.role.ajax'),
            'collection'    => function () {
                return PXAuth\Models\Role::with('permissions')->notSingleUser()->get();
            },
            'alert'         => [
                'class' => 'info',
                'text'  => '<i class="fa fa-info-circle"></i> From this panel you can administer all your user groups. You can delete, create and edit existing groups. You may choose moderators, toggle open/closed group status and set the group name and description'
            ]
        ]);

        $this->setTableColumns([
            'id' => [
                'th'      => '&nbsp;',
                'tr'      => '[[ id ]]',
                'sorting' => true,
                'width'   => '5%',
            ],
            'name' => [
                'th'        => 'Role Name',
                'tr'        => function ($model) {
                    $model = $model->transform();
                    return sprintf('<span class="label label-default" style="background-color: %s;">%s</span>', $model['color'], $model['name']);
                },
                'filtering' => true,
                'width'     => '15%',
            ],
            'color' => [
                'th'      => 'Color',
            ],
            'description' => [
                'th'      => 'Description',
                'tr'        => function ($model) {
                    return $model->description;
                },
                'width'   => '40%',
            ],
            'created_at' => [
                'alias'     => 'created',
                'th'        => 'Date Created',
                'tr'        => function ($model) {
                    return date_fuzzy($model->created_at);
                },
                'th-class'  => 'hidden-xs hidden-sm',
                'tr-class'  => 'hidden-xs hidden-sm',
                'width'     => '15%',
            ],
            'updated_at' => [
                'alias'     => 'updated',
                'th'        => 'Last Updated',
                'tr'        => function ($model) {
                    return date_fuzzy($model->updated_at);
                },
                'th-class'  => 'hidden-xs hidden-sm',
                'tr-class'  => 'hidden-xs hidden-sm',
                'width'     => '15%',
            ],
            'actions' => [
                'th' => 'Actions',
                'tr' => function ($model) {
                    return [
                        [
                            'btn-text'  => 'Edit',
                            'btn-link'  => (Auth::user()->can('admin.role.edit') ? sprintf('/admin/roles/%s/edit', $model->id) : '#'),
                            'btn-class' => (Auth::user()->can('admin.role.edit') ? 'btn btn-warning btn-sm btn-labeled' : 'btn btn-warning btn-sm btn-labeled disabled'),
                            'btn-icon'  => 'fa fa-pencil'
                        ],
                    ];
                },
                'width' => '7%',
            ]
        ]);
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

        return Redirect::route('admin.role.edit', ['role' => $objRole->id])
            ->withInfo('Role Created');
    }
}
