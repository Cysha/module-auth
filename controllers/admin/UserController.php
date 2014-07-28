<?php namespace Cysha\Modules\Auth\Controllers\Admin;

use Auth;
use URL;

class UserController extends BaseAdminController
{
    use \Cysha\Modules\Admin\Traits\DataTableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->objTheme->setTitle('<i class="fa fa-user"></i> User Manager');
        $this->objTheme->breadcrumb()->add('User Manager', URL::route('admin.user.index'));
        $this->assets();

        $this->setActions([
            'header' => [
                [
                    'btn-text'  => 'Create User',
                    'btn-link'  => URL::Route('admin.user.add'),
                    'btn-class' => 'btn btn-info btn-labeled',
                    'btn-icon'  => 'fa fa-plus'
                ],
            ],
        ]);

        $this->setTableOptions([
            'filtering'     => true,
            'pagination'    => true,
            'sorting'       => true,
            'sort_column'   => 'id',
            'source'        => URL::route('admin.user.ajax'),
            'collection'    => function () {
                return \Cysha\Modules\Auth\Models\User::with('roles')->get();
            },
            'alert'         => [
                'class' => 'info',
                'text'  => '<i class="fa fa-info-circle"></i> You can manage your users from here.'
            ],
        ]);

        $this->setTableColumns([
            'id' => [
                'th'        => '&nbsp;',
                'tr'        => function ($model) {
                    return $model->id;
                },
                'sorting'   => true,
                'width'     => '5%',
            ],
            'username' => [
                'th'        => 'Username',
                'tr'        => function ($model) {
                    return $model->username;
                },
                'sorting'   => true,
                'filtering' => true,
                'width'     => '10%',
            ],
            'name' => [
                'th'        => 'Full Name',
                'tr'        => function ($model) {
                    return $model->name;
                },
                'sorting'   => true,
                'filtering' => true,
                'width'     => '10%',
            ],
            'email' => [
                'th'        => 'Email',
                'tr'        => function ($model) {
                    return $model->email;
                },
                'filtering' => true,
                'width'     => '20%',
            ],
            'roles' => [
                'alias'     => 'roles',
                'th'        => 'Roles',
                'tr'        => function ($model) {
                    $roles = null;

                    $tpl = '<span class="label label-default" style="background-color: %s;">%s</span>&nbsp;';
                    foreach ($model->roles as $role) {
                        $roles .= sprintf($tpl, $role->color, $role->name);
                    }

                    return $roles;
                },
                'filtering' => true,
                'width'     => '15%',
            ],
            'verified' => [
                'th'        => 'Verified',
                'tr'        => function ($model) {
                    return $model->verified == true ? '<div class="label label-success">Verified</div>' : '<div class="label label-danger">Not Verified</div>';
                },
                'tr-class'  => 'text-center',
                'sorting'   => true,
                'filtering' => true,
                'width'     => '5%',
            ],
            'created_at' => [
                'alias'     => 'created',
                'th'        => 'Date Created',
                'tr'        => function ($model) {
                    return date_carbon($model->created_at, 'd/m/Y H:i:s');
                },
                'th-class'  => 'hidden-xs hidden-sm',
                'tr-class'  => 'hidden-xs hidden-sm',
                'width'     => '15%',
            ],
            'actions' => [
                'th' => 'Actions',
                'tr' => function ($model) {
                    return [[
                        'btn-text'  => 'View User',
                        'btn-link'  => ( Auth::user()->can('admin.user.view') ? sprintf('/admin/users/%d/view', $model->id) : '#' ),
                        'btn-class' => ( Auth::user()->can('admin.user.view') ? 'btn btn-default btn-sm btn-labeled' : 'btn btn-warning btn-sm btn-labeled disabled' ),
                        'btn-icon'  => 'fa fa-file-text-o'
                    ],
                    [
                        'btn-text'  => 'Edit',
                        'btn-link'  => ( Auth::user()->can('admin.user.edit') ? sprintf('/admin/users/%d/edit', $model->id) : '#' ),
                        'btn-class' => ( Auth::user()->can('admin.user.edit') ? 'btn btn-warning btn-sm btn-labeled' : 'btn btn-warning btn-sm btn-labeled disabled' ),
                        'btn-icon'  => 'fa fa-pencil'
                    ]];
                },
            ]
        ]);

    }


}
