<?php

return [
    'permission_manage' => [
        'auth_user',
        'auth_role',
        'auth_permission',
        'auth_config',
    ],

    /*
     * These will be loaded on /{backend}/config/cache
     */
    'cache_keys' => [
        'auth_permissions' => 'System Permissions',
    ],
];
