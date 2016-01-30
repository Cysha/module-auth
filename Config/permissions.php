<?php

return [
    'auth_config' => [
        'api' => 'Manage Api Settings',
        'users' => 'Manage User Settings',
        'authentication' => 'Manage Authentication Settings',
    ],

    'auth_user' => [
        // backend manager
        'manage' => 'Manage users',
        'manage.create' => 'Create a user',
        'manage.read' => 'Read a user',
        'manage.update' => 'Update a user',
        'manage.delete' => 'Delete a user',

        'roles' => 'Manage roles for a user',
        'roles.add' => 'Add roles to a user',
        'roles.delete' => 'Delete roles from a user',

        'permissions' => 'Manage permissions for a user',
        'permissions.add' => 'Add permissions to a user',
        'permissions.delete' => 'Delete permissions from a user',

        'apikey' => 'Manage apikeys for a user',
        'apikey.add' => 'Add apikeys to a user',
        'apikey.delete' => 'Delete apikeys from a user',
    ],

    'auth_role' => [
        // backend manager
        'manage' => 'Manage roles',
        'manage.create' => 'Create roles',
        'manage.read' => 'Read a role',
        'manage.update' => 'Update a role',
        'manage.delete' => 'Delete a role',
    ],

    'auth_permission' => [
        // backend manager
        'manage' => 'Manage permissions',
        'manage.create' => 'Create permissions',
        'manage.read' => 'Read a permission',
        'manage.update' => 'Update a permission',
        'manage.delete' => 'Delete a permission',

        'roles.add' => 'Add a permission to a role',
        'roles.delete' => 'Delete a permission to a role',

        'user.add' => 'Add a permission direct to a user',
        'user.delete' => 'Delete a permission direct to a user',
    ],
];
