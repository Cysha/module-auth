<?php

return [

    'backend_sidebar' => [
        'User Management' => [
            [
                'route'         => 'admin.user.manager',
                'text'          => 'Users',
                'icon'          => 'fa-user',
                'order'         => 1,
                'permission'    => 'manage@auth_user',
                'activePattern' => '\/{backend}\/users\/*',
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
    ],

];