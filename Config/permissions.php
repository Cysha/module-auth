<?php

return [
    'auth_user' => [
        // backend manager
        'manage'        => 'Manage users',
        'manage.create' => 'Create a user',
        'manage.read'   => 'Read a user',
        'manage.update' => 'Update a user',
        'manage.delete' => 'Delete a user',
    ],

    'auth_role' => [
        // backend manager
        'manage'             => 'Mange roles',
        'manage.create'      => 'Create roles',
        'manage.read'        => 'Read a role',
        'manage.update'      => 'Update a role',
        'manage.delete'      => 'Delete a role',

        // user subscriptions
        'users.read'         => 'Access the users on a specific role',
        'users.create'       => 'Add a user to a role',
        'users.delete'       => 'Remove a user\'s role',

        // permissions
        'permissions.read'   => 'Read the permissions on a role',
        'permissions.create' => 'Add a permission to a role',
        'permissions.delete' => 'Remove a permission from a role',
],

    'auth_permission' => [
        // backend manager
        'manage'        => 'Manage permissions',
        'manage.create' => 'Create permissions',
        'manage.read'   => 'Read a permission',
        'manage.update' => 'Update a permission',
        'manage.delete' => 'Delete a permission',
    ],
];
