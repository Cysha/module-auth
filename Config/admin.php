<?php

return [

    'acp_menu' => [
        'User Manager' => [
            [
                'route'      => 'admin.user.manager',
                'text'       => 'Users',
                'icon'       => 'fa-user',
                'permission' => 'manage@auth_user'
            ],
            [
                'route'      => 'admin.role.manager',
                'text'       => 'Roles',
                'icon'       => 'fa-users',
                'permission' => 'manage@auth_role'
            ],
            [
                'route'      => 'admin.user.manager',
                'text'       => 'Permissions',
                'icon'       => 'fa-check-square-o',
                'permission' => 'manage@auth_permissions'
            ],
        ],
    ],

];
