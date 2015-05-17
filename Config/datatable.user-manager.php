<?php

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
                'btn-link'  => 'admin.user.add',
                'btn-class' => 'btn btn-info btn-labeled',
                'btn-icon'  => 'fa fa-plus'
            ],
        ],
    ],

    /**
     * Set up some table options, these will be passed back to the view
     */
    'options' => [
        'filtering'     => true,
        'pagination'    => true,
        'sorting'       => true,
        'sort_column'   => 'id',
        'source'        => 'admin.user.manager',
        'collection'    => function () {
            $model = config('auth.model');
            return $model::with('roles')->get();
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
            'th'        => 'Screen Name',
            'tr'        => function ($model) {
                return $model->screenname;
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
                return !is_null($model->verified_at) ? '<div class="label label-success">Verified</div>' : '<div class="label label-danger">Not Verified</div>';
            },
            'tr-class'  => 'text-center',
            'sorting'   => true,
            'filtering' => true,
            'width'     => '5%',
        ],
        'last_logged_at' => [
            'th'        => 'Last Login',
            'tr'        => function ($model) {
                return date_carbon($model->last_logged_at, 'd/m/Y H:i:s');
            },
            'th-class'  => 'hidden-xs hidden-sm',
            'tr-class'  => 'hidden-xs hidden-sm',
            'width'     => '15%',
        ],
        'created_at' => [
            'alias'     => 'created',
            'th'        => 'Date Registered',
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
                $return = [];

                if (Lock::can('manage.read', 'auth_user')) {
                    $return[] = [
                        'btn-title' => 'View User',
                        'btn-link'  => sprintf('/admin/users/%d/view', $model->id),
                        'btn-class' => 'btn btn-default btn-xs btn-labeled',
                        'btn-icon'  => 'fa fa-file-text-o'
                    ];
                }

                if (Lock::can('manage.update', 'auth_user')) {
                    $return[] = [
                        'btn-title' => 'Edit',
                        'btn-link'  => sprintf('/admin/users/%d/edit', $model->id),
                        'btn-class' => 'btn btn-warning btn-xs btn-labeled',
                        'btn-icon'  => 'fa fa-pencil'
                    ];
                }

                return $return;
            },
        ],
    ],
];
