<?php namespace Cms\Modules\Auth\Datatables;

use Lock;

class RoleManager
{
    public function boot()
    {
        return [
            /**
             * Page Decoration Values
             */
            'page' => [
                'title' => 'Role Manager',
                'alert' => [
                    'class' => 'info',
                    'text'  => '<i class="fa fa-info-circle"></i> You can manage your roles from here.'
                ],
                //'header' => [
                //    [
                //        'btn-text'  => 'Create Role',
                //        'btn-link'  => 'admin.role.add',
                //        'btn-class' => 'btn btn-info btn-labeled',
                //        'btn-icon'  => 'fa fa-plus'
                //    ],
                //],
            ],

            /**
             * Set up some table options, these will be passed back to the view
             */
            'options' => [
                'filtering'     => true,
                'pagination'    => true,
                'sorting'       => true,
                'sort_column'   => 'id',
                'source'        => 'admin.role.manager',
                'collection'    => function () {
                    $model = 'Cms\Modules\Auth\Models\Role';
                    return $model::with('users')->get();
                },
            ],

            /**
             * Lists the tables columns
             */
            'columns' => [
                'id' => [
                    'th'        => '&nbsp;',
                    'tr'        => function ($model) {
                        return $model->id;
                    },
                    'sorting'   => true,
                    'width'     => '5%',
                ],
                'name' => [
                    'th'        => 'Role Name',
                    'tr'        => function ($model) {
                        return $model->name;
                    },
                    'filtering' => true,
                    'width'     => '15%',
                ],
                'users' => [
                    'th'        => 'User Count',
                    'tr'        => function ($model) {
                        return $model->userCount;
                    },
                    'filtering' => true,
                    'width'     => '15%',
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
                        if (\Lock::cannot('manage.update', 'auth_role')) {
                            return [];
                        }

                        return [
                            [
                                'btn-title' => 'Edit',
                                'btn-link'  => sprintf('/admin/roles/%s/edit', $model->id),
                                'btn-class' => 'btn btn-warning btn-xs btn-labeled',
                                'btn-icon'  => 'fa fa-pencil'
                            ],
                        ];
                    },
                    'width' => '7%',
                ],
            ],
        ];

    }
}
