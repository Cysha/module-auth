<?php

return [

    'acp_menu' => [
        'User Manager' => [
            [
                'route'      => 'admin.user.manager',
                'text'       => 'Users',
                'icon'       => 'fa-user',
                'order'      => 1,
                'permission' => 'manage@auth_user'
            ],
            [
                'route'      => 'admin.role.manager',
                'text'       => 'Roles',
                'icon'       => 'fa-users',
                'order'      => 2,
                'permission' => 'manage@auth_role'
            ],
            [
                'route'      => 'admin.permission.manager',
                'text'       => 'Permissions',
                'icon'       => 'fa-check-square-o',
                'order'      => 3,
                'permission' => 'manage@auth_permission'
            ],
        ],

        'System' => [
            [
                'route'      => 'admin.config.socialite',
                'text'       => 'Socialite Manager',
                'icon'       => 'fa-share-alt-square',
                'order'      => 5,
                'permission' => 'socialite@admin_config'
            ],
        ]
    ],

];
