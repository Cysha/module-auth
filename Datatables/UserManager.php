<?php namespace Cms\Modules\Auth\Datatables;

use Lock;

class UserManager
{
    public function boot()
    {
        return [
            /**
             * Page Decoration Values
             */
            'page' => [
                'title' => 'User Manager',
                'alert' => [
                    'class' => 'info',
                    'text'  => '<i class="fa fa-info-circle"></i> You can manage your users from here.'
                ],
                'header' => [
                    [
                        'btn-text'  => 'Create User',
                        'btn-route'  => 'admin.user.add',
                        'btn-class' => 'btn btn-info btn-labeled',
                        'btn-icon'  => 'fa fa-plus'
                    ],
                ],
            ],

            /**
             * Set up some table options, these will be passed back to the view
             */
            'options' => [
                'pagination' => false,
                'searching' => true,
                'column_search' => true,
                'ordering' => false,
                'sort_column' => 'id',
                'sort_order' => 'desc',
                'source' => 'admin.user.manager',
                'collection' => function () {
                    $model = config('auth.model');
                    return $model::with('roles')->get();
                },
            ],

            /**
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
                'screenname' => [
                    'th' => 'Screen Name',
                    'tr' => function ($model) {
                        return $model->screenname;
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '10%',
                ],
                'email' => [
                    'th' => 'Email',
                    'tr' => function ($model) {
                        return $model->email;
                    },
                    'searchable' => true,
                    'width' => '15%',
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
                    'searchable' => true,
                    'width' => '15%',
                ],
                'has2fa' => [
                    'th' => '2FA Enabled?',
                    'tr' => function ($model) {
                        return $model->has2fa === '1'
                            ? '<div class="label label-success">Enabled</div>'
                            : '<div class="label label-danger">Not Enabled</div>';
                    },
                    'tr-class' => 'text-center',
                    'width' => '5%',
                ],
                'verified' => [
                    'th' => 'Verified',
                    'tr' => function ($model) {
                        return !is_null($model->verified_at)
                            ? '<div class="label label-success">Verified</div>'
                            : '<div class="label label-danger">Not Verified</div>';
                    },
                    'tr-class' => 'text-center',
                    'orderable' => true,
                    'width' => '5%',
                ],
                'last_logged_at' => [
                    'th' => 'Last Login',
                    'tr' => function ($model) {
                        return !is_null($model->last_logged_at)
                            ? array_get(date_array($model->last_logged_at), 'element')
                            : 'Never';
                    },
                    'th-class' => 'hidden-xs hidden-sm',
                    'tr-class' => 'hidden-xs hidden-sm',
                    'width' => '12%',
                ],
                'actions' => [
                    'th' => 'Actions',
                    'tr' => function ($model) {
                        $return = [];

                        if (Lock::can('manage.read', 'auth_user')) {
                            $return[] = [
                                'btn-title' => 'View User',
                                'btn-link'  => route('admin.user.view', $model->id),
                                'btn-class' => 'btn btn-default btn-xs btn-labeled',
                                'btn-icon'  => 'fa fa-file-text-o'
                            ];
                        }

                        if (Lock::can('manage.update', 'auth_user')) {
                            $return[] = [
                                'btn-title' => 'Edit',
                                'btn-link'  => route('admin.user.edit', $model->id),
                                'btn-class' => 'btn btn-warning btn-xs btn-labeled',
                                'btn-icon'  => 'fa fa-pencil'
                            ];
                        }

                        return $return;
                    },
                ],
            ]
        ];

    }
}
