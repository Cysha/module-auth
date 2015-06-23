<?php

return [
    'permission_manage' => [
        'auth_user',
        'auth_role',
        'auth_permission',
    ],

    /**
     * These will be loaded on /{backend}/config/cache
     */
    'cache_keys' => [
        'auth_permissions' => 'System Permissions',
    ],
];
