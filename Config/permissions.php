<?php

return [
    'auth_user' => [
        // backend manager
        'manage', 'manage.create', 'manage.read', 'manage.update', 'manage.delete',
    ],

    'auth_role' => [
        // backend manager
        'manage', 'manage.create', 'manage.read', 'manage.update', 'manage.delete',

        // user subscriptions
        'users.read', 'users.create', 'users.delete',

        // permissions
        'permissions.read', 'permissions.create', 'permissions.delete',
    ],
];
