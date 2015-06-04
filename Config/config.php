<?php

return [
    'paths' => [
        'redirect_login'    => 'pxcms.user.dashboard',
        'redirect_logout'   => 'pxcms.pages.index',
        'redirect_register' => 'pxcms.user.registered',
    ],
    'users' => [
        'require_activating' => false,
        'default_user_group' => 3
    ],
];
