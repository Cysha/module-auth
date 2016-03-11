<?php

namespace Cms\Modules\Auth\Datatables;

class PermissionManager
{
    public function boot()
    {
        return [
            /*
             * Page Decoration Values
             */
            'page' => [
                'title' => '<i class="fa fa-fw fa-check-square-o"></i> Permissions Manager',
                // 'header' => [
                //     [
                //         'btn-text'  => 'Create Permission',
                //         'btn-link'  => 'admin.permission.add',
                //         'btn-class' => 'btn btn-info btn-labeled',
                //         'btn-icon'  => 'fa fa-plus'
                //     ],
                // ],
            ],

            /*
             * Set up some table options, these will be passed back to the view
             */
            'options' => [
                'pagination' => false,
                'searching' => true,
                'ordering' => false,
                'sort_column' => 'id',
                'sort_order' => 'desc',
                'source' => 'admin.permission.manager',
                'collection' => function () {
                    $model = 'Cms\Modules\Auth\Models\Permission';

                    return $model::with('roles')->get();
                },
            ],

            /*
             * Lists the tables columns
             */
            'columns' => [
                'id' => [
                    'th' => '&nbsp;',
                    'tr' => function ($model) {
                        return $model->id;
                    },
                    'orderable' => true,
                    'width' => '5%',
                ],
                'module' => [
                    'th' => 'Module',
                    'tr' => function ($model) {
                        return array_get(explode('_', $model->resource_type), 0);
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '5%',
                ],
                'model' => [
                    'th' => 'Model',
                    'tr' => function ($model) {
                        return array_get(explode('_', $model->resource_type), 1);
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '5%',
                ],
                'readable_name' => [
                    'th' => 'Readable Name',
                    'tr' => function ($model) {
                        return $model->readable_name;
                    },
                    'width' => '20%',
                ],
                'resource_type' => [
                    'th' => 'Resource Type',
                    'tr' => function ($model) {
                        return $model->resource_type;
                    },
                    'width' => '10%',
                ],
                'action' => [
                    'th' => 'Action',
                    'tr' => function ($model) {
                        return $model->action;
                    },
                    'filtering' => true,
                    'width' => '20%',
                ],
                'resource_id' => [
                    'th' => 'Resource ID',
                    'tr' => function ($model) {
                        return $model->resource_id;
                    },
                    'filtering' => true,
                    'width' => '10%',
                ],
                'roles' => [
                    'alias' => 'roles',
                    'th' => 'Roles',
                    'tr' => function ($model) {
                        $roles = null;

                        $tpl = '<span class="label label-default" style="background-color: %s;">%s</span>&nbsp;';
                        foreach ($model->roles as $role) {
                            $roles .= sprintf($tpl, $role->color, $role->name);
                        }

                        return $roles;
                    },
                    'filtering' => true,
                    'width' => '20%',
                ],
                'actions' => [
                    'th' => 'Actions',
                    'tr' => function ($model) {
                        return [];

                        return [
                            [
                                'btn-title' => 'View',
                                'btn-link' => sprintf('/admin/permissions/%d/view', $model->id),
                                'btn-class' => 'btn btn-default btn-xs btn-labeled',
                                'btn-icon' => 'fa fa-file-text-o',
                                'hasPermission' => 'manage.view@auth_permission',
                            ],
                            [
                                'btn-title' => 'Edit',
                                'btn-link' => sprintf('/admin/permissions/%d/edit', $model->id),
                                'btn-class' => 'btn btn-warning btn-xs btn-labeled',
                                'btn-icon' => 'fa fa-pencil',
                                'hasPermission' => 'manage.update@auth_permission',
                            ],
                        ];
                    },
                ],
            ],
        ];
    }
}
